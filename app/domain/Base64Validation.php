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
        //jsonのBase64データの場合
        if(strcmp($attribute, 'image') == 0)
        {
            //jsonで渡されたデータが、base64か
            if(!strpos($value,'base64'))
            {
                return false;
            }
         //encode後のデータの場合
        }elseif(strcmp($attribute, 'decodeImage') == 0)
        {
            //画像ファイルのmimetypeを取得
            $mime_type = finfo_buffer(finfo_open(), $value, FILEINFO_MIME_TYPE);
            //デコードに成功したか
            if(!$value)
            {
                return false;
            }

            //デコードしたデータがpngファイルか
            if(!strcmp($mime_type,'image/png') == 0)
            {
                return false;
            }

        }

        return true;
    }
}