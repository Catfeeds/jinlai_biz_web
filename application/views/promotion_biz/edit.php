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
				<label for=description class="col-sm-2 control-label">说明</label>
				<div class=col-sm-10>
					<input class=form-control name=description type=text value="<?php echo $item['description'] ?>" placeholder="说明" required>
				</div>
			</div>
			<div class=form-group>
				<label for=time_start class="col-sm-2 control-label">开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_start type=text value="<?php echo $item['time_start'] ?>" placeholder="开始时间" required>
				</div>
			</div>
			<div class=form-group>
				<label for=time_end class="col-sm-2 control-label">结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_end type=text value="<?php echo $item['time_end'] ?>" placeholder="结束时间" required>
				</div>
			</div>
			<div class=form-group>
				<label for=fold_allowed class="col-sm-2 control-label">是否允许折上折</label>
				<div class=col-sm-10>
					<input class=form-control name=fold_allowed type=text value="<?php echo $item['fold_allowed'] ?>" placeholder="是否允许折上折" required>
				</div>
			</div>
			<div class=form-group>
				<label for=type class="col-sm-2 control-label">活动类型</label>
				<div class=col-sm-10>
					<input class=form-control name=type type=text value="<?php echo $item['type'] ?>" placeholder="活动类型" required>
				</div>
			</div>
			<div class=form-group>
				<label for=discount class="col-sm-2 control-label">折扣率</label>
				<div class=col-sm-10>
					<input class=form-control name=discount type=text value="<?php echo $item['discount'] ?>" placeholder="折扣率" required>
				</div>
			</div>
			<div class=form-group>
				<label for=present_trigger_amount class="col-sm-2 control-label">赠品起送份数（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=present_trigger_amount type=text value="<?php echo $item['present_trigger_amount'] ?>" placeholder="赠品起送份数（份）" required>
				</div>
			</div>
			<div class=form-group>
				<label for=present class="col-sm-2 control-label">赠品信息</label>
				<div class=col-sm-10>
					<input class=form-control name=present type=text value="<?php echo $item['present'] ?>" placeholder="赠品信息" required>
				</div>
			</div>
			<div class=form-group>
				<label for=reduction_amount class="col-sm-2 control-label">减免金额（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=reduction_amount type=text value="<?php echo $item['reduction_amount'] ?>" placeholder="减免金额（元）" required>
				</div>
			</div>
			<div class=form-group>
				<label for=reduction_discount class="col-sm-2 control-label">减免比例</label>
				<div class=col-sm-10>
					<input class=form-control name=reduction_discount type=text value="<?php echo $item['reduction_discount'] ?>" placeholder="减免比例" required>
				</div>
			</div>
			<div class=form-group>
				<label for=coupon_id class="col-sm-2 control-label">赠送优惠券ID</label>
				<div class=col-sm-10>
					<input class=form-control name=coupon_id type=text value="<?php echo $item['coupon_id'] ?>" placeholder="赠送优惠券ID" required>
				</div>
			</div>
			<div class=form-group>
				<label for=coupon_combo_id class="col-sm-2 control-label">赠送优惠券套餐ID</label>
				<div class=col-sm-10>
					<input class=form-control name=coupon_combo_id type=text value="<?php echo $item['coupon_combo_id'] ?>" placeholder="赠送优惠券套餐ID" required>
				</div>
			</div>
			<div class=form-group>
				<label for=deposit class="col-sm-2 control-label">订金/预付款（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=deposit type=text value="<?php echo $item['deposit'] ?>" placeholder="订金/预付款（元）" required>
				</div>
			</div>
			<div class=form-group>
				<label for=balance class="col-sm-2 control-label">尾款（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=balance type=text value="<?php echo $item['balance'] ?>" placeholder="尾款（元）" required>
				</div>
			</div>
			<div class=form-group>
				<label for=time_complete_start class="col-sm-2 control-label">支付尾款开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_complete_start type=text value="<?php echo $item['time_complete_start'] ?>" placeholder="支付尾款开始时间" required>
				</div>
			</div>
			<div class=form-group>
				<label for=time_complete_end class="col-sm-2 control-label">支付尾款结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_complete_end type=text value="<?php echo $item['time_complete_end'] ?>" placeholder="支付尾款结束时间" required>
				</div>
			</div>
			<div class=form-group>
				<label for=groupbuy_order_amount class="col-sm-2 control-label">团购成团订单数（单）</label>
				<div class=col-sm-10>
					<input class=form-control name=groupbuy_order_amount type=text value="<?php echo $item['groupbuy_order_amount'] ?>" placeholder="团购成团订单数（单）" required>
				</div>
			</div>
			<div class=form-group>
				<label for=groupbuy_quantity_max class="col-sm-2 control-label">团购个人最高限量（份/位）</label>
				<div class=col-sm-10>
					<input class=form-control name=groupbuy_quantity_max type=text value="<?php echo $item['groupbuy_quantity_max'] ?>" placeholder="团购个人最高限量（份/位）" required>
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