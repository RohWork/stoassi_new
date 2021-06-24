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
            
            $vo = new stdClass();
            $vo->date = date('Y-m-d');
            $offset = $this->input->get('per_page');
            
            $config['total_rows'] = $this->cust_md->count_order($search_vo);
            
            $config = setPagination($config);
            $this->pagination->initialize($config);
            
            $data['offset'] = $offset;
            $data['order_list'] = $this->cust_md->order_list($vo);
            
            
            
            $this->load->view(LANGUAGE.'/header', $this->head_data);
            $this->load->view(LANGUAGE.'/order_list_counter', $data);
            
        }
    }

?>

