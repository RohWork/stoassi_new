<style>
    .select_font{
        font-size: 10px;
    }
    
</style>

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
					<label for="insert_recipe_group" class="col-sm-2 control-label">카테고리</label>
					<div class="col-sm-4">
                                            
                                            <select id="insert_recipe_group" name="insert_recipe_group" class="form-control">
                                                <?php
                                                    echo $group_select;
                                                ?>
                                            </select>
					</div>
				</div>
				<div class="form-group">
					<label for="insert_recipe_name" class="col-sm-2 control-label">레시피명</label>
					<div class="col-sm-9">
						<input type="text" id="insert_recipe_name" name="insert_recipe_name" class="form-control"/>
					</div>
				</div>
                                <div class="form-group">
					<label for="insert_recipe_value" class="col-sm-2 control-label">내용</label>
                                </div>
                                
                                <div class="form-group">		
                                        <div class="col-sm-9 col-sm-offset-2" >
                                            <table id="insert_recipe_value" width="100%">
                                                <tr style="height: 30px;line-height: 30px">
                                                    <th class="select_font" width="20%">재료그룹</th>
                                                    <th class="select_font" width="20%">투입재료</th>
                                                    <th class="select_font" width="15%">투입량</th>
                                                    <th class="select_font" width="15%">투입단위</th>
                                                    <th class="select_font" width="15%">소요시간</th>
                                                    <th class="select_font" width="15%" style="text-align: center">제거</th>
                                                </tr>
                                            </table>
					</div>
                                        <div class="col-sm-9 col-sm-offset-2" style="text-align: center;margin-top: 10px">
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
    
    var stock_info_array;
	
    $(document).ready(function(){
	$("#group_select").change(function(){
            location.href = "/RecipeList/recipe_list?group_idx="+$("#group_select").val();
        });
        
        stock_info_array = <?=json_encode($stock_data)?>;
        
        //console.log(stock_info_array);
    });

    $("#input_button").click(function(){
        $("#modal_recipe_insert").modal('show');
    });
    
    function recipe_val_add(){

        var recipe_val = $("#insert_recipe_value");
        var recipe_html = recipe_val.html();
        
        recipe_html += "<tr>"
                        +"<td><select class='form-control select_font' id='stock_category' name='stock_category[]' onchange='stock_info_set(this.value)'>"
                        +  "<?=$scategory_select?>"
                        +"</select></td>"
                        +"<td><select class='form-control select_font' id='stock_info' name='stock_info[]' onchange='stock_unit_set(this.value)'>"
                        +"</select></td>"
                        +"<td><input type='text' class='form-control' id='stock_cnt[]' name='stock_cnt[]'/></td>"
                        +"<td><input type='text' class='form-control' id='stock_unit[]' name='stock_unit[]' readonly></td>"
                        +"<td><input type='text' class='form-control  id='recipe_time[]' name='recipe_time[]'></td>"
                        +"<td style='text-align: center'><button type='button' class='glyphicon glyphicon-minus btn btn-danger'></span></td>"
                        +"</tr>";
        
        recipe_val.html(recipe_html);
     
        stock_info_set(1);
    }
    
    function stock_info_set(idx){
        
        
        
        $("#stock_info").html("");
        
        var stock_category_data = stock_info_array[idx];
        var stock_select = "";
        
        if(Array.isArray(stock_category_data)){
            for(var i=0;i<stock_category_data.length;i++){
                var row = stock_category_data[i];

                stock_select += "<option value="+row['idx']+">"+row['name']+"</optio>";
            }
        
            $("#stock_info").html(stock_select);
        }
    }
    function stock_unit_set(idx){
        
        
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