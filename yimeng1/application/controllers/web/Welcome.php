<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helpers('web_helper');
        $this->load->model('loop_model');
    }

    /**
     * 引导页
     */
    public function index()
    {
        if (get_client() == 'weixin') {
            $this->member_data = member_login();
        }
        display('default/index.html');
    }

    /**
     * 引导页
     */
    public function home()
    {
        if (get_client() == 'weixin') {
            $this->member_data = member_login();
        }
        display('default/home.html');
    }

    /**
     * 登录
     */
    public function login()
    {
        $this->load->helpers('web_helper');
        $m_id = get_m_id();
        if (!empty($m_id)) {
            header('location:' . site_url('/web/welcome'));
        } else {
            $redirect_url = trim($this->input->get('redirect_url', true));//返回链接
            if ($redirect_url == '') $redirect_url = site_url('/web');
            assign('redirect_url', $redirect_url);
            display('default/user/login.html');
        }
    }

    /**
     * 注册
     */
    public function reg()
    {
        $redirect_url = trim($this->input->get('redirect_url', true));//返回链接
        if ($redirect_url == '') $redirect_url = site_url('/web');
        assign('redirect_url', $redirect_url);
        display('default/user/reg.html');
    }

    /**
     * 找回密码
     */
    public function find_password()
    {
        $redirect_url = trim($this->input->get('redirect_url', true));//返回链接
        if ($redirect_url == '') $redirect_url = site_url('/web');
        assign('redirect_url', $redirect_url);
        display('default/user/find_password.html');
    }

    /**
     * 用户退出登陆
     */
    public function loginout()
    {
        if (config_item('safe_type') == 'cookie') {
            $m_id = (int)decrypt($this->input->cookie('m_id', true));
            $this->input->set_cookie('m_id', 'out', '');
        } else {
            $m_id = (int)$this->session->userdata('m_id');
            $this->session->unset_userdata('m_id');
        }
        cache('delete', 'web_member_data_' . $m_id);
        header('location:' . site_url(('/web')));
    }

}
