<?php

declare(strict_types=1);
/**
 * This file is part of zhuchunshu.
 * @link     https://github.com/zhuchunshu
 * @document https://github.com/zhuchunshu/super-forum
 * @contact  laravel@88.com
 * @license  https://github.com/zhuchunshu/super-forum/blob/master/LICENSE
 */
use App\Plugins\Core\src\Lib\Authority\Authority;
use App\Plugins\Core\src\Lib\Redirect;
use App\Plugins\Core\src\Lib\ShortCodeR\ShortCodeR;
use App\Plugins\Core\src\Lib\UserVerEmail;
use DivineOmega\PHPSummary\SummaryTool;
use JetBrains\PhpStorm\Pure;



if (! function_exists('plugins_core_user_reg_defuc')) {
    function plugins_core_user_reg_defuc()
    {
        return \App\Plugins\User\src\Models\UserClass::query()->select('id', 'name')->get();
    }
}

if (! function_exists('super_avatar')) {
    function super_avatar($user_data): string
    {
        if ($user_data->avatar) {
            return $user_data->avatar;
        }

        if (get_options('core_user_def_avatar', 'gavatar') !== 'ui-avatars') {
            return get_options('theme_common_gavatar', 'https://cn.gravatar.com/avatar/') . md5($user_data->email);
        }
        return 'https://ui-avatars.com/api/?background=random&format=svg&name=' . $user_data->username;
    }
}

if (! function_exists('avatar')) {
    function avatar($user_data): string
    {
        return super_avatar($user_data);
    }
}

if (! function_exists('redirect')) {
    #[Pure] function redirect(): Redirect
    {
        return new Redirect();
    }
}

if (! function_exists('core_user_ver_email_make')) {
    function core_user_ver_email(): UserVerEmail
    {
        return new UserVerEmail();
    }
}

if (! function_exists('Core_Ui')) {
    function Core_Ui(): App\Plugins\Core\src\Lib\Ui
    {
        return new App\Plugins\Core\src\Lib\Ui();
    }
}

if (! function_exists('core_Str_menu_url')) {
    function core_Str_menu_url(string $path): string
    {
        if ($path === '//') {
            $path = '/';
        }
        return $path;
    }
}

if (! function_exists('core_menu_pd')) {
    function core_menu_pd(string $id)
    {
        foreach (Itf()->get('menu') as $value) {
            if (arr_has($value, 'parent_id') && 'menu_' . $value['parent_id'] === (string) $id) {
                return true;
            }
        }
        return false;
    }
}

if (! function_exists('core_Itf_id')) {
    function core_Itf_id($name, $id)
    {
        return \Hyperf\Utils\Str::after($id, $name . '_');
    }
}

if (! function_exists('core_menu_pdArr')) {
    function core_menu_pdArr($id): array
    {
        $arr = [];
        foreach (Itf()->get('menu') as $key => $value) {
            if (arr_has($value, 'parent_id') && 'menu_' . $value['parent_id'] === $id) {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }
}

if (! function_exists('core_default')) {
    function core_default($string = null, $default = null)
    {
        if ($string) {
            return $string;
        }
        return $default;
    }
}

if (! function_exists('markdown')) {
    function markdown(): Parsedown
    {
        return new Parsedown();
    }
}

if (! function_exists('ShortCodeR')) {
    function ShortCodeR(): ShortCodeR
    {
        return new ShortCodeR();
    }
}

if (! function_exists('xss')) {
    function xss(): App\Plugins\Core\src\Lib\Xss\Xss
    {
        return new App\Plugins\Core\src\Lib\Xss\Xss();
    }
}

if (! function_exists('summary')) {
    function summary($content): string
    {
        return (new SummaryTool($content))->getSummary();
    }
}

if (! function_exists('deOptions')) {
    function deOptions($json)
    {
        return json_decode($json, true);
    }
}

if (! function_exists('getAllImg')) {
    function getAllImg($content): array
    {
        $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i'; //匹配img标签的正则表达式

        preg_match_all($preg, $content, $allImg); //这里匹配所有的imgecho
        return $allImg[1];
    }
}

if (! function_exists('format_date')) {
    function format_date($time)
    {
        $t = time() - strtotime((string) $time);
        $f = [
            '31536000' => __('app.year'),
            '2592000' => __('app.month'),
            '604800' => __('app.week'),
            '86400' => __('app.day'),
            '3600' => __('app.hour'),
            '60' => __('app.minute'),
            '1' => __('app.second'),
        ];
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int) $k)) {
                return $c . ' ' . $v . __('app.ago');
            }
        }
    }
}

