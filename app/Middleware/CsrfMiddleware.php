<?php

declare(strict_types=1);
/**
 * This file is part of zhuchunshu.
 * @link     https://github.com/zhuchunshu
 * @document https://github.com/zhuchunshu/super-forum
 * @contact  laravel@88.com
 * @license  https://github.com/zhuchunshu/super-forum/blob/master/LICENSE
 */
namespace App\Middleware;

use Hyperf\Utils\Str;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! config('codefec.app.csrf')) {
            return $handler->handle($request);
        }
        foreach (Itf()->get('csrf') as $value) {
            if (Str::is($this->clean_str($value), $this->clean_str(request()->path()))) {
                return $handler->handle($request);
            }
        }
        $sha1 = sha1(json_encode([
            request()->getHeader('host')[0],
            get_client_ip(),
            get_user_agent(),
        ], JSON_THROW_ON_ERROR));
        if (request()->isMethod('post') && $sha1 !== request()->input('_token')) {
            return admin_abort(['msg' => '会话超时,请刷新后重新提交', 'CSRF_TOKEN_CREATE' => is_string(recsrf_token()) ], 419);
        }
        return $handler->handle($request);
    }

    public function clean_str($str): array | string
    {
        return str_replace('/', '_', $str);
    }
}
