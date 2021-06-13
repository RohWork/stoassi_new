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
                <div class="col-md-2 col-xs-2"></div>
                <div class="col-md-8 col-xs-8"><center><h3><?=$shop_info['name']?></h3></center></div>
                <div class="col-md-2 col-xs-2"></div>
            </div>
            <div class="row" style="margin-top: 30px">
                <div class="col-md-1 col-xs-1"></div>
                    <div class="col-md-10 col-xs-10">
                        <table style="width:100%" width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr class="menu_height">
                               <?php
                                   $cnt = 0;
                                   foreach($language_list as $lang){
                                       if($cnt % 2 == 0 && $cnt != 0){
                                           echo "</tr><tr class='menu_height'>";
                                       }
                                ?>
                                    <td align='center' class='td_menu_button' onclick="location.href='/customer/orderMenu/2/<?=$shop_info['idx']?>?language=<?=$lang->language?>'"><?=$lang->language_full?></td>
                                <?php
                                       $cnt++;
                                   }
                               ?>  
                            </tr>
                        </table>
                    </div>
                <div class="col-md-1 col-xs-1"></div>
            </div>
        </div>
    </body>
</html>
        
        
