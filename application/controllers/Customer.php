<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
    
    	function __construct() {
		parent ::__construct();

                $this->allow=array('orderMenu');
                 
                
                $this->load->model('Member_model', 'member_md', TRUE);
               
	}
        
       public function orderMenu($level){
            
            $language = $this->input->get("language");
            
            $data = array();
            
            switch ($level){
                case 1:     //언어 선택
                    $this->load->view('orderMenu1', $data);
                    break;
                case 2:     //취식, 포장 여부 선택
                    $data['language'] = $language;                    
                    $this->load->view($language.'/orderMenu2', $data);
                    break;
                case 3:     //테이블번호, 혹은 시리얼 번호 입력 (하루 유지)
                    $data['language'] = $language;
                    $this->load->view($language.'/orderMenu3', $data);
                    break;
                case 4:     //상위 메뉴 선택
                    $data['language'] = $language;
                    $this->load->view($language.'/orderMenu4', $data);
                    break;
                case 5:     //하위 메뉴 선택
                    $data['language'] = $language;
                    $this->load->view($language.'/orderMenu5', $data);
                    break;
            }
        }
        
        function orderDetail(){
            $this->input->get("order_idx");
            
            
        }
}

?>