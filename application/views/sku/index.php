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

		<?php if ( !empty($comodity) ): ?>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?item_id='.$comodity['item_id']) ?>">创建</a>
		<?php endif ?>
	</div>
	<?php endif ?>

	<?php if ( !empty($comodity) ): ?>
	<section id=item-info class="row well">
		<figcaption><?php echo $comodity['name'] ?></figcaption>
		<figure class="col-xs-12 col-sm-6 col-md-4">
			<img src="<?php echo MEDIA_URL.'/item/'.$comodity['url_image_main'] ?>">
		</figure>
	</section>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>此商品暂时没有<?php echo $this->class_name_cn ?></p>
	</blockquote>

	<?php else: ?>
	<form method=get target=_blank>
		<fieldset>
			<div class=btn-group role=group>
				<button formaction="<?php echo base_url($this->class_name.'/delete') ?>" type=submit class="btn btn-default">删除</button>
			</div>
		</fieldset>

        <ul id=item-list class=row>
            <?php foreach ($items as $item): ?>
                <li>

                    <a href="<?php echo base_url($this->class_name.'/detail?id='.$item[$this->id_name]) ?>">
                        <p><?php echo $this->class_name_cn ?>ID <?php echo $item[$this->id_name] ?></p>
                        <p>一级规格 <?php echo $item['name_first'] ?></p>
                        <p>二级规格 <?php echo $item['name_second'] ?></p>
                        <p>三级规格 <?php echo $item['name_third'] ?></p>
                        <p>商城价/现价 ￥<?php echo $item['price'] ?></p>
                        <p>库存 <?php echo $item['stocks'] ?></p>
                    </a>

                    <div class="item-actions">
                        <span>
                            <input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
                        </span>

                        <ul class=horizontal>
                            <?php
                            // 需要特定角色和权限进行该操作
                            if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
                                ?>
                                <?php if ( empty($item['time_delete']) ): ?>
                                <li><a title="删除" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank>删除</a></li>
                            <?php endif ?>

                                <li class="color_primary"><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank>编辑</a></li>
                            <?php endif ?>
                        </ul>
                    </div>

                </li>
            <?php endforeach ?>
        </ul>

	</form>
	<?php endif ?>
</div>