<?php
/**
 * AdminLogin
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/23
 * Time: 18:06
 */

namespace app\admin\controller;


use app\BaseController;
use app\admin\validate\AdminUserValidate;
use app\admin\service\AdminUserService;

class AdminLogin extends BaseController
{
    /**
     * Notes   : 登录接口
     * Author  : yxd
     * DateTime: 2021/2/24 10:05
     */
    public function index(){
        $param['username'] = $this->request->post('username/s','');
        $param['password'] = $this->request->post('password/s','');
        $param['request_ip'] = $this->request->ip();
        validate(AdminUserValidate::class)->scene('user_login')->check($param);
        $data = AdminUserService::login($param);
        return success($data, '登录成功');
    }

    public function logout()
    {
        $param['id'] = $this->request->post('admin_user_id/s','');

        validate(AdminUserValidate::class)->scene('user_id')->check($param);

        $data = AdminUserService::logout($param['id']);

        return success($data, '退出成功');
    }
}