<?php
// admin应用公共文件

use think\facade\Config;
use think\facade\Request;

function admin_token()
{
    $admin_token_key = Config::get('admin.admin_token_key');
    $admin_token     = Request::header($admin_token_key, '');

    return $admin_token;
}

/**
 * 获取请求用户id
 *
 * @return integer
 */
function admin_user_id()
{
    $admin_user_id_key = Config::get('admin.admin_user_id_key');

    $admin_user_id     = Request::header($admin_user_id_key, '');

    return $admin_user_id;
}