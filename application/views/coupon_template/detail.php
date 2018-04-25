<link rel=stylesheet media=all href="/css/detail.css">
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

<script defer src="/js/detail.js"></script>

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
    <ul id=item-actions class=list-unstyled>
        <?php
        // 需要特定角色和权限进行该操作
        if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
            ?>
        <li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a></li>
        <?php endif ?>
    </ul>

	<dl id=list-info class=dl-horizontal>
        <?php
        // 当前项客户端URL
        $item_url = WEB_URL.$this->class_name.'/detail?id='.$item[$this->id_name];
        ?>

        <dt><?php echo $this->class_name_cn ?>链接</dt>
        <dd>
            <span><?php echo $item_url ?></span>
            <a href="<?php echo $item_url ?>" target=_blank>查看</a>
        </dd>

        <dt><?php echo $this->class_name_cn ?>二维码</dt>
        <dd>
            <figure class="qrcode col-xs-12 col-sm-6 col-md-3" data-qrcode-string="<?php echo $item_url ?>"></figure>
        </dd>

		<dt>优惠券模板ID</dt>
		<dd><?php echo $item['template_id'] ?></dd>
		<dt>名称</dt>
		<dd><?php echo $item['name'] ?></dd>
		<dt>说明</dt>
		<dd><?php echo empty($item['description'])? '无': $item['description'] ?></dd>
		<dt>面值</dt>
		<dd>￥ <?php echo $item['amount'] ?></dd>
		<dt>起用金额</dt>
		<dd><?php echo !empty($item['min_subtotal'])? '￥ '.$item['min_subtotal'].'（订单小计）': '不限' ?></dd>

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
				'2天' => '172800',
				'3天' => '259200',
				'7天' => '604800',
				'10天' => '864000',
				'14天' => '1209600',
				'30天' => '2592000',
				'45天' => '3888000',
				'90天' => '7776000',
				'120天' => '10368000',
				'180天/半年' => '15552000',
				'366天/1年' => '31622400',
			);
			$options = array_flip($options);
		?>

        <?php //var_dump($item) ?>
		
		<dt>有效期</dt>
		<dd>
            <!--
            开始时间  <?php echo $item['time_start'] ?>
            结束时间 <?php echo $item['time_end'] ?>
            有效期 <?php echo $options[ $item['period'] ]?>
            -->

            自领取时起<?php echo $options[ $item['period'] ]?>
            <?php echo empty($item['time_start'])? NULL: '<br>不早于 '. date('Y-m-d H:i:s', $item['time_start']) ?>
            <?php echo empty($item['time_end'])? NULL: '<br>不晚于 '. date('Y-m-d H:i:s', $item['time_end']) ?>
		</dd>
		
		<dt>总限量</dt>
		<dd><?php echo empty($item['max_amount'])? '不限': $item['max_amount'].'份'; ?></dd>

		<dt>单个用户限量</dt>
		<dd><?php echo empty($item['max_amount_user'])? '不限': $item['max_amount_user'].'份'; ?></dd>

		<!--
        <dt>限用系统分类</dt>
		<dd><?php echo empty($item['category_id'])? '不限': $category['name']; ?></dd>
		<dt>限用店内分类</dt>
		<dd><?php echo empty($item['category_biz_id'])? '不限': $category_biz['name']; ?></dd>
		<dt>限用商品</dt>
		<dd>
            <?php echo empty($item['item_id'])? '不限': '<a class="btn btn-default btn-lg btn-block" href="'.base_url('item/detail?id='.$commodity['item_id']).'">'.$commodity['name'].'</a>'; ?>
        </dd>
        -->
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