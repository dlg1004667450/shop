<?php
/**
 * @author  wanghui <whlives@qq.com>
 * @version 1.0 20160201
 * @package 找回密码
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Find_password extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loop_model');
    }

    /**
     * 信息验证
     */
    public function find_password_act()
    {
        if (is_post()) {
            $username = trim($this->input->post('username', true));
            $password = trim($this->input->post('password', true));
            $code     = (int)trim($this->input->post('code', true));
            if (!empty($username) && !empty($password)) {
                if (!empty($code)) {
                    $server_code = cache('get', 'sms_find_password' . $username);
                    if (time() - $server_code['time'] > 300) {
                        error_json('验证码已经过期');
                    } elseif ($server_code['content']['code'] != $code) {
                        error_json('验证码错误');
                    } else {
                        $this->load->model('loop_model');
                        $member_data = $this->loop_model->get_where('member', array('username' => $username));
                        if (!empty($member_data)) {
                            $update_data['salt']     = get_rand_num();
                            $update_data['password'] = md5(md5($password) . $update_data['salt']);
                            $res                     = $this->loop_model->update_id('member', $update_data, $member_data['id']);
                            if (!empty($res)) {
                                cache('del', 'sms_find_password' . $username);//删除验证码
                                error_json('y');
                            } else {
                                error_json('密码修改失败');
                            }
                        } else {
                            error_json('用户不存在');
                        }
                    }
                } else {
                    error_json('验证码不能为空');
                }
            } else {
                error_json('账号和密码不能为空');
            }
        }
    }


    /**
     * 发送手机验证码
     */
    public function sms_send()
    {
        $mobile = trim($this->input->post('mobile', true));
        $this->load->helpers('form_validation_helper');
        if (is_mobile($mobile)) {
            $this->load->model('member/user_model');
            $member_data = $this->user_model->repeat_username($mobile);
            if (!empty($member_data)) {
                $this->load->library('sms_send');
                $data = array(
                    'tel'     => $mobile,
                    'type'    => 'find_password',
                    'content' => array('code' => get_rand_num('int', 5))
                );
                $res  = $this->sms_send->send_to($data);
                if ($res == 'success') {
                    error_json('y');
                } else {
                    error_json($res);
                }
            } else {
                error_json('手机号码不存在');
            }
        } else {
            error_json('手机号码格式错误请不要加0或86');
        }
    }

    /**
     * 验证会员名是否存在
     */
    public function repeat_username()
    {
        $username = $this->input->post('param', true);
        if (!empty($username)) {
            $this->load->model('member/user_model');
            $member_data = $this->user_model->repeat_username($username);
            if (!empty($member_data)) {
                error_json('y');
            } else {
                error_json('手机号码不存在');
            }
        }
    }

}
