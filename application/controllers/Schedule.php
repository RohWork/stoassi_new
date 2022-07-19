<?php
class Schedule extends CI_Controller {
	
	function __construct() {
            parent ::__construct();

            $this->head_data = header_set("main");
            $this->load->model('Member_model', 'member_md', TRUE);
            $this->load->model('Shop_model', 'shop_md', TRUE);
            $this->load->model('Schedule_model', 'schedule_md', TRUE);
            
            
	}
        
        public function index(){
            
            $this->head_data = header_set("schedule_calendar");
            
            $shop_idx = $this->session->userdata('shop_idx');
            $user_idx = $this->session->userdata('user_idx');

                        
            $this->load->view(LANGUAGE.'/header', $this->head_data);
            $this->load->view(LANGUAGE.'/schedule_calendar');
            
        }
        
        public function get_month_schedule(){
            
            $shop_idx = $this->session->userdata('shop_idx');
            $user_idx = $this->session->userdata('user_idx');
            $post_date = $this->input->post('post_date');
            
            if(empty($post_date)){
                $post_date = date('Y-m');
                echo "데이터가없음";
            }
            
            echo $date;

            $result['schedule'] = $this->schedule_md->get_schedule($user_idx,$shop_idx,$post_date);
            
            
            header("Content-Type: application/json;");
            echo json_encode($result);
            
        }
}
?>