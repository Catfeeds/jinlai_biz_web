<link rel=stylesheet media=all href="/css/index.css">
<style>
    .order-figures {color:#c9caca;margin:50px -20px 0;}
    .order-figures>li {font-size:22px;border-right:1px solid #c9caca;padding:0 42px;}
    .order-figures>li:last-child {border:0;}
    .order-figures span {font-size:30px;color:#3e3a39;margin-top:12px;margin-left:-5px;display:block;}
    .order-figures li:first-child>span {color:#c9caca;}

    .item-actions li {float:right;}
    .reprice li:first-child a {color:#ff3649;border-color:#ff3649;}
    .accept li:first-child a {color:#ff843c;border-color:#ff843c;}
    .deliver li:first-child a {color:#1a6eef;border-color:#1a6eef;}
    .reprice, .accept, .deliver {background:url('/media/order/daifukuan@3x.png') no-repeat center bottom;height:94px;background-size:100% 26px;margin-left:-20px;margin-right:-20px;padding:0 20px;}
    .accept {background-image:url('/media/order/daijiedan@3x.png');}
    .deliver {background-image:url('/media/order/daifahuo@3x.png');}

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

<script defer src="/js/index.js"></script>

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
		<a class="btn btn-primary" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">所有</a>
	</div>

        <?php
            $status = $this->input->get('status');
            if ( ! empty($status) && count($items) > 1):
        ?>
        <div id=primary_actions class=action_bottom>
            <span id=enter_bulk>
                <i class="fa fa-pencil-square-o" aria-hidden=true></i>批量
            </span>
        </div>
        <?php endif ?>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>没有任何<?php echo $this->class_name_cn ?>被发起</p>
	</blockquote>

	<?php else: ?>
	<form method=get target=_blank>
        <?php
        $status = $this->input->get('status');
        if (!empty($status) && count($items) > 1):
        ?>
            <div id=bulk_action class=action_bottom>
            <span id=bulk_selector data-bulk-selector=off>
                <i class="fa fa-circle-o" aria-hidden=true></i>全选
            </span>
                <span id=exit_bulk>取消</span>
                <ul class=horizontal>
                    <li>
                        <button formaction="<?php echo base_url($this->class_name.'/note') ?>" type=submit>备注</button>
                    </li>

                    <?php if ($status === '待处理'): ?>
                        <li>
                            <button formaction="<?php echo base_url($this->class_name.'/refuse') ?>" type=submit>拒绝</button>
                        </li>
                        <li>
                            <button class=bg_primary formaction="<?php echo base_url($this->class_name.'/accept') ?>" type=submit>同意</button>
                        </li>
                    <?php endif ?>

                    <?php if ($status === '待退货'): ?>
                        <li>
                            <button class=bg_primary formaction="<?php echo base_url($this->class_name.'/confirm') ?>" type=submit>收货</button>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        <?php endif ?>

        <ul id=item-list class=row>
            <?php
                foreach ($items as $item):
                $status = $item['status'];
            ?>
            <li>
                <span class=item-status><?php echo $status ?></span>
                <a href="<?php echo base_url($this->class_name.'/detail?id='.$item[$this->id_name]) ?>">
                    <p><?php echo $this->class_name_cn ?>ID <?php echo $item[$this->id_name] ?></p>
                    <p>下单时间 <?php echo date('Y-m-d H:i:s', $item['time_create']) ?></p>

                    <ul class="order-figures row">
                        <li class="col-xs-4">申请金额<span>￥<?php echo $item['total_applied'] ?></span></li>
                        <li class="col-xs-4">同意金额<span>￥<?php echo $item['total_approved'] ?></span>
                        <li class="col-xs-4">已退金额<span<?php echo ($item['total_payed'] === '0.00')? ' style="color:#c9caca"': NULL ?>>￥<?php echo $item['total_payed'] ?></span>
                    </ul>
                </a>

                <div class=item-actions>
		            <span>
		                <input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
		            </span>

                    <ul class=horizontal>
                        <?php
                        // 需要特定角色和权限进行该操作
                        if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
                            $status = $item['status'];
                        ?>
                            <li><a title="备注" href="<?php echo base_url($this->class_name.'/note?ids='.$item[$this->id_name]) ?>" target=_blank>备注</a></li>

                            <?php if ($status === '待处理'): ?>
                            <li><a title="拒绝" href="<?php echo base_url($this->class_name.'/refuse?ids='.$item[$this->id_name]) ?>" target=_blank>拒绝</a></li>
                            <li><a title="同意" href="<?php echo base_url($this->class_name.'/accept?ids='.$item[$this->id_name]) ?>" target=_blank>同意</a></li>
                            <?php endif ?>

                            <?php if ($status === '待退货'): ?>
                            <li><a title="收货" href="<?php echo base_url($this->class_name.'/confirm?ids='.$item[$this->id_name]) ?>" target=_blank>收货</a></li>
                            <?php endif ?>
                        <?php endif ?>
                    </ul>
                </div>

            </li>
            <?php endforeach ?>
        </ul>

	</form>
	<?php endif ?>
</div>