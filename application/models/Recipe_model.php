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
        if(!empty($search_vo->config_per_page)){
            $this->db->limit($search_vo->config_per_page, $offset);
        }
        
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

    function update_recipe($vo, $idx){
        
         $data['modi_date'] =  date('Y-m-d H:i:s');
         $data['modifier'] = $this->session->userdata("user_id");
         $data['group_idx'] = $vo['group_idx'];
         $data['name'] = $vo['name'];
         $data['state'] = $vo['state'];
        
        $this->db->where('idx', $idx); 
        $this->db->update('recipe_info',$data);
        
        return $this->db->insert_id();
        
    }
    
    function insert_process($data){

        $this->db->insert('recipe_process',$data);
        
        return $this->db->insert_id();
    }
    
    function delete_process($recipe_idx){
       
        $this->db->where('recipe_idx', $recipe_idx); 
        $this->db->delete('recipe_process');
        
    }
    
    function get_recipe_info($idx){
        
        $this->db->select('ri.*');
        $this->db->from('recipe_info as ri');
        $this->db->where('ri.idx', $idx);
        
        return $this->db->get()->row();
    }
    function get_recipe_proces($idx){
        
        $this->db->select('rp.order_num, rp.set_time, rp.stock_input, rp.stock_idx, sc.idx as category_idx');
        $this->db->select('si.name AS stock_name');    
        $this->db->select('sc.name AS stock_category_name');
        
        $this->db->from('recipe_process as rp');
        $this->db->join('stock_info as si',"rp.stock_idx = si.idx","left");
        $this->db->join('stock_category as sc',"si.stock_category_idx = sc.idx","left");
        $this->db->where('rp.recipe_idx', $idx);
        
        return $this->db->get()->result();
    }
    
}    
?>