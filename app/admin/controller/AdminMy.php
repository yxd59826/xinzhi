<?php
/**
 * AdminMy
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/24
 * Time: 10:04
 */

namespace app\admin\controller;


use app\BaseController;
use app\admin\validate\AdminMyValidate;
use app\admin\service\AdminUserService;

class AdminMy extends BaseController
{
    /**
     * Notes   : 我的信息
     * Author  : yxd
     * DateTime: 2021/2/24 10:06
     */
    public function myInfo()
    {
        $param['id'] = $this->request->get('admin_user_id/d', '');
        validate(AdminMyValidate::class)->scene('user_id')->check($param);
        $data = AdminUserService::info($param['id']);
        if ($data['is_delete'] == 1) {
            exception('账号信息错误，请重新登录！');
        }
        return success($data);
    }
}