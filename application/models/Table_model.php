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
            
            if(!empty($search_vo->status)){
                $this->db->where('ti.status', $search_vo->status);
            }
            
            $this->db->order_by("ti.idx", "desc");

            return $this->db->get()->result();

        }
        
        function set_table($vo){
            

            $data['status'] = $vo->status;
            $data['table_code'] = $vo->table_code;
            
            $this->db->where('table_no',$vo->table_no);
            $this->db->where('shop_idx',$vo->shop_idx);
            
            $this->db->update('table_info');
            
            
            
        }
    }
?>