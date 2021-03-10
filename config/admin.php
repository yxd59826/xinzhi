<?php
/**
 * ${NAME}
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/24
 * Time: 11:15
 */

return [
    // 系统管理员id
    'admin_ids' => [1],
    // 是否记录日志
    'is_log' => true,
    // token密钥
    'token_key' => '58o6dAEZ4Jbb',
    // 请求头部token键名
    'admin_token_key' => 'AdminToken',
    // 请求头部user_id键名
    'admin_user_id_key' => 'AdminUserId',
    // 接口白名单
    'api_white_list' => [
        'admin/AdminLogin/login',
        'admin/AdminCode/getCode'
    ],
    // 权限白名单
    'rule_white_list' => [
        'admin/AdminMy/myInfo',
        'admin/AdminLogin/logout',
        'admin/AdminCode/getCode'
    ],
    // 请求频率限制（次数/时间）
    'throttle' => [
        'number' => 3, //次数,0不限制
        'expire' => 1, //时间,单位秒
    ],
];