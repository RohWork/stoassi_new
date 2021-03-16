
	<div class="container">
            <div class="page-header">
                    <h1>레시피 관리</h1>
                    <p class="lead">레시피 관리 화면</p>
            </div>
            <div class="row" style="margin-bottom: 3rem">
                <div class="col-sm-1">
                    <label class="control-label">그룹명</label>
                </div>
                <div class="col-sm-2" >
                    <select id="group_select" name="group_select" class="form-control">
                        <option value="0">선택없음</option>
                        <?php                            
                            echo $group_select;
                        ?>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                    <table class="table">
                            <thead>
                                    <tr>
                                            <th>NO</th>
                                            <th>그룹명(그룹 사용여부)</th>
                                            <th>레시피명</th>
                                            <th>소요시간</th>
                                            <th>사용여부</th>
                                            <th>수정/삭제</th>
                                    </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no =1+$offset;
                            if(!empty($rows)){
                                    foreach($rows as $row){
                                    ?>
                                            <tr>
                                                    <td><?=$no?></td>
                                                    <td><?=$row->group_name?>(<?=$row->group_state == "Y" ? "사용" : "사용안함" ?>)</td>
                                                    <td><?=$row->name?></td>
                                                    <td><?=date('Y-m-d', strtotime($row->modi_date))?></td>
                                                    <td><?=$row->state == "Y" ? "사용" : "사용안함" ?></td>
                                                    <td><button type="button" id="modi_button" onclick="detail_group_show('<?=$row->idx?>')" class="btn btn-default">확인/수정</button></td>
                                            </tr>
                                    <?php
                                            $no++;
                                    }
                            }else{
                                    echo "<tr><td colspan='6' align='center'>데이터없음</td></tr>";	
                            }
                                    ?>				
                            </tbody>
                    </table>
            </div>
            <div class="col-sm-12">
                    <button type="button" id="input_button" class="btn btn-primary">레시피 추가</button>
            </div>
            <div class="col-sm-offset-5">
                    <ul class="pagination">
                            <?= $pagination ?>
                    </ul>
            </div>
	</div>
	<!-- Modal -->
	<div id="modal_group_detail" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">그룹상세화면</h4>
		  </div>
		  <div class="modal-body">
			<form id="group_update_form" enctype="multipart/form-data" class="form-horizontal">
				<div class="form-group">
					<label for="stock_name" class="col-sm-3 control-label">그룹명</label>
					<div class="col-sm-8">
						<input type="hidden" id="update_group_idx" name="update_group_idx"/>
						<input type="text" id="update_group_name" name="update_group_name" class="form-control"/>
					</div>
				</div>
				<div class="form-group">
					<label for="update_group_useyn" class="col-sm-3 control-label">사용여부</label>
					<div class="col-sm-9">
						<label class="radio-inline">
						<input type="radio" name="update_group_useyn" id="group_use_y" value='Y'>사용
						</label>
						<label class="radio-inline">
						<input type="radio" name="update_group_useyn" id="group_use_n" value='N'>사용안함
						</label>
					</div>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="modal_close('group_update_form')" class="btn btn-default" data-dismiss="modal">취소</button>
			<button type="button" onclick="group_update()" class="btn btn-primary">저장하기</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<div id="modal_recipe_insert" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">레시피추가</h4>
		  </div>
		  <div class="modal-body">
			<form id="group_insert_form" name="group_insert_form" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
					<label for="insert_recipe_group" class="col-sm-3 control-label">카테고리명</label>
					<div class="col-sm-4">
                                            
                                            <select id="insert_recipe_group" name="insert_recipe_group" class="form-control">
                                                <?php
                                                    echo $group_select;
                                                ?>
                                            </select>
					</div>
				</div>
				<div class="form-group">
					<label for="insert_recipe_name" class="col-sm-3 control-label">레시피명</label>
					<div class="col-sm-8">
						<input type="text" id="insert_recipe_name" name="insert_recipe_name" class="form-control"/>
					</div>
				</div>
                                <div class="form-group">
					<label for="insert_recipe_value" class="col-sm-3 control-label">레시피내용</label>
                                </div>
                                
                                <div class="form-group">		
                                        <div class="col-sm-8 col-sm-offset-3" id="insert_recipe_value">
                                           
					</div>
                                        <div class="col-sm-8 col-sm-offset-3" style="text-align: center">
                                            <span class="form-control" style="cursor: pointer" onclick="recipe_val_add()">추가</span>
                                        </div>
                                    <input type="hidden" id="insert_recipe_cnt" name="insert_recipe_cnt" value="0"/>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="reset_insert()" class="btn btn-default" data-dismiss="modal">취소</button>
			<button type="button" onclick="recipe_insert()" class="btn btn-primary">저장하기</button>
		  </div>
		</div>
	  </div>
	</div>
</body>
<script>


	
    $(document).ready(function(){
	$("#group_select").change(function(){
            location.href = "/RecipeList/recipe_list?group_idx="+$("#group_select").val();
        });
        
    });

    $("#input_button").click(function(){
        $("#modal_recipe_insert").modal('show');
    });
    
    function recipe_val_add(){

        var recipe_val = $("#insert_recipe_value");
        var recipe_html = recipe_val.html();
        
        recipe_html += "<div class='row'>"

                        +"<label class='control-label col-md-2'>투입재료:</label>"
                        +"<div class='col-md-3'><select class='form-control  col-md-1' id='stock_idx[]' name='stock_idx[]'>"
                        +  "<option value='1'>진간장</option>"
                        +"</select></div>"
                        +"<div class='col-md-2'><input class='form-control  col-md-1' type='text' id='stock_cnt[]' name='stock_cnt[]'/></div>"
                        +"<div class='col-md-1'><span class='form-control  col-md-1' id='stock_unit[]' name='stock_unit[]'></span</div>"
                        +"<label class='control-label col-md-2'>소요시간:</label>"
                        +"<div class='col-md-1'><span class='form-control  col-md-1' id='recipe_time[]' name='recipe_time[]'></span></div>"
                        +"</div>";
        
        recipe_val.html(recipe_html);
        
        
        
        
        
    }

    function detail_group_show(idx){
        var params =  {
                "idx" : idx
        };
        $.ajax({
            url:'/RecipeGroup/get_group_info',
            type:'post',
            data:params,
            success:function(data){
                set_detail_modal(data.result);
            }
    })

            $("#modal_group_detail").modal('show');
    }

    function set_detail_modal(data){
        
            $("#update_group_idx").val(data.idx);
            $("#update_group_name").val(data.name);
            
            
            if(data.state == "Y"){
                $("#group_use_y").prop("checked", true);
            }else{
                $("#group_use_n").prop("checked", true);
            }
            

    }

    function modal_close(id_val){
            $("#"+id_val)[0].reset();
    }


  function group_insert(){

        var group_name = $("#insert_group_name");

		
        if(group_name.val() == ""){
            alert("그룹명을 입력하시기 바랍니다.");
            group_name.focus();
            return;
        }
		
        var form = $("#group_insert_form");

        $.ajax({
            url:'/RecipeGroup/set_group',
            type:'post',
            data:form.serialize(),
            dataType: 'json',
            success:function(data){

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
    
    
    function group_update(){
        
        var group_name = $("#update_group_name");

        if(group_name.val() == ""){
                alert("그룹명을 입력하시기 바랍니다.");
                group_name.focus();
                return;
        }
	        
        var form = $("#group_update_form");
        var formData = form.serialize();
        
        $.ajax({
            url:'/RecipeGroup/set_update_group',
            type:'post',
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