<?php
class Customer_model extends CI_Model {
    

    function insert_order($vo){
        
        $data['regi_date'] = $data['modi_date'] =  date('Y-m-d H:i:s');
        $data['table_no'] = $vo['table_no'];
        $data['recipe_idx'] = $vo['recipe_idx'];
        $data['place'] = $vo['place'];
        $data['cnt'] = $vo['cnt'];
        $data['shop_idx']= $vo['shop_idx'];
        if(!empty($vo['status'])){
            $data['status'] = $vo['status'];
        }
        
        $this->db->insert('order_list',$data);
        
        return $this->db->insert_id();
        
    }
    
    function order_list($vo){
        $this->db->select("if(ol.place = 1 , '취식' , '포장') AS place, ol.table_no, ol.cnt");
        $this->db->select("ol.regi_date, CASE WHEN ol.status = 1 then '결제대기' when ol.status = 2 then '결제완료' when ol.status = 3 then '조리완료' else '결제취소' END  AS STATUS");
        $this->db->select("ri.name AS recipe_name , rg.name AS group_name, ol.idx");
        $this->db->from('order_list AS ol');
        $this->db->join('recipe_info AS ri ', 'ol.recipe_idx = ri.idx', 'left');
        $this->db->join('recipe_group AS rg', 'ri.group_idx = rg.idx', 'left');
        
        $this->db->like('ol.regi_date', $vo->date);
        
        if(!empty($vo->status)){
            $this->db->like('ol.status', $vo->status, 'after');
        }
        
        return $this->db->get()->result();
    }
    
    function count_order($vo){
        
        $this->db->select('*');
        $this->db->from('order_list AS ol');
        $this->db->join('recipe_info AS ri ', 'ol.recipe_idx = ri.idx', 'left');
        $this->db->join('recipe_group AS rg', 'ri.group_idx = rg.idx', 'left');
        $this->db->like('ol.regi_date', $vo->date, 'after');
        if(!empty($vo->status)){
            $this->db->like('ol.status', $vo->status);
        }
        return $this->db->count_all_results();
    }
    
    function detail_order($vo){
        
        $this->db->select("if(ol.place = 1 , '취식' , '포장') AS place, ol.table_no, ol.cnt");
        $this->db->select("ol.regi_date, ol.status");
        $this->db->select("ri.name AS recipe_name , rg.name AS group_name, ol.idx");
        $this->db->from('order_list AS ol');
        $this->db->join('recipe_info AS ri ', 'ol.recipe_idx = ri.idx', 'left');
        $this->db->join('recipe_group AS rg', 'ri.group_idx = rg.idx', 'left');
        
        $this->db->where('ol.idx', $vo->idx);
        
        return $this->db->get()->row();
        
    }
    
    function set_order($vo){
        
        $data['modi_date'] = date('Y-m-d H:i:s');
        $data['status'] = $vo->status;
        
        $this->db->where("idx",$vo->idx);
        $this->db->update('order_list',$data);
        
    }
}
?>