if (! function_exists('get_all_at')) {
    /**
     * 获取内容中所有被艾特的用户.
     */
    function get_all_at(string $content): array
    {
        preg_match_all('/(?<=@)[^ ]+/u', replace_all_at_space($content), $arr);
        return $arr[0];
    }
}

if (! function_exists('replace_all_at_space')) {
    function replace_all_at_space(string $content): string
    {
        //$pattern = "/\\$\\[(.*?)]/u";
        $pattern = '/@(.*?)[^ <\\/p>]+/u';
        return preg_replace_callback($pattern, static function ($match) {
            return $match[0] . ' ';
        }, $content);
    }
}

if (! function_exists('remove_all_p_space')) {
    function remove_all_p_space(string $content): string
    {
        return str_replace(' </p>', '</p>', $content);
    }
}

if (! function_exists('replace_all_at')) {
    function replace_all_at(string $content): string
    {
        //$pattern = "/\\$\\[(.*?)]/u";
        $pattern = '/@(.*?)[^ ]+/u';
        $content = replace_all_at_space($content);
        return remove_all_p_space(preg_replace_callback($pattern, static function ($match) {
            return (new \App\Plugins\Core\src\Lib\TextParsing())->at($match[0]);
        }, $content));
    }
}

if (! function_exists('get_all_keywords')) {
    /**
     * 获取内容中所有话题关键词.
     */
    function get_all_keywords(string $content): array
    {
        preg_match_all('/(?<=\\.\\[)[^]]+/u', $content, $arrMatches);
        return $arrMatches[0];
    }

    function replace_all_keywords(string $content): string
    {
        $pattern = '/\\.\\[(.*?)]/u';
        return preg_replace_callback($pattern, static function ($match) {
            return (new \App\Plugins\Core\src\Lib\TextParsing())->keywords($match[1]);
        }, $content);
    }
}

if (! function_exists('remove_bbCode')) {
    function remove_bbCode($content)
    {
        $pattern = '/\\[(.*?)\\](.*?)\\[\\/(.*?)\\]/is';

        $content = preg_replace_callback($pattern, function ($match) {
            return '';
        }, $content);
        return str_replace(' ', '', $content);
    }
}

if (! function_exists('Authority')) {
    function Authority()
    {
        return new Authority();
    }
}

if (! function_exists('curd')) {
    function curd(): App\Plugins\Core\src\Lib\Curd
    {
        return new \App\Plugins\Core\src\Lib\Curd();
    }
}

if (! function_exists('core_http_build_query')) {
    function core_http_build_query(array $data, array $merge): string
    {
        $data = array_merge($data, $merge);
        return http_build_query($data);
    }
}

if (! function_exists('core_http_url')) {
    function core_http_url(): string
    {
        $query = http_build_query(request()->all());
        return request()->path() . '?' . $query;
    }
}

if (! function_exists('core_get_page')) {
    function core_get_page(string $url): array
    {
        $data = explode('=', parse_url($url)['query']);
        return [$data[0] => $data[1]];
    }
}

if (! function_exists('emoji_add')) {
    /**
     * @param string $name emoji name
     * @param string $emoji emoji json path
     * @param string $type emoji type emoji | image | emoticon(颜文字)
     * @throws Exception
     */
    function emoji_add(string $name, string $emoji, string $type, bool $size = false)
    {
        Itf()->add('emoji', random_int(2, 99212), [
            'name' => $name,
            'emoji' => $emoji,
            'type' => $type,
            'size' => $size,
        ]);
    }
}
