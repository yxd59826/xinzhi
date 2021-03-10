<?php
/**
 * AdminMenu
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/25
 * Time: 15:04
 */

namespace app\admin\controller;


use app\BaseController;
use app\admin\service\AdminMenuService;
use app\admin\model\AdminMenu as AdminMenuModel;
use think\facade\Db;

class AdminMenu extends BaseController
{
    /**
     * Notes   : 菜单列表
     * Author  : yxd
     * DateTime: 2021/2/25 15:40
     */
    public function menuList()
    {
        $pid = $this->request->get('pid',0);
        $data = AdminMenuService::list($pid);
        return success($data);
    }

    /**
     * Notes   : 菜单添加
     * Author  : yxd
     * DateTime: 2021/3/1 11:00
     */
    public function menuAdd(){
        $param = $this->request->post();
        $adminMenuModel = new AdminMenuModel();
        Db::startTrans();
        try {
            $menu = $adminMenuModel->save($param);
            Db::commit();
            return success($menu,'添加成功');
        } catch (\Exception $e) {
            Db::rollback();
            return error($e);
        }
    }

    /**
     * Notes   : 菜单编辑
     * Author  : yxd
     * DateTime: 2021/3/1 11:01
     */
    public function menuEdit(){
        $param = $this->request->post();
        $admin_menu_id = $param['admin_menu_id'];
        $adminMenuModel = new AdminMenuModel();
        Db::startTrans();
        try {
            unset($param['admin_menu_id']);
            unset($param['hasChildren']);
            $param['update_time'] = date('Y-m-d H:i:s');
            $menu = $adminMenuModel->where('admin_menu_id', '=', $admin_menu_id)->update($param);
            Db::commit();
            return success($menu,'更新成功');
        } catch (\Exception $e) {
            Db::rollback();
            return error($e);
        }
    }

    public function buildMenus(){
        $data = AdminMenuService::buildMenus();
        return success($data);
    }
}