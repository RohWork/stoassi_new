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
                                                            echo "<td align='center' onclick='menu_check_go(".$menu->idx.", this)'><b style='cursor:pointer'>".$menu->group_name."<b/><br/>".$menu->name."<br/>[상세보기]</td>";
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
                <input type="hidden" name="menu_idx" id="menu_idx"/>
                <input type="hidden" name="shop_idx" id="shop_idx" value="<?=$shop_idx?>"/>
            </form>
            <div class="row" style="margin-top: 30px">
                <div class="col-md-1 col-xs-1"></div>
                <div class="col-md-10 col-xs-10">
                    <button onclick="menu_send_go()" style="width:100%">메뉴추가</button>
                </div>
                <div class="col-md-1 col-xs-1"></div>
            </div>
            
            
	</div>
    </body>
    <script>
        function menu_check_go(idx, element){
            if($("#menu_idx").val().indexOf(idx) != -1){
                $(element).css('backgroundColor' , '#FFFFFF');
                $("#menu_idx").val($("#menu_idx").val().replace("/"+idx,''));
            }else{
                $(element).css('backgroundColor' , '#CCCCCC');
                $("#menu_idx").val($("#menu_idx").val()+"/"+idx);
            }
           // $("#orderForm")[0].submit();
            
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
                                alert("주문실패");
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
        
        
