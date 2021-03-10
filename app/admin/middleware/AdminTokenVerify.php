<?php
/**
 * AdminTokenVerify
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/24
 * Time: 16:26
 */

namespace app\admin\middleware;

use Closure;
use think\facade\Config;
use app\admin\service\AdminTokenService;

class AdminTokenVerify
{
    public function handle($request, Closure $next)
    {
        $menu_url       = request_pathinfo();
        $api_white_list = Config::get('admin.api_white_list');
        if (!in_array($menu_url, $api_white_list)) {

            $admin_token = admin_token();

            if (empty($admin_token)) {
                exception('Requests Headers：AdminToken must');
            }

            $admin_user_id = admin_user_id();

            if (empty($admin_user_id)) {
                exception('Requests Headers：AdminUserId must');
            }

            AdminTokenService::verify($admin_token, $admin_user_id);
        }

        return $next($request);
    }
}