<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
    
    	function __construct() {
		parent ::__construct();

                //$this->allow=array('orderMenu');
                 
                $this->load->model('Shop_model', 'shop_md', TRUE);
                $this->load->model('Recipe_model', 'recipe_md', TRUE);
                $this->load->model('Customer_model', 'cust_md', TRUE);
                $this->load->model('Table_model', 'table_md', TRUE);
	}
        
       public function orderMenu($level,$shop_idx=0){
            
            $language = $this->input->get_post("language");
            $place = $this->input->get_post("place");
            $table_code = $this->input->get_post("code");
            $shop_idx = '';
            
            $data = array();
            if(!empty($language)){
                $data['language'] = $language;   
            }else{
                $data['language'] = $language = 'kor';
            }
            if(!empty($place)){
                $data['place'] = $place;
            }
            if(!empty($table_code) || !empty($this->session->table_code)){
                
                $vo = new stdClass();
                if(!empty($table_code)){
                    $vo->table_code = $table_code;
                }else{
                    $vo->table_code = $this->session->table_code;
                }
                
                $table_info = $this->table_md->get_table_info($vo);
                
                $sess_data['table_code'] = $table_code;
                $sess_data['table_no'] = $table_info->table_no;
                $shop_idx = $table_info->shop_idx;
                
                $this->session->set_userdata($sess_data);    //테이블번호, 혹은 시리얼넘버는 변조가 안되도록 세션으로 처리
 
            }
            
            if(empty($this->session->table_code) && empty($table_code)){
                var_dump($this->session);
                show_error("Check to Your Code."); 
            }
            
            if(empty($shop_idx) || $shop_idx == 0){
                show_error("Check to Your URL."); 
            }else{
                $data['shop_info'] = $this->shop_md->get_shop_info($shop_idx);
                $data['language_list'] = $this->shop_md->get_shop_set($shop_idx);
                $data['shop_idx'] = $shop_idx;
            }
            
            if(empty($data['shop_info'])){
                show_error("Check to Your URL."); 
            }
            
            switch ($level){
                /*case 1:     //언어 선택
                    $this->load->view('orderMenu1', $data);
                    break;*/
  
                    
                /*case 2:     //테이블번호, 혹은 시리얼 번호 입력 (하루 유지)
                    $this->load->view($language.'/orderMenu3', $data);
                    break;*/
                case 2:     //메뉴 선택
                    
                    $params = new stdClass(); 
                    $params->shop_idx = $shop_idx;
                    $params->asc = "asc";
                    
                    $data['menu_info'] = $this->recipe_md->get_group_list("",$params);
                    
                    $this->load->view($language.'/orderMenu4', $data);
                    break;
                case 5:
                    
                    $params = new stdClass();
                    $params->group_idx = $data['menu_idx'] =  $this->input->get_post('menu_idx');
                    $params->asc = "asc";
                    $params->state = "Y";
                    
                    $data['menu_info'] = $this->recipe_md->get_recipe_list("",$params);
                    
                    
                    $this->load->view($language.'/orderMenu5', $data);
                    break;
                default:
                    $this->load->view($language.'/orderMenu2', $data);
                    break;
            }

            $this->load->view($language.'/customer_footer', $data);
            
        }
        
        function orderDetail(){
            $this->input->get("order_idx");
            
        }
        
        function setMenu(){
            
            $table_code =  $this->session->table_code;
            $table_code =  $this->session->table_no;
            $recipe_idx = $this->input->post("menu_array");
            $recipe_array = explode("/", $recipe_idx);
            $cnt = 1; //cnt 값은 나중에 입력 받을 예정
            $place = $this->input->post("place");
            $shop_idx = $this->input->post("shop_idx");
            $price = $this->input->post("total_price");
            
            if(empty($table_no)){
                $result['message'] = "TIME OUT";
                $result["result"] = "0";
            }else{

                for($i=0;$i<count($recipe_array);$i++){

                    $cnt = $this->input->post("cnt_".$recipe_array[$i]);

                    if($recipe_array[$i] != ""){
                        $data = array(
                            "table_code"      => $table_code,
                            "table_no"      => $table_no,
                            "cnt"           => $cnt,
                            "place"         => $place,
                            "recipe_idx"    => $recipe_array[$i],
                            "shop_idx"      => $shop_idx,
                            "price"         => $price,
                            "order_no"      => date('his'),
                        );
                        $this->cust_md->insert_order($data);
                        
                        $result["result"] = "1";
                    }else{
                        $result['message'] = "DATA ERROR";
                        $result["result"] = "0";
                    }
                }
                
            }
            
            header("Content-Type: application/json;");
            echo json_encode($result);
        }
        
        function getMenuList(){
            
            $menu_array = explode("/", $this->input->post("menu_array"));
            
            $params = new stdClass();
            $params->idx_in = $menu_array;
            
            $menu_info_array =  $this->recipe_md->get_recipe_list("",$params);
                        
            $result['return'] = $menu_info_array;
            
            header("Content-Type: application/json;");
            echo json_encode($result);
            
        }
        
        function setSubmitBasket(){
            
            $idx_array = $this->input->post("basket_idx");
            $cnt_array = $this->input->post("basket_cnt");
            $table_no = $this->session->table_no;
            $table_code = $this->session->table_code;
            $shop_idx = $this->input->post("basket_shop_idx");
            $place = $this->input->post("basket_place");
            
            $params = new stdClass();
            $params->idx_in = $idx_array;
            $menu_info_array =  $this->recipe_md->get_recipe_list("",$params);
            
            $total_price = 0;
            $total_tax = 0;
            $total_sum = 0;
            
            $cookie_idx = $_COOKIE['menu_array'];
            $cookie_cnt = $_COOKIE['cnt_array'];
            
            $cookie_idx_array = explode("/", $cookie_idx);
            $cookie_cnt_array = explode("/", $cookie_cnt);
            
            foreach($menu_info_array as $menu){
                $idx = array_search($menu->idx, $idx_array);
                
                $total_price += $menu->price * $cnt_array[$idx];
                $total_tax += $menu->price * ($menu->tax / 100) * $cnt_array[$idx];
                $cidx = 0;
                $cidx = array_search($menu->idx, $cookie_idx_array);
                if($cidx > 0){
                    array_splice($cookie_idx_array,$cidx,1);
                    array_splice($cookie_cnt_array,$cidx,1);
                }
                
            }
            
            $total_sum = $total_price + $total_tax;
            

            foreach($menu_info_array as $menu){
                
                $idx = array_search($menu->idx, $idx_array);
                
                $data = array(
                    "table_no"      => $table_no,
                    "table_code"      => $table_code,
                    "cnt"           => $cnt_array[$idx],
                    "place"         => $place,
                    "recipe_idx"    => $menu->idx,
                    "shop_idx"      => $shop_idx,
                    "price"         => $total_sum,
                    "order_no"      => date('his'),
                );

                $this->cust_md->insert_order($data);
            }
            
            $result = true;
            
            $new_cookie_idx  = "";
            $new_cookie_cnt  = "";
            
            for($i=1;$i<count($cookie_idx_array); $i++){
                $new_cookie_idx .= "/".$cookie_idx_array[$i];
                $new_cookie_cnt .= "/".$cookie_cnt_array[$i];
            }
            
            $exp = time() + 86400*1;
            
            setCookie("menu_array", $new_cookie_idx, $exp, "/");
            setCookie("cnt_array", $new_cookie_cnt, $exp, "/");
            
            header("Content-Type: application/json;");
            echo json_encode($result);
            
        }
}

?>