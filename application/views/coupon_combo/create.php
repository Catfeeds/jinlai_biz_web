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
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="名称" required>
				</div>
			</div>

			<div class=form-group>
				<label for=template_ids class="col-sm-2 control-label">所含优惠券※</label>
				<div class=col-sm-10>
					<input class=form-control name=template_ids type=text value="<?php echo set_value('template_ids') ?>" placeholder="请输入所含优惠券ID，多个ID间以一个半角逗号“,”分隔" required>

					<script>
						$(function(){
							$('[name="ids[]"]').change(function(){
								// 获取选项ID
								var item_id = $(this).val();
								console.log(item_id + ' selected');

								// 获取当前字段及字段值
								var input = $('[name=ids]');
								var origin_ids = input.val();

								// 若所选项值在当前值中不存在，则追加所选项值到当前值末尾，否则删除当前值中的所选值
								var value_to_check = origin_ids + ',';
								console.log(value_to_check);
								if (value_to_check.indexOf(','+item_id+',') == -1)
								{
									var current_ids = origin_ids + ',' + item_id;
									input.val(current_ids);
									console.log(current_ids + ' after appended');
								}
								else
								{
									var current_ids = value_to_check.replace(','+item_id+',', ',');
									current_ids = current_ids.slice(0, -1); // 去掉末尾的冗余半角逗号
									input.val(current_ids);
									console.log(current_ids + ' after deleted');
								}
							});

							$('form').submit(function(){
								var value = $('[name="ids"]').val();
								alert(value);
							});
						});
					</script>
				</div>
			</div>
			
			<div class=form-group>
				<label for=max_amount class="col-sm-2 control-label">总限量（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=max_amount type=number step=1 min=0 max=999999 value="<?php echo set_value('max_amount') ?>" placeholder="最高999999，0为不限，留空默认为0">
				</div>
			</div>

			<div class=form-group>
				<label for=time_start class="col-sm-2 control-label">开始领取时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_start type=datetime value="<?php echo set_value('time_start') ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+2days')) ?>；留空则马上开放领取">
				</div>
			</div>

			<div class=form-group>
				<label for=time_end class="col-sm-2 control-label">结束领取时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_end type=datetime value="<?php echo set_value('time_end') ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+5days')) ?>；留空则长期有效">
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