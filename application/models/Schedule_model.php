<?php
class Schedule_model extends CI_Model {
	
    public function __construct() {
        parent::__construct();
        $this->load->database('default', TRUE);
    }
    
    function get_schedule($user_idx, $shop_idx, $date){
        
        $this->db->select('cd.date, cd.state');
        $this->db->select('(SELECT COUNT(*) FROM commute_time AS ct WHERE cd.idx = ct.date_idx AND ct.state = 1) AS time_cnt');    
        $this->db->select('(SELECT COUNT(*) FROM commute_time AS ct2 WHERE cd.idx = ct2.date_idx AND ct2.member_idx = '.$user_idx.') AS use_cnt ');
        
        $this->db->from('commute_date as cd');
        $this->db->like('cd.date', $date, 'after');
        $this->db->like('cd.shop_idx', $shop_idx);
        
        return $this->db->get()->result();
        
    }
    
}