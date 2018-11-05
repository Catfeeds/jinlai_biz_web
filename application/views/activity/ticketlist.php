
<div class="page-content">
	
	<div class="row">
		<div class="col-xs-12">
			<table id="simple-table" class="table  table-bordered table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>名称</th>
						<th class="hidden-480">显示数量</th>
						<th>排序</th>
						<th class="hidden-480">LOGO</th>
						<th class="hidden-480">创建时间</th>
						<th>操作</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($res as $key => $value): ?>
						<tr>
							<td><?php echo $value['id']; ?></td>
							<td><?php echo $value['ticket_name']; ?></td>
							<td class="hidden-480"><?php echo $value['show_count']; ?></td>
							<td><?php echo $value['sort']; ?></td>
							<td class="hidden-480"><img src="<?php echo $value['picture']; ?>" width="100px;"/></td>
							<td class="hidden-480"><?php echo date("Y-m-d H:i:s",$value['time_create']); ?></td>
							<td>
								<div class="btn-group">
									<a class="btn btn-xs btn-danger cancel_coupon" href="<?php echo base_url('activity/delete_ticket?id=' . $value['id']) ?>" >
										<i class="ace-icon fa fa-trash-o bigger-120"></i>
									</a>
								</div>
							
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
