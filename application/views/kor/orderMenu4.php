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
        <link href="../../assets/css/sticky-footer-navbar.css" rel="stylesheet">
        <link href="../../assets/css/customer.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-2 col-xs-2"></div>
                <div class="col-md-8 col-xs-8"><center><h3><?=$shop_info['name']?></h3></center></div>
                <div class="col-md-2 col-xs-2"></div>
            </div>
            <form id="orderForm" name="orderForm" action="/customer/orderMenu/5/<?=$shop_info['idx']?>" method="post">
                <div class="row" style="margin-top: 30px">
                        <div class="col-md-2 col-xs-2"></div>
                             <div class="col-md-8 col-xs-8">
                                 <table style="width:100%" width="100%" border="0" cellpadding="0" cellspacing="0">
                                     <tr>
                                        <?php
                                            $cnt = 0;
                                            foreach($menu_info as $menu){
                                                if($cnt % 2  == 0){
                                                    echo "</tr><tr>";
                                                }
                                                            echo "<td align='center' style='cursor:pointer' onclick='submenu_go(".$menu->idx.")'><b>".$menu->name."<b/></td>";
                                                $cnt++;
                                            }
                                        ?>  
                                     </tr>
                                 </table>
                             </div>
                        <div class="col-md-2 col-xs-2"></div>
                    </div>
                <input type="hidden" name="language" id="language" value="<?=$language?>"/>
                <input type="hidden" name="place" id="place" value="<?=$place?>"/>
                <input type="hidden" name="menu_idx" id="menu_idx" />
            </form>
	</div>
    </body>
    <script>
        function submenu_go(idx){
            $("#menu_idx").val(idx);
            
            $("#orderForm")[0].submit();
            
        }
    </script>
</html>
        
        
