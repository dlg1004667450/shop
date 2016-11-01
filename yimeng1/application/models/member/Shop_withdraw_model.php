<?php
/**
 * @author wanghui <whlives@qq.com>
 * @version 1.0，20160202
 * @package 店铺提现
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop_withdraw_model extends CI_Model
{

    public function __construct()
    {
        /**
         * 载入数据库类
         */
        $this->load->database();
    }

    /**
     * 提现审核
     * @param array $id 提现申请id
     * @param int $status 提现申请状态
     * @return array
     */
    public function update_status($id = '', $status = '')
    {
        $status = (int)$status;
        if (!empty($id) && !empty($status)) {
            $this->load->model('loop_model');
            $member_shop_withdraw_data = $this->loop_model->get_id('member_shop_withdraw', $id);
            if ($member_shop_withdraw_data['status'] == 0) {
                $update_data = array(
                    'status' => $status,
                    'endtime' => time(),
                );
                $this->db->trans_start();
                //修改提现申请状态
                if (is_array($id)) {
                    $this->db->where_in('id', $id);
                    $id = join(',', $id);
                } else {
                    $this->db->where(array('id' => $id));
                }
                $this->db->update('member_shop_withdraw', $update_data);

                //修改订单结算状态
                $order_id_list = json_decode($member_shop_withdraw_data['order_id'], true);
                if ($status == 2) {
                    //修改订单结算状态为未结算
                    $update_order_data = array('is_shop_checkout' => '0');
                } elseif ($status == 1) {
                    //修改订单结算状态为已经结算
                    $update_order_data = array('is_shop_checkout' => '1');
                }
                $this->db->where_in('id', $order_id_list);
                $res = $this->db->update('order', array('is_shop_checkout' => '0'));

                $this->db->trans_complete();
                if ($res) {
                    admin_log_insert('修改店铺提现status为' . $status . 'id为' . $id);
                    return 'y';
                } else {
                    return '处理失败';
                }
            } else {
                return '本条信息已经处理过,不能重复操作';
            }
        }
    }
}
