
<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<p>
				<a href="<?php echo BASE_URL("salor/index?status=all"); ?>" class="all btn btn-danger">全部</a>
				<a href="<?php echo BASE_URL("salor/index?status=done"); ?>" class="done btn btn-success">已使用</a>
				<a href="<?php echo BASE_URL("salor/index?status=yet"); ?>" class="yet btn btn-info">未消费</a>
				<a href="<?php echo BASE_URL("salor/index?status=expire"); ?>" class="expire btn btn-inverse">已过期</a>
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<table id="simple-table" class="table  table-bordered table-hover">
				<thead>
					<tr>
						<th>记录ID</th>
						<th class="hidden-480">用户ID</th>
						<th>名称</th>
						<th>价格</th>
						<th class="hidden-480">数量</th>
						<th>总价</th>
						<th class="hidden-480">购买时间</th>
						<th class="hidden-480">核销时间</th>
						<th class="hidden-480">状态</th>
						
					</tr>
				</thead>

				<tbody>
					<?php foreach ($res as $key => $value): ?>
						<tr>
							<td><a href="<?php echo BASE_URL("salor/detail?record_id=" . $value['record_id']) ?>"><?php echo $value['record_id']; ?></a></td>
							<td class="hidden-480"><?php echo $value['user_id']; ?></td>
							<td><?php echo $value['name']; ?></td>
							<td><?php echo $value['price']; ?></td>
							<td class="hidden-480"><?php echo $value['count']; ?></td>
							<td><?php echo $value['single_total']; ?></td>
							<td class="hidden-480"><?php echo date('Y-m-d H:i:s',$value['time_create']); ?></td>

							<td class="hidden-480"><?php 
							if ($value['time_verified'] > 0)
								echo date('Y-m-d H:i:s',$value['time_verified']); 
							?></td>
							<td class="hidden-480">
								<span class="label label-sm label-<?php echo $status_color[$value['status']]?>"><?php echo $value['status']; ?></span>
							</td>
						</tr>
					<?php endforeach; ?>
					

				</tbody>


			</table>

		</div><!-- /.span -->
	</div>
	<div class="row">
		<div class="col-xs-6 col-xs-offset-4">
			<ul class="pagination">
				<?php echo $pager ?>
			</ul>
		</div>
	</div>
</div>
