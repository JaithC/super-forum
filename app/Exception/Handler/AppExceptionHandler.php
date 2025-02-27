<?php

declare(strict_types=1);
/**
 * This file is part of zhuchunshu.
 * @link     https://github.com/zhuchunshu
 * @document https://github.com/zhuchunshu/super-forum
 * @contact  laravel@88.com
 * @license  https://github.com/zhuchunshu/super-forum/blob/master/LICENSE
 */
namespace App\Exception\Handler;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Str;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());
        $log = admin_log()->insert('SuperForum', 'Internal Server Error.', Str::limit($throwable->getMessage()), [
            'message' => $throwable->getMessage(),
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
        ]);
        $log_id = $log['_id'];
        return $response->withHeader('Server', 'SuperForum')->withStatus(500)->withBody(new SwooleStream('Internal Server Error. log in <a href="' . url('/admin/server/logger/' . $log_id . '.html') . '">' . url('/admin/server/logger/' . $log_id . '.html') . '</a>'));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
