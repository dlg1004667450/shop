<?php
/**
 * @author wanghui <whlives@qq.com>
 * @version 1.0，20160202
 * @package 后台菜单
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model
{

    /**
     * 添加
     * @param array $data 添加数据
     */
    public function get_menu($type = 'order')
    {
        $menu_array = array(
            'goods' => array(
                '<i class="Hui-iconfont">&#xe620;</i> 商品管理' => array(
                    '商品管理' => '/shop/goods/goods/index',
                ),
                '<i class="Hui-iconfont">&#xe681;</i> 商品分类' => array(
                    '分类管理' => '/shop/goods/category/index',
                ),
            ),
            'order' => array(
                '<i class="Hui-iconfont">&#xe673;</i> 订单管理' => array(
                    '订单管理' => '/shop/order/order/index',
                ),
                '<i class="Hui-iconfont">&#xe637;</i> 单据管理' => array(
                    '退款申请' => '/shop/order/refund_doc/pending',
                    '提现记录' => '/shop/order/withdraw/index',
                    '资金流水' => '/shop/order/account_log/index',
                ),
                '<i class="Hui-iconfont">&#xe643;</i> 发货管理' => array(
                    '发货地址管理' => '/shop/order/address/index',
                ),
            ),
            'market' => array(
                '<i class="Hui-iconfont">&#xe6bb;</i> 促销管理' => array(
                    '促销管理' => '/shop/market/promotion/index',
                ),
                '<i class="Hui-iconfont">&#xe6ca;</i> 优惠券' => array(
                    '优惠券管理' => '/shop/market/coupons/index',
                ),
            ),
            'report' => array(
                '<i class="Hui-iconfont">&#xe695;</i> 操作记录' => array(
                    '后台记录' => '/shop/report/admin_log/index',
                ),
            ),
            'system' => array(
                '<i class="Hui-iconfont">&#xe61d;</i> 店铺设置' => array(
                    '店铺设置' => '/shop/system/setting/index',
                ),
                '<i class="Hui-iconfont">&#xe669;</i> 配送管理' => array(
                    '配送方式' => '/shop/system/delivery/index',
                ),
                '<i class="Hui-iconfont">&#xe605;</i> 权限' => array(
                    '管理员' => '/shop/system/admin/index',
                    '修改密码' => '/shop/system/admin/update_password',
                    '角色管理' => '/shop/system/role/index',
                ),
            ),
        );
        return $menu_array[$type];
    }
}
