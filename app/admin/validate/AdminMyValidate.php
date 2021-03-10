<?php
/**
 * AdminMyValidate
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/24
 * Time: 10:07
 */

namespace app\admin\validate;

use app\admin\service\AdminUserService;
use think\Db;

class AdminMyValidate extends \think\Validate
{
    protected $rule = [
        'id'            => ['require', 'checkAdminUser'],
        'username'      => ['require', 'checkUsername', 'length' => '2,32'],
        'nickname'      => ['require', 'checkNickname', 'length' => '1,32'],
        'password_old'  => ['require'],
        'password_new'  => ['require', 'length' => '6,18'],
        'avatar'        => ['require', 'file', 'image', 'fileExt' => 'jpg,png,gif', 'fileSize' => '51200'],
    ];

    // 错误信息
    protected $message = [
        'id.require'            => '缺少参数：用户id',
        'username.require'      => '请输入账号',
        'username.length'       => '账号长度为2至32个字符',
        'nickname.require'      => '请输入昵称',
        'nickname.length'       => '昵称长度为1至32个字符',
        'password_old.require'  => '请输入旧密码',
        'password_new.require'  => '请输入新密码',
        'password_new.length'   => '新密码长度为6至18个字符',
        'avatar.require'        => '请选择图片',
        'avatar.file'           => '请选择图片文件',
        'avatar.image'          => '请选择图片格式文件',
        'avatar.fileExt'        => '请选择jpg、png、gif格式图片',
        'avatar.fileSize'       => '请选择大小小于50kb图片',
    ];

    // 验证场景
    protected $scene = [
        'user_id'   => ['id'],
        'my_edit'   => ['id', 'username', 'nickname', 'email'],
        'my_pwd'    => ['id', 'password_old', 'password_new'],
        'my_avatar' => ['id', 'avatar'],

    ];

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
        $admin_user_id = $data['admin_user_id'];
        $username      = $data['username'];

        $admin_user = Db::name('admin_user')
            ->field('id')
            ->where('id', '<>', $admin_user_id)
            ->where('username', '=', $username)
            ->where('is_delete', '=', 0)
            ->find();

        if ($admin_user) {
            return '账号已存在：' . $username;
        }

        return true;
    }

    // 自定义验证规则：昵称是否已存在
    protected function checkNickname($value, $rule, $data = [])
    {
        $admin_user_id = $data['id'];
        $nickname      = $data['nickname'];

        $admin_user = Db::name('admin_user')
            ->field('id')
            ->where('id', '<>', $admin_user_id)
            ->where('nickname', '=', $nickname)
            ->where('is_delete', '=', 0)
            ->find();

        if ($admin_user) {
            return '昵称已存在：' . $nickname;
        }

        return true;
    }
}