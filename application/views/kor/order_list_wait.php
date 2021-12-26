<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- The above 2 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Refobi</title>
        <!-- 합쳐지고 최소화된 최신 CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

        <!-- 부가적인 테마 -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <!-- 쿠키 사용 --> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script> 

        <!-- 합쳐지고 최소화된 최신 자바스크립트 -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <!-- Custom styles for this template --> 
        <link href="../../assets/css/sticky-footer-navbar.css" rel="stylesheet">
        
        <style>
            .tr_head{
               border-bottom: 1px solid black;
               height: 30px;
               line-break: 3px;
               font-weight: bold;
            }
            .tr_content{
                height:30px;
                line-height: 30px;
            }
            .tr_content td{
                padding-left:10px;
            }
        </style>
    </head>
    <body>
        <table style="width:100%;" cellpadding='0' border='0' cellspacing='0'>
            <tr class='tr_head'>
                <th style='padding-left:10px;'><input type='checkbox'/></th>
                <th>상품명</th>
                <th>상품가격</th>
                <th>갯수</th>
                <th>주문일시</th>
            </tr>
            <?php
                foreach($list_wait as $list){
            ?>
            <tr class='tr_content'>
                <td><input type='checkbox' id="list_idx" name="list_idx[]" value="<?=$list->idx?>" /></td>
                <td><?=$list->recipe_name?></td>
                <td><?=$list->price?></td>
                <td><?=$list->cnt?></td>
                <td><?=$list->regi_date?></td>
            </tr>
            <?php
                }
            ?>
            <tr>
                <td colspan="5">
                    <button onclick="order_update();">결제처리</butt>
                </td>
            </tr>
        </table>
    </body>
    
    
    <script>
        function order_update(){
            
            var chkArray = new Array();
            var status = '2';
            var formData = new FormData();
            
            $("input[name='list_idx[]']:checked").each(function() { 
                var tmpVal = $(this).val(); 
                chkArray.push(tmpVal);
            });
            
            if( chkArray.length < 1 ){
                alert("값을 선택해주시기 바랍니다.");
                return;
            }
            
            formData.append('status', status);
            formData.append('chkArray', chkArray);
            
            $.ajax({
                url:'/order/set_update_order',
                type:'post',
                processData : false,
                contentType : false,
                data:formData,
                success:function(data){
                    alert('수정완료');
                    location.reload();
                },
                error: function(xhr,status,error) {
                    console.log(xhr,status,error);
                    alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                    return false;
                }	 
            });

        }
        </script>
</html>