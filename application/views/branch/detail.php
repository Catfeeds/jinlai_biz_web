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
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>
	
	<ul class=list-unstyled>
		<?php
		// 需要特定角色和权限进行该操作
		if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
		?>
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
		<?php endif ?>
	</ul>

	<dl id=list-info class=dl-horizontal>
		<dt>名称</dt>
		<dd><?php echo $item['name'] ?></dd>
		<dt>说明</dt>
		<dd><?php echo $item['description'] ?></dd>
		<dt>消费者联系电话</dt>
		<dd><?php echo $item['tel_public'] ?></dd>
		<dt>商务联系手机号</dt>
		<dd><?php echo $item['tel_protected_biz'] ?></dd>
		<dt>订单通知手机号</dt>
		<dd><?php echo $item['tel_protected_order'] ?></dd>
		<dt>休息日</dt>
		<dd><?php echo $item['day_rest'] ?></dd>
		<dt>开放时间</dt>
		<dd><?php echo $item['time_open'] ?>:00</dd>
		<dt>结束时间</dt>
		<dd><?php echo $item['time_close'] ?>:00</dd>
		<dt>主图</dt>
		<dd><?php echo $item['url_image_main'] ?></dd>
		<dt>形象图</dt>
		<dd><?php echo $item['figure_image_urls'] ?></dd>
		<dt>国别</dt>
		<dd><?php echo $item['nation'] ?></dd>
		<dt>省</dt>
		<dd><?php echo $item['province'] ?></dd>
		<dt>市</dt>
		<dd><?php echo $item['city'] ?></dd>
		<dt>区/县</dt>
		<dd><?php echo $item['county'] ?></dd>
		<dt>具体地址</dt>
		<dd><?php echo $item['street'] ?></dd>
		<dt>地区ID</dt>
		<dd><?php echo $item['region_id'] ?></dd>
		<dt>地区</dt>
		<dd><?php echo $item['region'] ?></dd>
		<dt>兴趣点ID</dt>
		<dd><?php echo $item['poi_id'] ?></dd>
		<dt>兴趣点</dt>
		<dd><?php echo $item['poi'] ?></dd>
		<dt>状态</dt>
		<dd><?php echo $item['status'] ?></dd>
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