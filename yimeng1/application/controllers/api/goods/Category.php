<?php
/**
 * @author  wanghui <whlives@qq.com>
 * @version 1.0，20160202
 * @package 商品分类
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loop_model');
    }

    /**
     * 根据上级栏目查询下级栏目
     */
    public function index()
    {
        $reid = (int)$this->input->get_post('reid', true);
        if (empty($reid)) $reid = 0;
        $where_data['where']  = array('reid' => $reid);
        $where_data['select'] = 'id,name,image';
        $list                 = $this->loop_model->get_list('goods_category', $where_data, '', '', 'sortnum asc,id asc');
        if (!empty($list)) {
            error_json($list);
        } else {
            error_json('没有数据');
        }
    }

    /**
     * 根据上级栏目查询下级栏目
     */
    public function flag_list()
    {
        $where_data['where']  = array('flag' => 1);
        $where_data['select'] = 'id,name,image';
        $list                 = $this->loop_model->get_list('goods_category', $where_data, '', '', 'sortnum asc,id asc');
        if (!empty($list)) {
            error_json($list);
        } else {
            error_json('没有数据');
        }
    }

    /**
     * 根据id获取栏目信息
     */
    public function get_id()
    {
        $id = (int)$this->input->get_post('id', true);
        if (!empty($id)) {
            $category = $this->loop_model->get_id('goods_category', $id);
            if (!empty($category)) {
                error_json($category);
            } else {
                error_json('没有数据');
            }
        } else {
            error_json('缺少参数');
        }
    }
}
