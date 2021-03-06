<?php
class Member extends CI_Controller {
	
	function __construct() {
            parent ::__construct();

            $this->head_data = header_set("main");
            $this->load->model('Member_model', 'member_md', TRUE);
            $this->load->model('Shop_model', 'shop_md', TRUE);
	}
        
        public function join(){
            
            $this->load->view(LANGUAGE.'/join');
        }
        
        public function get_shop_category(){
            
            $result = $this->shop_md->get_shop_category();
            
            header("Content-Type: application/json;");
            echo json_encode($result);
        }
        
        public function check_id(){
            
            $user_id = $this->input->post("user_id");
            $result = false;
            $search_vo  = new stdClass();
            
            $search_vo->user_id =  $user_id;
            
            
            $cnt = $this->member_md->count_member_list($search_vo);
            
            
            if($cnt < 1){
                $result = true;
            }
            
            $data = array(
                "result" => $result
            );
            
            header("Content-Type: application/json;");
            echo json_encode($data);
        }
        
        public function join_process(){
            
            $result = false;
            $data = array();
            $confirm_id = $this->input->post("confirm_id");
            $user_id = $this->input->post("user_id");
            
            $search_vo  = new stdClass();
            
            $search_vo->user_id =  $user_id;
            
            $cnt = $this->member_md->count_member_list($search_vo);
            
            
            
            if($cnt > 1){
                $data["message"]= "중복된 ID 입니다.";
            }else if ($confirm_id != $user_id){
                $data["message"]= "중복체크되지 않은 ID입니다.";
            }else{
            
                $member_params = array(
                    "id" => $this->input->post("confirm_id"),
                    "pwd" => base64_encode(hash('sha512',$this->input->post("user_pw1"),true)),
                    "name" => $this->input->post("user_name"),
                    "tel" => $this->input->post("user_tel"),
                    "email" => $this->input->post("user_email"),
                    "email_confirm" => $this->input->post("email_confirm"),
                    "writer" => $this->input->post("confirm_id"),
                    "state" => "Y",
                );
                
                $shop_params = array(
                    "id"    => $member_params['id'],
                    "name"  => $this->input->post("shop_name"),
                    "tel"   => $member_params['tel'],
                    "email" => $member_params['email'],
                    "addr"  => $this->input->post("shop_addr"),
                    "category_idx" => $this->input->post("shop_category"),
                    "writer"    => $member_params["writer"],
                );
                
                if(!empty($this->input->post("shop_state"))){
                    $shop_params["state"] = $this->input->post("shop_state");
                }
                
                $result_shop_idx = $this->shop_md->set_shop($shop_params);
                
                if($result_shop_idx){
                    
                    $member_params['shop_idx'] = $result_shop_idx;
                    
                    if($this->member_md->set_member($member_params)){
                        $data["message"]= "가입완료";
                        $data["result"] = true;
                    }else{
                        $data["message"]= "데이터처리(회원데이터) 실패";
                    }
                }else{
                    $data["message"]= "데이터처리(상점데이터) 실패";
                }
            }
            
            header("Content-Type: application/json;");
            echo json_encode($data);
            
        }
        
        public function manage_member_list(){
            
                $this->load->library('pagination');

		$search_vo  = new stdClass();
		
		$config['per_page'] = 10;
		$offset = $this->input->get('per_page');
		$config['base_url'] = current_url() . '?' . reset_GET('per_page');
		
		$search_vo->config_per_page = $config['per_page'];
		$config['total_rows'] = $this->member_md->count_member_list($search_vo);

		$config = setPagination($config);
		$this->pagination->initialize($config);
		
 		$data['pagination'] = $this->pagination->create_links();
		
		
		if ($config['total_rows'] > 0) {
                    $rows = $this->member_md->get_member_list($offset, $search_vo);
                } else {
                    $rows = false;
                }
		
		
		$data['rows'] = $rows;
		$data['offset'] = $offset;
		$data['base_url'] = $config['base_url'];

		
		$this->load->view(LANGUAGE.'/header', $this->head_data);
		$this->load->view(LANGUAGE.'/member_list', $data);
            
            
        }
        
        public function get_member_info(){
            
            
            $idx = $this->input->post("idx");
            $result = false;

            $result_array = $this->member_md->get_member_info_idx($idx);
            
            
            if(!empty($result_array)){
                $result = true;
            }
            
            $data = array(
                "result" => $result,
                "result_data"  => $result_array
            );
            
            header("Content-Type: application/json;");
            echo json_encode($data);
        }
        
        public function set_update_member(){
            
            $code = '';
            $message = '';
            
            $vo = array();
            $vo_shop = array();
            
            $member_idx = $this->input->post("update_member_idx");
            $vo['name'] = $this->input->post("update_name");
            $vo['tel'] = $this->input->post("update_tel");
            $vo['email'] = $this->input->post("update_email");
            
            if(!empty($this->input->post("update_pw1"))){
                $vo['pwd'] = base64_encode(hash('sha512',$this->input->post("update_pw1"),true));
            }
            
            $shop_idx = $this->input->post("update_shop_idx");
            $vo_shop['email'] = $this->input->post("update_email");
            $vo_shop['name'] = $this->input->post("update_shop_name");
            $vo_shop['category_idx'] = $this->input->post("update_shop_category");
            $vo_shop['state'] = $this->input->post("update_shop_state");
            $vo_shop['addr'] = $this->input->post("update_shop_addr");
            
            $this->db->trans_begin();
            
            $this->member_md->update_member_info($vo, $member_idx);
            $this->member_md->update_shop_info($vo_shop, $shop_idx);
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $code = 400;
                $message = "상품 수정 실패";
            } else {
                $this->db->trans_commit();
                $code = 200;
                $message = '성공';
            }
            
                        
            $data = array(
                'code' => $code,
                'message' => $message
            );

            header("Content-Type: application/json;");
            echo json_encode($data);
        }
}
?>