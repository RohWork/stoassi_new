<?php
echo get_qr("www.naver.com",'naver');
?>

	<div class="container">
		<div class="page-header">
			<h1>주문관리</h1>
			<p class="lead">주문내역</p>
                        <button id="excel_print">excel</button>
                        <input type="date" id="sdate" name="sdate"/>~
                        <input type="date" id="edate" name="edate"/>
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
    

        
</script>
</html>