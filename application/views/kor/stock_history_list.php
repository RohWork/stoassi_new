<div class="container">
        <div class="page-header">
                <h1>재고관리</h1>
                <p class="lead">재고관리 화면</p>
        </div>
        <div class="table-responsive">
            <table class="table">
                <tr class='tr_head'>
                    <th>재고명</th>
                    <th>재고개수</th>
                    <th>입출고여부</th>
                    <th>메모</th>
                    <th>등록일</th>
                </tr>
                <?php
                    foreach($stock_history as $list){
                ?>
                <tr class='tr_content'>
                    <td><?=$list->stock_name?></td>
                    <td><?=$list->count?></td>
                    <td><?=$list->inout == 1 ? "입고" : "출고" ?></td>
                    <td><?=$list->regi_date?></td>
                </tr>
                <?php
                    }
                ?>
            </table>
            <div class="col-sm-offset-5">
                <ul class="pagination">
                        <?= $pagination ?>
                </ul>
            </div>
        </div>
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
            formData.append('idx', chkArray);
            
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
        
        function all_check(){
            if($("#all_check").is(':checked')){
                $(".chk").prop("checked",true);
            }else{
                $(".chk").prop("checked",false);
            }
            
        }
        </script>
</html>