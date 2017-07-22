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
	</div>
	<?php endif ?>
	
	<ul class=list-unstyled>
		<?php
		// 需要特定角色和权限进行该操作
		if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
		?>
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
		<?php endif ?>
	</ul>
	
	<h2>基本信息</h2>
	
	<ul class="list-horizontal well">
		<li>状态 <strong><?php echo $item['status'] ?></strong></li>
		<li>退款 <?php echo $item['refund_status'] ?></li>
		<li>发票 <?php echo $item['invoice_status'] ?></li>
		<li>用户留言 <?php echo $item['note_user'] ?></li>
		<li>员工留言 <?php echo $item['note_stuff'] ?></li>
	</ul>

	<dl id=list-brief class=dl-horizontal>
		<dt>订单ID</dt>
		<dd><?php echo $item['order_id'] ?></dd>
		<dt>用户ID</dt>
		<dd class=row>
			<?php echo $item['user_id'] ?>
			<a class="col-xs-12 col-sm-6 col-md-3 btn btn-info btn-lg" href="<?php echo base_url('user/detail?id='.$item['user_id']) ?>" target=new>
				<i class="fa fa-info-circle fa-fw" aria-hidden=true></i>用户资料
			</a>
		</dd>
		<dt>用户下单IP地址</dt>
		<dd><?php echo $item['user_ip'] ?></dd>
		<dt>小计</dt>
		<dd>￥ <?php echo $item['subtotal'] ?></dd>
		<dt>营销活动ID</dt>
		<dd><?php echo $item['promotion_id'] ?></dd>
		<dt>优惠活动折抵金额</dt>
		<dd>￥ <?php echo $item['discount_promotion'] ?></dd>
		<dt>优惠券ID</dt>
		<dd><?php echo $item['coupon_id'] ?></dd>
		<dt>优惠券折抵金额</dt>
		<dd>￥ <?php echo $item['discount_coupon'] ?></dd>
		<dt>积分流水ID</dt>
		<dd><?php echo $item['credit_id'] ?></dd>
		<dt>积分折抵金额</dt>
		<dd>￥ <?php echo $item['discount_credit'] ?></dd>
		<dt>运费</dt>
		<dd>￥ <?php echo $item['freight'] ?></dd>
		<dt>应支付金额</dt>
		<dd><strong>￥ <?php echo $item['total'] ?></strong></dd>
		<dt>改价折抵金额</dt>
		<dd>￥ <?php echo $item['discount_teller'] ?></dd>
		<dt>改价操作者ID</dt>
		<dd><?php echo $item['teller_id'] ?></dd>
		
		<?php if ( !empty($item['time_pay']) ): ?>
		<dt>实际支付金额</dt>
		<dd><strong>￥ <?php echo $item['total_payed'] ?></strong></dd>
		<?php endif ?>
		
		<?php if ( !empty($item['time_refund']) ): ?>
		<dt>实际退款金额</dt>
		<dd><strong>￥ <?php echo $item['total_refund'] ?></strong></dd>
		<?php endif ?>
	</dl>

	<?php if ( !empty($item['time_pay']) ): ?>
	<section>
		<h2>支付信息</h2>
		<dl id=list-payment class=dl-horizontal>
			<dt>付款方式</dt>
			<dd><?php echo $item['payment_type'] ?></dd>
			<dt>付款流水号</dt>
			<dd><?php echo $item['payment_id'] ?></dd>
			<dt>付款账号</dt>
			<dd><?php echo $item['payment_account'] ?></dd>
		</dl>
	</section>
	
		<?php if ( $item['commission'] !== '0.00' ): ?>
		<section>
			<h2>佣金</h2>
			<dl id=list-commission class=dl-horizontal>
				<dt>佣金比例/提成率</dt>
				<dd><?php echo $item['commission_rate'] * 100 ?>%</dd>
				<dt>佣金</dt>
				<dd>￥ <?php echo $item['commission'] ?></dd>
				<dt>推广者ID</dt>
				<dd><?php echo $item['promoter_id'] ?></dd>
			</dl>
		</section>
		<?php endif ?>
	<?php endif ?>

	<section>
		<h2>收件人</h2>
		<dl id=list-addressee class=dl-horizontal>
			<dt>姓名</dt>
			<dd><?php echo $item['addressee_fullname'] ?></dd>
			<dt>手机号</dt>
			<dd><?php echo $item['addressee_mobile'] ?></dd>
			<dt>省份</dt>
			<dd><?php echo $item['addressee_province'] ?></dd>
			<dt>城市</dt>
			<dd><?php echo $item['addressee_city'] ?></dd>
			<dt>区/县</dt>
			<dd><?php echo $item['addressee_county'] ?></dd>
			<dt>详细地址</dt>
			<dd><?php echo $item['addressee_address'] ?></dd>
		</dl>
	</section>
	
	<section>
		<h2>商品</h2>
		<dl id=list-items class=dl-horizontal>
		
		</dl>
	</section>
	
	<section>
		<h2>交易记录</h2>
		<dl id=list-time class=dl-horizontal>
			<dt>用户下单时间</dt>
			<dd><?php echo date('Y-m-d H:i:s', $item['time_create']) ?></dd>
			<dt>用户取消时间</dt>
			<dd><?php echo empty($item['time_cancel'])? NULL: date('Y-m-d H:i:s', $item['time_cancel']) ?></dd>
			<dt>自动过期时间</dt>
			<dd><?php echo empty($item['time_expire'])? NULL: date('Y-m-d H:i:s', $item['time_expire']) ?></dd>
			<dt>用户付款时间</dt>
			<dd><?php echo empty($item['time_pay'])? NULL: date('Y-m-d H:i:s', $item['time_pay']) ?></dd>
			<dt>商家拒绝时间</dt>
			<dd><?php echo empty($item['time_refuse'])? NULL: date('Y-m-d H:i:s', $item['time_refuse']) ?></dd>
			<dt>商家接单时间</dt>
			<dd><?php echo empty($item['time_accept'])? NULL: date('Y-m-d H:i:s', $item['time_accept']) ?></dd>
			<dt>商家发货时间</dt>
			<dd><?php echo empty($item['time_deliver'])? NULL: date('Y-m-d H:i:s', $item['time_deliver']) ?></dd>
			<dt>用户确认时间</dt>
			<dd><?php echo empty($item['time_confirm'])? NULL: date('Y-m-d H:i:s', $item['time_confirm']) ?></dd>
			<dt>系统确认时间</dt>
			<dd><?php echo empty($item['time_confirm_auto'])? NULL: date('Y-m-d H:i:s', $item['time_confirm_auto']) ?></dd>
			<dt>用户评价时间</dt>
			<dd><?php echo empty($item['time_comment'])? NULL: date('Y-m-d H:i:s', $item['time_comment']) ?></dd>
			<dt>商家退款时间</dt>
			<dd><?php echo empty($item['time_refund'])? NULL: date('Y-m-d H:i:s', $item['time_refund']) ?></dd>

			<?php if ( ! empty($item['operator_id']) ): ?>
			<dt>最后操作时间</dt>
			<dd class=row>
				<?php echo $item['time_edit'] ?>
				<a class="col-xs-12 col-sm-6 col-md-3 btn btn-info btn-lg" href="<?php echo base_url('stuff/detail?user_id='.$item['operator_id']) ?>" target=new>
					<i class="fa fa-info-circle fa-fw" aria-hidden=true></i>查看最后操作者
				</a>
			</dd>
			<?php endif ?>
		</dl>
	</section>

</div>