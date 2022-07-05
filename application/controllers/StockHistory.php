<?php
class StockHistory extends CI_Controller {
	
	function __construct() {
            parent ::__construct();
            $this->allow=array();
            
            $this->head_data = header_set("stock_history");

            $this->load->model('Stock_model', 'stock_md', TRUE);
            
        }

        function history_list(){
            
            $data = array();
            
            $where = "";
            
            
            $data['stock_history'] = $this->stock_md->get_stock_history_array($where);
            
            $this->load->view(LANGUAGE.'/header', $this->head_data);
            $this->load->view(LANGUAGE.'/history_list', $data);
        }
        
}
