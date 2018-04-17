<link rel=stylesheet media=all href="/css/index.css">
<style>
    body {margin-bottom:202px;}
    .action_bottom{bottom:98px;}

	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px)
	{
        body {margin-bottom:0;}
        .action_bottom{bottom:0;}
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
        <?php $style_class = empty($this->input->get('status') )? 'btn-primary': 'btn-default'; ?>
        <a class="btn <?php echo $style_class ?>" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">所有</a>
        <a class="btn <?php echo $this->input->get('status') === 'publish'? 'btn-primary': 'btn-default' ?>" title="已上架商品" href="<?php echo base_url('item?status=publish') ?>">在售中</a>
        <a class="btn <?php echo $this->input->get('status') === 'suspend'? 'btn-primary': 'btn-default' ?>" title="已下架商品" href="<?php echo base_url('item?status=suspend') ?>">已下架</a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>">回收站</a>
	</div>

    <div id=primary_actions class=action_bottom>
        <?php if (count($items) > 1): ?>
        <span id=enter_bulk>
            <i class="far fa-edit"></i>批量
        </span>
        <?php endif ?>

        <ul class=horizontal>
            <li>
                <a class=bg_second title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>">创建</a>
            </li>
            <li>
                <a class=bg_primary title="快速创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create_quick') ?>">快速创建</a>
            </li>
        </ul>
    </div>
	<?php endif ?>

	<?php if ( empty($this->session->biz_id) ): ?>
	<blockquote>
		<p>您需要成为已入驻企业的员工，或者提交入驻申请，才可进行商品管理</p>
	</blockquote>

	<?php else: ?>
		<?php if ( empty($items) ): ?>
		<blockquote class=row>
			<p>您的货架空空如也，快点添加商品吧！</p>
		</blockquote>

		<?php else: ?>
		<form method=get target=_blank>
            <?php if (count($items) > 1): ?>
            <div id=bulk_action class=action_bottom>
                <span id="bulk_selector" data-bulk-selector=off>
                    <i class="far fa-circle"></i>全选
                </span>
                <span id=exit_bulk>取消</span>
                <ul class=horizontal>
                    <li>
                        <button class=bg_third formaction="<?php echo base_url($this->class_name.'/publish') ?>" type=submit>上架</button>
                    </li>
                    <li>
                        <button class=bg_second formaction="<?php echo base_url($this->class_name.'/suspend') ?>" type=submit>下架</button>
                    </li>
                    <li>
                        <button class=bg_primary formaction="<?php echo base_url($this->class_name.'/delete') ?>" type=submit>删除</button>
                    </li>
                </ul>
            </div>
            <?php endif ?>

            <ul id=item-list class=row>
                <?php foreach ($items as $item): ?>
                <li>
                    <span class=item-status><?php echo $item['status'] ?></span>
                    <a href="<?php echo base_url($this->class_name.'/detail?id='.$item[$this->id_name]) ?>">
                        <p><?php echo $this->class_name_cn ?>ID <?php echo $item[$this->id_name] ?></p>
                        <p><?php echo $item['name'] ?></p>
                        <p>
                            ￥<?php echo $item['price'] ?>
                            <?php if ($item['tag_price'] !== '0.00') echo '<del>￥ '.$item['tag_price'].'</del>' ?>
                        </p>
                    </a>

                    <div class="item-actions">
                        <span>
                            <input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
                        </span>

                        <ul class=horizontal>
                            <li class=color_warning><a href="<?php echo base_url('sku/index?item_id='.$item['item_id']) ?>" target=_blank>规格 <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>

                        <?php
                            // 需要特定角色和权限进行该操作
                            if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
                        ?>
                            <?php if ( empty($item['time_delete']) ): ?>
                            <li><a href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank>删除</a></li>
                            <?php endif ?>

                            <?php if ( empty($item['time_publish']) ): ?>
                            <li><a href="<?php echo base_url($this->class_name.'/publish?ids='.$item[$this->id_name]) ?>" target=_blank>上架</a></li>
                            <?php else: ?>
                            <li><a href="<?php echo base_url($this->class_name.'/suspend?ids='.$item[$this->id_name]) ?>" target=_blank>下架</a></li>
                            <?php endif ?>

                            <li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank>编辑</a></li>
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