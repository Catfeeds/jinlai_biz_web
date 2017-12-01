<link rel=stylesheet media=all href="/css/index.css">
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
		<a class="btn btn-primary" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/index?item_id='.$comodity['item_id']) ?>">所有</a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash?item_id='.$comodity['item_id']) ?>">回收站</a>

		<?php if ( !empty($comodity) ): ?>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?item_id='.$comodity['item_id']) ?>">创建</a>
		<?php endif ?>
	</div>
	<?php endif ?>

	<?php if ( !empty($comodity) ): ?>
	<section id=item-info class="row well">
		<figure class="col-xs-4">
			<img src="<?php echo MEDIA_URL.'/item/'.$comodity['url_image_main'] ?>">
		</figure>

        <div class="col-xs-8">
            <h3><?php echo $comodity['name'] ?></h3>
            <p>￥<?php echo $comodity['price'] ?></p>
        </div>
	</section>

    <div id=primary_actions class=action_bottom>
        <?php if ( isset($items) && count($items) > 1): ?>
            <span id=enter_bulk>
            <i class="fa fa-pencil-square-o" aria-hidden=true></i>批量
        </span>
        <?php endif ?>
        <ul class=horizontal>
            <li>
                <a class=bg_primary title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?item_id='.$comodity['item_id']) ?>">创建</a>
            </li>
        </ul>
    </div>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>此商品暂时没有<?php echo $this->class_name_cn ?></p>
	</blockquote>

	<?php else: ?>
	<form method=get target=_blank>
        <?php if (count($items) > 1): ?>
        <div id=bulk_action class=action_bottom>
            <span id="bulk_selector" data-bulk-selector=off>
                <i class="fa fa-circle-o" aria-hidden=true></i>全选
            </span>
            <span id=exit_bulk>取消</span>
            <ul class=horizontal>
                <li>
                    <button class=bg_primary formaction="<?php echo base_url($this->class_name.'/delete') ?>" type=submit>删除</button>
                </li>
            </ul>
        </div>
        <?php endif ?>

        <ul id=item-list class=row>
        <?php foreach ($items as $item): ?>
            <li>

                <a href="<?php echo base_url($this->class_name.'/detail?id='.$item[$this->id_name]) ?>">
                    <p><?php echo $this->class_name_cn ?>ID <?php echo $item[$this->id_name] ?></p>
                    <p>名称 <?php echo $item['name_first'].$item['name_second'].$item['name_third'] ?></p>
                    <p>商城价/现价 ￥<?php echo $item['price'] ?></p>
                    <p>库存 <?php echo $item['stocks'] ?>单位</p>
                </a>

                <div class=item-actions>
                    <span>
                        <input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
                    </span>

                    <ul class=horizontal>
                        <?php
                        // 需要特定角色和权限进行该操作
                        if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
                            ?>
                        <li><a title="删除" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank>删除</a></li>
                        <li class=color_primary><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank>编辑</a></li>
                        <?php endif ?>
                    </ul>
                </div>

            </li>
        <?php endforeach ?>
        </ul>

	</form>
	<?php endif ?>
</div>