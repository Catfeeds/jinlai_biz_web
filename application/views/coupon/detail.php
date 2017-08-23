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
				<dt>优惠券ID</dt>
		<dd><?php echo $item['coupon_id'] ?></dd>
		<dt>所属用户ID</dt>
		<dd><?php echo $item['user_id'] ?></dd>
		<dt>所属优惠券模板ID</dt>
		<dd><?php echo $item['template_id'] ?></dd>
		<dt>限定系统分类ID</dt>
		<dd><?php echo $item['category_id'] ?></dd>
		<dt>所属/限定商家ID</dt>
		<dd><?php echo $item['biz_id'] ?></dd>
		<dt>限定商家分类ID</dt>
		<dd><?php echo $item['category_biz_id'] ?></dd>
		<dt>限定商品ID</dt>
		<dd><?php echo $item['item_id'] ?></dd>
		<dt>名称</dt>
		<dd><?php echo $item['name'] ?></dd>
		<dt>说明</dt>
		<dd><?php echo $item['description'] ?></dd>
		<dt>面额（元）</dt>
		<dd><?php echo $item['amount'] ?></dd>
		<dt>最低订单小计（元）</dt>
		<dd><?php echo $item['min_subtotal'] ?></dd>
		<dt>开始时间</dt>
		<dd><?php echo $item['time_start'] ?></dd>
		<dt>结束时间</dt>
		<dd><?php echo $item['time_end'] ?></dd>
		<dt>失效时间</dt>
		<dd><?php echo $item['time_expire'] ?></dd>
		<dt>所属订单ID</dt>
		<dd><?php echo $item['order_id'] ?></dd>
		<dt>使用时间</dt>
		<dd><?php echo $item['time_used'] ?></dd>
		<dt>创建时间</dt>
		<dd><?php echo $item['time_create'] ?></dd>
		<dt>删除时间</dt>
		<dd><?php echo $item['time_delete'] ?></dd>
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