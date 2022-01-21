<style>
    .header_button{
        cursor: pointer;
    }
</style>
<?php
    if(empty($orderdesc)){
        $orderdesc = "desc";
    }
    if(empty($orderparam)){
        $orderparam = "order_date";
    }

    $class_type = array(
        'table_no' => '',
        'order_name' => '',
        'order_price' => '',
        'order_cnt' => '',
        'order_result' => '',
        'order_date' => '',
    );
    
    if($orderdesc == "asc"){
        $class_order = "top";
    }else{
        $class_order = "bottom";
    }
    
    $class_type[$orderparam] = "glyphicon glyphicon-triangle-".$class_order;
    
    
    
?>

	<div class="container">
		<div class="page-header">
			<h1>주문관리</h1>
			<p class="lead">주문내역</p>
                        <div>
                            <form action="" method="get" id="frm" name="frm">
                                <button type="button" id="excel_print" class="btn btn-primary">excel</button>
                                <input type="date" id="sdate" name="sdate" style="margin-left:100px"/>~
                                <input type="date" id="edate" name="edate"/>
                                <input type="hidden" id="orderdesc" name="orderdesc"/>
                                <input type="hidden" id="orderparam" name="orderparam"/>
                                
                                <button type="button" id="search" name="search" class="btn btn-primary">검색</button>
                            </form>
                        </div>
		</div>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
                                            <th>no</th>
                                            <th class="header_button" onclick="order_set('table_no');"><span class="<?=$class_type['table_no']?>"></span>테이블번호</th>
                                            <th class="header_button" onclick="order_set('order_name');"><span class="<?=$class_type['order_name']?>"></span>주문명</th>
                                            <th class="header_button" onclick="order_set('order_price');"><span class="<?=$class_type['order_price']?>"></span>주문금액</th>
                                            <th class="header_button" onclick="order_set('order_cnt');"><span class="<?=$class_type['order_cnt']?>"></span>주문수</th>
                                            <th class="header_button" onclick="order_set('order_result');"><span class="<?=$class_type['order_result']?>"></span>주문결과</th>
                                            <th class="header_button" onclick="order_set('order_date');"><span class="<?=$class_type['order_date']?>"></span>주문일시</th>
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
                                                <td><?=$row->STATUS ?></td>
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
			<!--<button type="button" id="input_button" class="btn btn-primary">주문추가</button>-->
                        
		</div>
		<div class="col-sm-offset-5">
			<ul class="pagination">
				<?= $pagination ?>
			</ul>
		</div>
	</div>

</body>
<script>
    
    $(document).ready(function(){
         
        var sdate = '<?=$sdate?>';
        var edate = '<?=$edate?>';
        if(sdate == ""){
            sdate = new Date().toISOString().substring(0, 10);
        }
        if(edate == ""){
            edate = new Date().toISOString().substring(0, 10);
        }
        
         $("#sdate").val(sdate);
         $("#edate").val(edate);
    
        $("#search").click(function(){
           $("#frm").submit();
        });
        
        $("#excel_print").click(function(){
            window.open("/Order/order_history_excel?sdate=<?=$sdate?>&edate=<?=$edate?>");
        });
    });
    
    function order_set(orderparam){
    
        if(orderparam == '<?=$orderparam?>' && <?=$order_desc?> == 'desc'){
            $("#orderdesc").val("asc");
        }else{
            $("#orderdesc").val("desc");
        }
        
        $("#orderparam").val(orderparam);
        
        $("#frm").submit();
    }
</script>
</html>