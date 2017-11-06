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

<link href="<?php echo CDN_URL ?>css/datepicker.min.css" rel="stylesheet">
<script src="<?php echo CDN_URL ?>js/datepicker.min.js"></script>
<script>
	$(function(){
		// 初始化日期选择器
		$('[type=datetime]').datepicker(
			{
			    language: 'cn', // 本地化语言在js/main.js中
			    minDate: new Date("<?php echo date('Y-m-d H:i') ?>"),
				maxDate: new Date("<?php echo date('Y-m-d H:i', strtotime("+366 days")) ?>"),
				timepicker: true, // 时间选择器
				timeFormat: "hh:ii"
			}
		)
	});
</script>

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
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<fieldset>
			<p class=help-block>必填项以“※”符号标示</p>

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
					<p class=help-block>放入优惠券包的优惠券模板，在优惠券包被领取时将忽视总限量（若有）及单个用户限量（若有），以优惠券包的总限量（若有）为准；作为单个优惠券模板被领取时不受影响。</p>

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
								//var value = $('[name=ids]').val();
								var value = $('[name=time_end]').val();
								console.log(value);
							});
						});
					</script>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>高级选项（可留空）</legend>

			<div class=form-group>
				<label for=max_amount class="col-sm-2 control-label">总限量（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=max_amount type=number step=1 min=0 max=999999 value="<?php echo set_value('max_amount') ?>" placeholder="最高999999，留空或0为不限">
					<p class=help-block>总共可被领取的优惠券数量上限；对于每位用户来说，每个优惠券包仅可领取一次</p>
				</div>
			</div>

			<div class=form-group>
				<label for=time_start class="col-sm-2 control-label">领取开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_start type=datetime value="<?php echo set_value('time_start') ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+2days')) ?>；留空则马上开放领取">
				</div>
			</div>

			<div class=form-group>
				<label for=time_end class="col-sm-2 control-label">领取结束时间</label>
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