<style>


	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px)
	{

	}
	
	/* 宽度在960像素以上的设备 */
	@media only screen and (min-width:961px)
	{

	}

	/* 宽度在1280像素以上的设备 */
	@media only screen and (min-width:1281px)
	{

	}
</style>

<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li class=active><?php echo $this->class_name_cn ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class="btn-group btn-group-justified" role=group>
		<div class=btn-group role=group>
		    <button type=button class="btn btn-default dropdown-toggle" data-toggle=dropdown aria-haspopup=true aria-expanded=false>
				所有 <span class="caret"></span>
		    </button>
		    <ul class=dropdown-menu>
				<li>
					<?php $style_class = empty($this->input->get('status') )? 'btn-primary': 'btn-default'; ?>
					<a class="btn <?php echo $style_class ?>" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">所有</a>
				</li>

		  		<?php
		  		$status_to_mark = array('待接单', '待发货', '待收货', '待评价', '已评价', '已退款');
		  		foreach ($status_to_mark as $status):
		  			// 页面URL
		  			$url = ($status === NULL)? base_url('order'): base_url('order?status='.$status);
		  			// 链接样式
		  			$style_class = ($this->input->get('status') !== $status)? 'btn-default': 'btn-primary';
		  			echo '<li><a class="btn '. $style_class. '" title="'. $status. '订单" href="'. $url. '">'. $status. '</a> </li>';
		  		endforeach;
		  		?>
		    </ul>
		</div>

		<a class="btn btn-warning" title="待接单商品订单" href="<?php echo base_url('order?status=待接单') ?>">待接单</a>
		<a class="btn btn-default" title="待发货商品订单" href="<?php echo base_url('order?status=待发货') ?>">待发货</a>
	</div>
	<?php endif ?>

	<?php if ( empty($this->session->biz_id) ): ?>
	<blockquote>
		<p>您需要成为已入驻企业的员工，或者提交入驻申请，才可进行订单管理</p>
	</blockquote>
	
	<?php elseif ( empty($items) ): ?>
	<blockquote>
		<p>这里空空如也，快点推广您的店铺和产品，让进来用户下单吧</p>
	</blockquote>

	<?php else: ?>
	<form method=get target=_blank>
		<?php
			if ( !empty($this->input->get('status')) ):
				$status = $this->input->get('status');
		?>
		<fieldset>
			<div class=btn-group role=group>
				<button formaction="<?php echo base_url($this->class_name.'/note') ?>" type=submit class="btn btn-default">备注</button>
				<?php if ($status === '待付款'): ?>
				<button formaction="<?php echo base_url($this->class_name.'/reprice') ?>" type=submit class="btn btn-default">改价</button>
				<?php endif ?>

				<?php if ($status === '待接单'): ?>
				<button formaction="<?php echo base_url($this->class_name.'/accept') ?>" type=submit class="btn btn-default">接单</button>
				<button formaction="<?php echo base_url($this->class_name.'/refuse') ?>" type=submit class="btn btn-default">退单</button>
				<?php endif ?>
				
				<?php if ($status === '待发货'): ?>
				<button formaction="<?php echo base_url($this->class_name.'/deliver') ?>" type=submit class="btn btn-default">发货</button>
				<?php endif ?>
			</div>
		</fieldset>
		<?php endif ?>

		<table class="table table-condensed table-responsive table-striped sortable">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th><?php echo $this->class_name_cn ?>ID</th>
					<th>下单时间</th>
					<?php
						$thead = array_values($data_to_display);
						foreach ($thead as $th):
							echo '<th>' .$th. '</th>';
						endforeach;
					?>
					<th>操作</th>
				</tr>
			</thead>

			<tbody>
			<?php foreach ($items as $item): ?>
				<tr>
					<td>
						<input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
					</td>
					<td><?php echo $item[$this->id_name] ?></td>
					<td><?php echo date('Y-m-d H:i:s', $item['time_create']) ?></td>
					<?php
						$tr = array_keys($data_to_display);
						foreach ($tr as $td):
							echo '<td>' .$item[$td]. '</td>';
						endforeach;
					?>
					<td>
						<ul class=list-unstyled>
							<li><a title="查看" href="<?php echo base_url($this->view_root.'/detail?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-fw fa-eye"></i> 查看</a></li>
							<?php
							// 需要特定角色和权限进行该操作
							if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
							?>
							<li><a title="备注" href="<?php echo base_url($this->class_name.'/note?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-fw fa-comment"></i> 备注</a></li>

							<li><a title="改价" href="<?php echo base_url($this->class_name.'/reprice?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-fw fa-tags"></i> 改价</a></li>

							<li><a title="退单" href="<?php echo base_url($this->class_name.'/refuse?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-fw fa-reply"></i> 退单</a></li>

							<li><a title="接单" href="<?php echo base_url($this->class_name.'/accept?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-fw fa-share"></i> 接单</a></li>

							<li><a title="发货" href="<?php echo base_url($this->class_name.'/deliver?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-fw fa-truck"></i> 发货</a></li>
							<?php endif ?>
						</ul>
					</td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>

	</form>
	<?php endif ?>
</div>