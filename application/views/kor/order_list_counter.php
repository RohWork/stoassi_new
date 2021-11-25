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
						<th>테이블번호</th>
                                                <th>테이블상태</th>
                                                <th>주문금액</th>
						<th>주문시각</th>
                                                <th>주문수</th>
						<th>결제여부/수정</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$no = 1+$offset;
				foreach($order_list as $row){		
				?>
					<tr>
                                                <td><span style="margin-left: 10px"><?=$row->table_no > 0 ? $row->table_no : "포장" ?></span></td>
                                                <td><?=$row->status == 1 ?  "비어있음" : "사용중" ?></td>
                                                <td><?=$row->price?> <?= !empty($row->price) ?  "zł" : "" ?></td>
						<td><?=$row->regi_date?></td>
                                                <td><?=$row->cnt?></td>
						<td><button type="button" id="modi_button" onclick="detail_order_show('<?=$row->table_no?>','<?=$row->table_code?>')" class="btn btn-default">상세보기</button></td>
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
	<!-- Modal -->
	<div id="modal_order_detail" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalOrderLabel">테이블상세화면</h4>
		  </div>
		  <div class="modal-body">
                        <form id="clear_table_form" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
                                        <div class="col-sm-8">
                                            <input type="hidden" id="detail_table_code" name="table_code" class="form-control" readonly/>
                                            <input type="hidden" id="detail_table_no" name="table_no" class="form-control"/>
                                            <input type="hidden" name="table_mode" id="table_mode" value="1"/>
                                        </div>
                                </div>
                        </form>
                      <div class="row">
                          <div class="col-sm-5 col-sm-offset-1">
                              <div style="width:100%;height: 80px;text-align: center;background-color: #F2EEC3;border: 1px solid black;;cursor: pointer" id='pay_wait'>
                                  <p style="font-size: 16px;padding: 5px 15px 0px 15px;font-weight: bold">결제대기</p>
                                  <p style="font-size: 25px;padding: 0px 15px 0px 15px;font-weight: bold" id="wait_val"></p>
                              </div>
                          </div>
                          <div class="col-sm-5">
                              <div style="width:100%;height: 80px;text-align: center;background-color: #BBC7F3;border: 1px solid black;cursor: pointer" id='pay_complete'>
                                  <p style="font-size: 16px;padding: 5px 15px 0px 15px;font-weight: bold">결제완료</p>
                                  <p style="font-size: 25px;padding: 0px 15px 0px 15px;font-weight: bold" id="compl_val"></p>
                              </div>
                          </div>
                      </div>
                      <div class="row" style="margin-top: 20px">
                          <div class="col-sm-10 col-sm-offset-1">
                              <div style="width:100%;height:50px;text-align: center;background-color: #FCE2E2;border: 1px solid black;cursor: pointer" id='pay_qr'>
                                  <p style="font-size: 16px;padding: 15px;font-weight: bold">QR코드<br/></p>
                              </div>
                          </div>
                      </div>
		  </div>
		  <div class="modal-footer">
                        <button type="button" onclick="table_set(1)" class="btn btn-primary">테이블정리</button>
			<button type="button" onclick="modal_close('clear_table_form')" class="btn btn-default" data-dismiss="modal">닫기</button>
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
                                    <label for="insert_table_code" class="col-sm-3 control-label">테이블코드</label>
                                    <div class="col-sm-8">
                                            <input type="text" id="insert_table_code" name="insert_table_code" class="form-control"/>
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
                                        <select name="insert_recipe" id="insert_recipe" class="form-control" onchange="recipe_pay()"></select>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="결제금액" class="col-sm-3 control-label">상품 금액</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="insert_recipe_price" id="insert_recipe_price" class="form-control" readonly/>
                                    </div>
                                    <label for="상품갯수" class="col-sm-2 control-label">상품 갯수</label>
                                    <div class="col-sm-2">
                                        <select id="insert_recipe_cnt" name="insert_recipe_cnt" class="form-control" onchange="recipe_pay()">
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
                                    <div class="col-sm-4">
                                        <input type="text" name="insert_recipe_total_price" id="insert_recipe_total_price" class="form-control" readonly/>
                                    </div>
                                    <label for="부가세" class="col-sm-2 control-label">부가세</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="insert_recipe_total_tax" id="insert_recipe_total_tax" class="form-control" readonly/>
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
        
        
        
        <div id="modal_table_set" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">손님입장(주문시작)</h4>
		  </div>
		  <div class="modal-body">
			<form id="table_set_form" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group">
                                    <label for="insert_table_code" class="col-sm-3 control-label">테이블번호</label>
                                    <div class="col-sm-8">
                                            <select id="table_no" name="table_no" class="form-control">
                                                <?php
                                                foreach($table_list as $row){
                                                    if($row->table_no == "0"){
                                                        echo "<option value='".$row->table_no."'>포장</option>";
                                                    }else{
                                                        echo "<option value='".$row->table_no."'>".$row->table_no."</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                    </div>
                                    <input type="hidden" name="table_mode" id="table_mode" value="2"/>
                            </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="modal_close('modal_table_set')" class="btn btn-default" data-dismiss="modal">취소</button>
			<button type="button" onclick="table_set(0)" class="btn btn-primary">테이블셋팅</button>
		  </div>
		</div>
	  </div>
	</div>
        
        <div id="modal_table_qr" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">QR코드 확인</h4>
		  </div>
		  <div class="modal-body">
			<form id="table_set_form" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-12" style="text-align: center">
                                        <img id='qr_code_img' style='width:250px; height:250px'/>
                                    </div>

                            </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="modal_close('modal_table_qr')" class="btn btn-default" data-dismiss="modal">닫기</button>
		  </div>
		</div>
	  </div>
	</div>
        <div id="modal_order_wait" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">결제대기</h4>
		  </div>
		  <div class="modal-body">
			<form id="table_set_form" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-12" id='content_order_wait'>
                                        
                                </div>
                            </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="modal_close('modal_order_wait')" class="btn btn-default" data-dismiss="modal">닫기</button>
		  </div>
		</div>
	  </div>
	</div>
        <div id="modal_order_complete" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">결제완료</h4>
		  </div>
		  <div class="modal-body">
			<form id="table_set_form" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-12" id='content_order_complete'>
                                       
                                </div>
                            </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="modal_close('modal_order_complete')" class="btn btn-default" data-dismiss="modal">닫기</button>
		  </div>
		</div>
	  </div>
	</div>
</body>
<script>
    
    var recipe_info = new Array();

    
    $(document).ready(function(){

            recipe_detail($("#insert_recipe_group").val());
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
                url:'/order/pop_table_qr',
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
            
            
            $("#myModalOrderLabel").html(table_no+"번 테이블상세화면");
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
    

        
</script>
</html>