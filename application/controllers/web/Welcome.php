<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helpers('web_helper');
    }

    /**
     * 引导页
     */
    public function index() {
        if (get_client() == 'weixin') {
            $this->member_data = member_login();
        }
        $this->load->view('default/index');
    }

    /**
     * 首页
     */
    public function home() {
        $this->output->enable_profiler();
        if (get_client() == 'weixin') {
            $this->member_data = member_login();
        }
//        $this->load->model('Adv_model');
        $this->load->model('Category_model');
        $this->load->model('Goods_model');
        
        //广告
        $data['adv_list'] = $this->Adv_model->get_adv(1);
        //大分类
        $data['cate_list'] = $this->Category_model->search_category('0', '0', 4);
        //大分类 下的产品
        foreach ($data['cate_list'] as $value) {
            $data['goods_list'][$value['id']] = $this->Goods_model->search_goods($value['id'], '',3,0);
        }
        
        $this->load->view('default/home', $data);
    }

    /**
     * 登录
     */
    public function login() {
        $m_id = get_m_id();
        if (!empty($m_id)) {
            redirect('web/welcome');
        } else {
            $redirect_url = trim($this->input->get('redirect_url', true)); //返回链接
            if ($redirect_url == '') {
                $redirect_url = site_url('/web');
            }
            $data['redirect_url'] = $redirect_url;
            $this->load->view('default/user/login', $data);
        }
    }

}
