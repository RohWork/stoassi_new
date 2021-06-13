<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
    
    	function __construct() {
		parent ::__construct();

                $this->allow=array('orderMenu');
                 
                $this->load->model('Shop_model', 'shop_md', TRUE);
                $this->load->model('Recipe_model', 'recipe_md', TRUE);
                $this->load->model('Customer_model', 'cust_md', TRUE);
	}
        
       public function orderMenu($level,$shop_idx=0){
            
            $language = $this->input->get_post("language");
            $place = $this->input->get_post("place");
            $table_no = $this->input->get_post("table_no");
            
            $data = array();
            if(!empty($language)){
                $data['language'] = $language;   
            }
            if(!empty($place)){
                $data['place'] = $place;
            }
            if(!empty($table_no)){
                $data['table_no'] = $table_no;
                $this->session->set_userdata('table_no', $table_no);    //테이블번호, 혹은 시리얼넘버는 변조가 안되도록 세션으로 처리
            }
            if(empty($shop_idx) || $shop_idx == 0){
                show_error("Check to Your URL."); 
            }else{
                $data['shop_info'] = $this->shop_md->get_shop_info($shop_idx);
                $data['language_list'] = $this->shop_md->get_shop_set($shop_idx);
            }
            
            switch ($level){
                case 1:     //언어 선택
                    $this->load->view('orderMenu1', $data);
                    break;
                case 2:     //취식, 포장 여부 선택     
                    $this->load->view($language.'/orderMenu2', $data);
                    break;
                case 3:     //테이블번호, 혹은 시리얼 번호 입력 (하루 유지)
                    $this->load->view($language.'/orderMenu3', $data);
                    break;
                case 4:     //메뉴 선택
                    
                    $params = new stdClass();
                    $params->shop_idx = $shop_idx;
                    $params->asc = "asc";
                    
                    $data['menu_info'] = $this->recipe_md->get_group_list("",$params);
                    
                    $this->load->view($language.'/orderMenu4', $data);
                    break;
                case 5:
                    
                    $params = new stdClass();
                    $params->group_idx = $this->input->post('menu_idx');
                    $params->asc = "asc";
                    
                    $data['menu_info'] = $this->recipe_md->get_recipe_list("",$params);
                    
                    $this->load->view($language.'/orderMenu5', $data);
                    break;
                
            }
            $this->load->view('customer_footer', $data);
        }
        
        function orderDetail(){
            $this->input->get("order_idx");
            
        }
        
        function setMenu(){
            
            $table_no =  $this->session->table_no;
            $recipe_idx = $this->input->post("menu_idx");
            $recipe_array = explode("/", $recipe_idx);
            $cnt = 1; //cnt 값은 나중에 입력 받을 예정
            $place = $this->input->post("place");
            
            for($i=0;$i<count($recipe_array);$i++){
                
                if($recipe_array[$i] != ""){
                    $data = array(
                        "table_no"      => $table_no,
                        "cnt"           => $cnt,
                        "place"         => $place,
                        "recipe_idx"    => $recipe_array[$i]
                    );

                    $this->cust_md->insert_order($data);
                }
            }
            $result["result"] = "1";
            
            header("Content-Type: application/json;");
            echo json_encode($result);
        }
}

?>