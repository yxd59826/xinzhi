<?php
/**
 * AdminTokenService
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/24
 * Time: 11:07
 */

namespace app\admin\service;

use app\admin\service\AdminSettingService;
use think\facade\Config;
use Firebase\JWT\JWT;

class AdminTokenService
{
    /**
     * Notes   : Token生成
     * Author  : yxd
     * DateTime: 2021/2/24 13:09
     * @param array $admin_user
     * @return mixed
     */
    public static function create($admin_user = [])
    {
        $admin_setting = AdminSettingService::admin_setting();
        $admin_token   = $admin_setting['admin_token'];

        $key = Config::get('admin.token_key');       //密钥
        $iss = $admin_token['iss'];                  //签发者
        $iat = time();                               //签发时间
        $nbf = time();                               //生效时间
        $exp = time() + $admin_token['exp'] * 3600;  //过期时间

        $data = [
            'id' => $admin_user['id'],
            'login_time'    => $admin_user['login_time'],
            'login_ip'      => $admin_user['login_ip'],
        ];

        $payload = [
            'iss'  => $iss,
            'iat'  => $iat,
            'nbf'  => $nbf,
            'exp'  => $exp,
            'data' => $data,
        ];

        $token = JWT::encode($payload, $key);

        return $token;
    }

    /**
     * Notes   : token解析
     * Author  : yxd
     * DateTime: 2021/2/24 13:10
     * @param $token
     * @param int $admin_user_id
     * @throws \think\Exception
     */
    public static function verify($token, $admin_user_id = 0)
    {
        try {
            $key    = Config::get('admin.token_key');
            $decode = JWT::decode($token, $key, array('HS256'));
        } catch (\Exception $e) {
            exception('账号登录状态已过期', 401);
        }

        $admin_user_id_token = $decode->data->id;

        if ($admin_user_id != $admin_user_id_token) {
            exception('账号请求信息错误', 401);
        }
    }
}