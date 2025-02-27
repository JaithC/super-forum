<?php

declare(strict_types=1);
/**
 * This file is part of zhuchunshu.
 * @link     https://github.com/zhuchunshu
 * @document https://github.com/zhuchunshu/super-forum
 * @contact  laravel@88.com
 * @license  https://github.com/zhuchunshu/super-forum/blob/master/LICENSE
 */
namespace App\Plugins\User\src\Controller\Admin;

use App\Plugins\User\src\Lib\UserAuth;
use App\Plugins\User\src\Models\User;
use App\Plugins\User\src\Models\UserClass as Uc;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Utils\Str;
use HyperfExt\Hashing\Hash;

#[Controller]
#[Middleware(\App\Middleware\AdminMiddleware::class)]
class UserController
{
    #[GetMapping(path: '/admin/users')]
    public function index()
    {
        $page = User::query()->with('class')->paginate(15);
        return view('User::Admin.Users.index', ['page' => $page]);
    }

    #[GetMapping(path: '/admin/users/search')]
    public function search()
    {
        $q = request()->input('q');
        $page = User::query()->where('username', 'like', '%' . $q . '%')->with('class')->paginate(15);
        return view('User::Admin.Users.index', ['page' => $page]);
    }

    #[PostMapping(path: '/admin/users/update/username')]
    public function update_username()
    {
        if (! admin_auth()->check()) {
            return Json_Api(419, false, ['msg' => '无权限']);
        }
        $user_id = request()->input('user_id');
        $username = request()->input('username');
        if (! $user_id) {
            return Json_Api(403, false, ['msg' => '请求的用户id不能为空']);
        }
        if (! $username) {
            return Json_Api(403, false, ['msg' => '请求的用户名不能为空']);
        }
        if (! User::query()->where('id', $user_id)->exists()) {
            return Json_Api(403, false, ['msg' => 'ID为:' . $user_id . '的用户不存在']);
        }
        if (User::query()->where('username', $username)->exists()) {
            return Json_Api(403, false, ['msg' => '此用户名已被使用']);
        }
        User::query()->where('id', $user_id)->update([
            'username' => $username,
        ]);
        (new UserAuth())->destroy($user_id);
        return Json_Api(200, true, ['msg' => '修改成功!']);
    }

    #[PostMapping(path: '/admin/users/update/email')]
    public function update_email()
    {
        if (! admin_auth()->check()) {
            return Json_Api(419, false, ['msg' => '无权限']);
        }
        $user_id = request()->input('user_id');
        $email = request()->input('email');
        if (! $user_id) {
            return Json_Api(403, false, ['msg' => '请求的用户id不能为空']);
        }
        if (! $email) {
            return Json_Api(403, false, ['msg' => '请求的邮箱不能为空']);
        }
        if (! User::query()->where('id', $user_id)->exists()) {
            return Json_Api(403, false, ['msg' => 'ID为:' . $user_id . '的用户不存在']);
        }
        if (User::query()->where('email', $email)->exists()) {
            return Json_Api(403, false, ['msg' => '此邮箱已被使用']);
        }
        User::query()->where('id', $user_id)->update([
            'email' => $email,
            'email_ver_time' => null,
        ]);
        (new UserAuth())->destroy($user_id);
        return Json_Api(200, true, ['msg' => '修改成功! 用户重新登陆并验证邮箱后生效']);
    }

    #[GetMapping(path: '/admin/users/update/{id}/UserClass')]
    public function update_UserClass_view($id)
    {
        if (! User::query()->where('id', $id)->exists()) {
            return admin_abort('页面不存在', 404);
        }
        $data = User::query()->where('id', $id)->with('Class')->first();
        $class = UC::query()->get();
        return view('User::Admin.Users.update_UserClass', ['data' => $data, 'class' => $class]);
    }

