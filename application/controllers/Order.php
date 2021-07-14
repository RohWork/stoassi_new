<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Order extends CI_Controller {
        
        function __construct() {
            parent ::__construct();

            $this->allow=array();
            
            $this->load->model('Shop_model', 'shop_md', TRUE);
            $this->load->model('Recipe_model', 'recipe_md', TRUE);
            $this->load->model('Customer_model', 'cust_md', TRUE);
            
            
        }

        public function order_list_counter(){
            
            $this->head_data = header_set("order_list_counter");
            
            
            
            $this->load->library('pagination');
            $vo = new stdClass();
            $vo->date = date('Y-m-d');
            $vo->shop_idx = $this->session->userdata("shop_idx");
            
            $offset = $this->input->get('per_page');
            
            $config['total_rows'] = $this->cust_md->count_order($vo);
            
            $config = setPagination($config);
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            
            
            $data['offset'] = $offset;
            $data['order_list'] = $this->cust_md->order_list($vo);
            $data['recipe_group_list'] = $this->recipe_md->get_group_list("",$vo);
            
            
            $this->load->view(LANGUAGE.'/header', $this->head_data);
            $this->load->view(LANGUAGE.'/order_list_counter', $data);
            
        }
        
        public function get_order_info(){
            
            $code = '';
            $message = '';
            
            $order_idx = $this->input->post("idx");
            
            if(empty($order_idx)){
                $message = "idx error";
                $code = 404;
            }else{
                $vo = new stdClass();
                $vo->idx = $order_idx;
                
                $result = $this->cust_md->detail_order($vo);
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
            
            $result = $this->recipe_md->get_recipe_list( "", $vo);
            
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
            $vo['table_no'] = $this->input->post("insert_table_no");
            $vo['place'] = $this->input->post("insert_place");
            $vo['status'] = $this->input->post("insert_status");
            
            $vo['cnt'] = "1";
            
            $result = $this->cust_md->insert_order($vo);
            
            $data['code'] = $code;
            $data['message'] = $message;
            $data['result'] = $result;
            
            header("Content-Type: application/json;");
            echo json_encode($data);
            
        }
        
        public function set_update_order(){
            
            $code = '';
            $message = '';
            
            $order_idx = $this->input->post("idx");
            $status = $this->input->post("status");
            
            if(empty($order_idx)){
                $message = "idx error";
                $code = 404;
            }else{
                $vo = new stdClass();
                $vo->idx = $order_idx;
                $vo->status = $status;
                
                $result = $this->cust_md->set_order($vo);
            }
            
            $data['code'] = $code;
            $data['message'] = $message;
            $data['result'] = $result;
            
            header("Content-Type: application/json;");
            echo json_encode($data);
            
        }
        
        
        public function order_list_kitchen(){
            $this->head_data = header_set("order_list_kitchen");
            
            
            $this->load->library('pagination');
            $vo = new stdClass();
            $vo->date = date('Y-m-d');
            
            echo $vo->date;
            
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
        
    }

?>

