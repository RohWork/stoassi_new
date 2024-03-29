<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// GET 변수들을 하나의 문자열로 묶어 준다
if ( ! function_exists('reset_GET')) {
    function reset_GET(){
        $re= array();

        $tmp= func_get_args();

        if( is_array( $tmp[0])){
            $args= $tmp[0];
        } else {
                $args= $tmp;
        }

        foreach($_GET as $k=>$v){
            if( !in_array($k, $args)) {
                if($v!='') {
                    $re[]= $k.'='.urlencode($v);
                }
            }
        }
        
        return implode('&',$re);
    }
}

// POST 전송여부 확인
if ( ! function_exists('is_post')) {
    function is_post(){
        return $_SERVER['REQUEST_METHOD']=='POST';
    }
}

// 이전 페이지 URL 가져오기
if ( ! function_exists('get_back_page')) {
    function get_back_page(){
        $ci =& get_instance();
        $ci->load->library('user_agent');

        $goto='';

        if( $ci->agent->is_referral()) {
            $goto= $ci->agent->referrer();
        } else {
                $goto= base_url();
        }

        return $goto;
    }
}

//모바일 여부
if ( ! function_exists('is_mobile')) {
    function is_mobile(){
        $ci =& get_instance();
        $ci->load->library('user_agent');
        return $ci->agent->is_mobile();
    }
}

//쿼리스트링
if ( ! function_exists('add_querystring_var')) {
    function add_querystring_var($url, $key, $value = ''){
        $url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);

        $value = $value ? "=".urlencode($value) : '';

        if (strpos($url, '?') === false) {
            return ($url . '?' . $key . $value);
        } else {
            return ($url . '&' . $key . $value);
        }
    }
}

//모바일 여부
if ( ! function_exists('header_set')) {
    function header_set($menu){
        
        $stock_array = array("stock_seller","stock_list","stock_category","stock_history");
        $recipe_array = array("recipe_group","recipe_list");
        $order_array = array("order_list_counter", "order_list_kichen","order_history");
        $schedule_array = array("schedule_calendar");
        $stock= $recipe = $order= $schedule = "";
        
        if(in_array($menu, $stock_array)){
            $stock = "active";
        }
        
        if(in_array($menu, $recipe_array)){
            $recipe = "active";
        }
        
        if(in_array($menu, $order_array)){
            $order = "active";
        }
        if(in_array($menu, $schedule_array)){
            $schedule = "active";
        }
        
        $head_data = array(
            "main"	=> "",
            "stock_list" => "",
            "stock_category" => "",
            "stock_seller" => "",
            "stock_history" => "",
            "recipe_group"   => "",
            "recipe_list"   => "",
            "order_list"   => "",
            "order_list_counter"   => "",
            "order_list_kitchen"   => "",
            "order_history"   => "",
            "schedule_calendar"   => "",
            "stock" => $stock,
            "recipe"    => $recipe,
            "order"    => $order,
            "schedule"  => $schedule
        );
        $head_data[$menu] = "class='active'";
        
        return $head_data;
    }
    
    
}

    function get_qr($url,$file_name){

        include $_SERVER['DOCUMENT_ROOT']."/include/phpqrcode/phpqrcode.php";
        
        $dir = "/result/";
        $file_name = $file_name.".png";
        
        if(!is_file($_SERVER['DOCUMENT_ROOT'].$dir.$file_name)){
            QRcode::png($url,$_SERVER['DOCUMENT_ROOT'].$dir.$file_name,0,3,2);
        }
        return $dir.$file_name;
        
    }
    
    function is_https_request(){
        if ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 

             || $_SERVER['SERVER_PORT'] == 443) {		

		return true; 
	}
	return false;
        
    }
?>