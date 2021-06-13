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
}
?>

