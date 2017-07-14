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
		<a class="btn btn-primary" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<fieldset>
			<legend>基本信息</legend>

			<div class=form-group>
				<label for=category_id class="col-sm-2 control-label">所属系统分类ID</label>
				<div class=col-sm-10>
					<select class=form-control name=category_id required>
						<option value="">请选择</option>
						<?php foreach ($categories as $option): ?>
							<option value="<?php echo $option['category_id'] ?>" <?php echo set_select('category_id', $option['category_id']) ?>><?php echo $option['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<?php if ( !empty($brands) ): ?>
			<div class=form-group>
				<label for=brand_id class="col-sm-2 control-label">所属品牌ID</label>
				<div class=col-sm-10>
					<input class=form-control name=brand_id type=text value="<?php echo set_value('brand_id') ?>" placeholder="所属品牌ID">
				</div>
			</div>
			<?php endif ?>

			<?php if ( !empty($biz_categories) ): ?>
			<div class=form-group>
				<label for=category_biz_id class="col-sm-2 control-label">所属商家分类ID</label>
				<div class=col-sm-10>
					<select class=form-control name=category_biz_id>
						<option value="">请选择</option>
						<?php foreach ($biz_categories as $option): ?>
							<option value="<?php echo $option['category_id'] ?>" <?php echo set_select('category_id', $option['category_id']) ?>><?php echo $option['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<?php endif ?>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">商品名称※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="最多30个字符，中英文、数字，不可为纯数字" required>
				</div>
			</div>

			<div class=form-group>
				<label for=slogan class="col-sm-2 control-label">商品宣传语/卖点</label>
				<div class=col-sm-10>
					<input class=form-control name=slogan type=text value="<?php echo set_value('slogan') ?>" placeholder="最多30个字符，中英文、数字，不可为纯数字">
				</div>
			</div>

			<div class=form-group>
				<label for=code_biz class="col-sm-2 control-label">商家自定义货号</label>
				<div class=col-sm-10>
					<input class=form-control name=code_biz type=text value="<?php echo set_value('code_biz') ?>" placeholder="最多20个英文大小写字母、数字">
				</div>
			</div>
			
			<div class=form-group>
				<label for=url_image_main class="col-sm-2 control-label">主图※</label>
				<div class=col-sm-10>
					<input class=form-control name=url_image_main type=text value="<?php echo set_value('url_image_main') ?>" placeholder="主图" required>
				</div>
			</div>
			<div class=form-group>
				<label for=figure_image_urls class="col-sm-2 control-label">形象图</label>
				<div class=col-sm-10>
					<input class=form-control name=figure_image_urls type=text value="<?php echo set_value('figure_image_urls') ?>" placeholder="形象图">
				</div>
			</div>

			<div class=form-group>
				<label for=figure_video_urls class="col-sm-2 control-label">形象视频</label>
				<div class=col-sm-10>
					<input class=form-control name=figure_video_urls type=text value="<?php echo set_value('figure_video_urls') ?>" placeholder="形象视频">
				</div>
			</div>
			
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">商品描述</label>
				<div class=col-sm-10>
					<textarea id=detail_editior name=description rows=10 placeholder="商品描述"><?php echo set_value('description') ?></textarea>

					<!-- ueditor 1.4.3.3 -->
					<link rel="stylesheet" media=all href="<?php echo base_url('ueditor/themes/default/css/ueditor.min.css') ?>">
					<script src="<?php echo base_url('ueditor/ueditor.config.js') ?>"></script>
					<script src="<?php echo base_url('ueditor/ueditor.all.min.js') ?>"></script>
					<script>var ue = UE.getEditor('detail_editior');</script>
				</div>
			</div>

			<div class=form-group>
				<label for=tag_price class="col-sm-2 control-label">标签价/原价（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=tag_price type=number step=0.01 min=0.00 max=99999.99 value="<?php echo set_value('tag_price') ?>" placeholder="保留两位小数，0.00为不显示，最高99999.99">
				</div>
			</div>
			<div class=form-group>
				<label for=price class="col-sm-2 control-label">商城价/现价（元）※</label>
				<div class=col-sm-10>
					<input class=form-control name=price type=number step=0.01 min=0.01 max=99999.99 value="<?php echo set_value('price') ?>" placeholder="保留两位小数，最高99999.99" required>
				</div>
			</div>
			<div class=form-group>
				<label for=unit_name class="col-sm-2 control-label">销售单位</label>
				<div class=col-sm-10>
					<input class=form-control name=unit_name type=text value="<?php echo set_value('unit_name') ?>" placeholder="最多10个字符，例如斤、双、头、件等，默认“份”">
				</div>
			</div>
			<div class=form-group>
				<label for=weight_net class="col-sm-2 control-label">净重（KG）</label>
				<div class=col-sm-10>
					<input class=form-control name=weight_net type=text value="<?php echo set_value('weight_net') ?>" placeholder="净重（KG），最高999.99">
				</div>
			</div>
			<div class=form-group>
				<label for=weight_gross class="col-sm-2 control-label">毛重（KG）</label>
				<div class=col-sm-10>
					<input class=form-control name=weight_gross type=text value="<?php echo set_value('weight_gross') ?>" placeholder="毛重（KG），最高999.99">
				</div>
			</div>
			<div class=form-group>
				<label for=weight_volume class="col-sm-2 control-label">体积重（KG）</label>
				<div class=col-sm-10>
					<input class=form-control name=weight_volume type=text value="<?php echo set_value('weight_volume') ?>" placeholder="体积重（KG），最高999.99">
				</div>
			</div>
			<div class=form-group>
				<label for=stocks class="col-sm-2 control-label">库存量（份）※</label>
				<div class=col-sm-10>
					<input class=form-control name=stocks type=text value="<?php echo set_value('stocks') ?>" placeholder="库存量（份），最多65535" required>
				</div>
			</div>
			<div class=form-group>
				<label for=quantity_max class="col-sm-2 control-label">每单最高限量（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=quantity_max type=text value="<?php echo set_value('quantity_max') ?>" placeholder="每单最高限量（份），0为不限，最高99">
				</div>
			</div>
			<div class=form-group>
				<label for=quantity_min class="col-sm-2 control-label">每单最低限量（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=quantity_min type=text value="<?php echo set_value('quantity_min') ?>" placeholder="每单最低限量（份），最低为1，最高99">
				</div>
			</div>
			<div class=form-group>
				<label for=coupon_allowed class="col-sm-2 control-label">是否可用优惠券※</label>
				<div class=col-sm-10>
					<select class=form-control name=coupon_allowed required>
						<option value=1 <?php echo set_select('coupon_allowed', 1) ?>>允许</option>
						<option value=0 <?php echo set_select('coupon_allowed', 0) ?>>不允许</option>
					</select>
				</div>
			</div>
			<div class=form-group>
				<label for=discount_credit class="col-sm-2 control-label">积分抵扣率</label>
				<div class=col-sm-10>
					<input class=form-control name=discount_credit type=number step=0.01 min=0.00 max=0.50 value="<?php echo set_value('discount_credit') ?>" placeholder="例如允许5%的金额使用积分抵扣则为0.05，10%为0.1，最高0.5">
				</div>
			</div>
			<div class=form-group>
				<label for=commission_rate class="col-sm-2 control-label">佣金比例/提成率</label>
				<div class=col-sm-10>
					<input class=form-control name=commission_rate type=number step=0.01 min=0.00 max=0.50 value="<?php echo set_value('commission_rate') ?>" placeholder="例如提成成交价5%的金额则为0.05，10%为0.1，最高0.5">
				</div>
			</div>
			<div class=form-group>
				<label for=time_to_publish class="col-sm-2 control-label">预定上架时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_to_publish type=text value="<?php echo set_value('time_to_publish') ?>" placeholder="最小精度为分钟；不可晚于当前时间">
				</div>
			</div>
			<div class=form-group>
				<label for=time_to_suspend class="col-sm-2 control-label">预定下架时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_to_suspend type=text value="<?php echo set_value('time_to_suspend') ?>" placeholder="最小精度为分钟；不可晚于当前时间">
				</div>
			</div>

			<?php if ( !empty($biz_promotions) ): ?>
			<div class=form-group>
				<label for=promotion_id class="col-sm-2 control-label">参与的营销活动ID</label>
				<div class=col-sm-10>
					<input class=form-control name=promotion_id type=text value="<?php echo set_value('promotion_id') ?>" placeholder="参与的营销活动ID">
				</div>
			</div>
			<?php endif ?>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>

</div>