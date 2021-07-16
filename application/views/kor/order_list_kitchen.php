
	<div class="container">
		<div class="page-header">
			<h1>주문관리</h1>
			<p class="lead">주문관리 화면 - 부엌</p>
		</div>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>NO</th>
						<th>주문상태</th>
						<th>주문시각</th>
                                                <th>메뉴타입</th>
						<th>주문명</th>
						<th>조리진행</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$no = 1+$offset;
				foreach($order_list as $row){		
				?>
					<tr>
						<td><?=$no?></td>
						<td><?=$row->STATUS?></td>
						<td><?=$row->regi_date?></td>
						<td><?=$row->group_name?></td>
                                                <td><?=$row->recipe_name?></td>
						<td><button type="button" id="modi_button" onclick="set_made_order('<?=$row->idx?>')" class="btn btn-default">조리진행</button></td>
					</tr>
				<?php
				$no ++;
				}
				?>				
				</tbody>
			</table>
		</div>
		<div class="col-sm-12">
			<!--<button type="button" id="input_button" class="btn btn-primary">주문추가</button>-->
		</div>
		<div class="col-sm-offset-5">
			<ul class="pagination">
				<?= $pagination ?>
			</ul>
		</div>
	</div>
	<!-- Modal -->
	<div id="modal_order_kitchen" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalOrderLabel">주문 조리 화면</h4>
		  </div>
		  <div class="modal-body">
			<form id="order_update_form" enctype="multipart/form-data" class="form-horizontal">
				<div class="form-group">
					<label for="stock_name" class="col-sm-3 control-label">테이블번호</label>
					<div class="col-sm-3">
                                            <span id="table_no" name="table_no"></span>
					</div>
                                        <label for="stock_category_idx" class="col-sm-3 control-label">주문시간</label>
					<div class="col-sm-3">
                                            <span id="regi_date" name="regi_date"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="update_stock_seller_idx" class="col-sm-3 control-label">고객메시지</label>
					<div class="col-sm-9">
                                            <span id="user_msg" name="user_msg"></span>
                                            <input type="hidden" id="idx" name="idx"/>
					</div>
				</div>
                                <div class="form-group" id="recipe_area">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm10" id="recipe_area">
                                        <label class="col-sm-3 control-label">조리순서</label>
                                        <label class="col-sm-3 control-label">재료명</label>
                                        <label class="col-sm-2 control-label">투입량</label>
                                        <label class="col-sm-2 control-label">단위</label>
                                        <label class="col-sm-2 control-label">진행시간</label>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="modal_close('stock_update_form')" class="btn btn-default" data-dismiss="modal">취소</button>
			<button type="button" onclick="order_update()" class="btn btn-primary">저장하기</button>
		  </div>
		</div>
	  </div>
	</div>
</body>
<script>

    $(document).ready(function(){
            var modfy_idx;
    });

    $("#input_button").click(function(){
            $("#modal_stock_insert").modal('show');
            get_category_info(0, 'insert');
            get_seller_info(0,'insert');
    });

    function set_made_order(idx){
        var params =  {
                "idx" : idx
        };
        $.ajax({
            url:'/order/get_order_recipe_info',
            type:'post',
            data:params,
            success:function(data){
                set_detail_modal(data.result.order, data.result.recipe);
            },
            error: function(xhr,status,error) {
                console.log(xhr,status,error);
                alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                return false;
            }	
            });

            $("#modal_order_kitchen").modal('show');
    }

    function set_detail_modal(data, recipe){

            
            $("#myModalOrderLabel").html("["+data.group_name+"]"+data.recipe_name);
            $("#table_no").html(data.table_no);
            $("#regi_date").html(data.regi_date.substr(10,6));
            //$("#user_msg").val(data.user_msg);
            $("#status").val(data.status);
            $("#idx").val(data.idx);
            
            recipe.forEach (function (el, index) {
                console.log('element', index, el);
                console.log(el.name);
                console.log(el.order_num);
                console.log(el.stock_name);
                console.log(el.stock_input);
                console.log(el.unit);
                console.log(el.set_time);
            });
            
    }

    function modal_close(id_val){
            $("#"+id_val)[0].reset();
    }




    
    function order_update(){
        
        var form = $("#order_update_form");
        var formData = new FormData(form[0]);
        
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