<?php
/**
 * Created by PhpStorm.
 * User: okurashoichi
 * Date: 2019-02-14
 * Time: 20:26
 */

namespace App\domain;


class Base64Validation extends \Illuminate\Validation\Validator
{
    public function validateBase64($attribute, $value, $parameters)
    {
        //画像ファイルのサイズをbyte数で取得
        $fileSize = strlen($value);

        //1.ファイルサイズが設定値よりも大きいか確認。10MBまでの画像と想定し,base64は元データよりも150%のサイズになるため15000000とする。
        if ($fileSize >= config('filesystems.fileMaxSize')) {
            return false;
        }

        //画像ファイルのmimetypeを取得
        $mime_type = finfo_buffer(finfo_open(), $value, FILEINFO_MIME_TYPE);

        //2. デコードしたデータがMIMEタイプがimage/pngファイルか
        if (!strcmp($mime_type, 'image/png') == 0 && !strcmp($mime_type, 'image/jpeg') == 0) {
            return false;
        }


        return true;
    }
}