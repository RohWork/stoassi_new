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
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-1 col-xs-1"></div>
                <div class="col-md-10 col-xs-10"><center><h3><?=$shop_info['name']?></h3></center></div>
                <div class="col-md-1 col-xs-1"></div>
            </div>
            <form id="orderForm" name="orderForm" action="/customer/orderMenu/5/<?=$shop_info['idx']?>" method="post">
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
                <input type="hidden" name="menu_array" id="menu_array"/>
            <div class="row" style="margin-top: 30px">
                <div class="col-md-1 col-xs-1"></div>
                <div class="col-md-10 col-xs-10">
                    <table id="menu_list" class="table">
                        <tr>
                            <th width="10%">NO</th>
                            <th width="70%">메뉴명</th>
                            <th width="20%">갯수</th>
                        </tr>
                    </table>
                    <div style="width: 100%; margin-left: 30px">
                        <label> 메뉴가격 :  </label><span id="sum"> </span><br/>
                        <label> 부가세 : </label><span id="tax"> </span><br/>
                        <label> 계산된 금액 : </label><span id="total"> </span>
                    </div>
                    <div style="text-align: center;width: 100%">
                        <div style="float:left;width:50%"><button onclick="menu_send_go()" class="form-control">장바구니추가</button></div>
                        <div style="float:left;width:50% "><button onclick="menu_send_go()" class="form-control">주문하기</button></div>
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

        <?php
            foreach($menu_info as $menu){
        ?>
                menuArray[<?=$menu->idx?>] = "<?=$menu->name?>";
        <?php
            }
        ?>
        
        var no = 1;
        
        function menu_check_go(idx, element){
            if($("#menu_array").val().indexOf(idx) != -1){
                $(element).css('backgroundColor' , '#FFFFFF');
                $("#menu_array").val($("#menu_array").val().replace("/"+idx,''));
                $("#tr_"+idx).remove();
                no--;
            }else{
                $(element).css('backgroundColor' , '#CCCCCC');
                $("#menu_array").val($("#menu_array").val()+"/"+idx);
                $("#menu_list").append(
                        "<tr id='tr_"+idx+"'><td>"+no+"</td><td>"+menuArray[idx]+"</td><td><input type='number' id='cnt_"+idx+"' name='cnt_"+idx+"' class='form-control' /></td></tr>"
                );
        
                no++;
            }
            
            
            
        }
        
        function menu_send_go(){
            if($("#menu_idx").val() == ""){
                alert("메뉴를 체크해주세요.");
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
    </script>
</html>
        
        
