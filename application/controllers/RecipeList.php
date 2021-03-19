<?php
class RecipeList extends CI_Controller {
	
	function __construct() {
            parent ::__construct();
            
            $this->head_data = header_set("recipe_list");

            $this->load->model('Recipe_model', 'recipe_md', TRUE);
            $this->load->model('Stock_model', 'stock_md', TRUE);
        }

 public function recipe_list(){

        $this->load->library('pagination');
        $search_vo  = new stdClass();
        $config['per_page'] = 10;
        $offset = $this->input->get('per_page');
        $config['base_url'] = current_url() . '?' . reset_GET('per_page');
        $search_vo->config_per_page = $config['per_page'];
        
        
        $search_vo->group_idx = $this->input->get("group_idx");

        $config['total_rows'] = $this->recipe_md->count_recipe_list($search_vo);
        $config = setPagination($config);
        $this->pagination->initialize($config);
        
        $data['pagination'] = $this->pagination->create_links();

        if ($config['total_rows'] > 0) {
            $rows = $this->recipe_md->get_recipe_list($offset, $search_vo);
        } else {
            $rows = false;
        }
        
        $search_vo->shop_idx = $this->session->userdata("shop_idx");
        $search_vo->state = "Y";
        
        $group_rows = $this->recipe_md->get_group_list(0,$search_vo);
        $stock_category_info = $this->stock_md->get_stock_category();
        $stock_info = $this->stock_md->get_stock_info_array("state = 1");
        
        $group_select = "";
        $scategory_select = "";
        $stock_data = array();

        
        foreach($group_rows as $grow){
            
            if($grow->idx == $search_vo->group_idx){
                $group_select .= "<option value='".$grow->idx."' selected>".$grow->name."</option>";
            }else{
                $group_select .= "<option value='".$grow->idx."'>".$grow->name."</option>";
            }

        }
        
        foreach($stock_category_info as $srow){
            $scategory_select .= "<option value='".$srow->idx."'>".$srow->name."</option>";
        }
        
        foreach($stock_info as $strow){
            
            $array_unit = array();
            $array_unit = array(                   "idx"   => $strow->idx, 
                                                    "name"  => $strow->name,
                                                    "unit"  => $strow->unit,
                                            );  
            if(empty($stock_select[$strow->stock_category_idx])){
                $stock_data[$strow->stock_category_idx] = array();
            }
            
            array_push($stock_data[$strow->stock_category_idx], $array_unit);
        }
        

        
        $data['rows'] = $rows;
        $data['base_url'] = $config['base_url'];
        $data['offset'] = $offset;
        $data['search_vo'] = $search_vo;
        $data['group_select'] = $group_select;
        $data['scategory_select'] = $scategory_select;
        $data['stock_info'] = $stock_info;
        $data['stock_data'] = $stock_data;
        $data['stock_select_key'] = array_keys($stock_select);
        
        $this->load->view(LANGUAGE.'/header', $this->head_data);
        $this->load->view(LANGUAGE.'/recipe_list', $data);
    }
    
    
    public function set_recipe(){
        $code = '';
        $message = '';
        
        $vo = array();
        
        $vo['name'] = $this->input->post("insert_group_name");
        $vo['shop_idx'] = $data['writer'] = $this->session->userdata("shop_idx");
                
        if (empty($vo['name'])){
            $code = 400;
            $message = 'insert_group_name 변수의 요청이 올바르지 않습니다.';
        }else{
            
            $this->db->trans_begin();
			
            $this->recipe_md->insert_group($vo);

            if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $code = 400;
                    $message = "타입 추가 실패";
            } else {
                    $this->db->trans_commit();
                    $code = 200;
                    $message = '타입 추가 완료';
            }

            
        }
            
        $data = array(
            'code' => $code,
            'message' => $message
        );
        
        header("Content-Type: application/json;");
        echo json_encode($data);
    }	

    public function get_recipe_info(){
        
        $group_idx = $this->input->post("idx");
        
        $where_arr = array(
                "idx" => $group_idx,
        );
	
        $result = $this->recipe_md->get_group_info($where_arr);
        
        if(empty($result)){
            $code = 400;
            $message = '잘못된 그룹정보입니다..';
        }else{
            $code = 200;
            $message = '성공.';
        }
        
        $data = array(
            'code' => $code,
            'message' => $message,
            'result'  => $result,
        );

        header("Content-Type: application/json;");
        echo json_encode($data);
        
    }
    
    public function set_update_recipe(){

        $code = '';
        $message = '';
        
        $vo = array();
        
        $group_idx = $this->input->post("update_group_idx");
        $vo['name'] = $this->input->post("update_group_name");
        $vo['state'] = $this->input->post("update_group_useyn");
                
        if (empty($vo['name'])){
            $code = 400;
            $message = 'update_group_name 변수의 요청이 올바르지 않습니다.';
        } else{
            
            $this->db->trans_begin();
            
            $this->recipe_md->group_update($vo, $group_idx);
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $code = 400;
                $message = "타입 수정 실패";
            } else {
                $this->db->trans_commit();
                $code = 200;
                $message = '타입 수정 완료';
            }
            
        }
            
        $data = array(
            'code' => $code,
            'message' => $message
        );
        
        header("Content-Type: application/json;");
        echo json_encode($data);


    }
}
