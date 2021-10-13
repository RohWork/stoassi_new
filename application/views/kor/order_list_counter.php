
	<div class="container">
		<div class="page-header">
			<h1>주문관리</h1>
			<p class="lead">주문관리 화면 - 카운터</p>
		</div>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>NO</th>
						<th>테이블번호</th>
                                                <th>주문번호</th>
                                                <th>주문금액</th>
						<th>주문상태</th>
						<th>주문시각</th>
                                                <th>주문수</th>
						<th>포장여부</th>
						<th>결제여부/수정</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$no = 1+$offset;
				foreach($order_list as $row){		
				?>
					<tr>
						<td><?=$no?></td>
						<td><?=$row->table_no?></td>
                                                <td><?=$row->order_no?></td>
                                                <td><?=$row->price?> <?= !empty($row->price) ?  "zł" : "" ?></td>
						<td><?=$row->STATUS?></td>
						<td><?=$row->regi_date?></td>
                                                <td><?=$row->cnt?></td>
						<td><?=$row->place?></td>
						<td><button type="button" id="modi_button" onclick="detail_order_show('<?=$row->idx?>')" class="btn btn-default">확인/수정</button></td>
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
                                            <select id="status" name="status" class="form-control">
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
	
	<div id="modal_order_insert" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">주문추가</h4>
		  </div>
		  <div class="modal-body">
			<form id="order_insert_form" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group">
                                    <label for="insert_table_no" class="col-sm-3 control-label">테이블번호</label>
                                    <div class="col-sm-8">
                                            <input type="text" id="insert_table_no" name="insert_table_no" class="form-control"/>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="insert_recipe_group" class="col-sm-3 control-label">메뉴 그룹</label>
                                    <div class="col-sm-8">
                                        <select name="insert_recipe_group" id="insert_recipe_group" onchange="recipe_detail(this.value)" class="form-control">
                                            <?php
                                                foreach($recipe_group_list as $row){
                                                    echo "<option value='".$row->idx."'>".$row->name."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="insert_recipe" class="col-sm-3 control-label">메뉴 선택</label>
                                    <div class="col-sm-8">
                                        <select name="insert_recipe" id="insert_recipe" class="form-control" onchange="recipe_pay(this.value)"></select>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="결제금액" class="col-sm-3 control-label">상품 금액</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="insert_recipe_price" id="insert_recipe_price" class="form-control" readonly/>
                                    </div>
                                    <label for="상품갯수" class="col-sm-3 control-label">상품 갯수</label>
                                    <div class="col-sm-2">
                                        <select id="insert_recipe_cnt" name="insert_recipe_cnt" class="form-control">
                                            <?php
                                                for($i=1;$i<100;$i++){
                                            ?>
                                                    <option value="<?=$i?>"><?=$i?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="총결제금액" class="col-sm-3 control-label">총 결제금액</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="insert_recipe_total_price" id="insert_recipe_total_price" class="form-control" readonly/>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="포장여부" class="col-sm-3 control-label">포장 여부</label>
                                    <div class="col-sm-4">
                                        <input type="radio" name="insert_place" id="insert_place" value="1" checked/>
                                        <span>취식</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="radio" name="insert_place" id="insert_place" value="2"/>
                                        <span>포장</span>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="stock_name" class="col-sm-3 control-label">주문상태</label>
                                    <div class="col-sm-8">
                                        <select id="insert_status" name="insert_status" class="form-control">
                                            <option value="1">결제대기</option>
                                            <option value="2">결제완료</option>
                                        </select>
                                    </div>
                            </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="modal_close('order_insert_form')" class="btn btn-default" data-dismiss="modal">취소</button>
			<button type="button" onclick="order_insert()" class="btn btn-primary">주문하기</button>
		  </div>
		</div>
	  </div>
	</div>
</body>
<script>
    
    var recipe_info = new Array();

    
    $(document).ready(function(){
            var modfy_idx;
            
            recipe_detail($("#insert_recipe_group").val());
    });

    $("#input_button").click(function(){
            $("#modal_order_insert").modal('show');
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
                
            },
            error: function(xhr,status,error) {
                console.log(xhr,status,error);
                alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                return false;
            }	 
        });
        recipe_pay($("#insert_recipe option:selected").val());
        
        
    }

    function recipe_pay(idx){
        
        console.log(idx);
        console.log(recipe_info[idx]);
        $("#insert_recipe_price").val(recipe_info[idx]['price']);
        
    }

    function order_insert(){



        if($("#insert_table_no").val() == ""){
                alert("테이블번호를 입력하시기 바랍니다.");
                $("#insert_table_no").focus();
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