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

<base href="<?php echo base_url('uploads/') ?>">

<script>
	$(function(){
		// 根据所选活动类型显示相应参数
		$('select[name=type]').change(function(){
			var fieldset_to_show = $(this).find('option:selected').attr('value');
			$('fieldset.params').hide();
			$('[data-type*="' + fieldset_to_show + '"]').show();
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
				<label for=type class="col-sm-2 control-label">活动类型※</label>
				<div class=col-sm-10>
					<?php $input_name = 'type' ?>
					<select class=form-control name="<?php echo $input_name ?>" required>
						<option value="" <?php echo set_select($input_name, '') ?>>请选择</option>
						<?php
							$options = array('单品折扣','单品满赠','单品满减','单品赠券','单品预购','单品团购','订单折扣','订单满赠','订单满减','订单赠券');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>><?php echo $option ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="名称" required>
				</div>
			</div>
			<div class=form-group>
				<label for=time_start class="col-sm-2 control-label">开始时间※</label>
				<div class=col-sm-10>
					<input class=form-control name=time_start type=datetime value="<?php echo set_value('time_start') ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+2days')) ?>" required>
				</div>
			</div>
			<div class=form-group>
				<label for=time_end class="col-sm-2 control-label">结束时间※</label>
				<div class=col-sm-10>
					<input class=form-control name=time_end type=datetime value="<?php echo set_value('time_end') ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+5days')) ?>" required>
				</div>
			</div>
			<div class=form-group>
				<label for=fold_allowed class="col-sm-2 control-label">是否允许折上折※</label>
				<div class=col-sm-10>
					<select class=form-control name=fold_allowed required>
						<option value=0 <?php echo set_select('fold_allowed', 0) ?>>否</option>
						<option value=1 <?php echo set_select('fold_allowed', 1) ?>>是</option>
					</select>
				</div>
			</div>
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明</label>
				<div class=col-sm-10>
					<input class=form-control name=description type=text value="<?php echo set_value('description') ?>" placeholder="说明">
				</div>
			</div>
			<div class=form-group>
				<label for=url_image class="col-sm-2 control-label">形象图</label>
				<div class=col-sm-10>
					<p class=help-block>该图用于手机等窄屏设备；请上传大小在2M以内，边长不超过2048px的jpg/png图片</p>

					<?php $name_to_upload = 'url_image' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" required>

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir="promotion_biz/image" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>
			<div class=form-group>
				<label for=url_image_wide class="col-sm-2 control-label">宽屏形象图</label>
				<div class=col-sm-10>
					<p class=help-block>该图用于桌面电脑等宽屏设备；请上传大小在2M以内，边长不超过2048px的jpg/png图片</p>

					<?php $name_to_upload = 'url_image_wide' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" required>

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir="promotion_biz/image_wide" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>
		</fieldset>

		<fieldset class=params data-type="单品折扣,订单折扣">
			<div class=form-group>
				<label for=discount class="col-sm-2 control-label">折扣率</label>
				<div class=col-sm-10>
					<input class=form-control name=discount type=number step=0.01 max=0.99 value="<?php echo set_value('discount') ?>" placeholder="折扣率">
				</div>
			</div>
		</fieldset>

		<fieldset class=params data-type="单品满赠,订单满赠">
			<div class=form-group>
				<label for=present_trigger_amount class="col-sm-2 control-label">赠品触发金额（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=present_trigger_amount type=number step=1 max=99999 value="<?php echo set_value('present_trigger_amount') ?>" placeholder="最高99999">
				</div>
			</div>
			<div class=form-group>
				<label for=present_trigger_count class="col-sm-2 control-label">赠品触发份数（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=present_trigger_count type=number step=1 max=99 value="<?php echo set_value('present_trigger_count') ?>" placeholder="最高99">
				</div>
			</div>
			<div class=form-group>
				<label for=present class="col-sm-2 control-label">赠品</label>
				<div class=col-sm-10>
					<input class=form-control name=present type=text value="<?php echo set_value('present') ?>" placeholder="赠品信息">
				</div>
			</div>
		</fieldset>

		<fieldset class=params data-type="单品满减,订单满减">
			<p class="bg-warning text-warning text-center">“减免金额”及“减免比例”只可填写一项；若两项都填写，将按“减免金额”优惠</p>

			<div class=form-group>
				<label for=reduction_trigger_amount class="col-sm-2 control-label">满减触发金额（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=reduction_trigger_amount type=number step=1 max=99999 value="<?php echo set_value('reduction_trigger_amount') ?>" placeholder="最高99999">
				</div>
			</div>
			<div class=form-group>
				<label for=reduction_amount class="col-sm-2 control-label">减免金额（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=reduction_amount type=number step=1 max=999 value="<?php echo set_value('reduction_amount') ?>" placeholder="最高99999">
				</div>
			</div>
			<div class=form-group>
				<label for=reduction_discount class="col-sm-2 control-label">减免比例</label>
				<div class=col-sm-10>
					<input class=form-control name=reduction_discount type=number step=0.01 max=0.99  value="<?php echo set_value('reduction_discount') ?>" placeholder="例如8折为0.80，3折为0.30，最低0.10">
				</div>
			</div>
		</fieldset>

		<fieldset class=params data-type="单品赠券,订单赠券">
			<div class=form-group>
				<label for=coupon_id class="col-sm-2 control-label">赠送优惠券模板</label>
				<div class=col-sm-10>
					<input class=form-control name=coupon_id type=text value="<?php echo set_value('coupon_id') ?>" placeholder="赠送优惠券模板ID">
				</div>
			</div>
			<div class=form-group>
				<label for=coupon_combo_id class="col-sm-2 control-label">赠送优惠券套餐</label>
				<div class=col-sm-10>
					<input class=form-control name=coupon_combo_id type=text value="<?php echo set_value('coupon_combo_id') ?>" placeholder="赠送优惠券套餐ID">
				</div>
			</div>
		</fieldset>

		<fieldset class=params data-type="单品预售">
			<div class=form-group>
				<label for=deposit class="col-sm-2 control-label">订金/预付款（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=deposit type=number step=1 max=99999 value="<?php echo set_value('deposit') ?>" placeholder="订金/预付款（元）">
				</div>
			</div>
			<div class=form-group>
				<label for=balance class="col-sm-2 control-label">尾款（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=balance type=number step=1 max=99999 value="<?php echo set_value('balance') ?>" placeholder="尾款（元）">
				</div>
			</div>
			<div class=form-group>
				<label for=time_book_start class="col-sm-2 control-label">支付预付款开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_book_start type=datetime value="<?php echo set_value('time_book_start') ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+2days')) ?>">
				</div>
			</div>
			<div class=form-group>
				<label for=time_book_end class="col-sm-2 control-label">支付预付款结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_book_end type=datetime value="<?php echo set_value('time_book_end') ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+5days')) ?>">
				</div>
			</div>
			<div class=form-group>
				<label for=time_complete_start class="col-sm-2 control-label">支付尾款开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_complete_start type=datetime value="<?php echo set_value('time_complete_start') ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+10days')) ?>">
				</div>
			</div>
			<div class=form-group>
				<label for=time_complete_end class="col-sm-2 control-label">支付尾款结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_complete_end type=datetime value="<?php echo set_value('time_complete_end') ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+15days')) ?>">
				</div>
			</div>
		</fieldset>

		<fieldset class=params data-type="单品团购">
			<div class=form-group>
				<label for=groupbuy_order_amount class="col-sm-2 control-label">团购成团订单数（单）</label>
				<div class=col-sm-10>
					<input class=form-control name=groupbuy_order_amount type=number step=1 max=99 value="<?php echo set_value('groupbuy_order_amount') ?>" placeholder="团购成团订单数（单）">
				</div>
			</div>
			<div class=form-group>
				<label for=groupbuy_quantity_max class="col-sm-2 control-label">团购个人最高限量（份/位）</label>
				<div class=col-sm-10>
					<input class=form-control name=groupbuy_quantity_max type=number step=1 min=1 max=9 value="<?php echo set_value('groupbuy_quantity_max') ?>" placeholder="团购个人最高限量（份/位）">
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