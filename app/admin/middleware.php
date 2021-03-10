<?php
/**
 * middleware
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/24
 * Time: 15:11
 */

return [
    // 日志记录
//    \app\admin\middleware\AdminLog::class,
    // token验证
    \app\admin\middleware\AdminTokenVerify::class,
    // 权限验证
//    \app\admin\middleware\AdminRuleVerify::class,
    // 请求频率限制
//    \app\admin\middleware\AdminThrottle::class,
];