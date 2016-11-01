<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Adv_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 添加
     */
    public function insert($data) {
        $this->db->insert('adv', $data);
        return $this->db->affected_rows();
    }

    /**
     * 获取
     */
    public function get_adv($id = FALSE, $type = 1, $num = FALSE, $offset = FALSE) {
        if ($id) {
            $query = $this->db->get_where('adv', array('id' => $id));
            return $query->row_array();
        } else {
            $this->db->get_where('adv', array('position_id' => $position_id, 'start_time<=' => time(), 'end_time>=' => time()));
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get('adv', $num, $offset);
            return $query->result_array();
        }
    }
//
//    public function delete($id) {
//        $this->db->delete('advs', array('id' => $id));
//        return $this->db->affected_rows();
//    }
//
//    public function deletec($data) {
//        $this->db->where_in('id', $data);
//        $this->db->delete('advs');
//        return $this->db->affected_rows();
//    }
//
//    public function movec($cid, $data) {
//        $this->db->where_in('id', $data);
//        $this->db->update('advs', array('cid' => $cid));
//        return $this->db->affected_rows();
//    }
//
//    public function update($data, $id) {
//        $this->db->where('id', $id);
//        $this->db->update('advs', $data);
//        return $this->db->affected_rows();
//    }

}
