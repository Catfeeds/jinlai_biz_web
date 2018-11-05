
<div class="page-content">

	<div class="row">
		<div class="col-xs-12">
			<table id="simple-table" class="table  table-bordered table-hover">
				<thead>
					<tr>
						<th class="hidden-480">退款ID</th>
						<th>记录ID</th>
						<th class="hidden-480">订单ID</th>
						<th>商品名</th>
						<th class="hidden-480">数量</th>
						<th>总金额</th>
						<th class="hidden-480">售后类型</th>
						<th class="hidden-480">理由</th>
						<th>申请金额</th>
						<th class="hidden-480">已同意</th>
						<th>已退款</th>
						<th class="hidden-480">创建时间</th>
						<th class="hidden-480">状态</th>
						<th>操作</th>

					</tr>
				</thead>

				<tbody>
					<?php foreach ($res as $key => $value): ?>
						<tr>
							<td class="hidden-480"><?php echo $value['refund_id']?></td>
							<td><a target="_blank" href="<?php echo BASE_URL("salor/detail?record_id=" . $value['record_id']) ?>"><?php echo $value['record_id']?></a></td>
							<td class="hidden-480"><?php echo $value['order_id']?></td>
							<td><?php echo $value['order_item']['name']?></td>
							<td class="hidden-480"><?php echo $value['order_item']['count']?></td>
							<td><?php echo $value['order_item']['single_total']?></td>
							<td class="hidden-480"><?php echo $value['type']?></td>
							<td class="hidden-480"><?php echo $value['reason']?></td>
							<td><?php echo $value['total_applied']?></td>
							<td class="hidden-480"><?php echo $value['total_approved']?></td>
							<td><?php echo $value['total_approved']?></td>
							<td class="hidden-480"><?php echo date("Y-m-d H:i:s",$value['time_create'])?></td>
							<td class="hidden-480"><?php echo $value['status']?></td>
							<?php if ($value['status'] == '待处理'): ?>
							<td><a class="btn btn-xs btn-success" href="<?php echo BASE_URL("salor/accept?refund_id=") . $value['refund_id']?>" >
									<i class="ace-icon fa fa-check bigger-120"></i>
									同意退款
								</a>
							</td>
							<?php else :?>
								<td></td>
							<?php endif ?>

						</tr>
					<?php endforeach; ?>


				</tbody>


			</table>

		</div><!-- /.span -->
	</div>

</div>
