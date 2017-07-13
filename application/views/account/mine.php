<style>
	#content h2 {font-size:20px;}
	#content li {text-align:center;}
		#content li i {display:block;}
	
		#general-actions li {text-align:left;}
	/* 宽度在640像素以上的设备 */
	@media only screen and (min-width:641px)
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
	<div id=user-info>
		<a title="我的用户资料" href="<?php echo base_url('user/mine') ?>">
			<?php $username = !empty($this->session->nickname)? $this->session->nickname: $this->session->mobile; ?>
			<h2><?php echo $username ?></h2>
		</a>
	</div>

	<section id=user-records>
		<ul class="row">
			<li class=col-xs-4><a title="收藏宝贝" href="<?php echo base_url('fav_item') ?>">收藏宝贝</a></li>
			<li class=col-xs-4><a title="关注店铺" href="<?php echo base_url('fav_biz') ?>">关注店铺</a></li>
			<li class=col-xs-4><a title="我的足迹" href="<?php echo base_url('footprint') ?>">我的足迹</a></li>
		</ul>
	</section>

	<section id=order-records>
		<h2>我的订单</h2>
		<ul id=list-order class="row">
			<li class=col-xs-2>
				<i class="fa fa-credit-card-alt" aria-hidden="true"></i>
				<a title="待付款订单" href="<?php echo base_url('order?status=create') ?>">待付款</a>
			</li>
			<li class=col-xs-2>
				<i class="fa fa-archive" aria-hidden="true"></i>
				<a title="待发货订单" href="<?php echo base_url('order?status=pay') ?>">待发货</a>
			</li>
			<li class=col-xs-2>
				<i class="fa fa-truck" aria-hidden="true"></i>
				<a title="待收货订单" href="<?php echo base_url('order?status=deliver') ?>">待收货</a>
			</li>
			<li class=col-xs-2>
				<i class="fa fa-thumbs-up" aria-hidden="true"></i>
				<a title="待评价订单" href="<?php echo base_url('order?status=finish') ?>">待评价</a>
			</li>
			<li id=order-refund class=col-xs-2>
				<i class="fa fa-undo" aria-hidden="true"></i>
				<a title="退款订单" href="<?php echo base_url('order?status=refund') ?>">退款/售后</a>
			</li>
			<li id=order-all class=col-xs-2>
				<i class="fa fa-undo" aria-hidden="true"></i>
				<a title="全部订单" href="<?php echo base_url('order') ?>">全部</a>
			</li>
		</ul>
	</section>
	
	<section id=assets-records>
		<ul class="row">
			<li class=col-xs-3><a title="我的钱包" href="<?php echo base_url('balance/mine') ?>">我的钱包</a></li>
			<li class=col-xs-3><a title="我的卡券" href="<?php echo base_url('coupon/mine') ?>">我的卡券</a></li>
			<li class=col-xs-3><a title="我的积分" href="<?php echo base_url('credit/mine') ?>">我的积分</a></li>
			<li class=col-xs-3><a title="我的地址" href="<?php echo base_url('address/mine') ?>">我的地址</a></li>
		</ul>
	</section>

	<section id=general-actions>
		<ul>
			<li><a title="邀请好友" href="<?php echo base_url('invite/mine') ?>">邀请好友</a></li>
			<li><a title="关于我们" href="<?php echo base_url('article/about-us') ?>">关于我们</a></li>
			<li><a title="设置" href="<?php echo base_url('setup') ?>">设置</a></li>
			<li><a title="退出账户" id=logout class="btn btn-block btn-danger" href="<?php echo base_url('logout') ?>">退出</a></li>
		</ul>
	</section>
	
</div>