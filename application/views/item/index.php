<link rel=stylesheet media=all href="/css/index.css">
<style>
    #bulk_action {background-color:#fff;position:fixed;left:0;right:0;bottom:98px;height:84px;line-height:84px;overflow:hidden;z-index:101;}
        #bulk_action span:first-child {margin-left:20px;line-height:100%;}
            #bulk_action span:first-child i {font-size:40px;width:40px;height:40px;}

        #bulk_action ul {float:right;height:100%;}
            #bulk_action li {height:100%;}
            #bulk_action button {color:#fff;font-size:26px;width:160px;height:100%;line-height:100%;text-align:center;}

    .color_primary a {color:#ff3649;border-color:#ff3649;}
    .color_info a {color:#1a6eef;border-color:#1a6eef;}
    .color_warning a {color:#ff843c;border-color:#ff843c;}

    .bg_primary {background-color:#ff3649;}
    .bg_info {background-color:#1a6eef;}
    .bg_warning {background-color:#ff843c;}

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
		<a class="btn btn-primary" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">所有</a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>">回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>">创建</a>
		<a class="btn btn-default" title="快速创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create_quick') ?>">快速创建</a>
	</div>
	<?php endif ?>

	<?php if ( empty($this->session->biz_id) ): ?>
	<blockquote>
		<p>您需要成为已入驻企业的员工，或者提交入驻申请，才可进行商品管理</p>
	</blockquote>

	<?php else: ?>
		<?php if ( $count['biz_freight_templates'] === 0 ): ?>
		<blockquote class=row>
			<p>您目前没有运费模板，将为买家包邮。</p>
			<a class="col-xs-12 col-sm-6 col-md-3 btn btn-primary btn-lg" href="<?php echo base_url('freight_template_biz/create') ?>">创建运费模板</a>
		</blockquote>
		<?php endif ?>

		<?php if ( empty($items) ): ?>
		<blockquote class=row>
			<p>您的货架空空如也，快点添加商品吧！</p>
			<a class="col-xs-12 col-sm-6 col-md-3 btn btn-primary btn-lg" href="<?php echo base_url('item/create') ?>">创建一个</a>
		</blockquote>

		<?php else: ?>
		<form method=get target=_blank>
            <div id=bulk_action>
                <span><i class="fa fa-circle-o" aria-hidden=true></i></span>
                <span>全选</span>
                <ul class=horizontal>
                    <li class=bg_info>
                        <button formaction="<?php echo base_url($this->class_name.'/publish') ?>" type=submit>上架</button>
                    </li>
                    <li class=bg_warning>
                        <button formaction="<?php echo base_url($this->class_name.'/suspend') ?>" type=submit>下架</button>
                    </li>
                    <li class=bg_primary>
                        <button formaction="<?php echo base_url($this->class_name.'/delete') ?>" type=submit>删除</button>
                    </libg_primary>
                </ul>
            </div>

            <ul id=item-list class=row>
                <?php foreach ($items as $item): ?>
                <li>

                    <a href="<?php echo base_url($this->class_name.'/detail?id='.$item[$this->id_name]) ?>">
                        <p><?php echo $this->class_name_cn ?>ID <?php echo $item[$this->id_name] ?></p>
                        <p>商品名称 <?php echo $item['name'] ?></p>
                        <p>商城价/现价 ￥<?php echo $item['price'] ?></p>
                        <p>状态 <?php echo $item['status'] ?></p>
                    </a>

                    <div class="item-actions">
                        <span>
                            <input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
                        </span>

                        <ul class=horizontal>
                            <li class=color_warning><a title="规格管理" href="<?php echo base_url('sku/index?item_id='.$item['item_id']) ?>" target=_blank>规格 <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>

                        <?php
                            // 需要特定角色和权限进行该操作
                            if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
                        ?>
                            <?php if ( !empty($item['time_publish']) ): ?>
                            <li><a title="下架" href="<?php echo base_url($this->class_name.'/suspend?ids='.$item[$this->id_name]) ?>" target=_blank>下架</a></li>
                            <?php endif ?>

                            <?php if ( !empty($item['time_suspend']) ): ?>
                            <li><a title="上架" href="<?php echo base_url($this->class_name.'/publish?ids='.$item[$this->id_name]) ?>" target=_blank>上架</a></li>

                                <?php if ( empty($item['time_delete']) ): ?>
                            <li><a title="删除" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank>删除</a></li>
                                <?php endif ?>
                            <?php endif ?>

                            <li class=color_primary><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank>编辑</a></li>
                        <?php endif ?>
                        </ul>
                    </div>

                </li>
                <?php endforeach ?>
            </ul>

		</form>
		<?php endif ?>

	<?php endif ?>
</div>