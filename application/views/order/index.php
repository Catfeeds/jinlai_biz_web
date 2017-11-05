<link rel=stylesheet media=all href="/css/index.css">
<style>
    .order-figures {color:#c9caca;margin:50px -20px 0;}
        .order-figures>li {font-size:22px;border-right:1px solid #c9caca;padding:0 42px;}
            .order-figures>li:last-child {border:0;}
        .order-figures span {font-size:30px;color:#3e3a39;margin-top:12px;margin-left:-5px;display:block;}
            .order-figures li:first-child>span {color:#c9caca;}

                    .reprice li:last-child a {color:#ff3649;border-color:#ff3649;}
                    .accept li:last-child a {color:#ff843c;border-color:#ff843c;}
                    .deliver li:last-child a {color:#1a6eef;border-color:#1a6eef;}

    .item-actions.reprice, .item-actions.accept, .item-actions.deliver {background:url('/media/order/daifukuan@3x.png') no-repeat center bottom;height:94px;background-size:710px 26px;margin-left:-20px;margin-right:-20px;padding:0 20px;}
    .item-actions.accept {background-image:url('/media/order/daijiedan@3x.png');}
    .item-actions.deliver {background-image:url('/media/order/daifahuo@3x.png');}

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
		<li class=active><?php echo $this->class_name_cn ?></li>
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
					<?php $style_class = empty($this->input->get('status') )? 'btn-primary': 'btn-default'; ?>
					<a class="btn <?php echo $style_class ?>" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">所有</a>
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

		<a class="btn <?php echo $this->input->get('status') === '待接单'? 'btn-primary': 'btn-default' ?>" title="待接单商品订单" href="<?php echo base_url('order?status=待接单') ?>">待接单</a>
		<a class="btn <?php echo $this->input->get('status') === '待发货'? 'btn-primary': 'btn-default' ?>" title="待发货商品订单" href="<?php echo base_url('order?status=待发货') ?>">待发货</a>
	</div>
	<?php endif ?>

	<?php if ( empty($this->session->biz_id) ): ?>
	<blockquote>
		<p>您需要成为已入驻企业的员工，或者提交入驻申请，才可进行订单管理</p>
	</blockquote>
	
	<?php elseif ( empty($items) ): ?>
	<blockquote>
		<p>这里空空如也，快点推广您的店铺和产品，让进来用户下单吧</p>
	</blockquote>

	<?php else: ?>
	<form method=get target=_blank>
		<?php
			if ( !empty($this->input->get('status')) ):
				$status = $this->input->get('status');
		?>
		<fieldset>
			<div class=btn-group role=group>
				<button formaction="<?php echo base_url($this->class_name.'/note') ?>" type=submit class="btn btn-default">备注</button>
				<?php if ($status === '待付款'): ?>
				<button formaction="<?php echo base_url($this->class_name.'/reprice') ?>" type=submit class="btn btn-default">改价</button>
				<?php endif ?>

				<?php if ($status === '待接单'): ?>
				<button formaction="<?php echo base_url($this->class_name.'/accept') ?>" type=submit class="btn btn-default">接单</button>
				<button formaction="<?php echo base_url($this->class_name.'/refuse') ?>" type=submit class="btn btn-default">退单</button>
				<?php endif ?>
				
				<?php if ($status === '待发货'): ?>
				<button formaction="<?php echo base_url($this->class_name.'/deliver') ?>" type=submit class="btn btn-default">发货</button>
				<?php endif ?>
			</div>
		</fieldset>
		<?php endif ?>

		<ul id=item-list class=row>
			<?php
				foreach ($items as $item):
					$status = $item['status'];
			?>
			<li>
                <span class=item-status><?php echo $item['status'] ?></span>
                <a href="<?php echo base_url($this->class_name.'/detail?id='.$item[$this->id_name]) ?>">
                    <p><?php echo $this->class_name_cn ?>ID <?php echo $item[$this->id_name] ?></p>
                    <p>下单时间 <?php echo date('Y-m-d H:i:s', $item['time_create']) ?></p>

                    <ul class="order-figures row">
                        <li class="col-xs-4">小计<span>￥<?php echo $item['subtotal'] ?></span></li>
                        <li class="col-xs-4">应支付<span>￥<?php echo $item['total'] ?></span>
                        <li class="col-xs-4">已支付<span>￥<?php echo $item['total_payed'] ?></span>
                    </ul>
                </a>

				<?php
					if ($status !== '已关闭'):
						switch ($status):
							case '待付款':
								$action_class = 'reprice';
								break;
							case '待接单':
								$action_class = 'accept';
								break;
							case '待发货':
								$action_class = 'deliver';
								break;
							default:
								$action_class = NULL;
						endswitch;
				?>
				<div class="item-actions <?php echo $action_class ?>">
					<span>
						<input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
					</span>

					<ul class=horizontal>
					<?php
					// 需要特定角色和权限进行该操作
					if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
					?>
						<li><a title="备注" href="<?php echo base_url($this->class_name.'/note?ids='.$item[$this->id_name]) ?>" target=_blank>备注</a></li>
						<?php if ($status === '待付款'): ?>
						<li><a title="改价" href="<?php echo base_url($this->class_name.'/reprice?ids='.$item[$this->id_name]) ?>" target=_blank>改价</a></li>
						<?php endif ?>

						<?php if ($status === '待接单'): ?>
						<li><a title="退单" href="<?php echo base_url($this->class_name.'/refuse?ids='.$item[$this->id_name]) ?>" target=_blank>退单</a></li>
						<li><a title="接单" href="<?php echo base_url($this->class_name.'/accept?ids='.$item[$this->id_name]) ?>" target=_blank>接单</a></li>
						<?php endif ?>
			
						<?php if ($status === '待发货'): ?>
						<li><a title="发货" href="<?php echo base_url($this->class_name.'/deliver?ids='.$item[$this->id_name]) ?>" target=_blank>发货</a></li>
						<?php endif ?>
						
					<?php endif ?>
					</ul>
				</div>
				<?php endif ?>
			
			</li>
			<?php endforeach ?>
		</ul>

	</form>
	<?php endif ?>
</div>