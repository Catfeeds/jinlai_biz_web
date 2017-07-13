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
	<?php var_dump($this->session->password) ?>
	<?php var_dump($this->session->avatar) ?>
	<?php var_dump($this->session->mobile) ?>
	<?php var_dump($this->session->biz_id) ?>
	<?php var_dump($this->session->role) ?>
	<?php var_dump($this->session->level) ?>

	<?php //if ( empty($this->session->biz_id) ): ?>
	<a title="创建商家" class="btn btn-primary btn-block btn-lg" href="<?php echo base_url('biz/create') ?>">创建商家</a>

	<?php //elseif ( empty($biz) ): ?>
	<p>商家状态异常</p>

	<?php //else: ?>
	<section id=biz-info>
		<a title="商家详情" href="<?php echo base_url('biz/detail?id='.$this->session->biz_id) ?>">
			<ul class=row>
				<li><?php echo $biz['brief_name'] ?></li>
				<li><?php echo $biz['name'] ?></li>
				<li><?php echo $biz['tel_public'] ?></li>
				<li><?php echo $biz['tel_protected_biz'] ?></li>
				<li><?php echo $biz['status'] ?></li>
			</ul>
		</a>
	</section>

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
	
	<section id=function-list>
		<ul class=row>
			<li class="col-xs-3 col-md-2">
				<a title="商品" href="<?php echo base_url('item') ?>">商品</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="商品评价" href="<?php echo base_url('comment_item') ?>">商品评价</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="订单评价" href="<?php echo base_url('comment_item') ?>">订单评价</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="商家营销" href="<?php echo base_url('promotion') ?>">店内营销</a>
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
			<li class="col-xs-3 col-md-2">
				<p>更多功能，持续放出中……</p>
			</li>
		</ul>
	</section>

	<?php //endif ?>
</div>