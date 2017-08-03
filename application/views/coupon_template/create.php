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

<?php
	$is_ios = strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')? TRUE: FALSE;
	// 在iOS设备上使用原生日期选择器
	if ( ! $is_ios ):
?>
<link href="/css/datepicker.min.css" rel="stylesheet">
<script src="/js/datepicker.min.js"></script>
<script>
	$(function(){
		// 初始化日期选择器
		$('[type=datetime]').datepicker(
			{
			    language: 'cn', // 本地化语言在js/main.js中
			    minDate: new Date("<?php echo date('Y-m-d H:i') ?>"),
				maxDate: new Date("<?php echo date('Y-m-d H:i', strtotime("+31 days")) ?>"),
				timepicker: true, // 时间选择器
				timeFormat: "hh:ii"
			}
		)
	});
</script>
<?php endif ?>

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
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="最多20个字符" required>
				</div>
			</div>

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明</label>
				<div class=col-sm-10>
					<input class=form-control name=description type=text value="<?php echo set_value('description') ?>" placeholder="最多30个字符">
				</div>
			</div>

			<div class=form-group>
				<label for=amount class="col-sm-2 control-label">面值（元）※</label>
				<div class=col-sm-10>
					<input class=form-control name=amount type=number step=1 min=1 max=999 value="<?php echo set_value('amount') ?>" placeholder="最高999" required>
				</div>
			</div>

			<div class=form-group>
				<label for=min_subtotal class="col-sm-2 control-label">起用金额/订单小计（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=min_subtotal type=number step=1 max=9999 value="<?php echo set_value('min_subtotal') ?>" placeholder="留空则不限，最高9999">
				</div>
			</div>
			
			<div class=form-group>
				<label for=max_amount class="col-sm-2 control-label">总限量（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=max_amount type=number step=1 max=999999 value="<?php echo set_value('max_amount') ?>" placeholder="留空则不限，最高999999">
				</div>
			</div>
			
			<div class=form-group>
				<label for=max_amount_user class="col-sm-2 control-label">单个用户限量（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=max_amount_user type=number step=1 max=99 value="<?php echo set_value('max_amount_user') ?>" placeholder="留空则不限，最高99">
				</div>
			</div>

			<div class=form-group>
				<label for=category_id class="col-sm-2 control-label">限用系统分类</label>
				<div class=col-sm-10>
					<select class=form-control name=category_id>
						<option value="">不限</option>
						<?php foreach ($categories as $option): ?>
							<option value="<?php echo $option['category_id'] ?>" <?php echo set_select('category_id', $option['category_id']) ?>><?php echo $option['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class=form-group>
				<label for=category_biz_id class="col-sm-2 control-label">限用店内分类</label>
				<div class=col-sm-10>
					<select class=form-control name=category_biz_id>
						<option value="">不限</option>
						<?php foreach ($biz_categories as $option): ?>
							<option value="<?php echo $option['category_id'] ?>" <?php echo set_select('category_id', $option['category_id']) ?>><?php echo $option['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class=form-group>
				<label for=item_id class="col-sm-2 control-label">限用商品</label>
				<div class=col-sm-10>
					<input class=form-control name=item_id type=text value="<?php echo set_value('item_id') ?>" placeholder="如仅限部分商品可用，请输入可用商品的商品ID，多个ID间用一个半角逗号“,”进行分隔">
				</div>
			</div>

			<div class=form-group>
				<label for=period class="col-sm-2 control-label">有效期</label>
				<div class=col-sm-10>
					<?php $input_name = 'period' ?>
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
								'2天' => '172800',
								'3天' => '259200',
								'7天' => '604800',
								'10天' => '864000',
								'14天' => '1209600',
								'30天' => '2592000',
								'45天' => '3888000',
								'90天' => '7776000',
								'120天' => '10368000',
								'180天/半年' => '15552000',
								'366天/1年' => '31622400',
								'2年' => '63244800',
								'3年' => '94867200',
							);
							foreach ($options as $name => $value):
						?>
						<option value="<?php echo $value ?>" <?php echo set_select($input_name, $value) ?>><?php echo $name ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class=form-group>
				<label for=time_start class="col-sm-2 control-label">开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_start type=datetime value="<?php echo set_value('time_start') ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+2days')) ?>">
				</div>
			</div>

			<div class=form-group>
				<label for=time_end class="col-sm-2 control-label">结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_end type=datetime value="<?php echo set_value('time_end') ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+5days')) ?>">
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