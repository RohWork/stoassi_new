<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 2 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Refobi</title>
        <!-- 합쳐지고 최소화된 최신 CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

        <!-- 부가적인 테마 -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <!-- 합쳐지고 최소화된 최신 자바스크립트 -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <!-- Custom styles for this template -->
        <link href="/assets/css/sticky-footer-navbar.css" rel="stylesheet">
        <link href="/assets/css/customer.css" rel="stylesheet">
        <!-- 쿠키 사용 --> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script> 
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-1 col-xs-1"></div>
                <div class="col-md-10 col-xs-10"><center><h3><?=$shop_info['name']?></h3></center></div>
                <div class="col-md-1 col-xs-1"></div>
            </div>
            <form id="orderForm" name="orderForm">
                <div class="row" style="margin-top: 30px">
                        <div class="col-md-1 col-xs-1"></div>
                             <div class="col-md-10 col-xs-10">
                                 <table style="width:100%" width="100%" border="0" cellpadding="0" cellspacing="0">
                                     <tr class="menu_height">
                                        <?php
                                            $cnt = 0;
                                            foreach($menu_info as $menu){
                                                if($cnt % 2 == 0 && $cnt != 0){
                                                    echo "</tr><tr class='menu_height'>";
                                                }
                                                            echo "<td align='center' onclick='menu_check_go(".$menu->idx.", this )'><b style='cursor:pointer'>".$menu->group_name."<b/><br/>".$menu->name."<br/>[상세보기]</td>";
                                                $cnt++;
                                            }
                                        ?>  
                                     </tr>
                                 </table>
                             </div>
                        <div class="col-md-1 col-xs-1"></div>
                    </div>
                <input type="hidden" name="language" id="language" value="<?=$language?>"/>
                <input type="hidden" name="place" id="place" value="<?=$place?>"/>
                <input type="hidden" name="shop_idx" id="shop_idx" value="<?=$shop_idx?>"/>
                <input type="hidden" name="menu_idx" id="menu_idx" value="<?=$menu_idx?>"/>
                <input type="hidden" name="menu_array" id="menu_array"/>
                <input type="hidden" name="total_price" id="total_price"/>
            <div class="row" style="margin-top: 30px">
                <div class="col-md-1 col-xs-1"></div>
                <div class="col-md-10 col-xs-10">
                    <table id="menu_list" class="table">
                        <tr>
                            <th width="10%">NO</th>
                            <th width="50%">메뉴명</th>
                            <th width="20%">금액</th>
                            <th width="20%">갯수</th>
                        </tr>
                    </table>
                    <div style="width: 100%; margin-bottom: 30px">
                        <label> 메뉴가격 :  </label><span id="sum"> </span><br/>
                        <label> 부가세 : </label><span id="tax"></span><br/>
                        <label> 계산된 금액 : </label><span id="total"> </span>
                        
                    </div>
                    <div style="text-align: center;width: 100%">
                        <div style="float:left;width:50%"><button type="button" onclick="menu_basket_go()" class="form-control">장바구니추가</button></div>
                        <div style="float:left;width:50% "><button type="button" onclick="menu_send_go()" class="form-control">주문하기</button></div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <div class="col-md-1 col-xs-1"></div>
            </div>
            
            </form>
	</div>
    </body>
    <script>
        
        var menuArray = new Array();
        var priceArray = new Array();
        var priceIdxArray = new Array(); 
        var tax;
        
        <?php
            foreach($menu_info as $menu){
        ?>
                menuArray[<?=$menu->idx?>] = "<?=$menu->name?>";
                priceArray[<?=$menu->idx?>] = "<?=$menu->price?>";
                tax = "<?=$menu->tax?>";
        <?php
            }
        ?>
        
        var no = 1;
        
        function menu_check_go(idx, element){
            if($("#menu_array").val().indexOf(idx) != -1){
                $(element).css('backgroundColor' , '#FFFFFF');
                $("#menu_array").val($("#menu_array").val().replace("/"+idx,''));
                $("#tr_"+idx).remove();
                priceIdxArray.splice(priceIdxArray.indexOf(idx),1);
                no--;
                calcPrice();
            }else{
                $(element).css('backgroundColor' , '#CCCCCC');
                $("#menu_array").val($("#menu_array").val()+"/"+idx);
                $("#menu_list").append(
                        "<tr id='tr_"+idx+"'>"+
                            "<td>"+no+"</td>"+
                            "<td>"+menuArray[idx]+"</td>"+
                            "<td>"+priceArray[idx]+"</td>"+
                        "<td>"+
                        "<button type='button' class='btn btn-primary' onclick='cnt_change("+idx+",1)'> + </button>"+
                        "<input type='text' maxlength='3' size='3' id='cnt_"+idx+"' name='cnt_"+idx+"' readonly value='1' style='text-align:center'/>"+
                        "<button type='button' class='btn btn-primary' onclick='cnt_change("+idx+",2)'>&nbsp;-&nbsp;</button>"+
                        "</td></tr>"
                );
                
                priceIdxArray.push(idx);
                no++;
                calcPrice();
            }
            
        }
        
        function menu_basket_go(){
        
            if($("#total_price").val() == "" || $("#total_price").val() < 1){
                alert("상품을 선택해주세요.");
                return;
            }else{
                
                var menu_idx_array = String($("#menu_array").val()).split("/");
                var menu_idx_string = "";
                var menu_cnt_string = "";
                                
                if(typeof($.cookie('menu_array')) != 'undefined'){
                    var menu_cookie_array = $.cookie('menu_array').split("/");
                    var cnt_cookie_array = $.cookie('cnt_array').split("/");
                }
                                
                for(var i=1; i<no; i++){
                    if(typeof($.cookie('menu_array')) != 'undefined'){  //장바구니가 비어있지 않을경우
                        var check_cookie = false;
                        for(var j=1;j<menu_cookie_array.length;j++){   //장바구니에 추가된 상품일경우
                            if(menu_cookie_array[j] == menu_idx_array[i]){
                                menu_cnt_string += "/"+(Number(cnt_cookie_array[j])+Number($("#cnt_"+menu_idx_array[i]).val()));
                                menu_idx_string += "/"+menu_idx_array[i];
                                check_cookie = true;
                                
                                menu_cookie_array[j] = "";
                            }
                        }
                        if(check_cookie == false){  //장바구니에 추가되있지 않는 상품일경우
                             menu_cnt_string += "/"+$("#cnt_"+menu_idx_array[i]).val();
                             menu_idx_string += "/"+menu_idx_array[i];
                        }
                        
                    }else{
                        menu_cnt_string += "/"+$("#cnt_"+menu_idx_array[i]).val();
                        menu_idx_string += "/"+menu_idx_array[i];
                    }
                    
                    if($("#cnt_"+menu_idx_array[i]).val() < 1){
                        alert('갯수를 입력해주세요');
                        return false;
                    }
                }
                
                if(typeof($.cookie('menu_array')) != 'undefined'){
                    for(var j=1;j<menu_cookie_array.length;j++){    //장바구니에서 변경될게 없는 상품
                        if(menu_cookie_array[j] != ""){
                            menu_cnt_string += "/"+cnt_cookie_array[j];
                            menu_idx_string += "/"+menu_cookie_array[j];
                        }
                    }
                }
                
                $.cookie('menu_array', menu_idx_string, {path: '/' });
                $.cookie('cnt_array', menu_cnt_string, {path: '/' });
                count_basket();
            }
        }
        
        
        function menu_send_go(){
            if($("#menu_idx").val() == ""){
                alert("메뉴를 체크해주세요.");
                return;
            }else{
                $.ajax({
                        url:'/customer/setMenu',
                        type:'post',
                        data: $("#orderForm").serialize(),
                        success:function(data){
                            if(data.result == true){
                                alert("주문완료");
                            }else{
                                alert(data.message);
                            }
                        },
                        error: function(xhr,status,error) {
                            console.log(xhr,status,error);
                            alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                            return false;
                        }	 
                });
            }
        }
        
        function calcPrice(){
            
            var cnt = 0;
            var price = 0;
            var taxValue = 0;
            var totalPrice = 0 ;
            var sumPrice = 0;
            

            for(var i=0; i <priceIdxArray.length; i++){
                
                var tmpIdx = priceIdxArray[i];
                
                cnt = Number($("#cnt_"+tmpIdx).val());
                price = Number(priceArray[tmpIdx]);
                
                sumPrice = sumPrice + (price * cnt);
                
            }
            
            
            
            taxValue = parseFloat(sumPrice * (tax / 100));
            totalPrice = parseFloat(sumPrice + (sumPrice * (tax / 100)));
            
            $("#sum").text(sumPrice);
            $("#tax").text(taxValue);
            $("#total").text(totalPrice);
            $("#total_price").val(totalPrice);
            
        }
        
        function cnt_change(idx,mode){
            
            var idx_cnt =  $("#cnt"+idx).val();
            console.log(idx);
            if(mode == 1 ){
               idx_cnt ++;
               $("#cnt_"+idx).val(idx_cnt);
            }else{
                idx_cnt --;
                
                if(cnt < 0){
                    alert("0보다 작습니다.");
                    return false;
                }else{
                   $("#cnt_"+idx).val(idx_cnt);
                }
            }
            calcPrice();
        }
    </script>
</html>
        
        
