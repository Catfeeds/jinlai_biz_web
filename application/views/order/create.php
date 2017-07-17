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
							<label for=order_id class="col-sm-2 control-label">订单ID</label>
							<div class=col-sm-10>
								<input class=form-control name=order_id type=text value="<?php echo set_value('order_id') ?>" placeholder="订单ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=biz_id class="col-sm-2 control-label">商户ID</label>
							<div class=col-sm-10>
								<input class=form-control name=biz_id type=text value="<?php echo set_value('biz_id') ?>" placeholder="商户ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=user_id class="col-sm-2 control-label">用户ID</label>
							<div class=col-sm-10>
								<input class=form-control name=user_id type=text value="<?php echo set_value('user_id') ?>" placeholder="用户ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=user_ip class="col-sm-2 control-label">用户下单IP地址</label>
							<div class=col-sm-10>
								<input class=form-control name=user_ip type=text value="<?php echo set_value('user_ip') ?>" placeholder="用户下单IP地址" required>
							</div>
						</div>
						<div class=form-group>
							<label for=subtotal class="col-sm-2 control-label">小计（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=subtotal type=text value="<?php echo set_value('subtotal') ?>" placeholder="小计（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=promotion_id class="col-sm-2 control-label">营销活动ID</label>
							<div class=col-sm-10>
								<input class=form-control name=promotion_id type=text value="<?php echo set_value('promotion_id') ?>" placeholder="营销活动ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=discount_promotion class="col-sm-2 control-label">优惠活动折抵金额（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=discount_promotion type=text value="<?php echo set_value('discount_promotion') ?>" placeholder="优惠活动折抵金额（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=coupon_id class="col-sm-2 control-label">优惠券ID</label>
							<div class=col-sm-10>
								<input class=form-control name=coupon_id type=text value="<?php echo set_value('coupon_id') ?>" placeholder="优惠券ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=discount_coupon class="col-sm-2 control-label">优惠券折抵金额（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=discount_coupon type=text value="<?php echo set_value('discount_coupon') ?>" placeholder="优惠券折抵金额（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=credit_id class="col-sm-2 control-label">积分流水ID</label>
							<div class=col-sm-10>
								<input class=form-control name=credit_id type=text value="<?php echo set_value('credit_id') ?>" placeholder="积分流水ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=discount_credit class="col-sm-2 control-label">积分折抵金额（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=discount_credit type=text value="<?php echo set_value('discount_credit') ?>" placeholder="积分折抵金额（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=freight class="col-sm-2 control-label">运费（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=freight type=text value="<?php echo set_value('freight') ?>" placeholder="运费（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=total class="col-sm-2 control-label">应支付金额（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=total type=text value="<?php echo set_value('total') ?>" placeholder="应支付金额（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=discount_teller class="col-sm-2 control-label">改价折抵金额（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=discount_teller type=text value="<?php echo set_value('discount_teller') ?>" placeholder="改价折抵金额（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=teller_id class="col-sm-2 control-label">改价操作者ID</label>
							<div class=col-sm-10>
								<input class=form-control name=teller_id type=text value="<?php echo set_value('teller_id') ?>" placeholder="改价操作者ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=total_payed class="col-sm-2 control-label">实际支付金额（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=total_payed type=text value="<?php echo set_value('total_payed') ?>" placeholder="实际支付金额（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=total_refund class="col-sm-2 control-label">实际退款金额（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=total_refund type=text value="<?php echo set_value('total_refund') ?>" placeholder="实际退款金额（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=addressee_fullname class="col-sm-2 control-label">收件人全名</label>
							<div class=col-sm-10>
								<input class=form-control name=addressee_fullname type=text value="<?php echo set_value('addressee_fullname') ?>" placeholder="收件人全名" required>
							</div>
						</div>
						<div class=form-group>
							<label for=addressee_mobile class="col-sm-2 control-label">收件人手机号</label>
							<div class=col-sm-10>
								<input class=form-control name=addressee_mobile type=text value="<?php echo set_value('addressee_mobile') ?>" placeholder="收件人手机号" required>
							</div>
						</div>
						<div class=form-group>
							<label for=addressee_province class="col-sm-2 control-label">收件人省份</label>
							<div class=col-sm-10>
								<input class=form-control name=addressee_province type=text value="<?php echo set_value('addressee_province') ?>" placeholder="收件人省份" required>
							</div>
						</div>
						<div class=form-group>
							<label for=addressee_city class="col-sm-2 control-label">收件人城市</label>
							<div class=col-sm-10>
								<input class=form-control name=addressee_city type=text value="<?php echo set_value('addressee_city') ?>" placeholder="收件人城市" required>
							</div>
						</div>
						<div class=form-group>
							<label for=addressee_county class="col-sm-2 control-label">收件人区/县</label>
							<div class=col-sm-10>
								<input class=form-control name=addressee_county type=text value="<?php echo set_value('addressee_county') ?>" placeholder="收件人区/县" required>
							</div>
						</div>
						<div class=form-group>
							<label for=addressee_address class="col-sm-2 control-label">收件人详细地址</label>
							<div class=col-sm-10>
								<input class=form-control name=addressee_address type=text value="<?php echo set_value('addressee_address') ?>" placeholder="收件人详细地址" required>
							</div>
						</div>
						<div class=form-group>
							<label for=payment_type class="col-sm-2 control-label">付款方式</label>
							<div class=col-sm-10>
								<input class=form-control name=payment_type type=text value="<?php echo set_value('payment_type') ?>" placeholder="付款方式" required>
							</div>
						</div>
						<div class=form-group>
							<label for=payment_account class="col-sm-2 control-label">付款账号</label>
							<div class=col-sm-10>
								<input class=form-control name=payment_account type=text value="<?php echo set_value('payment_account') ?>" placeholder="付款账号" required>
							</div>
						</div>
						<div class=form-group>
							<label for=payment_id class="col-sm-2 control-label">付款流水号</label>
							<div class=col-sm-10>
								<input class=form-control name=payment_id type=text value="<?php echo set_value('payment_id') ?>" placeholder="付款流水号" required>
							</div>
						</div>
						<div class=form-group>
							<label for=note_user class="col-sm-2 control-label">用户留言</label>
							<div class=col-sm-10>
								<input class=form-control name=note_user type=text value="<?php echo set_value('note_user') ?>" placeholder="用户留言" required>
							</div>
						</div>
						<div class=form-group>
							<label for=note_stuff class="col-sm-2 control-label">员工留言</label>
							<div class=col-sm-10>
								<input class=form-control name=note_stuff type=text value="<?php echo set_value('note_stuff') ?>" placeholder="员工留言" required>
							</div>
						</div>
						<div class=form-group>
							<label for=commission_rate class="col-sm-2 control-label">佣金比例/提成率</label>
							<div class=col-sm-10>
								<input class=form-control name=commission_rate type=text value="<?php echo set_value('commission_rate') ?>" placeholder="佣金比例/提成率" required>
							</div>
						</div>
						<div class=form-group>
							<label for=commission class="col-sm-2 control-label">佣金（元）</label>
							<div class=col-sm-10>
								<input class=form-control name=commission type=text value="<?php echo set_value('commission') ?>" placeholder="佣金（元）" required>
							</div>
						</div>
						<div class=form-group>
							<label for=promoter_id class="col-sm-2 control-label">推广者ID</label>
							<div class=col-sm-10>
								<input class=form-control name=promoter_id type=text value="<?php echo set_value('promoter_id') ?>" placeholder="推广者ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_create class="col-sm-2 control-label">创建时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_create type=text value="<?php echo set_value('time_create') ?>" placeholder="创建时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_cancel class="col-sm-2 control-label">用户取消时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_cancel type=text value="<?php echo set_value('time_cancel') ?>" placeholder="用户取消时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_expire class="col-sm-2 control-label">自动过期时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_expire type=text value="<?php echo set_value('time_expire') ?>" placeholder="自动过期时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_pay class="col-sm-2 control-label">用户付款时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_pay type=text value="<?php echo set_value('time_pay') ?>" placeholder="用户付款时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_refuse class="col-sm-2 control-label">商家拒绝时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_refuse type=text value="<?php echo set_value('time_refuse') ?>" placeholder="商家拒绝时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_deliver class="col-sm-2 control-label">商家发货时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_deliver type=text value="<?php echo set_value('time_deliver') ?>" placeholder="商家发货时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_confirm class="col-sm-2 control-label">用户确认时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_confirm type=text value="<?php echo set_value('time_confirm') ?>" placeholder="用户确认时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_confirm_auto class="col-sm-2 control-label">系统确认时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_confirm_auto type=text value="<?php echo set_value('time_confirm_auto') ?>" placeholder="系统确认时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_comment class="col-sm-2 control-label">用户评价时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_comment type=text value="<?php echo set_value('time_comment') ?>" placeholder="用户评价时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_refund class="col-sm-2 control-label">商家退款时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_refund type=text value="<?php echo set_value('time_refund') ?>" placeholder="商家退款时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_delete class="col-sm-2 control-label">用户删除时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_delete type=text value="<?php echo set_value('time_delete') ?>" placeholder="用户删除时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_edit class="col-sm-2 control-label">最后操作时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_edit type=text value="<?php echo set_value('time_edit') ?>" placeholder="最后操作时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=operator_id class="col-sm-2 control-label">最后操作者ID</label>
							<div class=col-sm-10>
								<input class=form-control name=operator_id type=text value="<?php echo set_value('operator_id') ?>" placeholder="最后操作者ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=invoice_status class="col-sm-2 control-label">发票状态</label>
							<div class=col-sm-10>
								<input class=form-control name=invoice_status type=text value="<?php echo set_value('invoice_status') ?>" placeholder="发票状态" required>
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