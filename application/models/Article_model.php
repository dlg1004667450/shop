<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Article_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    //添加文章
    public function insert($data) {
        $this->db->insert('articles', $data);
        return $this->db->affected_rows();
    }

    /* 获取文章
     * 并用left关联categoryes表
     *
     */

    public function get_article($id = FALSE, $num = FALSE, $offset = FALSE) {

        if ($id) {
            $query = $this->db->get_where('articles', array('id' => $id));
            return $query->row_array();
        } else {
            $this->db->order_by('id', 'DESC');
            $this->db->join('categoryes', 'categoryes.cid = articles.cid', 'left');
            $query = $this->db->get('articles', $num, $offset);
            return $query->result_array();
        }
    }

    //搜索
    public function search_article($cid = FALSE, $title = FALSE, $num, $offset) {
        $this->db->order_by('id', 'DESC');
        $this->db->join('categoryes', 'categoryes.cid = articles.cid', 'left');
        if ($cid) {
            $this->db->where('articles.cid', $cid);
        }
        if ($title) {
            $this->db->like('articles.title', $title);
        }
        $query = $this->db->get('articles', $num, $offset);
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
