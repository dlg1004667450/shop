<?php
/**
 * @author  wanghui <whlives@qq.com>
 * @version 1.0，20160202
 * @package 用户充值
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Balance extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helpers('web_helper');
        $this->member_data = member_login();
        $this->load->model('loop_model');
    }

    /**
     * 在线充值
     */
    public function online_recharge()
    {
        display('default/member/balance/online_recharge.html');
    }

    /**
     * 在线充值提交
     */
    public function online_recharge_act()
    {
        if (is_post()) {
            $data_post = $this->input->post(NULL, true);
            if (empty($data_post['amount'])) msg('充值金额不能为空');
            if (empty($data_post['payment_id'])) msg('请选择充值方式');
            $amount = price_format($data_post['amount']);
            if ($amount <= 0) {
                msg('充值金额不能小等于0');
            } else {
                //查询支付方式
                $payment_data = $this->loop_model->get_id('payment', $data_post['payment_id']);
                if ($payment_data['status'] == 0) {
                    //开始添加充值单
                    $online_recharge_data = array(
                        'm_id'         => $this->member_data['id'],
                        'recharge_no'  => date('YmdHis') . get_rand_num('int', 6),
                        'amount'       => $amount,
                        'addtime'      => time(),
                        'payment_id'   => $payment_data['id'],
                        'payment_name' => $payment_data['name'],
                    );
                    $res                  = $this->loop_model->insert('member_user_online_recharge', $online_recharge_data);
                    if (!empty($res)) {
                        header('location:' . site_url('/api/pay/do_pay?client=web&recharge_no=' . $online_recharge_data['recharge_no']));
                    } else {
                        msg('提交失败');
                    }
                } else {
                    msg('充值方式不存在');
                }
            }
        } else {
            error_json('提交方式错误');
        }
    }

    /**
     * 提现申请
     */
    public function withdraw()
    {
        $member_user_data            = $this->loop_model->get_where('member_user', array('m_id' => $this->member_data['id']));
        $member_user_data['balance'] = format_price($member_user_data['balance']);
        assign('member_user_data', $member_user_data);
        display('default/member/balance/withdraw.html');
    }

    /**
     * 提现申请处理
     */
    public function withdraw_act()
    {
        if (is_post()) {
            $data_post = $this->input->post(NULL, true);
            if (empty($data_post['amount'])) error_json('提现金额不能为空');
            if (empty($data_post['name'])) error_json('姓名不能为空');
            if (empty($data_post['pay_number'])) error_json('账户不能为空');
            if ($data_post['type'] == 1 && empty($data_post['bank_name'])) error_json('开户银行不能为空');
            $member_user_data = $this->loop_model->get_where('member_user', array('m_id' => $this->member_data['id']));
            $amount           = price_format($data_post['amount']);
            if ($amount <= 0) {
                error_json('提现金额不能小等于0');
            } elseif ($member_user_data['balance'] < $amount) {
                error_json('账户余额不足');
            } else {
                //开始扣除资金
                $this->load->model('member/user_account_log_model');
                $data   = array(
                    'm_id'   => $this->member_data['id'],
                    'amount' => $amount,
                    'event'  => 2,
                    'note'   => '用户申请提现',
                );
                $log_id = $this->user_account_log_model->insert($data);
                if ($log_id['status'] == 'y') {
                    //查询是否扣款成功
                    $log_data = $this->loop_model->get_id('member_user_account_log', $log_id['id']);
                    if (!empty($log_data)) {
                        //开始添加提现单
                        $withdraw_data = array(
                            'm_id'       => $this->member_data['id'],
                            'amount'     => $amount,
                            'name'       => $data_post['name'],
                            'bank_name'  => $data_post['bank_name'],
                            'pay_number' => $data_post['pay_number'],
                            'type'       => $data_post['type'],
                            'addtime'    => time(),
                            'note'       => $data_post['note'],
                        );
                        $res           = $this->loop_model->insert('member_user_withdraw', $withdraw_data);
                        if (!empty($res)) {
                            error_json('y');
                        } else {
                            error_json('申请失败');
                        }
                    } else {
                        error_json('申请失败');
                    }
                }
            }
        } else {
            error_json('提交方式错误');
        }
    }

    /**
     * 资金流水
     */
    public function account_log()
    {
        $pagesize = 20;//分页大小
        $page     = (int)$this->input->get('per_page');
        $page <= 1 ? $page = 1 : $page = $page;//当前页数
        //搜索条件start
        $event = $this->input->get_post('event');
        if (!empty($event)) $where_data['where']['event'] = $event;

        $search_where = array(
            'event' => $event,
        );
        assign('search_where', $search_where);
        //搜索条件end
        $where_data['where']['m_id'] = $this->member_data['id'];//过滤用户
        //查到数据
        $list_data = $this->loop_model->get_list('member_user_account_log', $where_data, $pagesize, $pagesize * ($page - 1), 'id desc');//列表
        foreach ($list_data as $key) {
            $key['amount'] = format_price($key['amount']);
            $list[]        = $key;
        }
        assign('list', $list);
        //开始分页start
        $all_rows = $this->loop_model->get_list_num('member_user_account_log', $where_data);//所有数量;
        assign('page_count', ceil($all_rows / $pagesize));
        //开始分页end

        //自己类型
        $this->load->model('member/user_account_log_model');
        assign('event_name', $this->user_account_log_model->get_type_name());

        $member_user_data            = $this->loop_model->get_where('member_user', array('m_id' => $this->member_data['id']));
        $member_user_data['balance'] = format_price($member_user_data['balance']);
        assign('member_user_data', $member_user_data);
        display('default/member/balance/account_log.html');
    }
}
