<?php
/**
 * AdminLog
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/3/3
 * Time: 15:23
 */

namespace app\admin\middleware;

use Closure;
use think\Request;
use think\Response;
use think\facade\Config;

class AdminLog
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $is_log = Config::get('admin.is_log', false);
        if ($is_log){

        }
    }
}

