<style>
	#function-list li {text-align:center;}
	#biz-info h2 {font-size:4rem;text-align:center;}

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
	<div id=recruiting-tempt>
		<p>这一部分可以显示简单的平台介绍和招商信息，例如：</p>
		<img alt="进来商家招商中" src="https://biz.517ybang.com/media/home/recruiting.jpg">
		<p class=text-center>加入「进来」，让首家品控网购平台上最有消费能力的消费者在你店里疯狂买买买！</p>
	</div>
	
	<div id=prerequisite class=well>
		<p class=helper-block>准备好以下材料即可开始入驻申请（影印件指彩色原件的扫描件或数码照）：</p>
		<ul>
			<li>营业执照影印件</li>
			<li>法人身份证影印件</li>
			<li>对公银行账户（基本户、一般户均可）</li>
		</ul>
		
		<p>如果负责日常业务对接的不是法人本人，则另需：</p>
		<ul>
			<li>经办人身份证影印件</li>
			<li>授权书 <small><a title="进来商城经办人授权书" href="<?php echo base_url('article/auth_doc_join_application') ?>"><i class="fa fa-info-circle" aria-hidden=true></i> 授权书示例</a></small></li>
		</ul>
	</div>

	<a title="创建商家" class="btn btn-primary btn-block btn-lg" href="<?php echo base_url('biz/create') ?>">开始申请</a>

	<?php elseif ( empty($biz) ): ?>
	<p>员工关系状态异常，请尝试重新登录</p>
	<a class="btn btn-primary btn-block btn-lg" href="<?php echo base_url('logout') ?>">重新登录</a>

	<?php else: ?>
	<section id=biz-info>
		<div class="jumbotron row">
			<a title="商家详情" href="<?php echo base_url('biz/detail?id='.$this->session->biz_id) ?>">
				<h2><?php echo $biz['brief_name'] ?></h2>
				<ul class=row>
					<li><i class="fa fa-building fa-fw" aria-hidden=true></i> <?php echo $biz['name'] ?></li>
					<li><i class="fa fa-phone fa-fw" aria-hidden=true></i> <?php echo $biz['tel_public'] ?></li>
					<li class=text-right><i class="fa fa-info-circle fa-fw" aria-hidden=true></i> <?php echo $biz['status'] ?></li>
				</ul>
			</a>
		</div>
	</section>

		<?php if ($biz['status'] !== '冻结'): ?>
	<!--
	<section id=order-status>
		<ul class=row>
			<li class="col-xs-4 col-md-2">
				<a title="待付款" href="<?php echo base_url('order?status=create') ?>">待付款</a>
			</li>
			<li class="col-xs-4 col-md-2">
				<a title="待确认" href="<?php echo base_url('order?status=pay') ?>">待接单</a>
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
		.count {display:block;}

		#function-list ul {border:1px solid #eee;border-bottom:0;}
			#function-list li {height:8rem;margin-top:-1px;border:1px solid #eee;border-left:0;}
				#function-list li>a {display:block;width:100%;height:100%;text-align:center;}
	</style>

	<section id=function-list>
		<ul class=row>
			<li class="col-xs-3 col-md-2">
				<a title="商品管理" href="<?php echo base_url('item') ?>">
					<span class=count><?php echo $count['item'] ?></span>商品管理
				</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="订单管理" href="<?php echo base_url('order') ?>">
					<span class=count><?php echo $count['order'] ?></span>订单管理
				</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<span class=count><?php echo $count['item_category_biz'] ?></span>
				<a title="店内分类" href="<?php echo base_url('item_category_biz') ?>">商品分类</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<span class=count><?php echo $count['freight_template_biz'] ?></span>
				<a title="运费模板" href="<?php echo base_url('freight_template_biz') ?>">运费模板</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<span class=count><?php echo $count['promotion'] ?></span>
				<a title="平台活动" href="<?php echo base_url('promotion') ?>">平台活动</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<span class=count><?php echo $count['promotion_biz'] ?></span>
				<a title="店内活动" href="<?php echo base_url('promotion_biz') ?>">店内活动</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<span class=count><?php echo $count['coupon_template'] ?></span>
				<a title="优惠券" href="<?php echo base_url('coupon_template') ?>">优惠券</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<span class=count><?php echo $count['coupon_combo'] ?></span>
				<a title="优惠券包" href="<?php echo base_url('coupon_combo') ?>">优惠券包</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<span class=count><?php echo $count['stuff'] ?></span>
				<a title="团队管理" href="<?php echo base_url('stuff') ?>">团队管理</a>
			</li>
		</ul>

		<hr>

		<p class=text-center>更多功能将与客户端同时开放</p>
		<ul class=row>
			<li class="col-xs-3 col-md-2">
				<a title="退款处理" href="<?php echo base_url('refund') ?>">退款处理</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="商品评价" href="<?php echo base_url('comment_item') ?>">商品评价</a>
			</li>
			<li class="col-xs-3 col-md-2">
				<a title="订单评价" href="<?php echo base_url('comment_order') ?>">订单评价</a>
			</li>
		</ul>
	</section>
		<?php endif //if ($biz['status'] !== '冻结'): ?>

	<?php endif ?>
</div>