<?php
/**
 * MemberValidate
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/25
 * Time: 10:40
 */

namespace app\admin\validate;

use app\admin\service\MemberService;
use app\common\utils\PassWordUtils;
use think\Validate;
use think\facade\Db;

class MemberValidate extends Validate
{
    // 验证规则
    protected $rule = [
        'id' => ['require', 'checkMember'],
        'username' => ['require', 'alphaDash', 'checkUsername', 'length' => '2,32'],
        'nickname' => ['checkNickname', 'length' => '1,32'],
        'password' => ['require', 'alphaNum', 'length' => '6,18'],
        'password_old' => ['require', 'checkPwdOld'],
        'password_new' => ['require', 'alphaNum', 'length' => '6,18'],
        'phone' => ['mobile', 'checkPhone'],
        'email' => ['email', 'checkEmail'],
        'avatar' => ['require', 'file', 'image', 'fileExt' => 'jpg,png,jpeg,gif,dmp', 'fileSize' => '102400'],
    ];

    // 错误信息
    protected $message = [
        'id.require' => '缺少参数：会员id',
        'username.require' => '请输入账号',
        'username.length' => '账号长度为2至32个字符',
        'username.alphaDash' => '账号由字母、数字、下划线、破折号组成',
        'nickname.require' => '请输入昵称',
        'nickname.length' => '昵称长度为1至32个字符',
        'password.require' => '请输入密码',
        'password.length' => '密码长度为6至18个字符',
        'password.alphaNum' => '密码只能为数字和字母',
        'password_old.require' => '请输入旧密码',
        'password_new.require' => '请输入新密码',
        'password_new.length' => '新密码长度为6至18个字符',
        'password_new.alphaNum' => '新密码只能为数字和字母',
        'phone.mobile' => '请输入正确的手机号码',
        'email.email' => '请输入正确的邮箱地址',
        'avatar.require' => '请选择图片',
        'avatar.file' => '请选择图片文件',
        'avatar.image' => '请选择图片格式文件',
        'avatar.fileExt' => '请选择jpg、png格式图片',
        'avatar.fileSize' => '请选择大小小于100kb图片',
    ];

    // 验证场景
    protected $scene = [
        'member_id' => ['id'],
        'member_add' => ['username', 'nickname', 'password', 'phone', 'email'],
        'member_edit' => ['id', 'username', 'nickname', 'phone', 'email'],
        'member_dele' => ['id'],
        'member_password' => ['id', 'password'],
        'member_pwdedit' => ['id', 'password_old', 'password_new'],
        'member_disable' => ['id'],
        'member_avatar' => ['id', 'avatar'],
        'member_register' => ['username', 'nickname', 'password', 'phone', 'email'],
        'member_login' => ['username', 'password'],
    ];

    // 验证场景定义：登录
    protected function scenemember_login()
    {
        return $this->only(['username', 'password'])
            ->remove('username', ['length', 'alphaNum', 'checkUsername'])
            ->remove('password', ['length', 'alphaNum']);
    }

    // 自定义验证规则：会员是否存在
    protected function checkMember($value, $rule, $data = [])
    {
        $member_id = $value;

        $member = MemberService::info($member_id);

        if ($member['is_delete'] == 1) {
            return '会员已被删除：' . $member_id;
        }

        return true;
    }

    // 自定义验证规则：账号是否已存在
    protected function checkUsername($value, $rule, $data = [])
    {
        $member_id = isset($data['id']) ? $data['id'] : '';
        $username = $data['username'];
        if ($member_id) {
            $where[] = ['id', '<>', $member_id];
        }
        $where[] = ['username', '=', $username];
        $where[] = ['is_delete', '=', 0];

        $member = Db::name('member')
            ->field('id')
            ->where($where)
            ->find();

        if ($member) {
            return '账号已存在：' . $username;
        }

        return true;
    }

    // 自定义验证规则：昵称是否已存在
    protected function checkNickname($value, $rule, $data = [])
    {
        $member_id = isset($data['id']) ? $data['id'] : '';
        $nickname = $data['nickname'];

        if ($member_id) {
            $where[] = ['id', '<>', $member_id];
        }
        $where[] = ['nickname', '=', $nickname];
        $where[] = ['is_delete', '=', 0];

        $member = Db::name('member')
            ->field('id')
            ->where($where)
            ->find();

        if ($member) {
            return '昵称已存在：' . $nickname;
        }

        return true;
    }

    // 自定义验证规则：手机是否已存在
    protected function checkPhone($value, $rule, $data = [])
    {
        $member_id = isset($data['id']) ? $data['id'] : '';
        $phone = $data['phone'];

        if ($member_id) {
            $where[] = ['id', '<>', $member_id];
        }
        $where[] = ['phone', '=', $phone];
        $where[] = ['is_delete', '=', 0];

        $member = Db::name('member')
            ->field('id')
            ->where($where)
            ->find();

        if ($member) {
            return '手机已存在：' . $phone;
        }

        return true;
    }

    // 自定义验证规则：邮箱是否已存在
    protected function checkEmail($value, $rule, $data = [])
    {
        $member_id = isset($data['id']) ? $data['id'] : '';
        $email = $data['email'];

        if ($member_id) {
            $where[] = ['id', '<>', $member_id];
        }
        $where[] = ['email', '=', $email];
        $where[] = ['is_delete', '=', 0];

        $member = Db::name('member')
            ->field('id')
            ->where($where)
            ->find();

        if ($member) {
            return '邮箱已存在：' . $email;
        }

        return true;
    }

    // 自定义验证规则：旧密码是否正确
    protected function checkPwdOld($value, $rule, $data = [])
    {
        $member_id = isset($data['id']) ? $data['id'] : '';
        $member = MemberService::info($member_id);
        $password = $member['password'];
        $password_old = PassWordUtils::admin_password_encrypt($data['password_old']);

        if ($password != $password_old) {
            return '旧密码错误';
        }

        return true;
    }
}