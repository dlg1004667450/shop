<?php
/**
 * @author wanghui <whlives@qq.com>
 * @version 1.0，20160202
 * @package 会员资金流水
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User_account_log_model extends CI_Model
{

    public function __construct()
    {
        /**
         * 载入数据库类
         */
        $this->load->database();
    }

    /**
     * 会员资金流水变动
     * @return array
     */
    public function insert($data)
    {
        $amount = (int)$data['amount'];
        if (empty($data['m_id'])) {
            return '用户id不能为空';
        } elseif (empty($data['event'])) {
            return '资金类型不能为空';
        } elseif (!isset($amount)) {
            return '金额不能为空';
        } elseif ($amount < 0) {
            return '金额不能小于0';
        } else {
            $member_data = self::get_member($data['m_id']);
            if (empty($member_data)) return '用户数据不存在';
            $type = self::get_type($data['event']);
            if ($type != 1 && $type != 2) return '类型错误';
            if ($type == 1) {
                $amount_log = $member_data['balance'] + $amount;
            } elseif ($type == 2) {
                $amount_log = $member_data['balance'] - $amount;
                if ($amount_log < 0) {
                    return '账户余额不足';
                }
            }
            $this->db->trans_start();
            $this->db->where(array('m_id' => $data['m_id']));
            $this->db->update('member_user', array('balance' => $amount_log));
            $update_data = array(
                'm_id'       => $data['m_id'],
                'amount'     => $amount,
                'amount_log' => $amount_log,
                'event'      => $data['event'],
                'addtime'    => time(),
                'admin_user' => $data['admin_user'],
                'note'       => $data['note'],
            );
            $this->db->insert('member_user_account_log', $update_data);
            $res_log = $this->db->insert_id();
            $this->db->trans_complete();
            if (!empty($res_log)) {
                return array('status'=>'y','id'=>$res_log);
            } else {
                return '处理失败';
            }
        }
    }

    /**
     * 会员信息
     * @return array
     */
    public function get_member($m_id)
    {
        $m_id = (int)$m_id;
        $this->load->model('loop_model');
        $member_data = $this->loop_model->get_where('member_user', array('m_id' => $m_id));
        if (!empty($member_data)) {
            return $member_data;
        }
    }

    /**
     * 资金类型
     * @return array
     */
    public function get_type($event)
    {
        $event = (int)$event;
        //1为增加2为减少
        switch ($event) {
            case 1:
                $type = 1;//充值
                break;
            case 2:
                $type = 2;//提现
                break;
            case 3:
                $type = 2;//订单支付
                break;
            case 4:
                $type = 1;//订单退款
                break;
            case 5:
                $type = 1;//提现退款
                break;
            case 6:
                $type = 1;//推荐奖励
                break;
            case 100:
                $type = 1;//订单结算
                break;
            default:
                $type = '';
        }
        //1为增加2为减少
        if ($type == 1 || $type == 2) {
            return $type;
        }
    }

    /**
     * 资金变动类型名称
     * @return array
     */
    public function get_type_name()
    {
        return array(1=>'充值',2=>'提现',3=>'订单支付',4=>'订单退款',5=>'提现退款',6=>'推荐奖励',100=>'订单结算');
    }
}
