<?php
/**
 * Region
 * Created by PhpStorm.
 * User: yxd
 * Date: 2021/2/25
 * Time: 10:32
 */

namespace app\admin\controller;


use app\BaseController;

class Region extends BaseController
{
    /**
     * Notes   : 地区列表
     * Author  : yxd
     * DateTime: 2021/2/25 10:33
     */
    public function regionList()
    {
        $region_pid    = Request::param('region_pid/d', 0) ?: 0;
        $region_name   = Request::param('region_name/s', '');
        $region_pinyin = Request::param('region_pinyin/s', '');
        $sort_field    = Request::param('sort_field/s ', '');
        $sort_type     = Request::param('sort_type/s', '');

        if ($region_name || $region_pinyin) {
            if ($region_name) {
                $where[] = ['region_name', '=', $region_name];
            }
            if ($region_pinyin) {
                $where[] = ['region_pinyin', '=', $region_pinyin];
            }
        } else {
            $where[] = ['region_pid', '=', $region_pid];
        }

        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }

        $data = RegionService::list($where, $order);

        return success($data);
    }
}