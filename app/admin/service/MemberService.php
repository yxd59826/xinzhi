<?php
/**
 * MemberService
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/24
 * Time: 15:01
 */

namespace app\admin\service;

use app\admin\model\Member;
use think\facade\Filesystem;

class MemberService
{
    /**
     * Notes   : 分页获取用户
     * Author  : yxd
     * DateTime: 2021/2/24 15:16
     * @param array   $where   条件
     * @param integer $page    页数
     * @param integer $limit   数量
     * @param array   $order   排序
     * @param string  $field   字段
     *
     * @return array
     */
    public static function list($where = [], $page = 1, $limit = 10, $order = [], $field = '')
    {
        $memberModel = new Member();
        if (empty($field)) {
            $field = 'id,username,nickname,avatar,phone,email,sort,remark,create_time,login_time,is_disable';
        }

        $where[] = ['is_delete', '=', 0];

        if (empty($order)) {
            $order = ['id' => 'desc','sort' => 'desc'];
        }

        $count = $memberModel
            ->where($where)
            ->count('id');

        $list = $memberModel
            ->field($field)
            ->append(['avatar'])
            ->where($where)
            ->page($page)
            ->limit($limit)
            ->order($order)
            ->select()
            ->toArray();
        $pages = ceil($count / $limit);

        $data['count'] = $count;
        $data['pages'] = $pages;
        $data['page']  = $page;
        $data['limit'] = $limit;
        $data['list']  = $list;

        return $data;
    }

    /**
     * Notes   : 会员信息
     * Author  : yxd
     * DateTime: 2021/2/25 10:43
     */
    public static function info($id)
    {
        $memberModel = new Member();
        $member = $memberModel->where('id', $id)->find();
        if (empty($member)) {
            exception('会员不存在：' . $id);
        }
        $member['avatar'] = server_url().$member['avatar'];
        return $member;
    }

    /**
     * Notes   : 头像上传
     * Author  : yxd
     * DateTime: 2021/2/25 13:51
     */
    public static function avatar($param)
    {
        $member_id = $param['id'];
        $avatar    = $param['avatar'];

        $avatar_name = Filesystem::disk('public')
            ->putFile('member', $avatar, function () use ($member_id) {
                return $member_id . '/' . $member_id . '_avatar';
            });

        $update['avatar']      = '/storage/' . $avatar_name . '?t=' . date('YmdHis');
        $update['update_time'] = date('Y-m-d H:i:s');
        $memberModel = new Member();
        $res = $memberModel->where('id', $member_id)->update($update);

        if (empty($res)) {
            exception();
        }
        return $update;
    }
}