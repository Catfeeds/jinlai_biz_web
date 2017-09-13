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
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class="btn-group btn-group-justified" role=group>
		<div class=btn-group role=group>
		    <button type=button class="btn btn-default dropdown-toggle" data-toggle=dropdown aria-haspopup=true aria-expanded=false>
				所有 <span class="caret"></span>
		    </button>
		    <ul class=dropdown-menu>
				<li>
					<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">所有</a>
				</li>

		  		<?php
		  		$status_to_mark = array('待接单', '待发货', '待收货', '待评价', '已评价', '已退款');
		  		foreach ($status_to_mark as $status):
		  			// 页面URL
		  			$url = ($status === NULL)? base_url('order'): base_url('order?status='.$status);
		  			// 链接样式
		  			$style_class = ($this->input->get('status') !== $status)? 'btn-default': 'btn-primary';
		  			echo '<li><a class="btn '. $style_class. '" title="'. $status. '订单" href="'. $url. '">'. $status. '</a> </li>';
		  		endforeach;
		  		?>
		    </ul>
		</div>

		<a class="btn btn-warning" title="待接单商品订单" href="<?php echo base_url('order?status=待接单') ?>">待接单</a>
		<a class="btn btn-default" title="待发货商品订单" href="<?php echo base_url('order?status=待发货') ?>">待发货</a>
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
		<?php if ( isset($item['note_user']) ): ?><li>用户留言 <?php echo $item['note_user'] ?></li><?php endif ?>
		<?php if ( isset($item['note_stuff']) ): ?><li>员工留言 <?php echo $item['note_stuff'] ?></li><?php endif ?>
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

		<?php if ( isset($item['promotion_id']) ): ?>
		<dt>营销活动ID</dt>
		<dd><?php echo $item['promotion_id'] ?></dd>
		<dt>优惠活动折抵</dt>
		<dd>￥ <?php echo $item['discount_promotion'] ?></dd>
		<?php endif ?>
		
		<?php if ( isset($item['coupon_id']) ): ?>
		<dt>优惠券ID</dt>
		<dd><?php echo $item['coupon_id'] ?></dd>
		<dt>优惠券折抵</dt>
		<dd>￥ <?php echo $item['discount_coupon'] ?></dd>
		<?php endif ?>
		
		<?php if ( isset($item['credit_id']) ): ?>
		<dt>积分流水ID</dt>
		<dd><?php echo $item['credit_id'] ?></dd>
		<dt>积分折抵</dt>
		<dd>￥ <?php echo $item['credit_payed'] ?></dd>
		<?php endif ?>

		<?php if ( isset($item['freight']) ): ?>
		<dt>运费</dt>
		<dd>￥ <?php echo $item['freight'] ?></dd>
		<?php endif ?>
		
		<?php if ( isset($item['repricer_id']) ): ?>
		<dt>改价折抵</dt>
		<dd>￥ <?php echo $item['discount_reprice'] ?></dd>
		<dt>改价操作者ID</dt>
		<dd><?php echo $item['repricer_id'] ?></dd>
		<?php endif ?>

		<dt>应支付</dt>
		<dd><strong>￥ <?php echo $item['total'] ?></strong></dd>

		<?php if ( !empty($item['time_pay']) ): ?>
		<dt>已支付</dt>
		<dd><strong>￥ <?php echo $item['total_payed'] ?></strong></dd>
		<?php endif ?>
		
		<?php if ( !empty($item['time_refund']) ): ?>
		<dt>实际退款</dt>
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
		<h2>收件地址</h2>
		<dl id=list-addressee class=dl-horizontal>
			<dt>姓名</dt>
			<dd><?php echo $item['fullname'] ?></dd>
			<dt>手机号</dt>
			<dd><?php echo $item['mobile'] ?></dd>
			<dt>地址</dt>
			<dd>
				<?php echo $item['province'] ?> <?php echo $item['city'] ?> <?php echo $item['county'] ?><br>
				<?php echo $item['street'] ?>
			</dd>
		</dl>
	</section>

	<section>
		<h2>订单商品</h2>
		<ul id=list-items>
		<?php foreach ($item['order_items'] as $order_item): ?>
		<li class=row>
			<?php //var_dump($order_item) ?>

			<figure class=col-xs-2>
				<img src="<?php echo $order_item['item_image'] ?>">
			</figure>
			<div class="item-name col-xs-10">
				<h3><?php echo $order_item['name'] ?></h3>
				<?php if ( isset($order_item['slogan']) ): ?>
				<h4><?php echo $order_item['slogan'] ?></h4>
				<?php endif ?>
			</div>
			<div class="col-xs-12 text-right">￥<?php echo $order_item['price'] ?> × <?php echo $order_item['count'] ?></div>

		</li>
		<?php endforeach ?>
		</ul>
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