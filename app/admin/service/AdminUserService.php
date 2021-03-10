<?php
/**
 * AdminUserService
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/23
 * Time: 18:11
 */

namespace app\admin\service;

use app\admin\model\AdminUser as AdminUserModel;
use app\common\utils\PassWordUtils;
use app\admin\service\AdminTokenService;
use think\facade\Db;

class AdminUserService
{
    /**
     * Notes   : 获取管理员信息
     * Author  : yxd
     * DateTime: 2021/2/24 10:05
     */
    public static function info($id){
        $info = AdminUserModel::find($id);
        if (empty($info)) {
            exception('用户不存在：' . $id);
        }
        $menu_ids = Db::name('admin_role')
            ->field('admin_role_id')
            ->where('admin_role_id', 'in', $info['admin_role_ids'])
            ->where('is_delete', 0)
            ->where('is_disable', 0)
            ->column('admin_menu_ids');

        $menu_ids[]   = $info['admin_menu_ids'];
        $menu_ids_str = implode(',', $menu_ids);
        $menu_ids_arr = explode(',', $menu_ids_str);
        $menu_ids     = array_unique($menu_ids_arr);
        $menu_ids     = array_filter($menu_ids);

        $where[] = ['admin_menu_id', 'in', $menu_ids];
        $where[] = ['is_delete', '=', 0];
        $where[] = ['is_disable', '=', 0];
        $where[] = ['path', '<>', ''];

        $where_un[] = ['is_delete', '=', 0];
        $where_un[] = ['is_disable', '=', 0];
        $where_un[] = ['path', '<>', ''];
        $where_un[] = ['is_unauth', '=', 1];

        $menu_url = Db::name('admin_menu')
            ->field('path')
            ->whereOr([$where, $where_un])
            ->column('path');
        $info['roles'] = $menu_url;
        $info['avatar'] = server_url().$info['avatar'];
        return $info;
    }

    /**
     * Notes   : 登录动作
     * Author  : yxd
     * DateTime: 2021/2/24 10:05
     */
    public static function login($param){
        $username = $param['username'];
        $password = PassWordUtils::admin_password_encrypt($param['password']);
        $field = 'id,username,nickname,login_num,is_disable';

        $where[] = ['username', '=', $username];
        $where[] = ['password', '=', $password];
        $where[] = ['is_delete', '=', 0];
        $adminUserModel = new AdminUserModel();
        $admin_user = $adminUserModel->field($field)->where($where)->find();

        if (empty($admin_user)) {
            exception('账号或密码错误');
        }

        if ($admin_user['is_disable'] == 1) {
            exception('账号已被禁用，请联系管理员');
        }
        $data['id'] = $admin_user['id'];
        $update = [
            'id'            => $admin_user['id'],
            'login_time'    => time(),
            'login_ip'      => $param['request_ip'],
        ];
        $data['token'] = AdminTokenService::create($update);
        return $data;
    }

    /**
     * Notes   : 退出登录
     * Author  : yxd
     * DateTime: 2021/2/24 14:12
     */
    public static function logout($admin_user_id)
    {
        $update['logout_time'] = date('Y-m-d H:i:s');

        Db::name('admin_user')
            ->where('id', $admin_user_id)
            ->update($update);
        $update['id'] = $admin_user_id;
        return $update;
    }
}