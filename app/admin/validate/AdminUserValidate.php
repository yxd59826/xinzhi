<?php
/**
 * AdminUserValidate
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/23
 * Time: 18:08
 */

namespace app\admin\validate;

use app\admin\service\AdminUserService;
use think\facade\Db;

class AdminUserValidate extends \think\Validate
{
    // 验证规则
    protected $rule = [
        'id'            => ['require', 'checkAdminUser'],
        'username'      => ['require', 'checkUsername', 'length' => '2,32'],
        'nickname'      => ['require', 'checkNickname', 'length' => '1,32'],
        'password'      => ['require', 'length' => '6,18'],
        'avatar'        => ['require', 'file', 'image', 'fileExt' => 'jpg,png,gif', 'fileSize' => '512000'],
    ];

    // 错误信息
    protected $message = [
        'id.require'            => '缺少参数：用户id',
        'username.require'      => '请输入账号',
        'username.length'       => '账号长度为2至32个字符',
        'nickname.require'      => '请输入昵称',
        'nickname.length'       => '昵称长度为1至32个字符',
        'password.require'      => '请输入密码',
        'password.length'       => '密码长度为6至18个字符',
        'avatar.require'        => '请选择图片',
        'avatar.file'           => '请选择图片文件',
        'avatar.image'          => '请选择图片格式文件',
        'avatar.fileExt'        => '请选择jpg、png、gif格式图片',
        'avatar.fileSize'       => '请选择大小小于500kb图片',
    ];

    // 验证场景
    protected $scene = [
        'user_id'      => ['id'],
        'user_login'   => ['username', 'password'],
        'user_add'     => ['username', 'nickname', 'password'],
        'user_edit'    => ['id', 'username', 'nickname'],
        'user_dele'    => ['id'],
        'user_admin'   => ['id'],
        'user_disable' => ['id'],
        'user_rule'    => ['id'],
        'user_pwd'     => ['id', 'password'],
        'user_avatar'  => ['id', 'avatar'],

    ];

    // 验证场景定义：登录
    protected function sceneuser_login()
    {
        return $this->only(['username', 'password'])
            ->remove('username', ['length', 'checkUsername'])
            ->remove('password', ['length']);
    }

    // 自定义验证规则：用户是否存在
    protected function checkAdminUser($value, $rule, $data = [])
    {
        $admin_user_id = $value;

        $admin_user = AdminUserService::info($admin_user_id);

        if ($admin_user['is_delete'] == 1) {
            return '用户已被删除：' . $admin_user_id;
        }

        return true;
    }

    // 自定义验证规则：账号是否已存在
    protected function checkUsername($value, $rule, $data = [])
    {
        $admin_user_id = isset($data['id']) ? $data['id'] : '';
        $username      = $data['username'];

        if ($admin_user_id) {
            $where[] = ['id', '<>', $admin_user_id];
        }
        $where[] = ['username', '=', $username];
        $where[] = ['is_delete', '=', 0];

        $admin_user = Db::name('admin_user')
            ->field('id')
            ->where($where)
            ->find();

        if ($admin_user) {
            return '账号已存在：' . $username;
        }

        return true;
    }
}