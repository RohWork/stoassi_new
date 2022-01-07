<?php
echo get_qr("www.naver.com",'naver');
?>

	<div class="container">
		<div class="page-header">
			<h1>주문관리</h1>
			<p class="lead">주문관리 화면 - 카운터</p>
		</div>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
                                                <th>no</th>
						<th>테이블번호</th>
                                                <th>주문명</th>
                                                <th>주문금액</th>
						<th>주문수</th>
                                                <th>주문결과</th>
                                                <th>주문일시</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$no = 1+$offset;
				foreach($order_list as $row){		
				?>
					<tr>
                                                <td><?=$no?></td>
                                                <td><span style="margin-left: 10px"><?=$row->table_no > 0 ? $row->table_no : "포장" ?></span></td>
                                                <td><?=$row->recipe_name ?></td>
                                                <td><?=$row->price?> <?= !empty($row->price) ?  "zł" : "" ?></td>
                                                <td><?=$row->cnt ?></td>
                                                <td><?=$row->status ?></td>
						<td><?=$row->regi_date?></td>
					</tr>
				<?php
				$no ++;
				}
				?>				
				</tbody>
			</table>
		</div>
		<div class="col-sm-12">
			<button type="button" id="input_button" class="btn btn-primary">주문추가</button>
                        <button type="button" id="table_input" class="btn btn-primary">테이블셋팅</button>
		</div>
		<div class="col-sm-offset-5">
			<ul class="pagination">
				<?= $pagination ?>
			</ul>
		</div>
	</div>

</body>
<script>
    
    var recipe_info = new Array();

    
    $(document).ready(function(){

            recipe_detail($("#insert_recipe_group").val());
    });
    
    $("#set_pay").click(function(){
       $("#order_wait_frame").get(0).contentWindow.order_update();  
    });
    $("#set_cancel").click(function(){
       $("#order_complete_frame").get(0).contentWindow.order_update();  
    });
    
    
    $("#input_button").click(function(){
        $("#modal_order_insert").modal('show');
    });
    
    $("#table_input").click(function(){
        $("#modal_table_set").modal('show'); 
    });
    
    $("#pay_qr").click(function(){
            var table_code = $("#detail_table_code").val();
        
            var params =  {
                "code" : table_code
            };
        
            $.ajax({
                url:'/order/get_table_qr',
                type:'post',
                data:params,
                success:function(data){
                    
                    var result = data.result;
                    
                    $("#qr_code_img").attr("src",result.file);
                },
                error: function(xhr,status,error) {
                    console.log(xhr,status,error);
                    alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                    return false;
                }	 
            });
        
        
        $("#modal_table_qr").modal('show');
    });
    
    $("#pay_wait").click(function(){
        
        $("#order_wait_frame").attr('src','/order/pop_wait_order?code='+$("#detail_table_code").val());
        
        $("#modal_order_wait").modal('show');
    });
    $("#pay_complete").click(function(){
        
        $("#order_complete_frame").attr('src','/order/pop_complete_order?code='+$("#detail_table_code").val());
        
        $("#modal_order_complete").modal('show');
    });
    
    function detail_order_show(table_no,table_code){
            
            if(table_code == ''){
                alert('테이블을 세팅후 확인해주세요.');
                return;
            }
            
            var params =  {
                "table_code" : table_code
            };
            
            $.ajax({
                url:'/order/get_order_info',
                type:'post',
                data:params,
                success:function(data){
                    
                    var count = data.result.count;
                    
                    $("#compl_val").html(count.cnt_complete);
                    $("#wait_val").html(count.cnt_wait);
                    
                },
                error: function(xhr,status,error) {
                    console.log(xhr,status,error);
                    alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                    return false;
                }	 
            });
            
            
            $("#myModalOrderLabel").html(table_no+"번 테이블 상세화면");
            $("#myModalQrLabel").html(table_no+"번 테이블 QR코드");
            $("#myModalWaitLabel").html(table_no+"번 테이블 결제대기");
            $("#myModalCompleteLabel").html(table_no+"번 테이블 결제완료");
            
            $("#detail_table_code").val(table_code);
            $("#detail_table_no").val(table_no);
            $("#modal_order_detail").modal('show');
    }


    function modal_close(id_val){
            $("#"+id_val)[0].reset();
    }

    
    function recipe_detail(idx){
        
        var params =  {
                "group_idx" : idx
        };
        
        $("#insert_recipe option").remove();
        
        $.ajax({
            url:'/order/get_recipe_info',
            type:'post',
            data:params,
            success:function(data){
                recipe_info = new Array();
                
                var str = "";
                data.result.forEach(function (item){
                    str += "<option value='"+item.idx+"'>"+item.name+"</option>";
                    recipe_info[item.idx] = new Array();
                    recipe_info[item.idx]['price'] = item.price;
                    recipe_info[item.idx]['tax'] = item.tax;
                });
                $("#insert_recipe").html(str);

                recipe_pay();
                
            },
            error: function(xhr,status,error) {
                console.log(xhr,status,error);
                alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                return false;
            }	 
        });
        

        
        
    }

    function recipe_pay(){
        var idx = $("#insert_recipe option:selected").val();
        var price = Number(recipe_info[idx]['price']);
        var tax = Number(recipe_info[idx]['tax']);
        var cnt = Number($("#insert_recipe_cnt option:selected").val());
        
        var total_tax = price * (tax / 100) * cnt;
        var total_price = (price * cnt)+total_tax;
        
        $("#insert_recipe_price").val(price);
        $("#insert_recipe_total_tax").val(total_tax);
        $("#insert_recipe_total_price").val(total_price);
        
    }

    function order_insert(){



        if($("#insert_table_code").val() == ""){
                alert("테이블번호를 입력하시기 바랍니다.");
                $("#insert_table_code").focus();
                return;
        }

        if($("#insert_recipe").val() == null){
                alert("메뉴명을 선택하시기 바랍니다.");
                $("#insert_recipe").focus();
                return;
        }

        var form = $("#order_insert_form");
        var formData = form.serialize();

        $.ajax({
            url:'/order/set_order',
            type:'post',
            data:formData,
            success:function(data){
                    alert(data.message);

                    if(data.code == 200){
                            location.reload();
                    }
            },
            error: function(xhr,status,error) {
                console.log(xhr,status,error);
                alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                return false;
            }	 
        });

    }

    
    function table_set(mode){
        
        var form;
        var formData;
        var url;
        
        if(mode == 0){
            form = $("#table_set_form");    //테이블세팅
            formData = new FormData(form[0]);
            url = '/order/set_table';
        }else{
            form = $("#clear_table_form"); //테이블정리
            formData = new FormData(form[0]);
             url = '/order/clear_table';
        }
        
        
        $.ajax({
            url:url,
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
    function qr_print(){
        
        var popupWindow = window.open("", "_blank" );
        popupWindow.document.write( "<center>" );
        popupWindow.document.write( $("#qr_print_area").html() );
        popupWindow.document.write( "</center>" );
        popupWindow.print();
        popupWindow.close();
    }

        
</script>
</html>