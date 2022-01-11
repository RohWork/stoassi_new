<?php 
    $file_name = 'history_ex_'.date('Y-m-d');
    header("Content-Disposition: attachment; filename=$file_name.xls");
?>

<html>
    <head></head>
    <body
    <div class="container">
		<div class="page-header">
			<h1>주문내역</h1>
		</div>
		<div class="table-responsive">
			<table class="table" border="1">
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
	</div>

</body>
</html>