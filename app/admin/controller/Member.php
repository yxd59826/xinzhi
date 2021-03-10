<?php
/**
 * Member
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/24
 * Time: 14:58
 */

namespace app\admin\controller;


use app\BaseController;
use app\admin\service\MemberService;
use app\admin\validate\MemberValidate;
use app\admin\model\Member as MemberModel;
use think\facade\Db;

class Member extends BaseController
{
    /**
     * Notes   : 用户列表
     * Author  : yxd
     * DateTime: 2021/2/24 15:13
     */
    public function memberList(){
        $page       = $this->request->get('page/d', 1);
        $limit      = $this->request->get('limit/d', 10);
        $id         = $this->request->get('id/d', '');
        $username   = $this->request->get('username/s', '');
        $phone      = $this->request->get('phone/s', '');
        $email      = $this->request->get('email/s', '');
        $date_type  = $this->request->get('date_type/s', '');
        $date_range = $this->request->get('date_range/a', []);
        $where = [];
        if ($id) {
            $where[] = ['id', '=', $id];
        }
        if ($username) {
            $where[] = ['username', 'like', '%' . $username . '%'];
        }
        if ($phone) {
            $where[] = ['phone', 'like', '%' . $phone . '%'];
        }
        if ($email) {
            $where[] = ['email', 'like', '%' . $email . '%'];
        }
        if ($date_type && $date_range) {
            $where[] = [$date_type, '>=', $date_range[0] . ' 00:00:00'];
            $where[] = [$date_type, '<=', $date_range[1] . ' 23:59:59'];
        }
        $order = [];
        $field = '';
        $data = MemberService::list($where, $page, $limit, $order, $field);
        return success($data);
    }

    /**
     * Notes   : 会员添加
     * Author  : yxd
     * DateTime: 2021/2/24 17:48
     */
    public function memberAdd(){
        $param = $this->request->post();
        validate(MemberValidate::class)->scene('member_add')->check($param);
        $memberModel = new MemberModel();
        Db::startTrans();
        try {
            $member = $memberModel->save($param);
            Db::commit();
            return success($member,'添加成功');
        } catch (\Exception $e) {
            Db::rollback();
            return error();
        }
    }

    /**
     * Notes   : 会员更新
     * Author  : yxd
     * DateTime: 2021/2/25 13:21
     */
    public function memberEdit(){
        $param = $this->request->post();
        validate(MemberValidate::class)->scene('member_edit')->check($param);
        $memberModel = new MemberModel();
        Db::startTrans();
        try {
            $member = $memberModel->update($param);
            Db::commit();
            return success($member,'更新成功');
        } catch (\Exception $e) {
            Db::rollback();
            return error();
        }
    }

    /**
     * Notes   : 头像上传
     * Author  : yxd
     * DateTime: 2021/2/25 13:47
     */
    public function memberAvatar()
    {
        $param['id'] = $this->request->post('id/d');
        $param['avatar']    = $this->request->file('avatar_file');

        validate(MemberValidate::class)->scene('member_avatar')->check($param);

        $data = MemberService::avatar($param);
        return success($data);
    }
}