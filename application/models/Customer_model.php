<?php
class Customer_model extends CI_Model {
    

    function insert_order($vo){
        
        $vo['regi_date'] = $vo['modi_date'] =  date('Y-m-d H:i:s');
        
        $this->db->insert('order_list',$vo);
        
        return $this->db->insert_id();
        
    }
    
    function order_list($vo){
        $this->db->select("if(ti.table_no > 0 , '취식' , '포장') AS place, ol.table_code, ol.cnt, ol.order_no, ol.price");
        $this->db->select("ol.regi_date, CASE WHEN ol.status = 1 then '결제대기' when ol.status = 2 then '결제완료' when ol.status = 3 then '조리완료' else '결제취소' END  AS STATUS");
        $this->db->select("ri.name AS recipe_name , rg.name AS group_name, ol.idx");
        $this->db->from('order_list AS ol');
        $this->db->join('recipe_info AS ri ', 'ol.recipe_idx = ri.idx', 'left');
        $this->db->join('recipe_group AS rg', 'ri.group_idx = rg.idx', 'left');
        $this->db->join('table_info as ti', 'ti.table_code = ol.table_code', 'left');
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
    
    function count_table_order($vo){
        $this->db->select('*');
        $this->db->from('table_info as ti');
        
        if(!empty($vo->status)){
            $this->db->where_in('ti.status', $vo->status);
        }
        
        return $this->db->count_all_results();
    }
    
    function select_table_order($vo){
        
        $this->db->select("ti.`table_no`, ti.`table_code`, ti.status");
        $this->db->select("SUM(ol.`price`) as price, MAX(ol.`regi_date`) as regi_date, SUM(ol.`cnt`) AS cnt");
        $this->db->from("table_info as ti");
        $this->db->join("order_list as ol", "ti.table_code = ol.table_code and ol.status != 4", "left");
        if(!empty($vo->status)){
            $this->db->where_in('ti.status', $vo->status);
        }
        $this->db->group_by('ti.`table_no`');
        
        return $this->db->get()->result();
    }
    
    function detail_order($vo){
        
        $this->db->select("if(ti.table_no > 0 , '취식' , '포장') AS place, ol.table_code, ol.cnt");
        $this->db->select("ol.regi_date, ol.status");
        $this->db->select("ri.name AS recipe_name , rg.name AS group_name, ol.idx, ri.idx as recipe_idx");
        $this->db->from('order_list AS ol');
        $this->db->join('recipe_info AS ri ', 'ol.recipe_idx = ri.idx', 'left');
        $this->db->join('recipe_group AS rg', 'ri.group_idx = rg.idx', 'left');
        $this->db->join('table_info as ti', 'ti.table_code = ol.table_code', 'left');
        $this->db->where('ol.idx', $vo->idx);
        
        return $this->db->get()->row();
        
    }
    
    function set_order($vo){
        
        $data['modi_date'] = date('Y-m-d H:i:s');
        $data['status'] = $vo->status;
        
        $this->db->where("idx",$vo->idx);
        $this->db->update('order_list',$data);
        
    }
    
    function get_order_recipe($recipe_idx){
        
        
        $this->db->select("ri.name, rp.order_num, si.name as stock_name, rp.stock_input, si.unit,rp.set_time");
        $this->db->select("si.idx as stock_idx");
        $this->db->from('recipe_info AS ri');
        $this->db->join('recipe_process AS rp ', 'ri.idx = rp.recipe_idx', 'left');
        $this->db->join('stock_info AS si', 'si.idx  = rp.stock_idx', 'left');

        $this->db->where("ri.idx",$recipe_idx);
        $this->db->order_by("rp.order_num","ASC");
        
        return $this->db->get()->result();
    }
    
    function insert_stock_history($vo){
        $this->db->insert("stock_history",$vo);
        return $this->db->insert_id();
    }
    
    function update_stock_count($vo){
        
        $this->db->set('count', 'count-'.$vo['count']);
        $this->db->where('idx', $vo['stock_idx']);
        $this->db->update('stock_info');
        
    }
    
    function get_order_count($code){
        
        $sql = "SELECT
                        SUM(cnt_wait) AS cnt_wait,
                        SUM(cnt_complete) AS cnt_complete,
                        shop_idx
                FROM(
                        SELECT
                                CASE WHEN STATUS = 1 THEN COUNT(idx) ELSE 0 END AS cnt_wait,
                                CASE WHEN STATUS = 2 THEN COUNT(idx) ELSE 0 END AS cnt_complete,
                                shop_idx
                        FROM order_list 
                        WHERE table_code = '$code'
                        GROUP BY STATUS
                ) A";
        $query = $this->db->query($sql); 
        $result = $query->row(); 
        return $result; 
        
    }
    
}
?>

