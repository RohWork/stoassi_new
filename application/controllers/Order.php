<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Order extends CI_Controller {
        
        function __construct() {
            parent ::__construct();

            $this->allow=array();
            
            $this->load->model('Shop_model', 'shop_md', TRUE);
            $this->load->model('Recipe_model', 'recipe_md', TRUE);
            $this->load->model('Customer_model', 'cust_md', TRUE);
            $this->load->model('Table_model', 'table_md', TRUE);
            
        }

        public function order_list_counter(){
            
            $this->head_data = header_set("order_list_counter");
            
            
            
            $this->load->library('pagination');
            
            $vo = new stdClass();
            $vo->date = date('Y-m-d');
            $vo->shop_idx = $this->session->userdata("shop_idx");
            
            
            $offset = $this->input->get('per_page');
            
            $vo->status = Array('1','2');
            $config['total_rows'] = $this->cust_md->count_table_order($vo);
            
            $config = setPagination($config);
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            
            
            $data['offset'] = $offset;
            $data['order_list'] = $this->cust_md->select_table_order($vo);
            
            $vo->state= 'Y';
            $data['recipe_group_list'] = $this->recipe_md->get_group_list("",$vo);
            
            $vo->status='1';
            $data['table_list'] = $this->table_md->get_table_list($vo);
            
            $vo->status = '2';
            $data['use_table_list'] = $this->table_md->get_table_list($vo);
            
            $this->load->view(LANGUAGE.'/header', $this->head_data);
            $this->load->view(LANGUAGE.'/order_list_counter', $data);
            
        }
        
        public function get_order_info(){
            $code = '';
            $message = '';
            $result = array();
            
            $code = $this->input->post('table_code');
            
            if(empty($code)){
                $message = "code error";
                $code = 404;
            }else{
                $vo = new stdClass();
                
                $vo->code = $code;
                $result['count'] = $this->cust_md->get_order_count($vo->code);
                
            }
            
            $data['code'] = $code;
            $data['message'] = $message;
            $data['result'] = $result;
            
            header("Content-Type: application/json;");
            echo json_encode($data);
            
        }
        
        public function get_recipe_info(){
            $code = '';
            $message = '';
            
            $group_idx = $this->input->post("group_idx");
            
            $vo = new stdClass();
            $vo->group_idx = $group_idx;
            $vo->state = 'Y';
            
            $result = $this->recipe_md->get_recipe_list( "", $vo);
            
            $data['code'] = $code;
            $data['message'] = $message;
            $data['result'] = $result;
            
            header("Content-Type: application/json;");
            echo json_encode($data);
            
        }
        
        public function set_table(){
            
            $code = '';
            $message = '';
            

            $vo = new stdClass();
            $vo->table_no = $this->input->post("table_no");
            $vo->status = $this->input->post("table_mode");
            $vo->shop_idx = $this->session->userdata("shop_idx");
            $vo->table_code = md5(date('his'));
            
            $result = $this->table_md->set_table($vo);
            
            $data['code'] = $code;
            $data['message'] = $message;
            $data['result'] = $result;
            
            header("Content-Type: application/json;");
            echo json_encode($data);
            
        }
        
        public function clear_table(){
            $code = '';
            $message = '';
            

            $vo = new stdClass();
            
            $vo->table_no = $this->input->post("table_no");
            $vo->status = $this->input->post("table_mode");
            $vo->shop_idx = $this->session->userdata("shop_idx");
            $vo->table_code = "";
            
            $result = $this->table_md->set_table($vo);
            
            $data['code'] = $code;
            $data['message'] = $message;
            $data['result'] = $result;
            
            header("Content-Type: application/json;");
            echo json_encode($data);
            
        }
        
        public function set_order(){
            
            $code = '200';
            $message = '추가완료';
            
            $vo['shop_idx'] = $this->session->userdata("shop_idx");
            $vo['recipe_idx'] = $this->input->post("insert_recipe");
            $vo['table_code'] = $this->input->post("insert_table_code");
            $vo['status'] = $this->input->post("insert_status");
            $vo['price'] = $this->input->post("insert_recipe_total_price");
            $vo['cnt'] = $this->input->post("insert_recipe_cnt");
            
            $std = new stdClass();
            $std->shop_idx = $this->session->userdata("shop_idx");
            $std->table_code = $vo['table_code'];
            
            $row = $this->table_md->get_table_list($std);
            
            if(!empty($row)){
                $vo['table_no'] = $row[0]->table_no;
                $result = $this->cust_md->insert_order($vo);
            }else{
                $code = '400';
                $message = '존재하지않는 테이블 코드입니다';
                $result = false;
            }
            $data['code'] = $code;
            $data['message'] = $message;
            $data['result'] = $result;
            
            header("Content-Type: application/json;");
            echo json_encode($data);
            
        }
        
        public function set_update_order(){
            
            $code = '';
            $message = '';
            
            $order_idx = explode(",",$this->input->post("idx"));
            $status = $this->input->post("status");
            
            
            if(empty($order_idx)){
                $message = "idx error";
                $code = 404;
            }else{
                
                $cnt = 0;

                if (is_array($order_idx))

                $cnt = count($order_idx);

                for($i=0;$i<$cnt;$i++){
                    $vo = new stdClass();
                    $vo->idx = $order_idx[$i];
                    $vo->status = $status;

                    $result = $this->cust_md->set_order($vo);
                }
            }
            
            $data['code'] = $code;
            $data['message'] = $message;
            
            header("Content-Type: application/json;");
            echo json_encode($data);
            
        }
        
        
        public function order_list_kitchen(){
            $this->head_data = header_set("order_list_kitchen");
            
            
            $this->load->library('pagination');
            $vo = new stdClass();
            $vo->date = date('Y-m-d');
            
            $vo->status = 2;
            $offset = $this->input->get('per_page');
            
            $config['total_rows'] = $this->cust_md->count_order($vo);
            
            $config = setPagination($config);
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            
            
            $data['offset'] = $offset;
            $data['order_list'] = $this->cust_md->order_list($vo);
            
            
            
            $this->load->view(LANGUAGE.'/header', $this->head_data);
            $this->load->view(LANGUAGE.'/order_list_kitchen', $data);
            
        }
        
        public function pop_wait_order(){


            $vo = new stdClass();
            
            $vo->table_code = $this->input->get('code');

            $vo->status = 1;

            $data['list_wait'] = $this->cust_md->order_list($vo);
            
            $this->load->view(LANGUAGE.'/order_list_wait', $data);
            
        }
        public function pop_complete_order(){


            $vo = new stdClass();
            $vo->table_code = $this->input->get('code');
            
            $vo->status = array(2,3);

            $data['list_complete'] = $this->cust_md->order_list($vo);
            
            $this->load->view(LANGUAGE.'/order_list_complete', $data);
            
        }
        
        public function order_history(){


            $this->head_data = header_set("order_history");
            
            $this->load->library('pagination');
            
            $vo = new stdClass();
            //$vo->date = date('Y-m-d');
            $vo->shop_idx = $this->session->userdata("shop_idx");
            $data['sdate'] = $vo->sdate = $this->input->get("sdate");
            $data['edate'] = $vo->edate = $this->input->get("edate");
            $data['orderparam'] = $vo->orderparam = $this->input->get('orderparam');
            $data['orderdesc'] = $vo->orderparam = $this->input->get('orderdesc');
            
            $config['base_url'] = current_url() . '?' . reset_GET('per_page');
            $config['per_page'] = 10;

            $offset = $this->input->get('per_page');
            
            $config['total_rows'] = $this->cust_md->count_order($vo);
            
            $config = setPagination($config);
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            
            $vo->config_per_page = $config['per_page'];
            
            $data['offset'] =  $offset;
            $data['order_list'] = $this->cust_md->order_limit_list($vo,$offset);
            

            
            $this->load->view(LANGUAGE.'/header', $this->head_data);
            $this->load->view(LANGUAGE.'/order_history', $data);
            
        }
        
        public function order_history_excel(){


            $this->head_data = header_set("order_history");
            
            $this->load->library('pagination');
            
            $vo = new stdClass();
            //$vo->date = date('Y-m-d');
            $vo->shop_idx = $this->session->userdata("shop_idx");
            $data['sdate'] = $vo->sdate = $this->input->get("sdate");
            $data['edate'] = $vo->edate = $this->input->get("edate");
            
            

            $offset = $this->input->get('per_page');
            
            $vo->config_per_page = $config['per_page'];
            
            $data['offset'] =  $offset;
            $data['order_list'] = $this->cust_md->order_limit_list($vo,$offset);
            
            $this->load->view(LANGUAGE.'/order_history_excel', $data);
            
        }
        
        public function get_table_qr(){
            
            $code = '';
            $message = '';
            
            $code = $this->input->post('code');
            
            if(empty($code)){
                $message = "code error";
                $code = 404;
            }else{
                $result_qr = $this->cust_md->get_order_count($code);

                if(is_https_request()){
                    $host = "https://";
                }else{
                    $host = "http://";
                }
                $url = $host.$_SERVER["HTTP_HOST"].'/customer/orderMenu/1/'.$result_qr->shop_idx.'?code='.$code;

                $result['file'] = get_qr($url,$code);
            }
            
            $data['code'] = $code;
            $data['message'] = $message;
            $data['result'] = $result;
            
            header("Content-Type: application/json;");
            echo json_encode($data);
        }
        
        public function get_order_recipe_info(){
            
            $code = '';
            $message = '';
            
            $order_idx = $this->input->post("idx");
            
            if(empty($order_idx)){
                $message = "idx error";
                $code = 404;
            }else{
                $vo = new stdClass();
                $vo->idx = $order_idx;
                
                $result['order'] = $this->cust_md->detail_order($vo);
                
                $recipe_idx = $result['order']->recipe_idx;
                
                $result['recipe'] = $this->cust_md->get_order_recipe($recipe_idx);
                
            }
            
            $data['code'] = $code;
            $data['message'] = $message;
            $data['result'] = $result;
            
            header("Content-Type: application/json;");
            echo json_encode($data);
            
        }
        
        public function set_update_order_recipe(){
            
            $code = '';
            $message = '';
            
            $order_idx = $this->input->post("idx");
            $use_yn = $this->input->post("use_yn");
            $status = 3;
           
            
            if(empty($order_idx)){
                $message = "idx error";
                $code = 404;
            }else{
                $vo = new stdClass();
                $vo->idx = $order_idx;
                $vo->status = $status;
                
                
                $order = $this->cust_md->detail_order($vo);
                $recipe_idx = $order->recipe_idx;
                $recipe = $this->cust_md->get_order_recipe($recipe_idx);
                
                foreach($recipe as $ri){

                    if($use_yn[$ri->order_num] == 1){
                        $svo = array(
                            "stock_idx" => $ri->stock_idx,
                            "count"     => $ri->stock_input,
                            "state"     => 1,
                            "inout"     => 2,
                            "memo"      => $ri->name,
                            "writer"    => $this->session->userdata('user_id'),
                            'regi_date' => date('Y-m-d h:i:s')
                        );

                        $this->cust_md->insert_stock_history($svo);
                        
                        $this->cust_md->update_stock_count($svo);
                    }
                }
                
                
                $result = $this->cust_md->set_order($vo);
            }
            
            $data['code'] = $code;
            $data['message'] = $message;
            $data['result'] = $result;
            
            header("Content-Type: application/json;");
            echo json_encode($data);
            
        }
        
    }

?>

