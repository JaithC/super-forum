<?php

declare(strict_types=1);
/**
 * This file is part of zhuchunshu.
 * @link     https://github.com/zhuchunshu
 * @document https://github.com/zhuchunshu/super-forum
 * @contact  laravel@88.com
 * @license  https://github.com/zhuchunshu/super-forum/blob/master/LICENSE
 */
namespace App\Plugins\Core\src\Handler;

use App\Plugins\User\src\Models\UserUpload;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class UploadHandler
{
    public function save($file, $folder, $file_prefix = null, $max_width = 1500): array
    {
        if (! auth()->check()) {
            if (! admin_auth()->Check()) {
                return [
                    'path' => '/404.jpg',
                    'success' => false,
                    'status' => '上传失败,未登录',
                ];
            }

            $user_id = 1;
        } else {
            $user_id = auth()->id();
        }
        if (! $file_prefix) {
            $file_prefix = Str::random();
        }
        // 构建存储的文件夹规则，值如：uploads/images/avatars/201709/21/
        // 文件夹切割能让查找效率更高。
        $folder_name = "upload/{$folder}/" . date('Ym/d', time());
        if (! is_dir(public_path($folder_name))) {
            mkdir(public_path($folder_name), 0777, true);
        }
        // 文件具体存储的物理路径，`public_path()` 获取的是 `public` 文件夹的物理路径。
        // 值如：/home/vagrant/Code/larabbs/public/uploads/images/avatars/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getExtension()) ?: 'png';

        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
        // 值如：1_1493521050_7BVc9v9ujP.png
        $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;

        // 将图片移动到我们的目标存储路径中
        $file->moveTo(public_path($folder_name . '/' . $filename));

        // 如果限制了图片宽度，就进行裁剪
        if ($max_width && $extension !== 'gif') {
            // 此类中封装的函数，用于裁剪图片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }
        UserUpload::query()->create([
            'user_id' => $user_id,
            'path' => public_path("{$folder_name}/{$filename}"),
            'url' => "/{$folder_name}/{$filename}",
        ]);
        return [
            'path' => "/{$folder_name}/{$filename}",
            'success' => true,
            'status' => '上传成功!',
        ];
    }

    public function reduceSize($file_path, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {
            // 设定宽度是 $max_width，高度等比例缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }
}
