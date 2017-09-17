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
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
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
					<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">所有</a>
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

		<a class="btn btn-default" title="待接单商品订单" href="<?php echo base_url('order?status=待接单') ?>">待接单</a>
		<a class="btn btn-default" title="待发货商品订单" href="<?php echo base_url('order?status=待发货') ?>">待发货</a>
	</div>
	<?php endif ?>

	<table class="table table-striped table-condensed table-responsive">
		<thead>
			<tr>
				<th><?php echo $this->class_name_cn ?>ID</th>
				<?php
					$thead = array_values($data_to_display);
					foreach ($thead as $th):
						echo '<th>' .$th. '</th>';
					endforeach;
				?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo $item[$this->id_name] ?></td>
				<?php
					$tr = array_keys($data_to_display);
					foreach ($tr as $td):
						echo '<td>' .$item[$td]. '</td>';
					endforeach;
				?>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<div class="alert alert-warning" role=alert>
		<p>确定要发货？请选择发货方式，并填写相应信息。</p>
	</div>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-deliver form-horizontal', 'role' => 'form');
		echo form_open($this->class_name.'/deliver', $attributes);
	?>
		<fieldset>
			<input name=ids type=hidden value="<?php echo implode(',', $ids) ?>">

			<div class=form-group>
				<label for=deliver_method class="col-sm-2 control-label">发货方式</label>
				<div class=col-sm-10>
					<?php
						$input_name = 'deliver_method';
						$options = array('自行配送', '本地配送', '物流快递');
						foreach ($options as $option):
					?>
					<label class=radio-inline>
						<input type=radio name="<?php echo $input_name ?>" value="<?php echo $option ?>" required <?php echo set_radio($input_name, $option, TRUE) ?>> <?php echo $option ?>
					</label>
					<?php endforeach ?>
					
					<div class="btn-group btn-group-justified" role=group>
						<a class="btn btn-primary" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">自行配送</a>
					  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>">本地配送</a>
						<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>">物流快递</a>
					</div>
				</div>
			</div>
			
			<div class=form-group>
				<label for=deliver_biz class="col-sm-2 control-label">物流服务商</label>
				<div class=col-sm-10>
					<?php $input_name = 'deliver_biz' ?>
					<select class=form-control name="<?php echo $input_name ?>" required>
						<option value="" <?php echo set_select($input_name, '') ?>>请选择</option>
						<?php
							$options = array('自营', '达达', '蜂鸟', '顺丰速运', '圆通', '中通', '申通', '百世汇通', '天天',);
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>><?php echo $option ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class=form-group>
				<label for=waybill_id class="col-sm-2 control-label">运单号</label>
				<div class=col-sm-10>
					<input class=form-control name=waybill_id type=text placeholder="请输入运单号" autofocus>
					<p class=help-block>自行配送时可留空</p>
				</div>
			</div>

			<div class=form-group>
				<label for=password class="col-sm-2 control-label">密码</label>
				<div class=col-sm-10>
					<input class=form-control name=password type=password placeholder="请输入您的登录密码" required>
				</div>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-warning btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>

	</form>
</div>