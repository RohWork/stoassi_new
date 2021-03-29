<?php
class RecipeList extends CI_Controller {
	
	function __construct() {
            parent ::__construct();
            
            $this->allow=array();
            
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
            if(empty($stock_data[$strow->stock_category_idx])){
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

        
        $this->load->view(LANGUAGE.'/header', $this->head_data);
        $this->load->view(LANGUAGE.'/recipe_list', $data);
    }
    
    
    public function set_recipe(){
        $code = '';
        $message = '';
        
        $vo = array();
        $vo_process = array();
        
        $vo['name'] = $this->input->post("insert_recipe_name");
        $vo['group_idx'] = $this->input->post("insert_recipe_group");
        $vo['recipe_idx'] = $this->input->post("insert_recipe_group");
        
        
        $vo['shop_idx'] = $data['writer'] = $this->session->userdata("shop_idx");
                
        if (empty($vo['name'])){
            $code = 400;
            $message = 'insert_recipe_name 변수의 요청이 올바르지 않습니다.';
        }else{
            
            $stock_category_array   = $this->input->post("insert_stock_category");
            $stock_info_array       = $this->input->post("insert_stock_info");
            $stock_cnt_array        = $this->input->post("insert_stock_cnt");
            $stock_unit_array       = $this->input->post("insert_stock_unit");
            $recipe_time_array      = $this->input->post("insert_recipe_time");


            $this->db->trans_begin();
			
            $vo_process['recipe_idx'] = $this->recipe_md->insert_recipe($vo);
            
            for($i=0;$i<count($stock_info_array);$i++){
                
                $vo_process['stock_idx'] = $stock_info_array[$i];
                $vo_process['order_num'] = $i;
                $vo_process['stock_input'] = $stock_cnt_array[$i];
                $vo_process['set_time'] = $recipe_time_array[$i];
                
                $this->recipe_md->insert_process($vo_process);
            }
            if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $code = 400;
                    $message = "레시피 추가 실패";
            } else {
                    $this->db->trans_commit();
                    $code = 200;
                    $message = '레시피 추가 완료';
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
        
        $recipe_idx = $this->input->post("idx");
        

	
        $result = $this->recipe_md->get_recipe_info($recipe_idx);
        $result_process = $this->recipe_md->get_recipe_proces($recipe_idx);
        
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
            'process' => $result_process,
        );

        header("Content-Type: application/json;");
        echo json_encode($data);
        
    }
    
    public function set_update_recipe(){

        $code = '';
        $message = '';
        
        $vo = array();
        
        $recipe_idx = $this->input->post("update_recipe_idx");
        $vo['name'] = $this->input->post("update_recipep_name");
        $vo['state'] = $this->input->post("update_recipe_useyn");
        $vo['group_idx'] = $this->input->post("update_recipe_group");
        
        
        
        if (empty($vo['name'])){
            $code = 400;
            $message = 'update_group_name 변수의 요청이 올바르지 않습니다.';
        } else{
            
            $this->db->trans_begin();
            
            $this->recipe_md->recipe_update($vo, $recipe_idx);
            
            $stock_category_array   = $this->input->post("update_stock_category");
            $stock_info_array       = $this->input->post("update_stock_info");
            $stock_cnt_array        = $this->input->post("update_stock_cnt");
            $stock_unit_array       = $this->input->post("update_stock_unit");
            $recipe_time_array      = $this->input->post("update_recipe_time");
            
            $order_num = $this->input->post("order_num");
            
            for($i=0;$i<count($stock_info_array);$i++){
                
                $vo_process['stock_idx'] = $stock_info_array[$i];
                $vo_process['order_num'] = $i;
                $vo_process['stock_input'] = $stock_cnt_array[$i];
                $vo_process['set_time'] = $recipe_time_array[$i];
                if($i <= $order_num){
                    $this->recipe_md->update_process($vo_process, $recipe_idx, $i);
                }else{
                    $this->recipe_md->insert_process($vo_process);
                }
            }
            
            
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
