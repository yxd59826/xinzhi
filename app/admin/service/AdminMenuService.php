<?php
/**
 * AdminMenuService
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/25
 * Time: 15:13
 */

namespace app\admin\service;

use app\admin\model\AdminMenu;

class AdminMenuService
{
    /**
     * Notes   : 菜单列表
     * Author  : yxd
     * DateTime: 2021/2/25 15:14
     */
    public static function list($pid = 0){
        $where[] = ['is_delete', '=', 0];
        $where[] = ['menu_pid', '=', $pid];
        $order = ['menu_sort' => 'desc', 'admin_menu_id' => 'asc'];
        $adminMenuModel = new AdminMenu();
        $list = $adminMenuModel
            ->where($where)
            ->order($order)
            ->select()
            ->toArray();
        foreach ($list as $key=>$value){
            $children = self::hasTree($value['admin_menu_id']);
            if ($children){
                $list[$key]['hasChildren'] = true;
            }else{
                $list[$key]['hasChildren'] = false;
            }
        }
        $menu['list'] = $list;
        return $menu;
    }

    public static function hasTree($pid){
        $adminMenuModel = new AdminMenu();
        return $adminMenuModel->where('menu_pid', $pid)->count();
    }

    public static function makeTree($admin_menu, $menu_pid)
    {
        $tree = [];

        foreach ($admin_menu as $k => $v) {
            if ($v['menu_pid'] == $menu_pid) {
                $v['children'] = self::makeTree($admin_menu, $v['admin_menu_id']);
                $tree[] = $v;
            }
        }

        return $tree;
    }

    public static function buildMenus(){
        $where[] = ['is_delete', '=', 0];
        $order = ['menu_sort' => 'desc', 'admin_menu_id' => 'asc'];
        $field = 'admin_menu_id,menu_pid,menu_name as name,title,component,path,hidden,menu_icon as icon,cache';
        $adminMenuModel = new AdminMenu();
        $list = $adminMenuModel
            ->where($where)
            ->order($order)
            ->field($field)
            ->select()
            ->toArray();
        $tree = self::makeTree($list,0);
        return $tree;
    }
}