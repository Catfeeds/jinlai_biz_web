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

<base href="<?php echo $this->media_root ?>">

<script>
	$(function(){
		// 仅显示适用于当前营销活动类型的参数
		var fieldset_to_show = '<?php echo $item['type'] ?>';
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
		<dt>商家营销活动ID</dt>
		<dd><?php echo $item['promotion_id'] ?></dd>
		<dt>活动类型</dt>
		<dd><?php echo $item['type'] ?></dd>
		<dt>名称</dt>
		<dd><?php echo $item['name'] ?></dd>
		<dt>开始时间</dt>
		<dd><?php echo empty($item['time_start'])? '自领取时起': substr(date('Y-m-d H:i:s', $item['time_start']), 0,16); ?></dd>
		<dt>结束时间</dt>
		<dd><?php echo empty($item['time_end'])? '见有效期': substr(date('Y-m-d H:i:s', $item['time_end']), 0,16); ?></dd>
		<dt>说明</dt>
		<dd><?php echo $item['description'] ?></dd>

		<?php if ( !empty($item['url_image']) ): ?>
		<dt>形象图</dt>
		<dd class=row>
			<figure class="col-xs-12 col-sm-6 col-md-4">
				<img src="<?php echo $item['url_image'] ?>">
			</figure>
		</dd>
		<?php endif ?>
		
		<?php if ( !empty($item['url_image_wide']) ): ?>
		<dt>宽屏形象图</dt>
		<dd class=row>
			<figure class="col-xs-12 col-sm-6 col-md-4">
				<img src="<?php echo $item['url_image_wide'] ?>">
			</figure>
		</dd>
		<?php endif ?>

		<dt>是否允许折上折</dt>
		<dd><?php echo ($item['fold_allowed'] === '1')? '是': '否'; ?></dd>
	</dl>

	<dl data-type="单品折扣,订单折扣" class="dl-horizontal params">
		<dt>折扣率</dt>
		<dd><?php echo $item['discount'] * 100 ?>%</dd>
	</dl>

	<dl data-type="单品满赠,订单满赠" class="dl-horizontal params">
		<dt>赠品触发金额</dt>
		<dd>￥ <?php echo $item['present_trigger_amount'] ?></dd>
		<dt>赠品触发份数</dt>
		<dd><?php echo $item['present_trigger_count'] ?> 份</dd>
		<dt>赠品</dt>
		<dd><?php echo $item['present'] ?></dd>
	</dl>

	<dl data-type="单品满减,订单满减" class="dl-horizontal params">
		<dt>满减触发金额</dt>
		<dd>￥ <?php echo $item['reduction_trigger_amount'] ?></dd>
		<dt>满减触发件数</dt>
		<dd><?php echo $item['reduction_trigger_count'] ?></dd>
		<dt>减免金额</dt>
		<dd>￥ <?php echo $item['reduction_amount'] ?></dd>
		<dt>最高减免次数</dt>
		<dd><?php echo $item['reduction_amount_time'] ?> 次</dd>
		<dt>减免比例</dt>
		<dd><?php echo $item['reduction_discount'] * 100 ?>%</dd>
	</dl>

	<dl data-type="单品赠券,订单赠券" class="dl-horizontal params">
		<dt>赠送优惠券模板</dt>
		<dd><?php echo $item['coupon_id'] ?></dd>
		<dt>赠送优惠券套餐</dt>
		<dd><?php echo $item['coupon_combo_id'] ?></dd>
	</dl>

	<dl data-type="单品预售" class="dl-horizontal params">
		<dt>订金/预付款</dt>
		<dd>￥ <?php echo $item['deposit'] ?></dd>
		<dt>尾款</dt>
		<dd>￥ <?php echo $item['balance'] ?></dd>
		<dt>支付预付款开始时间</dt>
		<dd><?php echo empty($item['time_book_start'])? '未设置': date('Y-m-d H:i:s', $item['time_book_start']); ?></dd>
		<dt>支付预付款结束时间</dt>
		<dd><?php echo empty($item['time_book_end'])? '未设置': date('Y-m-d H:i:s', $item['time_book_end']); ?></dd>
		<dt>支付尾款开始时间</dt>
		<dd><?php echo empty($item['time_complete_start'])? '未设置': date('Y-m-d H:i:s', $item['time_complete_start']); ?></dd>
		<dt>支付尾款结束时间</dt>
		<dd><?php echo empty($item['time_complete_end'])? '未设置': date('Y-m-d H:i:s', $item['time_complete_end']); ?></dd>
	</dl>

	<dl data-type="单品团购" class="dl-horizontal params">
		<dt>团购成团订单数</dt>
		<dd><?php echo $item['groupbuy_order_amount'] ?> 单</dd>
		<dt>团购个人最高限量</dt>
		<dd><?php echo $item['groupbuy_quantity_max'] ?> 份/用户</dd>
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