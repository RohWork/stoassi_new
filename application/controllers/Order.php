<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Customer extends CI_Controller {
        
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
            
            $data['order_list'] = $this->cust_md->order_list($vo);
            
            
            
            $this->load->view(LANGUAGE.'/header', $this->head_data);
            $this->load->view(LANGUAGE.'/order_list_counter', $data);
            
        }
    }

?>

