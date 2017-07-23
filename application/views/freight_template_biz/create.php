<style>
	.params {display:none;}
	div.params {border-top:1px solid #aaa;}

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
		// 根据所选类型显示相应参数
		$('select[name=type]').change(function(){
			var fieldset_to_show = $(this).find('option:selected').attr('value');
			$('fieldset.params').hide();
			$('[data-type="' + fieldset_to_show + '"]').show();
		});

		// 显示物流配送类型
		$('select[name=type_actual]').change(function(){
			var type_actual = $(this).find('option:selected').attr('value');
			$('.type-actual').text(type_actual);
		});
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
		<a class="btn btn-primary" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<fieldset>
			<p class="bg-info text-info text-center">必填项以“※”符号表示</p>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="例如：全场包邮、满2件包邮、满2公斤包邮等" required>
				</div>
			</div>
			<div class=form-group>
				<label for=type class="col-sm-2 control-label">类型※</label>
				<div class=col-sm-10>
					<?php $input_name = 'type' ?>
					<select class=form-control name="<?php echo $input_name ?>" required>
						<option value="" <?php echo set_select($input_name, '') ?>>请选择</option>
						<?php
							$options = array('物流配送','电子凭证');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>><?php echo $option ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</fieldset>
			
		<fieldset class=params data-type="电子凭证">
			<p class="bg-warning text-warning text-center">若全部留空，则电子凭证自用户付款时起366个自然日内有效，逾期全额退款</p>

			<div class=form-group>
				<label for=time_valid_from class="col-sm-2 control-label">有效期起始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_valid_from type=datetime value="<?php echo set_value('time_valid_from') ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+2days')) ?>">
				</div>
			</div>
			<div class=form-group>
				<label for=time_valid_end class="col-sm-2 control-label">有效期结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_valid_end type=datetime value="<?php echo set_value('time_valid_end') ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+366days')) ?>">
				</div>
			</div>
			<div class=form-group>
				<label for=period_valid class="col-sm-2 control-label">有效期（天）</label>
				<div class=col-sm-10>
					<p class="bg-info text-info">若填写了此项，则有效期将根据订单付款时间及此项数值自动计算，若超出上述“有效期结束时间”，则以“有效期结束时间”为准</p>
					<input class=form-control name=period_valid type=number step=1 max=366 value="<?php echo set_value('period_valid') ?>" placeholder="最短3天，最长366天；留空则默认为366天">
				</div>
			</div>
			<div class=form-group>
				<label for=expire_refund_rate class="col-sm-2 control-label">过期退款比例</label>
				<div class=col-sm-10>
					<p class="bg-info text-info">若电子凭证逾期未使用，系统将把该比例的用户实付款项原路退回给用户</p>
					<input class=form-control name=expire_refund_rate type=number step=0.01 max=1 value="<?php echo set_value('expire_refund_rate') ?>" placeholder="例如100%为1，80%为0.8，以此类推；留空则默认为100%">
				</div>
			</div>
		</fieldset>

		<fieldset class=params data-type="物流配送">
			<p class="bg-warning text-warning text-center">若全部留空，则默认在确认接单3个自然日内发货，包邮（运费计算方式为“计件”）</p>

			<div class=form-group>
				<label for=time_latest_deliver class="col-sm-2 control-label">最晚发货时间</label>
				<div class=col-sm-10>
					<?php $input_name = 'time_latest_deliver' ?>
					<select class=form-control name="<?php echo $input_name ?>">
						<option value="" <?php echo set_select($input_name, '') ?>>请选择</option>
						<?php
							$options = array(
								'1小时' => '3600',
								'2小时' => '7200',
								'3小时' => '10800',
								'4小时' => '14400',
								'6小时' => '21600',
								'8小时' => '28800',
								'12小时' => '43200',
								'24小时/1天' => '86400',
								'48小时/2天' => '172800',
								'72小时/3天' => '259200',
								'5天' => '432000',
								'7天' => '604800',
								'10天' => '864000',
								'14天' => '1209600',
								'30天' => '2592000',
								'45天' => '3888000',
							);
							foreach ($options as $name => $value):
						?>
						<option value="<?php echo $value ?>" <?php echo set_select($input_name, $value) ?>><?php echo $name ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			
			<div class=form-group>
				<label for=type_actual class="col-sm-2 control-label">运费计算方式</label>
				<div class=col-sm-10>
					<?php $input_name = 'type_actual' ?>
					<select class=form-control name="<?php echo $input_name ?>" required>
						<option value="" <?php echo set_select($input_name, '') ?>>请选择</option>
						<?php
							$options = array('计件','净重','毛重','体积重');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>><?php echo $option ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<p class="bg-info text-info text-center">计量单位为“件”（计件时）、“KG”（计净重/毛重/体积重时）</p>

			<div class=form-group>
				<label for=max_amount class="col-sm-2 control-label">每单最高配送<span class=type-actual></span>量（单位）</label>
				<div class=col-sm-10>
					<input class=form-control name=max_amount type=number step=1 max=9999 value="<?php echo set_value('max_amount') ?>" placeholder="最高9999">
				</div>
			</div>
			<div class=form-group>
				<label for=start_amount class="col-sm-2 control-label"><span class=type-actual></span>起始量</label>
				<div class=col-sm-10>
					<input class=form-control name=start_amount type=number step=1 max=9999 value="<?php echo set_value('start_amount') ?>" placeholder="最高9999">
				</div>
			</div>
			<div class=form-group>
				<label for=fee_start class="col-sm-2 control-label"><span class=type-actual></span>起始量运费</label>
				<div class=col-sm-10>
					<input class=form-control name=fee_start type=number step=1 max=9999 value="<?php echo set_value('fee_start') ?>" placeholder="最高9999">
				</div>
			</div>
			<div class=form-group>
				<label for=fee_unit class="col-sm-2 control-label"><span class=type-actual></span>超出后运费（元/单位）</label>
				<div class=col-sm-10>
					<input class=form-control name=fee_unit type=number step=1 max=9999 value="<?php echo set_value('fee_unit') ?>" placeholder="最高9999">
				</div>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>

</div>