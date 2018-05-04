<link rel=stylesheet media=all href="/css/home.css">

<div id=content class=container>
	<?php if ( empty($this->session->biz_id) ): ?>
	<div id=recruiting-tempt>
		<figure>
			<img alt="进来商家招商中" src="<?php echo base_url('/media/home/recruiting.jpg') ?>">
		</figure>
		<p>加入「进来」，让首家品控网购平台上最有消费能力的消费者在你店里疯狂买买买！</p>
	</div>

	<div id=prerequisite>
		<p>准备好以下材料即可开始入驻申请：</p>
		<ul>
			<li>营业执照影印件（彩色原件的扫描件或数码照，下同）</li>
			<li>法人身份证影印件</li>
			<li>对公银行账户（基本户、一般户均可）</li>
		</ul>

		<p>如果负责日常业务对接的不是法人本人，则另需：</p>
		<ul>
			<li>经办人身份证影印件</li>
			<li>授权书 <small><a title="进来商城经办人授权书" href="<?php echo base_url('article/auth-doc-for-join-application') ?>" target=_blank><i class="far fa-info-circle" aria-hidden=true></i> 授权书示例</a></small></li>
		</ul>
	</div>

    <a id=to_admission title="快速创建" class="btn btn-default btn-lg col-xs-6" href="<?php echo base_url('biz/create_quick') ?>">以后补充，先开店吧</a>
    <a id=to_admission title="创建商家" class="btn btn-primary btn-lg col-xs-6" href="<?php echo base_url('biz/create') ?>">准备好了，申请入驻</a>

	<?php else: ?>
	<section id=biz-info>
		<span id=biz-status data-biz_id="<?php echo $biz['biz_id'] ?>">
            <i class="far fa-info-circle" aria-hidden=true></i> <?php echo $biz['status'] ?>
            <?php echo empty($biz['identity_id'])? '未认证': '已认证'; ?>
        </span>

		<a title="商家详情" href="<?php echo base_url('biz/detail?id='.$this->session->biz_id) ?>">
			<?php if ( ! empty($biz['url_logo']) ): ?>
			<figure id=biz-logo>
				<img src="<?php echo MEDIA_URL.'biz/'.$biz['url_logo'] ?>">
			</figure>
			<?php endif ?>

			<h2><?php echo $biz['brief_name'] ?></h2>
			<p><?php echo $biz['tel_public'] ?></p>
		</a>
	</section>

		<?php if ($biz['status'] !== '冻结'): ?>
	<!--
	<section id=order-status>
		<ul class=row>
			<li class="col-xs-4 col-md-2">
				<a title="待付款" href="<?php echo base_url('order?status=待付款') ?>">待付款</a>
			</li>
			<li class="col-xs-4 col-md-2">
				<a title="待确认" href="<?php echo base_url('order?status=待接单') ?>">待接单</a>
			</li>
			<li class="col-xs-4 col-md-2">
				<a title="待发货" href="<?php echo base_url('order?status=待发货') ?>">待发货</a>
			</li>
			<li class="col-xs-4 col-md-2">
				<a title="待收货" href="<?php echo base_url('order?status=待收货') ?>">待收货</a>
			</li>
			<li class="col-xs-4 col-md-2">
				<a title="待评价" href="<?php echo base_url('order?status=待评价') ?>">待评价</a>
			</li>
			<li class="col-xs-4 col-md-2">
				<a title="已完成" href="<?php echo base_url('order?status=待评价') ?>">待评价</a>
			</li>
		</ul>
	</section>
	-->
	<section id=frequent-list>
		<ul class=row>
			<li class="col-xs-3 col-md-2">
				<a title="待接单订单" href="<?php echo base_url('order?status=待接单') ?>">
					<img src="/media/home/daijiedan@3x.png">
					待接单<span class=count><?php echo $count['order_pay'] ?></span>
				</a>
			</li>
			<li class="col-xs-3 col-md-2">
                <a title="待发货订单" href="<?php echo base_url('order?status=待发货') ?>">
                    <img src="/media/home/daifahuo@3x.png">
                    待发货<span class=count><?php echo $count['order_confirm'] ?></span>
                </a>
            </li>
			<li class="col-xs-3 col-md-2">
                <a title="退款/售后" href="<?php echo base_url('refund') ?>" class="bg-warning">
                    <img src="/media/home/shouhou@3x.png">
                    退款/售后<span class=count><?php echo $count['refund'] ?></span>
                </a>
			</li>
			<li class="col-xs-3 col-md-2">
                <a title="商品评价" href="<?php echo base_url('comment_item') ?>">
				    <img src="/media/home/pingjia@3x.png">
				    商品评价
                </a>
			</li>
		</ul>
	</section>

	<section id=function-list>
		<ul class=row>
			<li class="col-xs-4 col-md-2">
				<a title="商品管理" href="<?php echo base_url('item') ?>">
					<img src="/media/home/shangpin@3x.png">
					商品<span class=count><?php echo $count['item'] ?></span>
				</a>
			</li>
            <li class="col-xs-4 col-md-2">
                <a title="店内分类" href="<?php echo base_url('item_category_biz') ?>">
                    <img src="/media/home/fenlei@3x.png">
                    商品分类
                </a>
            </li>
            <li class="col-xs-4 col-md-2">
                <a title="运费模板" href="<?php echo base_url('freight_template_biz') ?>" class="bg-warning">
                    <img src="/media/home/moban@3x.png">
                    运费模板
                </a>
            </li>

            <li class="col-xs-4 col-md-2">
                <a title="优惠券" href="<?php echo base_url('coupon_template') ?>">
                    <img src="/media/home/coupon@3x.png">
                    优惠券
                </a>
            </li>
            <li class="col-xs-4 col-md-2">
                <a title="优惠券包" href="<?php echo base_url('coupon_combo') ?>">
                    <img src="/media/home/combo@3x.png">
                    优惠券包
                </a>
            </li>
            <li class="col-xs-4 col-md-2">
                <a title="商家文章管理" href="<?php echo base_url('article_biz') ?>">
                    <img src="/media/home/shangpin@3x.png">
                    文章
                </a>
            </li>

            <li class="col-xs-4 col-md-2">
                <a title="店内活动" href="<?php echo base_url('promotion_biz') ?>" class="bg-warning">
                    <img src="/media/home/huodong-biz@3x.png">
                    店内活动
                </a>
            </li>
            <li class="col-xs-4 col-md-2">
                <a title="平台活动" href="<?php echo base_url('promotion') ?>" class="bg-warning">
                    <img src="/media/home/huodong-platform@3x.png">
                    平台活动
                </a>
            </li>
            <li class="col-xs-4 col-md-2">
                <a title="店铺装修" href="<?php echo base_url('ornament_biz') ?>">
                    <img src="/media/home/fenlei@3x.png">
                    店铺装修
                </a>
            </li>

            <li class="col-xs-4 col-md-2">
                <a title="门店管理" href="<?php echo base_url('branch') ?>">
                    <img src="/media/home/mendian@3x.png">
                    门店/仓库
                </a>
            </li>
            <li class="col-xs-4 col-md-2">
				<a title="团队管理" href="<?php echo base_url('stuff') ?>" class="bg-warning">
					<img src="/media/home/tuandui@3x.png">
					团队
				</a>
			</li>

		</ul>
	</section>
		<?php endif //if ($biz['status'] !== '冻结'): ?>

	<?php endif ?>
</div>