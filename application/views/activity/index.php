
<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<p>
				<a href="<?php echo BASE_URL("activity/activity_index?status=all"); ?>" class="all btn btn-warning">全部<a>
				<a href="<?php echo BASE_URL("activity/activity_index?status=yet"); ?>" class="yet btn btn-info">未使用</a>
				<a href="<?php echo BASE_URL("activity/activity_index?status=done"); ?>" class="done btn btn-success">已使用</a>
				<a href="<?php echo BASE_URL("activity/activity_index?status=cancel"); ?>" class="cancel btn btn-danger">已作废</a>
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
						<th>活动券</th>
						<th>核销码</th>
						<th>电话</th>
						<th class="hidden-480">领取时间</th>
						<th class="hidden-480">使用时间</th>
						<th>状态</th>
						<th>操作</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($res as $key => $value): ?>
						<tr>
							<td><?php echo $value['id']; ?></td>
							<td class="hidden-480"><?php echo $value['user_id']; ?></td>
							<td><?php echo $value['coupon_name']; ?></td>

							<td><?php 
								if ($value['status'] == '未使用') :
									echo substr($value['verify_code'], 0, 2) . '******' . substr($value['verify_code'], -1, 2); 
								else:
									echo $value['verify_code'];
								endif;
							?>
							</td>
							<td><?php echo $value['mobile']?></td>
							<td class="hidden-480"><?php echo date("Y-m-d H:i:s",$value['time_create']); ?></td>
							<td class="hidden-480"><?php echo empty($value['time_verify']) ? '' : date("Y-m-d H:i:s",$value['time_verify']); ?></td>
							<td>
								<span class="label label-sm label-<?php echo $status_color[$value['status']]?>"><?php echo $value['status']; ?></span>
							</td>
							<td>
								<div class="btn-group">
									<?php if ($value['status'] == '未使用') : ?>
										<button class="btn btn-xs btn-danger cancel_coupon" data="<?php echo $value['id']?>" >
											<i class="ace-icon fa fa-ban bigger-120"></i>
										</button>
									<?php endif;?>
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
