<style>
	.params {display:none;}

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

<script>
	$(function(){
		// 仅显示适用于当前类型的参数
		var div_to_show = '<?php echo $item['type'] ?>';
		$('[data-type*="' + div_to_show + '"]').show();
		
		var fieldset_to_show = '<?php echo $item['type_actual'] ?>';
		$('[data-type*="' + fieldset_to_show + '"]').show();
	});
</script>

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
		<dt>运费模板ID</dt>
		<dd><?php echo $item['template_id'] ?></dd>
		<dt>名称</dt>
		<dd><?php echo $item['name'] ?></dd>
		<dt>类型</dt>
		<dd><?php echo $item['type'] ?></dd>
	</dl>
	
	<div data-type="电子凭证" class="dl-horizontal params well">
		<dl class="dl-horizontal">
			<dt>有效期起始时间</dt>
			<dd><?php echo $item['time_valid_from'] ?></dd>
			<dt>有效期结束时间</dt>
			<dd><?php echo $item['time_valid_end'] ?></dd>
			<dt>有效期</dt>
			<dd><?php echo $item['period_valid'] ?></dd>
			<dt>过期退款比例</dt>
			<dd><?php echo $item['expire_refund_rate'] * 100?>%</dd>
		</dl>
	</div>
	
	<div data-type="物流配送" class="dl-horizontal params well">
		<dt>物流配送类型</dt>
		<dd><?php echo $item['type_actual'] ?></dd>
		<dt>最晚发货时间</dt>
		<dd><?php echo $item['time_latest_deliver'] ?></dd>
		
		<div data-type="计件" class="dl-horizontal params">
			<p>件数前 <?php echo $item['fee_count_amount'] ?>件 费用 ￥<?php echo $item['fee_count_start'] ?>，超出后每件 ￥<?php echo $item['fee_count'] ?> ；每单最高 <?php echo $item['max_count'] ?> 件</p>
		</div>

		<div data-type="净重" class="dl-horizontal params">
			<p>净重前 <?php echo $item['fee_net_amount'] ?>KG 费用 ￥<?php echo $item['fee_net_start'] ?>，超出后每KG ￥<?php echo $item['fee_net'] ?> ；每单最高 <?php echo $item['max_net'] ?> KG</p>
		</div>
		
		<div data-type="毛重" class="dl-horizontal params">
			<p>毛重前 <?php echo $item['fee_gross_amount'] ?>KG 费用 ￥<?php echo $item['fee_gross_start'] ?>，超出后每KG ￥<?php echo $item['fee_gross'] ?> ；每单最高 <?php echo $item['max_gross'] ?> KG</p>
		</div>
		
		<div data-type="体积重" class="dl-horizontal params">
			<p>体积重前 <?php echo $item['fee_volumn_amount'] ?>KG 费用 ￥<?php echo $item['fee_volumn_start'] ?>，超出后每KG ￥<?php echo $item['fee_volume'] ?> ；每单最高 <?php echo $item['max_volume'] ?> KG</p>
		</div>
	</div>

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