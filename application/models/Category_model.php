<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    //添加文章
    public function insert($data) {
        $this->db->insert('goods_category', $data);
        return $this->db->affected_rows();
    }

    /* 获取文章
     * 并用left关联categoryes表
     *
     */

    public function get_category($id = FALSE, $num = FALSE, $offset = FALSE) {

        if ($id) {
            $query = $this->db->get_where('goods_category', array('id' => $id));
            return $query->row_array();
        } else {
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get('goods_category', $num, $offset);
            return $query->result_array();
        }
    }

    //搜索
    public function search_category($reid = 'all', $flag = 0, $num = FALSE, $offset = FALSE) {
        if ($reid != 'all') {
            $this->db->where('reid', $reid);
        }
        if ($flag) {
            $this->db->where('flag', 1);
        }
         $this->db->order_by('id', 'DESC');
        $query = $this->db->get('goods_category', $num, $offset);
        return $query->result_array();
    }

    //搜索条件查询条数
    public function search_article_nums($cid = FALSE, $title = FALSE) {

        $this->db->join('categoryes', 'categoryes.cid = articles.cid', 'left');
        if ($cid) {
            $this->db->where('articles.cid', $cid);
        }
        if ($title) {
            $this->db->like('articles.title', $title);
        }
        return $this->db->count_all_results('articles');
    }

    public function delete($id) {
        $this->db->delete('articles', array('id' => $id));
        return $this->db->affected_rows();
    }

    public function deletec($data) {
        $this->db->where_in('id', $data);
        $this->db->delete('articles');
        return $this->db->affected_rows();
    }

    public function movec($cid, $data) {
        $this->db->where_in('id', $data);
        $this->db->update('articles', array('cid' => $cid));
        return $this->db->affected_rows();
    }

    public function update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('articles', $data);
        return $this->db->affected_rows();
    }

}
