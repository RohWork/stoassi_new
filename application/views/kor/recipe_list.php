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
			<h4 class="modal-title" id="myModalLabel">레시피상세화면</h4>
		  </div>
		  <div class="modal-body">
			<form id="recipe_update_form" enctype="multipart/form-data" class="form-horizontal">
				<div class="form-group">
					<label for="update_recipe_group" class="col-sm-2 control-label">카테고리</label>
					<div class="col-sm-4">
                                            
                                            <select id="update_recipe_group" name="update_recipe_group" class="form-control">
                                                <?php
                                                    echo $group_select;
                                                ?>
                                            </select>
					</div>
				</div>
                                <div class="form-group">
					<label for="update_recipe_name" class="col-sm-2 control-label">레시피명</label>
					<div class="col-sm-9">
						<input type="text" id="update_recipe_name" name="update_recipe_name" class="form-control"/>
					</div>
				</div>
                                <div class="form-group">		
                                        <div class="col-sm-9 col-sm-offset-2" >
                                            <table id="update_recipe_value" width="100%">
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
                                            <span class="form-control" style="cursor: pointer" onclick="recipe_val_add('update')">추가</span>
                                        </div>
				</div>
                                
				<div class="form-group">
					<label for="update_recipe_useyn" class="col-sm-3 control-label">사용여부</label>
					<div class="col-sm-9">
						<label class="radio-inline">
						<input type="radio" name="update_recipe_useyn" id="recipe_use_y" value='Y'>사용
						</label>
						<label class="radio-inline">
						<input type="radio" name="update_recipe_useyn" id="recipe_use_n" value='N'>사용안함
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
			<form id="recipe_insert_form" name="recipe_insert_form" enctype="multipart/form-data" class="form-horizontal">
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
                                            <span class="form-control" style="cursor: pointer" onclick="recipe_val_add('insert')">추가</span>
                                        </div>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="reset_insert('insert')" class="btn btn-default" data-dismiss="modal">취소</button>
			<button type="button" onclick="recipe_insert()" class="btn btn-primary">저장하기</button>
		  </div>
		</div>
	  </div>
	</div>
        
</body>
<script>
    
    var stock_info_array;
    var stock_insert_cnt = 0;
    var stock_update_cnt = 0;
	
    $(document).ready(function(){
	$("#group_select").change(function(){
            location.href = "/RecipeList/recipe_list?group_idx="+$("#group_select").val();
        });
        
        stock_info_array = <?=json_encode($stock_data)?>;
        
        //console.log(stock_info_array);
    });

    $("#input_button").click(function(){
        stock_insert_cnt = 0;
        $("#modal_recipe_insert").modal('show');
    });
    
    function reset_insert(mode){
        
        $("#insert_recipe_name").val("");
        var scnt;
        
        if(mode == "insert"){
            scnt = stock_insert_cnt;
        }else{
            scnt = stock_update_cnt;
        }
        
        for(var i=1;i<=scnt;i++){
            $("#recipe"+mode+i).remove();
        }
    }
    
    
    function recipe_val_add(mode){
        
        var recipe_val;
        var stock_info_cnt;
        
        if(mode == "insert"){
            recipe_val = $("#insert_recipe_value");
            stock_insert_cnt++;
            
            stock_info_cnt = stock_insert_cnt;
        }else{
            recipe_val = $("#update_recipe_value");
            stock_update_cnt++;
            
            stock_info_cnt = stock_update_cnt;
        }

        

        var recipe_html  = "<tr id='recipe"+mode+stock_info_cnt+"'>"
                        +"<td><select class='form-control select_font' id='stock_category"+mode+stock_info_cnt+"' name='stock_category[]' onchange='stock_info_set(this.value,"+stock_info_cnt+","+mode+")'>"
                        +  "<?=$scategory_select?>"
                        +"</select></td>"
                        +"<td><select class='form-control select_font' id='stock_info"+mode+stock_info_cnt+"' name='stock_info[]' onchange='stock_unit_set(this.value,"+stock_info_cnt+","+mode+")'>"
                        +"</select></td>"
                        +"<td><input type='text' class='form-control' id='stock_cnt"+mode+stock_info_cnt+"' name='stock_cnt[]'/></td>"
                        +"<td><input type='text' class='form-control' id='stock_unit"+mode+stock_info_cnt+"' name='stock_unit[]' readonly></td>"
                        +"<td><input type='text' class='form-control  id='recipe_time"+mode+stock_info_cnt+"' name='recipe_time[]' value=0></td>"
                        +"<td style='text-align: center'><button type='button' class='glyphicon glyphicon-minus btn btn-danger' onclick='delete_recipe("+stock_info_cnt+","+mode+")'></span></td>"
                        +"</tr>";
        
        recipe_val.append(recipe_html);
     
        stock_info_set(1,stock_info_cnt,mode);
        stock_unit_set($("#stock_info"+stock_info_cnt).val(),stock_info_cnt,mode);
    }
    
    function stock_info_set(idx,cnt,mode){   //카테고리 선택시 투입재료 셀렉트박스 생성

        $("#stock_info"+mode+cnt).html("");
        
        var stock_category_data = stock_info_array[idx];
        var stock_select = "";
        
        if(Array.isArray(stock_category_data)){
            for(var i=0;i<stock_category_data.length;i++){
                var row = stock_category_data[i];
                    
                stock_select += "<option value="+row['idx']+">"+row['name']+"</option>";
            }
        
            $("#stock_info"+mode+cnt).html(stock_select);
        }else{
            $("#stock_info"+mode+cnt).html("");
        }
        stock_unit_set($("#stock_info"+mode+cnt).val(),cnt,mode);
    }
    
    function stock_unit_set(idx,cnt,mode){   //투입재료 선택시 투입단위 추가
    
        var sc_idx = $("#stock_category"+mode+cnt).val();
        
        var stock_category_data = stock_info_array[sc_idx];
        
        if(Array.isArray(stock_category_data)){
            for(var i=0;i<stock_category_data.length;i++){
                var row = stock_category_data[i];
                if(row['idx'] == idx){    
                    $("#stock_unit"+mode+cnt).val(row['unit']);
                }
            }
        }
        
    }
    function delete_recipe(idx,mode){
        $("#recipe"+mode+idx).remove();
    }

    function recipe_insert(){

        var recipe_name = $("#insert_recipe_name");
        
		
        if(recipe_name.val() == ""){
            alert("레시피명을 입력하시기 바랍니다.");
            recipe_name.focus();
            return;
        }
		
        var form = $("#recipe_insert_form");

        $.ajax({
            url:'/RecipeList/set_recipe',
            type:'post',
            data:form.serialize()+"&stock_info_cnt="+stock_info_cnt,
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