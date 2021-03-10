<?php
/**
 * Member
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/24
 * Time: 15:15
 */

namespace app\admin\model;


use think\Model;

class Member extends Model
{
    protected $autoWriteTimestamp = true;

    public function getAvatarAttr($value){
        return server_url().$value;
    }
}