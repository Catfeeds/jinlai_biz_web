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
			<legend>基本信息</legend>

									<div class=form-group>
							<label for=promotion_id class="col-sm-2 control-label">商家营销活动ID</label>
							<div class=col-sm-10>
								<input class=form-control name=promotion_id type=text value="<?php echo set_value('promotion_id') ?>" placeholder="商家营销活动ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=biz_id class="col-sm-2 control-label">所属商家ID</label>
							<div class=col-sm-10>
								<input class=form-control name=biz_id type=text value="<?php echo set_value('biz_id') ?>" placeholder="所属商家ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=name class="col-sm-2 control-label">名称</label>
							<div class=col-sm-10>
								<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="名称" required>
							</div>
						</div>
						<div class=form-group>
							<label for=description class="col-sm-2 control-label">说明</label>
							<div class=col-sm-10>
								<input class=form-control name=description type=text value="<?php echo set_value('description') ?>" placeholder="说明" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_start class="col-sm-2 control-label">开始时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_start type=text value="<?php echo set_value('time_start') ?>" placeholder="开始时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_end class="col-sm-2 control-label">结束时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_end type=text value="<?php echo set_value('time_end') ?>" placeholder="结束时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=fold_allowed class="col-sm-2 control-label">是否允许折上折</label>
							<div class=col-sm-10>
								<input class=form-control name=fold_allowed type=text value="<?php echo set_value('fold_allowed') ?>" placeholder="是否允许折上折" required>
							</div>
						</div>
						<div class=form-group>
							<label for=type class="col-sm-2 control-label">活动类型</label>
							<div class=col-sm-10>
								<input class=form-control name=type type=text value="<?php echo set_value('type') ?>" placeholder="活动类型" required>
							</div>
						</div>
						<div class=form-group>
							<label for=discount class="col-sm-2 control-label">折扣率</label>
							<div class=col-sm-10>
								<input class=form-control name=discount type=text value="<?php echo set_value('discount') ?>" placeholder="折扣率" required>
							</div>
						</div>
						<div class=form-group>
							<label for=present_trigger_amount class="col-sm-2 control-label">赠品起送份数（份）</label>
							<div class=col-sm-10>
								<input class=form-control name=present_trigger_amount type=text value="<?php echo set_value('present_trigger_amount') ?>" placeholder="赠品起送份数（份）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=present class="col-sm-2 control-label">赠品信息</label>
							<div class=col-sm-10>
								<input class=form-control name=present type=text value="<?php echo set_value('present') ?>" placeholder="赠品信息" required>
							</div>
						</div>
						<div class=form-group>
							<label for=reduction_amount class="col-sm-2 control-label">减免金额（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=reduction_amount type=text value="<?php echo set_value('reduction_amount') ?>" placeholder="减免金额（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=reduction_discount class="col-sm-2 control-label">减免比例</label>
							<div class=col-sm-10>
								<input class=form-control name=reduction_discount type=text value="<?php echo set_value('reduction_discount') ?>" placeholder="减免比例" required>
							</div>
						</div>
						<div class=form-group>
							<label for=coupon_id class="col-sm-2 control-label">赠送优惠券ID</label>
							<div class=col-sm-10>
								<input class=form-control name=coupon_id type=text value="<?php echo set_value('coupon_id') ?>" placeholder="赠送优惠券ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=coupon_combo_id class="col-sm-2 control-label">赠送优惠券套餐ID</label>
							<div class=col-sm-10>
								<input class=form-control name=coupon_combo_id type=text value="<?php echo set_value('coupon_combo_id') ?>" placeholder="赠送优惠券套餐ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=deposit class="col-sm-2 control-label">订金/预付款（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=deposit type=text value="<?php echo set_value('deposit') ?>" placeholder="订金/预付款（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=balance class="col-sm-2 control-label">尾款（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=balance type=text value="<?php echo set_value('balance') ?>" placeholder="尾款（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_complete_start class="col-sm-2 control-label">支付尾款开始时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_complete_start type=text value="<?php echo set_value('time_complete_start') ?>" placeholder="支付尾款开始时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_complete_end class="col-sm-2 control-label">支付尾款结束时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_complete_end type=text value="<?php echo set_value('time_complete_end') ?>" placeholder="支付尾款结束时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=groupbuy_order_amount class="col-sm-2 control-label">团购成团订单数（单）</label>
							<div class=col-sm-10>
								<input class=form-control name=groupbuy_order_amount type=text value="<?php echo set_value('groupbuy_order_amount') ?>" placeholder="团购成团订单数（单）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=groupbuy_quantity_max class="col-sm-2 control-label">团购个人最高限量（份/位）</label>
							<div class=col-sm-10>
								<input class=form-control name=groupbuy_quantity_max type=text value="<?php echo set_value('groupbuy_quantity_max') ?>" placeholder="团购个人最高限量（份/位）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_create class="col-sm-2 control-label">创建时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_create type=text value="<?php echo set_value('time_create') ?>" placeholder="创建时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_delete class="col-sm-2 control-label">删除时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_delete type=text value="<?php echo set_value('time_delete') ?>" placeholder="删除时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_edit class="col-sm-2 control-label">最后操作时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_edit type=text value="<?php echo set_value('time_edit') ?>" placeholder="最后操作时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=creator_id class="col-sm-2 control-label">创建者ID</label>
							<div class=col-sm-10>
								<input class=form-control name=creator_id type=text value="<?php echo set_value('creator_id') ?>" placeholder="创建者ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=operator_id class="col-sm-2 control-label">最后操作者ID</label>
							<div class=col-sm-10>
								<input class=form-control name=operator_id type=text value="<?php echo set_value('operator_id') ?>" placeholder="最后操作者ID" required>
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