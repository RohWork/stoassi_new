<style>
    .select_font{
        font-size: 10px;
    }

    table {
        clear: both;
    }
    th {
        height: 50px;
        width: 70px;
        background-color:  orange;
    }
    td {
        text-align: center;
        height: 50px;
        width: 70px;
        background-color: #FFFDC5;  
    }
    input {
        height: 50px;
        width: 70px;
        border: none;
        background-color:  orange;
        font-size: 30px;
    }
    .year_mon{
        font-size: 25px;
    }
    .colToday{
        background-color: #FFA07A;
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
                                                    <td><button type="button" id="modi_button" onclick="detail_recipe_show('<?=$row->idx?>')" class="btn btn-default">확인/수정</button></td>
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
	<div id="modal_recipe_detail" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
					<label for="update_recipe_price" class="col-sm-2 control-label">금액</label>
					<div class="col-sm-9">
						<input type="number" id="update_recipe_price" name="update_recipe_price" class="form-control"/>
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
					<label for="update_recipe_useyn" class="col-sm-2 control-label">사용여부</label>
					<div class="col-sm-9">
						<label class="radio-inline">
						<input type="radio" name="update_recipe_useyn" id="recipe_use_y" value='Y'>사용
						</label>
						<label class="radio-inline">
						<input type="radio" name="update_recipe_useyn" id="recipe_use_n" value='N'>사용안함
						</label>
					</div>
				</div>
                                <input type="hidden" id="update_recipe_idx" name="update_recipe_idx" />
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="reset_insert()" class="btn btn-default" data-dismiss="modal">취소</button>
			<button type="button" onclick="recipe_update()" class="btn btn-primary">저장하기</button>
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
					<label for="insert_recipe_price" class="col-sm-2 control-label">금액</label>
					<div class="col-sm-9">
						<input type="number" id="insert_recipe_price" name="insert_recipe_price" class="form-control"/>
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
    var mode = "insert";
    

    $(document).ready(function(){
        
	$("#group_select").change(function(){
            location.href = "/RecipeList/recipe_list?group_idx="+$("#group_select").val();
        });
        
        stock_info_array = <?=json_encode($stock_data)?>;

    });

    $("#input_button").click(function(){
        stock_insert_cnt = 0;
        mode = "insert";
        $("#modal_recipe_insert").modal('show');
    });
    
    function detail_recipe_show(idx){
        
        var params =  {
                "idx" : idx
        };
        $.ajax({
            url:'/RecipeList/get_recipe_info',
            type:'post',
            data:params,
            success:function(data){
                set_detail_modal(data.result, data.process);
            }
        });

        stock_update_cnt = 0;
        mode = "update";
        $("#modal_recipe_detail").modal('show');
    }

    
    function reset_insert(){
        
        
        var scnt;
        
        if(mode == "insert"){
            $("#insert_recipe_name").val("");
            scnt = stock_insert_cnt;
        }else{
            $("#update_recipe_name").val("");
            scnt = stock_update_cnt;
        }
        
        
        for(var i=1;i<=scnt;i++){
            $("#recipe_"+mode+""+i).remove();
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

        

        var recipe_html  = "<tr id='recipe_"+mode+""+stock_info_cnt+"'>"
                        +"<td><select class='form-control select_font' id='stock_category_"+mode+""+stock_info_cnt+"' name='"+mode+"_stock_category[]' onchange='stock_info_set(this.value,"+stock_info_cnt+")'>"
                        +  "<?=$scategory_select?>"
                        +"</select></td>"
                        +"<td><select class='form-control select_font' id='stock_info_"+mode+""+stock_info_cnt+"' name='"+mode+"_stock_info[]' onchange='stock_unit_set(this.value,"+stock_info_cnt+")'>"
                        +"</select></td>"
                        +"<td><input type='text' class='form-control' id='stock_cnt_"+mode+""+stock_info_cnt+"' name='"+mode+"_stock_cnt[]'/></td>"
                        +"<td><input type='text' class='form-control' id='stock_unit_"+mode+""+stock_info_cnt+"' name='"+mode+"_stock_unit[]' readonly value=></td>"
                        +"<td><input type='text' class='form-control  id='recipe_time_"+mode+""+stock_info_cnt+"' name='"+mode+"_recipe_time[]' value=0></td>"
                        +"<td style='text-align: center'><button type='button' class='glyphicon glyphicon-minus btn btn-danger' onclick='delete_recipe("+stock_info_cnt+")'></span></td>"
                        +"</tr>";
        
        recipe_val.append(recipe_html);
     
        stock_info_set(1,stock_info_cnt);
        stock_unit_set($("#stock_info_"+mode+""+stock_info_cnt).val(),stock_info_cnt);
    }
    
    function stock_info_set(idx,cnt){   //카테고리 선택시 투입재료 셀렉트박스 생성

        $("#stock_info_"+mode+""+cnt).html("");
        
        var stock_category_data = stock_info_array[idx];
        var stock_select = "";
        
        if(Array.isArray(stock_category_data)){
            for(var i=0;i<stock_category_data.length;i++){
                var row = stock_category_data[i];
                    
                stock_select += "<option value="+row['idx']+">"+row['name']+"</option>";
            }
        
            $("#stock_info_"+mode+""+cnt).html(stock_select);
        }else{
            $("#stock_info_"+mode+""+cnt).html("");
        }
        stock_unit_set($("#stock_info_"+mode+""+cnt).val(),cnt,mode);
    }
    
    function stock_unit_set(idx,cnt){   //투입재료 선택시 투입단위 추가
    
        var sc_idx = $("#stock_category_"+mode+""+cnt).val();
        
        var stock_category_data = stock_info_array[sc_idx];
        
        if(Array.isArray(stock_category_data)){
            for(var i=0;i<stock_category_data.length;i++){
                var row = stock_category_data[i];
                if(row['idx'] == idx){    
                    $("#stock_unit_"+mode+""+cnt).val(row['unit']);
                }
            }
        }
        
    }
    function delete_recipe(idx){
        $("#recipe_"+mode+""+idx).remove();
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

    
    function set_detail_modal(data, process){
        
            $("#update_recipe_idx").val(data.idx);
            $("#update_recipe_name").val(data.name);
            $("#update_recipe_price").val(data.price);
            
            for(var i=0; i<process.length;i++){
                recipe_val_set(process[i]['category_idx'], process[i]['stock_idx'], process[i]['stock_input'], process[i]['set_time'] );
            }
            
            if(data.state == "Y"){
                $("#recipe_use_y").prop("checked", true);
            }else{
                $("#recipe_use_n").prop("checked", true);
            }
            
    }

    function recipe_val_set( category_idx, stock_idx,  stock_input, set_time ){
        
        recipe_val = $("#update_recipe_value");
        stock_update_cnt++;

        stock_info_cnt = stock_update_cnt;

        var recipe_html  = "<tr id='recipe_"+mode+""+stock_info_cnt+"'>"
                    +"<td><select class='form-control select_font' id='stock_category_"+mode+""+stock_info_cnt+"' name='"+mode+"_stock_category[]' onchange='stock_info_set(this.value,"+stock_info_cnt+")'>"
                    +  "<?=$scategory_select?>"
                    +"</select></td>"
                    +"<td><select class='form-control select_font' id='stock_info_"+mode+""+stock_info_cnt+"' name='"+mode+"_stock_info[]' onchange='stock_unit_set(this.value,"+stock_info_cnt+")'>"
                    +"</select></td>"
                    +"<td><input type='text' class='form-control' id='stock_cnt_"+mode+""+stock_info_cnt+"' name='"+mode+"_stock_cnt[]' value='"+stock_input+"'/></td>"
                    +"<td><input type='text' class='form-control' id='stock_unit_"+mode+""+stock_info_cnt+"' name='"+mode+"_stock_unit[]' readonly></td>"
                    +"<td><input type='text' class='form-control  id='recipe_time_"+mode+""+stock_info_cnt+"' name='"+mode+"_recipe_time[]' value="+set_time+"></td>"
                    +"<td style='text-align: center'><button type='button' class='glyphicon glyphicon-minus btn btn-danger' onclick='delete_recipe("+stock_info_cnt+")'></span></td>"
                    +"</tr>";
        
        recipe_val.append(recipe_html);
     
        
        stock_info_set(category_idx,stock_info_cnt);    
        
        $("#stock_category_"+mode+""+stock_info_cnt).val(category_idx);
        $("#stock_info_"+mode+""+stock_info_cnt).val(stock_idx);
        stock_unit_set($("#stock_info_"+mode+""+stock_info_cnt).val(), stock_info_cnt);   
    }

  
    
    
    function recipe_update(){
        
        var recipe_name = $("#update_recipe_name");

        if(recipe_name.val() == ""){
                alert("레시피명을 입력하시기 바랍니다.");
                recipe_name.focus();
                return;
        }
	        
        var form = $("#recipe_update_form");
        var formData = form.serialize() +"&order_num="+stock_update_cnt;
        
        $.ajax({
            url:'/RecipeList/set_update_recipe',
            type:'post',
            data:formData,
            success:function(data){
                alert(data.message);
                location.reload();
            },
            error: function(xhr,status,error) {
                console.log(xhr,status,error);
                alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                return false;
            }	 
        });

    }
    
    $('#modal_recipe_detail').on('hide.bs.modal', function (e) {
        reset_insert();
    });


        
</script>
</html>