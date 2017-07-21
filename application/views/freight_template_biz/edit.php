<style>
	.params {display:none;}

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

<script>
	$(function(){
		// 仅显示适用于当前类型的参数
		var div_to_show = '<?php echo $item['type'] ?>';
		$('[data-type*="' + div_to_show + '"]').show();
		
		var fieldset_to_show = '<?php echo $item['type_actual'] ?>';
		$('[data-type*="' + fieldset_to_show + '"]').show();
	});
</script>

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
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<fieldset>
			<p class="bg-info text-info text-center">必填项以“※”符号表示</p>
			
			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="名称" required>
				</div>
			</div>
			<div class=form-group>
				<label for=type class="col-sm-2 control-label">类型</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $item['type'] ?></p>
				</div>
			</div>
		</fieldset>
			
		<div class=params data-type="电子凭证">
			<div class=form-group>
				<label for=time_valid_from class="col-sm-2 control-label">有效期起始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_valid_from type=text value="<?php echo $item['time_valid_from'] ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+2days')) ?>">
				</div>
			</div>
			<div class=form-group>
				<label for=time_valid_end class="col-sm-2 control-label">有效期结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_valid_end type=text value="<?php echo $item['time_valid_end'] ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+366days')) ?>">
				</div>
			</div>
			<div class=form-group>
				<label for=period_valid class="col-sm-2 control-label">有效期</label>
				<div class=col-sm-10>
					<input class=form-control name=period_valid type=text value="<?php echo $item['period_valid'] ?>" placeholder="有效期">
				</div>
			</div>
			<div class=form-group>
				<label for=expire_refund_rate class="col-sm-2 control-label">过期退款比例</label>
				<div class=col-sm-10>
					<input class=form-control name=expire_refund_rate type=number step=0.01 max=1 value="<?php echo $item['expire_refund_rate'] ?>" placeholder="过期退款比例">
				</div>
			</div>
		</div>
		
		<div class=params data-type="物流配送">
			<div class=form-group>
				<label for=type_actual class="col-sm-2 control-label">物流配送类型</label>
				<div class=col-sm-10>
					<?php $input_name = 'type_actual' ?>
					<select class=form-control name="<?php echo $input_name ?>" required>
						<option value="" <?php if ( empty($item[$input_name]) ) echo 'selected'; ?>>请选择</option>
						<?php
							$options = array('计件','净重','毛重','体积重');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php if ($option === $item[$input_name]) echo 'selected'; ?>><?php echo $option ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class=form-group>
				<label for=time_latest_deliver class="col-sm-2 control-label">最晚发货时间</label>
				<div class=col-sm-10>
					<?php $input_name = 'time_latest_deliver' ?>
					<select class=form-control name="<?php echo $input_name ?>">
						<option value="" <?php if ( empty($item[$input_name]) ) echo 'selected'; ?>>请选择</option>
						<?php
							$options = array(
								'1小时' => '3600',
								'2小时' => '7200',
								'3小时' => '10800',
								'4小时' => '14400',
								'6小时' => '21600',
								'8小时' => '28800',
								'12小时' => '43200',
								'24小时' => '86400',
								'48小时' => '172800',
								'72小时' => '259200',
								'5天' => '432000',
								'7天' => '604800',
								'10天' => '864000',
								'14天' => '1209600',
								'30天' => '2592000',
								'45天' => '3888000',
							);
							foreach ($options as $name => $value):
						?>
						<option value="<?php echo $value ?>" <?php if ($value === $item[$input_name]) echo 'selected'; ?>><?php echo $name ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<fieldset class=params data-type="计件">
				<div class=form-group>
					<label for=max_count class="col-sm-2 control-label">每单最高件数（件）</label>
					<div class=col-sm-10>
						<input class=form-control name=max_count type=number step=1 max=9999 value="<?php echo $item['max_count'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_count_start class="col-sm-2 control-label">计件起始运费（元）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_count_start type=number step=1 max=9999 value="<?php echo $item['fee_count_start'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_count_amount class="col-sm-2 control-label">计件起始量（KG）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_count_amount type=number step=1 max=9999 value="<?php echo $item['fee_count_amount'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_count class="col-sm-2 control-label">每件运费（元）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_count type=number step=1 max=9999 value="<?php echo $item['fee_count'] ?>" placeholder="最高9999">
					</div>
				</div>
			</fieldset>

			<fieldset class=params data-type="净重">
				<div class=form-group>
					<label for=max_net class="col-sm-2 control-label">每单最高净重（KG）</label>
					<div class=col-sm-10>
						<input class=form-control name=max_net type=number step=1 max=9999 value="<?php echo $item['max_net'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_net_start class="col-sm-2 control-label">净重起始运费（元）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_net_start type=number step=1 max=9999 value="<?php echo $item['fee_net_start'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_net_amount class="col-sm-2 control-label">净重起始量（KG）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_net_amount type=number step=1 max=9999 value="<?php echo $item['fee_net_amount'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_net class="col-sm-2 control-label">每KG净重运费（元）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_net type=number step=1 max=9999 value="<?php echo $item['fee_net'] ?>" placeholder="最高9999">
					</div>
				</div>
			</fieldset>

			<fieldset class=params data-type="毛重">
				<div class=form-group>
					<label for=max_gross class="col-sm-2 control-label">每单最高毛重（KG）</label>
					<div class=col-sm-10>
						<input class=form-control name=max_gross type=number step=1 max=9999 value="<?php echo $item['max_gross'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_gross_start class="col-sm-2 control-label">毛重起始运费（元）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_gross_start type=number step=1 max=9999 value="<?php echo $item['fee_gross_start'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_gross_amount class="col-sm-2 control-label">毛重起始量（KG）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_gross_amount type=number step=1 max=9999 value="<?php echo $item['fee_gross_amount'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_gross class="col-sm-2 control-label">每KG毛重运费（元）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_gross type=number step=1 max=9999 value="<?php echo $item['fee_gross'] ?>" placeholder="最高9999">
					</div>
				</div>
			</fieldset>

			<fieldset class=params data-type="体积重">
				<div class=form-group>
					<label for=max_volume class="col-sm-2 control-label">每单最高体积重（KG）</label>
					<div class=col-sm-10>
						<input class=form-control name=max_volume type=number step=1 max=9999 value="<?php echo $item['max_volume'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_volumn_start class="col-sm-2 control-label">体积重起始运费（元）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_volumn_start type=number step=1 max=9999 value="<?php echo $item['fee_volumn_start'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_volumn_amount class="col-sm-2 control-label">体积重起始量（KG）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_volumn_amount type=number step=1 max=9999 value="<?php echo $item['fee_volumn_amount'] ?>" placeholder="最高9999">
					</div>
				</div>
				<div class=form-group>
					<label for=fee_volume class="col-sm-2 control-label">每KG体积重运费（元）</label>
					<div class=col-sm-10>
						<input class=form-control name=fee_volume type=number step=1 max=9999 value="<?php echo $item['fee_volume'] ?>" placeholder="最高9999">
					</div>
				</div>
			</fieldset>
		</div>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>

</div>