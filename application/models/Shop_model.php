<?php
class Shop_model extends CI_Model {
	
    public function __construct() {
        parent::__construct();
        $this->load->database('default', TRUE);
    }
    
    function get_shop_category(){
        
        $this->db->select('idx,name');
        $this->db->from('shop_category');

        return $this->db->get()->result_array();
    }
    
    function set_shop($data){
        
        $data['regi_date'] = date('Y-m-d H:i:s');
        $data['modi_date'] = date('Y-m-d H:i:s');

        $this->db->insert('shop_info', $data);

        return $this->db->insert_id();
    }
    
    function get_shop_info($shop_idx){
       $this->db->select("idx,name");
       $this->db->from("shop_info");
       $this->db->where("idx", $shop_idx);
        
       return $this->db->get()->row_array();
    }
    
    function get_shop_set($shop_idx){
        $this->db->select("*");
        $this->db->from("shop_setting");
        $this->db->where("shop_idx", $shop_idx);
        
        return $this->db->get()->result();
    }
}

?>
