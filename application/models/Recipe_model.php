<?php
class Recipe_model extends CI_Model {
	
    public function __construct() {
        parent::__construct();
        $this->load->database('default', TRUE);
    }
    
    function count_group_list($search_vo){
        
        $this->db->select('*');
        $this->db->from('recipe_group as rg');
        $this->db->where('rg.shop_idx', $search_vo->shop_idx);
        $this->db->order_by("rg.idx", "desc");
        
        return $this->db->count_all_results();
        
    }

    function get_group_list($offset, $search_vo){
        
        $this->db->select('*');
        $this->db->from('recipe_group as rg');
        $this->db->where('rg.shop_idx', $search_vo->shop_idx);
        $this->db->order_by("rg.idx", "desc");
        if(!empty($search_vo->config_per_page)){
            $this->db->limit($search_vo->config_per_page, $offset);
        }
        if(!empty($search_vo->state)){
            $this->db->where('rg.state', $search_vo->state);
        }
        
        return $this->db->get()->result();
        
    }
    function insert_group($data){
        
        $data['regi_date'] = date('Y-m-d H:i:s');
        $data['modi_date'] = date('Y-m-d H:i:s');
        $data['writer'] = $this->session->userdata("user_id");
        
        $this->db->insert('recipe_group',$data);
    }
    
    function get_group_info($data){
        
        $this->db->select("*");
        $this->db->from("recipe_group");
        $this->db->where($data);

        return $this->db->get()->row();
        
        
    }
    
    function group_update($data, $idx){

        $data['modi_date'] = date('Y-m-d H:i:s');
        $data['modifier'] = $this->session->userdata("user_id");
        
        $this->db->where("idx",$idx);
        $this->db->update('recipe_group',$data);
    }
    
    
    
    function count_recipe_list($search_vo){
        
        $this->db->select('*');
        $this->db->from('recipe_info as ri');
        if(!empty($search_vo->group_idx)){
            $this->db->where('ri.group_idx', $search_vo->group_idx);
        }
        $this->db->order_by("ri.idx", "desc");
        
        return $this->db->count_all_results();
        
    }

    function get_recipe_list($offset, $search_vo){
        
        $this->db->select('ri.*, rg.name as group_name, rg.state as group_state');
        $this->db->from('recipe_info as ri');
        $this->db->join('recipe_group as rg',"ri.group_idx = rg.idx","left");
        if(!empty($search_vo->group_idx)){
            $this->db->where('ri.group_idx', $search_vo->group_idx);
        }
        $this->db->order_by("ri.idx", "desc");
        $this->db->limit($search_vo->config_per_page, $offset);
        
        return $this->db->get()->result();
        
    }
    
    function insert_recipe($vo){
        
        $data['regi_date'] = $data['modi_date'] =  date('Y-m-d H:i:s');
        $data['writer'] = $data['modifier'] = $this->session->userdata("user_id");
        $data['group_idx'] = $vo['group_idx'];
        $data['name'] = $vo['name'];
        
        $this->db->insert('recipe_info',$data);
        
        return $this->db->insert_id();
        
    }
    
    function insert_process($data){

        $this->db->insert('recipe_process',$data);
        
        return $this->db->insert_id();
    }
    
    
}    
?>