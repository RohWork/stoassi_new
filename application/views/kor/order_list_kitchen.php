
	<div class="container">
		<div class="page-header">
			<h1>재고관리</h1>
			<p class="lead">재고관리 화면</p>
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
						<td><button type="button" id="modi_button" onclick="detail_order_show('<?=$row->idx?>')" class="btn btn-default">조리진행</button></td>
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
	<div id="modal_order_detail" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalOrderLabel">주문상세화면</h4>
		  </div>
		  <div class="modal-body">
			<form id="order_update_form" enctype="multipart/form-data" class="form-horizontal">
				<div class="form-group">
					<label for="stock_name" class="col-sm-3 control-label">테이블번호</label>
					<div class="col-sm-8">
                                            <span id="table_no" name="table_no"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="stock_category_idx" class="col-sm-3 control-label">주문시간</label>
					<div class="col-sm-8">
                                            <span id="regi_date" name="regi_date"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="stock_name" class="col-sm-3 control-label">주문상태</label>
					<div class="col-sm-8">
                                            <select id="status" name="status">
                                                <option value="1">결제대기</option>
                                                <option value="2">결제완료</option>
                                                <option value="4">결제취소</option>
                                            </select>
					</div>
				</div>
				<div class="form-group">
					<label for="update_stock_seller_idx" class="col-sm-3 control-label">고객메시지</label>
					<div class="col-sm-8">
                                            <span id="user_msg" name="user_msg"></span>
                                            <input type="hidden" id="idx" name="idx"/>
					</div>
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
	
	<div id="modal_stock_insert" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">재고상품추가</h4>
		  </div>
		  <div class="modal-body">
			<form id="stock_insert_form" enctype="multipart/form-data" class="form-horizontal">
				<div class="form-group">
					<label for="stock_name" class="col-sm-3 control-label">재료명</label>
					<div class="col-sm-8">
						<input type="text" id="insert_stock_name" name="insert_stock_name" class="form-control"/>
					</div>
				</div>
				<div class="form-group">
					<label for="insert_stock_category_idx" class="col-sm-3 control-label">자료타입</label>
					<div class="col-sm-8">
						<select name="insert_stock_category_idx" id="insert_stock_category_idx" class="form-control"></select>
					</div>
				</div>
				<div class="form-group">
					<label for="insert_stock_image" class="col-sm-3 control-label">재료사진</label>
					<div class="col-sm-8">
						<input type="file" id="insert_stock_image" name="insert_stock_image" class="form-control"/>
					</div>
				</div>
				<div class="form-group">
					<label for="insert_stock_seller_idx" class="col-sm-3 control-label">구입처</label>
					<div class="col-sm-8">
						<select name="insert_stock_seller_idx" id="insert_stock_seller_idx" class="form-control"></select>
					</div>
				</div>
				<div class="form-group">
					<label for="insert_stock_count" class="col-sm-3 control-label">재료갯수</label>
					<div class="col-sm-4">
						<input type="number" id="insert_stock_count" name="insert_stock_count" class="form-control" value="0"/>
					</div>
					<label for="insert_stock_unit" class="col-sm-2 control-label">갯수단위</label>
					<div class="col-sm-2">
						<input type="text" id="insert_stock_unit" name="insert_stock_unit" class="form-control"/>
					</div>
				</div>
				<div class="form-group">
					<label for="insert_stock_comment" class="col-sm-3 control-label">재료설명</label>
					<div class="col-sm-8">
						<textarea id="insert_stock_comment" name="insert_stock_comment" class="form-control"></textarea>
					</div>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="modal_close('stock_insert_form')" class="btn btn-default" data-dismiss="modal">취소</button>
			<button type="button" onclick="stock_insert()" class="btn btn-primary">저장하기</button>
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

    function detail_order_show(idx){
        var params =  {
                "idx" : idx
        };
        $.ajax({
            url:'/order/get_order_info',
            type:'post',
            data:params,
            success:function(data){
                set_detail_modal(data.result);
            },
            error: function(xhr,status,error) {
                console.log(xhr,status,error);
                alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                return false;
            }	
    })

            $("#modal_order_detail").modal('show');
    }

    function set_detail_modal(data){

            
            $("#myModalOrderLabel").html("["+data.group_name+"]"+data.recipe_name);
            $("#table_no").html(data.table_no);
            $("#regi_date").html(data.regi_date.substr(10,6));
            //$("#user_msg").val(data.user_msg);
            $("#status").val(data.status);
            $("#idx").val(data.idx);
     
    }

    function modal_close(id_val){
            $("#"+id_val)[0].reset();
    }




    function stock_insert(){

        var stock_name = $("#insert_stock_name");
        var stock_unit = $("#insert_stock_unit");

        if(stock_name.val() == ""){
                alert("재료명을 입력하시기 바랍니다.");
                stock_name.focus();
                return;
        }
        if(stock_unit.val() == ""){
                alert("갯수 단위를 입력하시기 바랍니다.");
                stock_unit.focus();
                return;
        }
		if($("#update_stock_image").val() != ""){
			file_upload_test($("#insert_stock_image"));
		}
        var form = $("#stock_insert_form");
        var formData = new FormData(form[0]);
        formData.append("file", $("#insert_stock_image")[0].files[0]);

        $.ajax({
            url:'/stock/set_stock',
            type:'post',
            processData : false,
            contentType : false,
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