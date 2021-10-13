<?php
    class Table_model extends CI_Model {
        
        public function __construct() {
            parent::__construct();
            $this->load->database('default', TRUE);
        }

        function get_table_list($search_vo){

            $this->db->select('*');
            $this->db->from('table_info as ti');
            $this->db->where('ti.shop_idx', $search_vo->shop_idx);
            $this->db->order_by("ti.idx", "desc");

            return $this->db->get()->result();

        }

    }
?>