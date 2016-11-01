<?php
/**
 * @author  wanghui <whlives@qq.com>
 * @version 1.0，20160202
 * @package 用户登录
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loop_model');
    }

    /**
     * 开始注册
     */
    public function user_reg()
    {
        if (is_post()) {
            $username = trim($this->input->post('username', true));
            $password = trim($this->input->post('password', true));
            $code     = (int)trim($this->input->post('code', true));
            if (!empty($username) && !empty($password)) {
                if ($code == '') error_json('短信验证码不能为空');

                //验证码验证
                $server_code = cache('get', 'sms_reg' . $username);
                $this->load->helpers('form_validation_helper');
                if (!is_mobile($username)) {
                    error_json('用户名必须是手机号码');
                } elseif (time() - $server_code['time'] > 300) {
                    error_json('验证码已经过期');
                } elseif ($server_code['content']['code'] != $code) {
                    error_json('验证码错误');
                } else {
                    $flag_user = decrypt($this->input->cookie('flag_user'));//推荐人id
                    //注册信息
                    $user_data = array(
                        'username'  => $username,
                        'password'  => $password,
                        'flag_user' => $flag_user,
                        'tel'       => $username
                    );
                    $wx_user_data = json_decode(decrypt($this->input->cookie('wx_userinfo')), true);
                    if (!empty($wx_user_data)) {
                        $user_data['headimgurl'] = $wx_user_data['headimgurl'];
                        $user_data['full_name']  = $wx_user_data['nickname'];
                        $user_data['sex']        = $wx_user_data['sex'];
                        $user_data['openid']     = $wx_user_data['openid'];
                    }
                    $this->load->model('member/user_model');
                    $res = $this->user_model->update($user_data);
                    if (!empty($res)) {
                        if ($res == 'y') {
                            $member_data = $this->loop_model->get_where('member', array('username' => $username));
                            $this->loop_model->update_where('member_user', array('endtime' => time()), array('m_id' => $member_data['id']));
                            //设置登录信息
                            if (config_item('safe_type') == 'cookie') {
                                $this->input->set_cookie('m_id', encrypt($member_data['id']), config_item('safe_time'));
                            } else {
                                $this->session->set_userdata('m_id', $member_data['id']);
                            }
                            cache('del', 'sms_reg' . $username);//删除验证码
                            error_json('y');
                        }
                        error_json($res);
                    } else {
                        error_json('注册失败');
                    }
                }
            } else {
                error_json('账号和密码不能为空');
            }
        }
    }

    /**
     * 验证会员名是否存在
     */
    public function repeat_username()
    {
        $username = $this->input->post('param', true);
        if (!empty($username)) {
            $this->load->helpers('form_validation_helper');
            if (!is_mobile($username)) {
                error_json('用户名必须是手机号码');
            }
            $this->load->model('member/user_model');
            $member_data = $this->user_model->repeat_username($username);
            if (!empty($member_data)) {
                error_json('手机号码已经存在');
            } else {
                error_json('y');
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
            if (empty($member_data)) {
                $this->load->library('sms_send');
                $data = array(
                    'tel'     => $mobile,
                    'type'    => 'reg',
                    'content' => array('code' => get_rand_num('int', 5))
                );
                $res  = $this->sms_send->send_to($data);
                if ($res == 'success') {
                    error_json('y');
                } else {
                    error_json($res);
                }
            } else {
                error_json('手机号已注册');
            }
        } else {
            error_json('手机号码格式错误请不要加0或86');
        }
    }
}
