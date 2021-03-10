<?php
/**
 * AdminSettingService
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/24
 * Time: 11:11
 */

namespace app\admin\service;

use think\facade\Db;
use think\facade\App;

class AdminSettingService
{
    private static $admin_setting_id = 1;

    /**
     * Notes   : 获取管理员配置
     * Author  : yxd
     * DateTime: 2021/2/24 11:14
     */
    public static function admin_setting(){
        $admin_setting_id = self::$admin_setting_id;
        $admin_setting = Db::name('admin_setting')
            ->where('admin_setting_id', $admin_setting_id)
            ->find();
        if (empty($admin_setting)) {
            $admin_setting['admin_setting_id'] = $admin_setting_id;
            $admin_setting['admin_verify']     = serialize([]);
            $admin_setting['admin_token']      = serialize([]);
            $admin_setting['create_time']      = date('Y-m-d H:i:s');
            Db::name('admin_setting')
                ->insert($admin_setting);
        }

        // Token
        $admin_token = unserialize($admin_setting['admin_token']);
        if (empty($admin_token)) {
            $admin_token['iss'] = 'ydAdmin';  //签发者
            $admin_token['exp'] = 12;          //有效时间（小时）
        }

        $admin_setting['admin_token']  = serialize($admin_token);
        $admin_setting['update_time']  = date('Y-m-d H:i:s');
        Db::name('admin_setting')
            ->where('admin_setting_id', $admin_setting_id)
            ->update($admin_setting);
        $admin_setting['admin_token']  = $admin_token;

        return $admin_setting;
    }
}