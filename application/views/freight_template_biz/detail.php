<link rel=stylesheet media=all href="/css/detail.css">
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
		/*
		// 仅显示适用于当前类型的参数
		var div_to_show = '<?php echo $item['type'] ?>';
		$('[data-type*="' + div_to_show + '"]').show();
		
		var fieldset_to_show = '<?php echo $item['type_actual'] ?>';
		$('[data-type*="' + fieldset_to_show + '"]').show();
		*/
		
		// 仅显示适用于当前类型的参数
		var fieldset_to_show = '<?php echo $item['type'] ?>';
		$('[data-type*="' + fieldset_to_show + '"]').show();
		
		// 显示物流配送类型
		var type_actual = '<?php echo $item['type_actual'] ?>';
		$('.type-actual').text(type_actual);
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
    ?>
    <ul id=item-actions class=list-unstyled>
        <?php
        // 需要特定角色和权限进行该操作
        if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
            ?>
            <li class="col-xs-12">
                <a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a>
            </li>
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
			<dd><?php echo $item['period_valid'] / 86400 ?>天</dd>
			<dt>过期退款比例</dt>
			<dd><?php echo $item['expire_refund_rate'] * 100?>%</dd>
		</dl>
	</div>
	
	<div data-type="物流配送" class="dl-horizontal params well">
		<dl class="dl-horizontal">
			<dt>运费计算方式</dt>
			<dd><?php echo $item['type_actual'] ?></dd>
			<?php
				$options = array(
					'1小时' => '3600',
					'2小时' => '7200',
					'3小时' => '10800',
					'4小时' => '14400',
					'6小时' => '21600',
					'8小时' => '28800',
					'12小时' => '43200',
					'24小时/1天' => '86400',
					'48小时/2天' => '172800',
					'72小时/3天' => '259200',
					'5天' => '432000',
					'7天' => '604800',
					'10天' => '864000',
					'14天' => '1209600',
					'30天' => '2592000',
					'45天' => '3888000',
				);
				$options = array_flip($options);
			?>
			<dt>最晚发货时间</dt>
			<dd><?php echo $options[ $item['time_latest_deliver'] ] ?></dd>
		</dl>

		<p class="bg-info text-info text-center">计量单位为“件”（计件时）、“KG”（计净重/毛重/体积重时）</p>
		<p>
			<span class=type-actual></span>
			前<em><?php echo $item['start_amount'] ?></em>单位以内<em>￥<?php echo $item['fee_start'] ?></em>，超出后每单位<em>￥<?php echo $item['fee_unit'] ?></em>；每单最高<em><?php echo $item['max_amount'] ?></em>单位，满<?php echo $item['exempt_amount'] ?>单位包邮，满<?php echo $item['exempt_subtotal'] ?>元包邮。
		</p>
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