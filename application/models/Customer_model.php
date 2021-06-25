<?php
class Customer_model extends CI_Model {
    

    function insert_order($vo){
        
        $data['regi_date'] = $data['modi_date'] =  date('Y-m-d H:i:s');
        $data['table_no'] = $vo['table_no'];
        $data['recipe_idx'] = $vo['recipe_idx'];
        $data['place'] = $vo['place'];
        $data['cnt'] = $vo['cnt'];
        
        $this->db->insert('order_list',$data);
        
        return $this->db->insert_id();
        
    }
    
    function order_list($vo){
        $this->db->select("if(ol.place = 1 , '취식' , '포장') AS place, ol.table_no, ol.cnt");
        $this->db->select("ol.regi_date, if(ol.`status` = 1, '결제대기', if(ol.status=2,'결제완료','조리완료') ) AS STATUS");
        $this->db->select("ri.name AS recipe_name , rg.name AS group_name");
        $this->db->from('order_list AS ol');
        $this->db->join('recipe_info AS ri ', 'ol.recipe_idx = ri.idx', 'left');
        $this->db->join('recipe_group AS rg', 'ri.group_idx = rg.idx', 'left');
        
        $this->db->like('ol.regi_date', $vo->date);
        
        return $this->db->get()->result();
    }
    
    function count_order($vo){
        
        $this->db->select('*');
        $this->db->from('order_list AS ol');
        $this->db->join('recipe_info AS ri ', 'ol.recipe_idx = ri.idx', 'left');
        $this->db->join('recipe_group AS rg', 'ri.group_idx = rg.idx', 'left');
        $this->db->like('ol.regi_date', $vo->date);
        return $this->db->count_all_results();
    }
}
?>

