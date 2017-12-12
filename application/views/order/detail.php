<link rel=stylesheet media=all href="/css/detail.css">
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

<script defer src="/js/detail.js"></script>

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
    ?>
    <ul id=item-actions class=list-unstyled>
        <?php
        // 需要特定角色和权限进行该操作
        if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
            ?>
            <?php
                $status = $item['status'];
                if ($status === '待付款'):
            ?>
            <li><a title="改价" href="<?php echo base_url($this->class_name.'/reprice?ids='.$item[$this->id_name]) ?>" target=_blank>改价</a></li>
            <li><a title="拒绝" href="<?php echo base_url($this->class_name.'/refuse?ids='.$item[$this->id_name]) ?>" target=_blank>拒绝</a></li>
            <?php endif ?>

            <?php if ($status === '待接单'): ?>
            <li><a title="接单" href="<?php echo base_url($this->class_name.'/accept?ids='.$item[$this->id_name]) ?>" target=_blank>接单</a></li>
            <li><a title="拒绝" href="<?php echo base_url($this->class_name.'/refuse?ids='.$item[$this->id_name]) ?>" target=_blank>拒绝</a></li>
            <?php endif ?>

            <?php if ($status === '已拒绝' && !empty($item['time_pay']) && ($item['refund_status'] === '退款中')): ?>
            <li><a title="退款列表" href="<?php echo base_url('refund/index?order_id='.$item[$this->id_name]) ?>" target=_blank>处理退款</a></li>
            <?php endif ?>

            <?php if ($status === '待发货'): ?>
            <li><a title="发货" href="<?php echo base_url($this->class_name.'/deliver?ids='.$item[$this->id_name]) ?>" target=_blank>发货</a></li>
            <?php endif ?>

            <li><a title="备注" href="<?php echo base_url($this->class_name.'/note?ids='.$item[$this->id_name]) ?>" target=_blank>备注</a></li>
        <?php endif ?>
    </ul>

	<h2>基本信息</h2>
    <section class=well>
        <ul>
            <li>订单ID <?php echo $item['order_id'] ?></li>
            <li>用户ID
                <?php echo $item['user_id'] ?>
                <a href="<?php echo base_url('user/detail?id='.$item['user_id']) ?>" target=new>查看资料</a>
            </li>
            <li>下单设备IP地址 <?php echo $item['user_ip'] ?>（高级功能，开放试用）</li>
            <li>状态 <strong><?php echo $item['status'] ?></strong></li>
            <?php if ( !empty($item['refund_status']) ): ?><li>退款状态 <?php echo $item['refund_status'] ?></li><?php endif ?>
            <li>发票 <?php echo $item['invoice_status'] ?></li>

            <?php if ( !empty($item['note_user']) ): ?><li>用户留言 <?php echo $item['note_user'] ?></li><?php endif ?>
            <?php if ( !empty($item['note_stuff']) ): ?><li>员工留言 <?php echo $item['note_stuff'] ?></li><?php endif ?>
        </ul>
    </section>

    <section>
        <h2>收货地址</h2>
        <div id=list-addressee>
            <?php if ( !empty($item['longitude']) && !empty($item['latitude']) ): ?>
                <figure class="row">
                    <figcaption>
                        <p class=help-block>经纬度 <?php echo $item['longitude'] ?>, <?php echo $item['latitude'] ?></p>
                    </figcaption>
                    <div id=map style="height:300px;background-color:#aaa"></div>
                </figure>

                <script src="https://webapi.amap.com/maps?v=1.3&key=bf0fd60938b2f4f40de5ee83a90c2e0e"></script>
                <script src="https://webapi.amap.com/ui/1.0/main.js"></script>
                <script>
                    var lnglat = [<?php echo $item['longitude'] ?>, <?php echo $item['latitude'] ?>];
                    var map = new AMap.Map('map',{
                        center: lnglat,
                        zoom: 16,
                        scrollWheel: false,
                        mapStyle: 'amap://styles/91f3dcb31dfbba6e97a3c2743d4dff88', // 自定义样式
                    });
                    marker = new AMap.Marker({
                        position: lnglat,
                    });
                    marker.setMap(map);

                    // 为BasicControl设置DomLibrary，jQuery
                    AMapUI.setDomLibrary($);
                    AMapUI.loadUI(['control/BasicControl'], function(BasicControl) {
                        // 缩放控件
                        map.addControl(new BasicControl.Zoom({
                            position: 'rb', // 右下角
                        }));
                    });
                </script>
            <?php endif ?>
            <p>
                <?php echo $item['street'] ?><br>
                <?php echo $item['province'] ?> <?php echo $item['city'] ?> <?php echo $item['county'] ?>，<?php echo $item['nation'] ?>
            </p>
            <p>
                <?php if ($this->user_agent['is_mobile']): ?>
                <a class="btn btn-default btn-lg" href="tel:<?php echo $item['mobile'] ?>">
                    <i class="fa fa-phone" aria-hidden=true></i> <?php echo $item['mobile'] ?>
                </a>
                <?php
                    else:
                        echo $item['mobile'];
                    endif;
                ?>
            </p>
            <p><?php echo $item['fullname'] ?></p>
        </div>
    </section>

    <section>
        <h2>订单商品</h2>
        <ul id=list-items>
            <?php foreach ($item['order_items'] as $order_item): ?>
                <li class=row>
                    <figure class=col-xs-2>
                        <img src="<?php echo $order_item['item_image'] ?>">
                    </figure>
                    <div class="item-name col-xs-10">
                        <h3><?php echo $order_item['name'] ?></h3>
                        <?php if ( isset($order_item['slogan']) ): ?>
                            <h4><?php echo $order_item['slogan'] ?></h4>
                        <?php endif ?>
                        <div>￥<?php echo $order_item['price'] ?> × <?php echo $order_item['count'] ?></div>

                        <?php if ($order_item['refund_status'] !== '未申请'): ?>
                        <a class="btn btn-default" title="<?php echo $order_item['refund_status'] ?>" href="<?php echo base_url('refund/detail?order_id='.$item[$this->id_name]) ?>" target=_blank><?php echo $order_item['refund_status'] ?></a>
                        <?php endif ?>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    </section>

	<section>
        <h2>财务信息</h2>

        <dl id=list-brief class=dl-horizontal>
            <dt>商品小计</dt>
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
            <dd>
                <?php echo $item['repricer_id'] ?>
                <a href="<?php echo base_url('stuff/detail?user_id='.$item['operator_id']) ?>" target=new>查看</a>
            </dd>
            <?php endif ?>

            <dt>应支付</dt>
            <dd>￥ <?php echo $item['total'] ?></dd>

            <?php if ( !empty($item['time_pay']) ): ?>
            <dt>已支付</dt>
            <dd>
                <strong <?php echo ($item['total_payed'] < $item['total'])? ' style="color:red"': NULL ?>>￥ <?php echo $item['total_payed'] ?></strong>
            </dd>
            <?php endif ?>

            <?php if ( !empty($item['time_refund']) ): ?>
            <dt>实际退款</dt>
            <dd><strong>￥ <?php echo $item['total_refund'] ?></strong></dd>
            <?php endif ?>
        </dl>

    </section>


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
		<h2>交易记录</h2>
        <p class="help-block">系统将自动清除已关闭或已取消3天（含）以上的订单</p>

		<dl id=list-time class=dl-horizontal>
			<dt>用户下单时间</dt>
			<dd><?php echo date('Y-m-d H:i:s', $item['time_create']) ?></dd>

            <?php if ( isset($item['time_cancel']) ): ?>
			<dt>用户取消时间</dt>
			<dd><?php echo $item['time_cancel'] ?></dd>
            <?php endif ?>

            <?php if ( isset($item['time_expire']) ): ?>
                <dt>自动过期时间</dt>
                <dd><?php echo $item['time_expire'] ?></dd>
            <?php endif ?>

            <?php if ( isset($item['time_pay']) ): ?>
                <dt>用户付款时间</dt>
                <dd><?php echo $item['time_pay'] ?></dd>
            <?php endif ?>

            <?php if ( isset($item['time_refuse']) ): ?>
                <dt>商家拒绝时间</dt>
                <dd><?php echo $item['time_refuse'] ?></dd>
            <?php endif ?>

            <?php if ( isset($item['time_accept']) ): ?>
                <dt>商家接单时间</dt>
                <dd><?php echo $item['time_accept'] ?></dd>
            <?php endif ?>

            <?php if ( isset($item['time_deliver']) ): ?>
                <dt>商家发货时间</dt>
                <dd><?php echo $item['time_deliver'] ?></dd>
            <?php endif ?>

            <?php if ( isset($item['time_confirm']) ): ?>
                <dt>用户确认时间</dt>
                <dd><?php echo $item['time_confirm'] ?></dd>
            <?php endif ?>

            <?php if ( isset($item['time_confirm_auto']) ): ?>
                <dt>系统确认时间</dt>
                <dd><?php echo $item['time_confirm_auto'] ?></dd>
            <?php endif ?>

            <?php if ( isset($item['time_comment']) ): ?>
                <dt>用户评价时间</dt>
                <dd><?php echo $item['time_comment'] ?></dd>
            <?php endif ?>

            <?php if ( isset($item['time_refund']) ): ?>
                <dt>商家退款时间</dt>
                <dd><?php echo $item['time_refund'] ?></dd>
            <?php endif ?>

			<?php if ( ! empty($item['operator_id']) ): ?>
			<dt>最后操作时间</dt>
			<dd class=row>
				<?php echo $item['time_edit'] ?>
				<a href="<?php echo base_url('stuff/detail?user_id='.$item['operator_id']) ?>" target=new>查看最后操作者</a>
			</dd>
			<?php endif ?>
		</dl>
	</section>

</div>