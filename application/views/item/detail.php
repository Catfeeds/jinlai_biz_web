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
		<dt>状态</dt>
		<dd><?php echo $item['status'] ?></dd>
		<dt>商品ID</dt>
		<dd><?php echo $item['item_id'] ?></dd>

		<dt>所属商品分类</dt>
		<dd><?php echo $category['name'] ?></dd>
		<dt>所属品牌ID</dt>
		<dd><?php echo $item['brand_id'] ?></dd>
		<dt>所属商家ID</dt>
		<dd><?php echo $item['biz_id'] ?></dd>
		
		<?php if ( !empty($item['category_biz_id']) ): ?>
		<dt>所属商家分类ID</dt>
		<dd><?php echo $item['category_biz_id'] ?></dd>
		<?php endif ?>
		
		<?php if ( !empty($item['code_biz']) ): ?>
		<dt>商家自定义货号</dt>
		<dd><?php echo $item['code_biz'] ?></dd>
		<?php endif ?>

		<dt>主图</dt>
		<dd><img src="<?php echo $item['url_image_main'] ?>"></dd>

		<?php if ( !empty($item['figure_image_urls']) ): ?>
		<dt>形象图</dt>
		<dd>
			<base href="http://s.handu.com/images/201703/goods_img/">
			<ul class=row>
				<?php
					$figure_image_urls = explode(',', $item['figure_image_urls']);
					foreach($figure_image_urls as $url):
				?>
				<li class="col-xs-6 col-sm-4 col-md-3">
					<img src="<?php echo $url ?>">
				</li>
				<?php endforeach ?>
			</ul>
		</dd>
		<?php endif ?>
		
		<?php if ( !empty($item['figure_video_urls']) ): ?>
		<dt>形象视频</dt>
		<dd><?php echo $item['figure_video_urls'] ?></dd>
		<?php endif ?>

		<dt>商品名称</dt>
		<dd><?php echo $item['name'] ?></dd>
		<dt>商品宣传语/卖点</dt>
		<dd><?php echo $item['slogan'] ?></dd>
		<dt>标签价/原价（元）</dt>
		<dd><del>￥ <?php echo $item['tag_price'] ?></del></dd>
		<dt>商城价/现价（元）</dt>
		<dd>￥ <?php echo $item['price'] ?></dd>
		<dt>销售单位</dt>
		<dd><?php echo $item['unit_name'] ?></dd>
		<dt>净重（KG）</dt>
		<dd><?php echo $item['weight_net'] ?></dd>
		<dt>毛重（KG）</dt>
		<dd><?php echo $item['weight_gross'] ?></dd>
		<dt>体积重（KG）</dt>
		<dd><?php echo $item['weight_volume'] ?></dd>
		<dt>库存量（份）</dt>
		<dd><?php echo $item['stocks'] ?></dd>
		<dt>每单最高限量（份）</dt>
		<dd><?php echo $item['quantity_max'] ?></dd>
		<dt>每单最低限量（份）</dt>
		<dd><?php echo $item['quantity_min'] ?></dd>
		<dt>是否可用优惠券</dt>
		<dd><?php echo ($item['coupon_allowed'] === '1')? '是': '否'; ?></dd>
		<dt>积分抵扣率</dt>
		<dd><?php echo $item['discount_credit'] ?></dd>
		<dt>佣金比例/提成率</dt>
		<dd><?php echo $item['commission_rate'] * 100 ?>%</dd>
		
		<?php if ( ! empty($item['time_to_publish']) ): ?>
		<dt>预定上架时间</dt>
		<dd><?php echo date('Y-m-d H:i:s', $item['time_to_publish']) ?></dd>
		<?php endif ?>

		<?php if ( ! empty($item['time_to_suspend']) ): ?>
		<dt>预定下架时间</dt>
		<dd><?php echo date('Y-m-d H:i:s', $item['time_to_suspend']) ?></dd>
		<?php endif ?>

		<?php if ( ! empty($item['promotion_id']) ): ?>
		<dt>参与的营销活动ID</dt>
		<dd><?php echo $item['promotion_id'] ?></dd>
		<?php endif ?>
	</dl>
	
	<h2>商品描述</h2>
	<section id=description>
		<div class="alert alert-warning" role="alert">
			<p class="text-center">以下仅为内容预览，实际样式以前台相应页面为准。</p>
		</div>
		<?php echo $item['description'] ?>
	</section>

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