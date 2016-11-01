<?php
/**
 * @author  wanghui <whlives@qq.com>
 * @version 1.0，20160127
 * @package 后台自定义全局函数
 */
defined('BASEPATH') OR exit('No direct script access allowed');

//判断是否有分享链接
$CI        = &get_instance();
$flag_user = $CI->input->get('flag_user', true);
if (!empty($flag_user)) {
    $CI->input->set_cookie('flag_user', encrypt($flag_user), 3600 * 24);
}

/**
 * 检测前台用户是否登陆,强制跳转登录
 */
if (!function_exists('member_login')) {
    function member_login()
    {
        $CI = &get_instance();
        //微信里面的时候首先获取openid
        if (get_client() == 'weixin') {
            $CI->load->helpers('wechat_helper');
            $m_id = get_userinfo();
        } else {
            //获取登录信息
            if (config_item('safe_type') == 'cookie') {
                $m_id = (int)decrypt($CI->input->cookie('m_id', true));
            } else {
                $m_id = (int)$CI->session->userdata('m_id');
            }
        }

        //用户信息
        if (empty($m_id)) {
            header('location:' . site_url('/web/welcome/login?redirect_url=' . urlencode(get_now_url())));
            exit;
        } else {
            $member_data = array();
            $CI->load->model('loop_model');
            $member_data = $CI->loop_model->get_id('member', $m_id, 'id,username,headimgurl,im');
            //查询im账号是否注册,没有注册开始增加
            if (empty($member_data['im'])) {
                $member_user_data = $CI->loop_model->get_where('member_user', array('m_id' => $m_id));
                $CI->load->library('im_api');
                $CI->im_api->add_user($m_id, $member_data['headimgurl'], $member_user_data['full_name']);
            }
            assign('m_id', $m_id);
            return $member_data;
        }
    }
}

/**
 * 获取前台用户登录信息,提示信息,不强制跳转
 */
if (!function_exists('get_member_data')) {
    function get_member_data()
    {
        $CI = &get_instance();
        //获取登录信息
        if (config_item('safe_type') == 'cookie') {
            $m_id = (int)decrypt($CI->input->cookie('m_id', true));
        } else {
            $m_id = (int)$CI->session->userdata('m_id');
        }
        if (empty($m_id)) {
            error_json('请登录后操作');
        } else {
            $member_data = array();
            $CI->load->model('loop_model');
            $member_data = $CI->loop_model->get_id('member', $m_id, 'id,username,headimgurl');
            return $member_data;
        }
    }
}

/**
 * 获取前台登录用户id,不提示
 */
if (!function_exists('get_m_id')) {
    function get_m_id()
    {
        $CI = &get_instance();
        //获取登录信息
        if (config_item('safe_type') == 'cookie') {
            $m_id = (int)decrypt($CI->input->cookie('m_id', true));
        } else {
            $m_id = (int)$CI->session->userdata('m_id');
        }
        return $m_id;
    }
}