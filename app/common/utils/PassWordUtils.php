<?php
/**
 * PassWordUtils
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/24
 * Time: 8:46
 */

namespace app\common\utils;


class PassWordUtils
{
    private static $admin_openssl_key = 'ydgw_2021_02_24_admin&&hbyd##';
    private static $admin_en_method = 'AES-256-ECB';

    private static $member_openssl_key = 'ydgw_2021_02_24_member&&hbyd##';
    private static $member_en_method = 'AES-256-ECB';

    public static function admin_password_encrypt($password)
    {
        $pwd_en = openssl_encrypt($password, self::$admin_en_method, self::$admin_openssl_key);
        return $pwd_en;
    }

    public static function admin_password_decrypt($password)
    {
        $pwd_de = openssl_decrypt($password, self::$admin_en_method, self::$admin_openssl_key);
        return $pwd_de;
    }

}