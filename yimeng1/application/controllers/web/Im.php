<?php
/**
 * @author  wanghui <whlives@qq.com>
 * @version 1.0，20160202
 * @package 在线客服系统
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Im extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helpers('web_helper');
        $this->member_data = member_login();
        $this->load->model('loop_model');
    }

    /**
     * 消息列表
     */
    public function index()
    {
        $this->load->library('im_api');
        //自己的信息
        $this->member_data['uid'] = $this->im_api->encryption($this->member_data['id']);
        assign('member_data', $this->member_data);
        display('default/im/index.html');
    }

    /**
     * 在线沟通
     */
    public function chat($shop_id)
    {
        if (!empty($shop_id)) {
            if ($shop_id != $this->member_data['id']) {
                $this->load->library('im_api');
                //聊天对象信息
                $shop_data          = $this->loop_model->get_id('member', $shop_id);
                $shop_data['touid'] = $this->im_api->encryption($shop_id);
                assign('shop_data', $shop_data);
                $shop_user_data         = $this->loop_model->get_where('member_user', array('m_id' => $shop_id));
                assign('shop_user_data', $shop_user_data);
                //自己的信息
                $this->member_data['uid'] = $this->im_api->encryption($this->member_data['id']);
                assign('member_data', $this->member_data);
                display('default/im/chat.html');
            } else {
                msg('不能和自己聊天');
            }
        } else {
            msg('用户id不能为空');
        }
    }

    /**
     * 设置与某人的消息已读
     */
    public function set_read_state($shop_id)
    {
        if (!empty($shop_id)) {
            if ($shop_id != $this->member_data['id']) {
                $this->load->library('im_api');
                //聊天对象信息
                $shop_data          = $this->loop_model->get_id('member', $shop_id);
                $shop_data['touid'] = $this->im_api->encryption($shop_id);
                assign('shop_data', $shop_data);
                //自己的信息
                $this->member_data['uid'] = $this->im_api->encryption($this->member_data['id']);
                assign('member_data', $this->member_data);
                display('default/im/set_read_state.html');
            }
        }
    }

    /**
     * 根据im id查询用户昵称和头像
     */
    public function user_info()
    {
        $im_id       = $this->input->post('im_id', true);
        $where_array = array(
            'select'   => 'full_name,id,username,headimgurl,im',
            'join'     => array(
                array('member_user as u', 'm.id=u.m_id')
            ),
            'where_in' => array(
                'im' => $im_id,
            )
        );
        $list_data   = $this->loop_model->get_list('member as m', $where_array);
        foreach ($list_data as $key) {
            if (empty($key['full_name'])) $key['full_name'] = substr($key['username'],0,3).'****'.substr($key['username'],-4,4);
            $key['link'] = site_url('/web/im/chat/'.$key['id']);
            unset($key['username']);
            unset($key['id']);
            $list[$key['im']] = $key;
        }
        error_json($list);
    }
}
