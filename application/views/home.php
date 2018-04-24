<style>
	section {background-color:#fff;margin-top:20px;border-radius:20px;overflow:hidden;}
		section li {overflow:hidden;border-right:2px solid #efefef;text-align:center;}
			section li img {width:60px;height:60px;margin:0 auto 18px;}
			section li>a {display:block;width:100%;height:100%;line-height:1;}
		span.count:before {content:"(";}
		span.count:after {content:")";}
		
	#recruiting-tempt {margin-top:20px;}
		#recruiting-tempt figure {border-radius:12px;overflow:hidden;}
		#recruiting-tempt p {font-size:30px;margin-top:30px;text-align:center;}
	#prerequisite {background-color:#fff;margin-top:50px;border-radius:20px;padding:40px 20px 50px;overflow:hidden;}
		#prerequisite p {font-size:30px;font-weight:bold;}
			#prerequisite p:last-child {} {margin-top:40px;}
		#prerequisite ul {margin-top:50px;}
			#prerequisite li {font-size:30px;margin-bottom:30px;}
				#prerequisite li:last-child {margin-bottom:0;}
	#to_admission {margin:110px 0 80px;}

	#biz-info {text-align:center;padding:60px 0 70px;position:relative;}
		#biz-status {color:#9fa0a0;position:absolute;top:30px;right:30px;}
		#biz-logo {background-color:#fff;width:150px;height:150px;line-height:150px;border:2px solid #efefef;border-radius:50%;display:inline-block;text-align:center;overflow:hidden;}
            #biz-logo img {max-width:100%;max-height:100%;width:auto;margin:0 auto;display:inline-block}
		#biz-info h2 {font-size:30px;margin:30px 0;}
		#biz-info p {font-size:26px;line-height:1;margin:0;}

	#frequent-list {padding:45px 0;}
		#frequent-list li:nth-child(4n+0) {border-right:0;}

	#function-list {margin-bottom:60px;}
        #function-list li {margin-bottom:-2px;border-bottom:2px solid #efefef;}
			#function-list li:nth-child(3n+0) {border-right:0;}
		#function-list a {padding:45px 0 50px;}

	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px)
	{
        #function-list li:nth-child(3n+0) {border-right:2px solid #efefef;}
        #function-list li:nth-child(6n+0) {border-right:0;}
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
                <a title="退款/售后" href="<?php echo base_url('refund') ?>">
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