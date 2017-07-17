<style>
	#function-list li {text-align:center;}

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

<div id=content class=container>
	<?php if ( empty($this->session->biz_id) ): ?>
	<div>
		<p>这一部分可以显示简单的平台介绍和招商信息，例如：</p>
		<img alt="进来商家招商中" src="https://biz.517ybang.com/media/home/recruiting.jpg">
		<p class=text-center>加入「进来」，让首家品控网购平台上最有消费能力的消费者在你店里疯狂买买买！</p>
	</div>
	<a title="创建商家" class="btn btn-primary btn-block btn-lg" href="<?php echo base_url('biz/create') ?>">申请入驻</a>

	<?php elseif ( empty($biz) ): ?>
	<p>商家状态异常，请<a class="btn btn-primary btn-lg" href="<?php echo base_url('logout') ?>">重新登录</a></p>

	<?php else: ?>
	<section id=biz-info>
		<a title="商家详情" href="<?php echo base_url('biz/detail?id='.$this->session->biz_id) ?>">
			<h2><?php echo $biz['brief_name'] ?></h2>
			<ul class="row text-right">
				<li><?php echo $biz['name'] ?></li>
				<li>消费者服务电话 <?php echo $biz['tel_public'] ?></li>
				<li>经营状态 <?php echo $biz['status'] ?></li>
			</ul>
		</a>
	</section>

		<?php if ($biz['status'] !== '冻结'): ?>
	<!--
	<section id=order-status>
		<ul class=row>
			<li class="col-xs-4 col-md-2">
				<a title="待付款" href="<?php echo base_url('order?status=create') ?>">待付款</a>
			</li>
			<li class="col-xs-4 col-md-2">
				<a title="待确认" href="<?php echo base_url('order?status=pay') ?>">待确认</a>
			</li>
			<li class="col-xs-4 col-md-2">
				<a title="待发货" href="<?php echo base_url('order?status=accept') ?>">待发货</a>
			</li>
			<li class="col-xs-4 col-md-2">
				<a title="待收货" href="<?php echo base_url('order?status=deliver') ?>">待收货</a>
			</li>
			<li class="col-xs-4 col-md-2">
				<a title="待评价" href="<?php echo base_url('order?status=finish') ?>">待评价</a>
			</li>
			<li class="col-xs-4 col-md-2">
				<a title="已完成" href="<?php echo base_url('order?status=comment') ?>">已完成</a>
			</li>
		</ul>
	</section>
	-->
	
	<style>
		#function-list li {height:6rem;}
			#function-list li>a {display:block;width:100%;height:100%;text-align:center;}
	</style>

	<section id=function-list>
		<ul class=row>
			<li class="col-xs-3 col-md-2">
				<a title="员工" href="<?php echo base_url('stuff') ?>">员工管理</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="商品" href="<?php echo base_url('item') ?>">商品管理</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="店内分类" href="<?php echo base_url('item_category_biz') ?>">店内分类</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="商品评价" href="<?php echo base_url('comment_item') ?>">商品评价</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="订单评价" href="<?php echo base_url('comment_item') ?>">订单评价</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="商家营销" href="<?php echo base_url('promotion_biz') ?>">店内营销</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="平台营销" href="<?php echo base_url('promotion') ?>">平台营销</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="优惠券" href="<?php echo base_url('coupon_template') ?>">优惠券</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="优惠券包" href="<?php echo base_url('coupon_combo') ?>">优惠券包</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="运费模板" href="<?php echo base_url('freight_template') ?>">运费模板</a>
			</li>
		</ul>
	</section>
		<?php endif ?>
	<?php endif ?>
</div>