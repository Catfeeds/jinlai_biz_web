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
	<?php if ( empty($item) ): ?>
	<p><?php echo $error ?></p>

	<?php
		else:
			// 需要特定角色和权限进行该操作
			$current_role = $this->session->role; // 当前用户角色
			$current_level = $this->session->level; // 当前用户级别
			$role_allowed = array('管理员', '经理');
			$level_allowed = 30;
			if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
                $status = $item['status'];
			?>
		    <ul id=item-actions class=list-unstyled>
                <li><a title="备注" href="<?php echo base_url($this->class_name.'/note?ids='.$item[$this->id_name]) ?>" target=_blank>备注</a></li>

                <?php if ($status === '待处理'): ?>
                <li><a title="拒绝" href="<?php echo base_url($this->class_name.'/refuse?ids='.$item[$this->id_name]) ?>" target=_blank>拒绝</a></li>
                <li><a title="同意" href="<?php echo base_url($this->class_name.'/accept?ids='.$item[$this->id_name]) ?>" target=_blank>同意</a></li>
                <?php endif ?>

                <?php if ($status === '待退货'): ?>
                <li><a title="收货" href="<?php echo base_url($this->class_name.'/confirm?ids='.$item[$this->id_name]) ?>" target=_blank>收货</a></li>
                <?php endif ?>
		    </ul>
	<?php endif ?>

    <section id=list-items>
        <h2>订单商品</h2>
        <?php $order_item = $item['order_item'] ?>
        <a href="<?php echo base_url('order/detail?id='.$order_item['order_id']) ?>">
            <ul>
                <li class=row>
                    <figure class=col-xs-2>
                        <img src="<?php echo empty($order_item['item_image'])? MEDIA_URL.'sku/'.$order_item['sku_image']: $order_item['item_image'] ?>">
                    </figure>
                    <div class="item-name col-xs-10">
                        <h3><?php echo $order_item['name'] ?></h3>
                        <?php if ( isset($order_item['slogan']) ): ?>
                        <h4><?php echo $order_item['slogan'] ?></h4>
                        <?php endif ?>
                        <div>￥<?php echo $order_item['price'] ?> × <?php echo $order_item['count'] ?></div>

                        <?php if ($order_item['refund_status'] !== '未申请'): ?>
                            <a class="btn btn-default" title="<?php echo $order_item['refund_status'] ?>" href="<?php echo base_url('refund/detail?record_id='.$order_item['record_id']) ?>" target=_blank><?php echo $order_item['refund_status'] ?></a>
                        <?php endif ?>
                    </div>
                </li>
            </ul>
        </a>
    </section>

    <section id="info-user">
        <h2>用户</h2>
        <?php $user = $item['user'] ?>
        <a href="<?php echo base_url('user/detail?id='.$user['user_id']) ?>">
            <div class="row">
                <figure class=col-xs-2>
                    <img src="<?php echo MEDIA_URL.'user/'.$user['avatar'] ?>">
                </figure>
                <div class="item-name col-xs-10">
                    <h3><?php echo $user['nickname'] ?></h3>
                </div>
            </div>
        </a>
    </section>

    <dl id=list-info class=dl-horizontal>
		<dt><?php echo $this->class_name_cn ?>ID</dt>
		<dd><?php echo $item[$this->id_name] ?></dd>

		<dt>相关订单ID</dt>
		<dd><?php echo $item['order_id'] ?></dd>
		<dt>类型</dt>
		<dd><?php echo $item['type'] ?></dd>
		<dt>货物状态</dt>
		<dd><?php echo $item['cargo_status'] ?></dd>
		<dt>原因</dt>
		<dd><?php echo $item['reason'] ?></dd>

		<dt>补充说明</dt>
		<dd>
            <p><?php echo empty($item['description'])? '未填写': $item['description'] ?></p>
        </dd>

        <dt>相关图片</dt>
        <dd>
            <?php if ( empty($item['url_images']) ): ?>
            <p>未上传</p>
            <?php else: ?>
            <ul class=row>
                <?php
                $figure_image_urls = explode(',', $item['url_images']);
                foreach($figure_image_urls as $url):
                    ?>
                    <li class="col-xs-6 col-sm-4 col-md-3">
                        <img src="<?php echo MEDIA_URL.'refund/'.$url ?>">
                    </li>
                <?php endforeach ?>
            </ul>
            <?php endif ?>
        </dd>

		<dt>申请金额</dt>
		<dd>￥ <?php echo $item['total_applied'] ?></dd>

        <?php if ($item['total_approved'] !== '0.00'): ?>
		<dt>同意金额</dt>
		<dd>￥ <?php echo $item['total_approved'] ?></dd>
        <?php endif ?>

        <?php if ($item['total_payed'] !== '0.00'): ?>
        <dt>已退金额</dt>
        <dd>￥ <?php echo $item['total_payed'] ?></dd>
        <?php endif ?>

        <?php if ( ! empty($item['deliver_method'])): ?>
        <dt>发货方式</dt>
		<dd><?php echo $item['deliver_method'] ?></dd>
		<dt>物流服务商</dt>
		<dd><?php echo $item['deliver_biz'] ?></dd>
		<dt>物流运单号</dt>
		<dd><?php echo $item['waybill_id'] ?></dd>
		<dt>状态</dt>
		<dd><?php echo $item['status'] ?></dd>
        <?php endif ?>
	</dl>

	<dl id=list-record class=dl-horizontal>
		<dt>用户创建时间</dt>
		<dd><?php echo date('Y-m-d H:i:s', $item['time_create']) ?></dd>

        <?php if ( ! empty($item['time_cancel']) ): ?>
        <dt>用户取消时间</dt>
        <dd><?php echo date('Y-m-d H:i:s', $item['time_cancel']) ?></dd>
        <?php endif ?>

        <?php if ( ! empty($item['time_close']) ): ?>
        <dt>关闭时间</dt>
        <dd><?php echo date('Y-m-d H:i:s', $item['time_close']) ?></dd>
        <?php endif ?>

        <?php if ( ! empty($item['time_refuse']) ): ?>
        <dt>商家拒绝时间</dt>
        <dd><?php echo date('Y-m-d H:i:s', $item['time_refuse']) ?></dd>
        <?php endif ?>

        <?php if ( ! empty($item['time_accept']) ): ?>
        <dt>商家同意时间</dt>
        <dd><?php echo date('Y-m-d H:i:s', $item['time_accept']) ?></dd>
        <?php endif ?>

        <?php if ( ! empty($item['time_refund']) ): ?>
        <dt>商家退款时间</dt>
        <dd><?php echo date('Y-m-d H:i:s', $item['time_refund']) ?></dd>
        <?php endif ?>

		<?php if ( ! empty($item['time_delete']) ): ?>
		<dt>删除时间</dt>
		<dd><?php echo $item['time_delete'] ?></dd>
		<?php endif ?>

		<?php if ( ! empty($item['operator_id']) ): ?>
		<dt>最后操作时间</dt>
		<dd>
			<?php echo $item['time_edit'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['operator_id']) ?>" target=new>查看最后操作者</a>
		</dd>
		<?php endif ?>
	</dl>
    <?php endif ?>
</div>