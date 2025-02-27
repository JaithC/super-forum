<?php

declare(strict_types=1);
/**
 * This file is part of zhuchunshu.
 * @link     https://github.com/zhuchunshu
 * @document https://github.com/zhuchunshu/super-forum
 * @contact  laravel@88.com
 * @license  https://github.com/zhuchunshu/super-forum/blob/master/LICENSE
 */
namespace App\Plugins\Comment;

/**
 * Class Comment.
 * @see https://github.com/zhuchunshu/sf-comment
 * @name Comment
 * @version 1.0.0
 */
class Comment
{
    public function handler()
    {
        $this->helpers();
        $this->setting();
        $this->bootstrap();
    }

    private function setting()
    {
        require_once __DIR__ . '/setting.php';
    }

    private function helpers()
    {
        require_once __DIR__ . '/helpers.php';
    }

    private function bootstrap()
    {
        require_once __DIR__ . '/bootstrap.php';
    }
}
