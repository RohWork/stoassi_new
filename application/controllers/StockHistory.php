<?php
class StockHistory extends CI_Controller {
	
	function __construct() {
            parent ::__construct();
            $this->allow=array();
            
            $this->head_data = header_set("stock_history");

            $this->load->model('Stock_model', 'stock_md', TRUE);
            
        }

        function history_list(){
            
            $this->load->library('pagination');
            
            $data = array();
            
            $where = "";
            
            $search_vo  = new stdClass();
            
            $config['per_page'] = 10;
            $offset = $this->input->get('per_page');
            $config['base_url'] = current_url() . '?' . reset_GET('per_page');

            $search_vo->config_per_page = $config['per_page'];
            $config['total_rows'] = $this->stock_md->count_stock_history($search_vo);

            $config = setPagination($config);
            $this->pagination->initialize($config);

            $data['pagination'] = $this->pagination->create_links();
            
            
            $data['stock_history'] = $this->stock_md->get_stock_history_array($search_vo);
            
            $this->load->view(LANGUAGE.'/header', $this->head_data);
            $this->load->view(LANGUAGE.'/stock_history_list', $data);
        }
        
}