    #[PostMapping(path: '/admin/users/update/UserClass')]
    public function update_UserClass()
    {
        if (! admin_auth()->check()) {
            return Json_Api(419, false, ['msg' => '无权限']);
        }
        $user_id = request()->input('user_id');
        $class_id = request()->input('class_id');
        if (! $user_id || ! $class_id) {
            return redirect()->back()->with('danger', '请求参数不完整')->go();
        }
        User::query()->where('id', $user_id)->update([
            'class_id' => $class_id,
        ]);
        (new UserAuth())->destroy($user_id);
        return redirect()->url('/admin/users')->with('success', '修改成功!')->go();
    }

    #[PostMapping(path: '/admin/users/update/token')]
    public function update_Token()
    {
        if (! admin_auth()->check()) {
            return Json_Api(419, false, ['msg' => '无权限']);
        }
        $user_id = request()->input('user_id');
        if (! $user_id) {
            return Json_Api(403, false, ['msg' => '请求参数不完整']);
        }
        User::query()->where('id', $user_id)->update([
            '_token' => Str::random(),
        ]);
        (new UserAuth())->destroy($user_id);
        return Json_Api(200, true, ['msg' => '更新成功!']);
    }

    #[PostMapping(path: '/admin/users/update/password')]
    public function update_password()
    {
        if (! admin_auth()->check()) {
            return Json_Api(419, false, ['msg' => '无权限']);
        }
        $user_id = request()->input('user_id');
        $password = request()->input('password');
        if (! $user_id || ! $password) {
            return Json_Api(403, false, ['msg' => '请求参数不完整']);
        }
        User::query()->where('user_id', $user_id)->update([
            'password' => Hash::make($password),
        ]);
        (new UserAuth())->destroy($user_id);
        return Json_Api(200, true, ['msg' => '更新成功!']);
    }

    #[PostMapping(path: '/admin/users/remove')]
    public function remove_user()
    {
        if (! admin_auth()->check()) {
            return Json_Api(419, false, ['msg' => '无权限']);
        }
        $user_id = request()->input('user_id');
        if (! $user_id) {
            return Json_Api(403, false, ['msg' => '请求参数不完整']);
        }
        User::query()->where('id', $user_id)->delete();
        (new UserAuth())->destroy($user_id);
        return Json_Api(200, true, ['msg' => '已删除!']);
    }

    #[GetMapping(path: '/admin/users/{id}/show')]
    public function show($id)
    {
        if (! User::query()->where('id', $id)->exists()) {
            return redirect()->url('/admin/users')->with('danger', '用户不存在')->go();
        }
        $user = $this->get_user_data($id);
        return view('User::Admin.Users.show', ['user' => $user]);
    }

    #[GetMapping(path: '/admin/users/{id}/edit')]
    public function edit($id)
    {
        if (! User::query()->where('id', $id)->exists()) {
            return redirect()->url('/admin/users')->with('danger', '用户不存在')->go();
        }
        $user = $this->get_user_data($id);
        return view('User::Admin.Users.edit', ['user' => $user]);
    }

    #[PostMapping(path: '/admin/users/{id}/edit')]
    public function edit_submit($id)
    {
        if (! User::query()->where('id', $id)->exists()) {
            return redirect()->url('/admin/users')->with('danger', '用户不存在')->go();
        }
        $user = User::query()->with('Options','Class')->find($id);
        foreach (request()->all() as $key => $value) {
            if ($key !== '_token') {
                if (is_array($value)) {
                    foreach ($value as $_k => $_v) {
                        $user->{$key}->{$_k} = trim($_v);
                    }
                } elseif (is_string($value)) {
                    $user->{$key} = trim($value);
                }
            }
        }
        $user->save();
        return redirect()->url('/admin/users/' . $id . '/show')->with('success', '修改成功!')->go();
        //return view('User::Admin.Users.edit', ['user' => $user]);
    }

    public function get_user_data($user_id): \Hyperf\Database\Model\Model | \Hyperf\Database\Model\Builder | null
    {
        return User::query()->with(['Options', 'Class'])->where('id', $user_id)->first();
    }
}
