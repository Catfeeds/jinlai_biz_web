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
	
	<ul class=list-unstyled>
		<?php
		// 需要特定角色和权限进行该操作
		if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
		?>
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
		<?php endif ?>
	</ul>

	<dl id=list-info class=dl-horizontal>
		<dt>规格ID</dt>
		<dd><?php echo $item['sku_id'] ?></dd>
		<dt>所属商品</dt>
		<dd><?php echo $comodity['name'] ?></dd>

		<dt>规格图片</dt>
		<?php if ( !empty($item['url_image']) ): ?>
        <dd>
            <?php $name_to_upload = 'url_image' ?>
            <ul class=upload_preview>
                <li>
                    <figure>
                        <img src="<?php echo $item[$name_to_upload] ?>">
                    </figure>
                </li>
            </ul>
        </dd>
		<?php else: ?>
		<dd>未上传</dd>
		<?php endif ?>

		<dt>一级规格</dt>
		<dd><?php echo $item['name_first'] ?></dd>
		<dt>二级规格</dt>
		<dd><?php echo $item['name_second'] ?></dd>
		<dt>三级规格</dt>
		<dd><?php echo $item['name_third'] ?></dd>

		<dt>价格</dt>
		<dd>￥ <?php echo $item['price'] ?></dd>
		<dt>库存量</dt>
		<dd><?php echo $item['stocks'] ?></dd>

		<dt>物流信息</dt>
		<dd>
			<p class="bg-info text-info text-center">以下3项中若填写了多项，将以毛重为准进行运费计算</p>
			<ul class="list-horizontal row">
				<li class="col-xs-12 col-sm-4">净重 <?php echo ($item['weight_net'] !== '0.00')? $item['weight_net']: '-'; ?> KG</li>
				<li class="col-xs-12 col-sm-4">毛重 <?php echo ($item['weight_gross'] !== '0.00')? $item['weight_gross']: '-'; ?> KG</li>
				<li class="col-xs-12 col-sm-4">体积重 <?php echo ($item['weight_volume'] !== '0.00')? $item['weight_volume']: '-'; ?> KG</li>
			</ul>
		</dd>
	</dl>

	<dl id=list-record class=dl-horizontal>
		<dt>创建时间</dt>
		<dd>
			<?php echo $item['time_create'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['creator_id']) ?>" target=new>查看创建者</a>
		</dd>

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
</div>