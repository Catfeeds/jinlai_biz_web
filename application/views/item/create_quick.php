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
	<div class="btn-group btn-group-justified" role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">所有</a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>">回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>">创建</a>
		<a class="btn btn-primary" title="快速创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create_quick') ?>">快速创建</a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create-quick form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create_quick', $attributes);
	?>
		<fieldset>
			<p class=help-block>必填项以“※”符号标示</p>

			<div class=form-group>
				<label for=category_id class="col-sm-2 control-label">系统分类※</label>
				<div class=col-sm-10>
					<select class=form-control name=category_id required>
						<option value="">请选择</option>
						<?php foreach ($categories as $option): ?>
							<option value="<?php echo $option['category_id'] ?>" <?php echo set_select('category_id', $option['category_id']) ?>><?php echo $option['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<?php if ( !empty($biz_categories) ): ?>
			<div class=form-group>
				<label for=category_biz_id class="col-sm-2 control-label">店内分类</label>
				<div class=col-sm-10>
					<select class=form-control name=category_biz_id>
						<option value="">可选</option>
						<?php foreach ($biz_categories as $option): ?>
							<option value="<?php echo $option['category_id'] ?>" <?php echo set_select('category_id', $option['category_id']) ?>><?php echo $option['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<?php endif ?>
			
			<?php if ( !empty($brands) ): ?>
			<div class=form-group>
				<label for=brand_id class="col-sm-2 control-label">品牌</label>
				<div class=col-sm-10>
					<input class=form-control name=brand_id type=text value="<?php echo set_value('brand_id') ?>" placeholder="所属品牌ID">
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
				<label for=url_image_main class="col-sm-2 control-label">主图※</label>
				<div class=col-sm-10>
					<p class=help-block>正方形图片视觉效果最佳</p>

                    <?php $name_to_upload = 'url_image_main' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" required>

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="item/image_main" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> data-max-count="1" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
				</div>
			</div>

			<div class=form-group>
				<label for=price class="col-sm-2 control-label">商城价/现价（元）※</label>
				<div class=col-sm-10>
					<input class=form-control name=price type=number step=0.01 min=0.01 max=99999.99 value="<?php echo set_value('price') ?>" placeholder="最高99999.99" required>
				</div>
			</div>

			<div class=form-group>
				<label for=stocks class="col-sm-2 control-label">库存量（单位）※</label>
				<div class=col-sm-10>
					<input class=form-control name=stocks type=number step=1 max=65535 value="<?php echo set_value('stocks') ?>" placeholder="库存量（份），最多65535" required>
				</div>
			</div>

			<div class=form-group>
				<label for=coupon_allowed class="col-sm-2 control-label">是否可用优惠券※</label>
				<div class=col-sm-10>
					<select class=form-control name=coupon_allowed required>
						<option value=1 <?php echo set_select('coupon_allowed', 1) ?>>是</option>
						<option value=0 <?php echo set_select('coupon_allowed', 0) ?>>否</option>
					</select>
				</div>
			</div>

			<?php if ( !empty($biz_promotions) ): ?>
			<div class=form-group>
				<label for=promotion_id class="col-sm-2 control-label">店内活动</label>
				<div class=col-sm-10>
					<select class=form-control name=promotion_id>
						<option value="">不参加</option>
						<?php foreach ($biz_categories as $option): ?>
							<option value="<?php echo $option['promotion_id'] ?>" <?php echo set_select('promotion_id', $option['promotion_id']) ?>><?php echo $option['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<?php endif ?>

			<div class=form-group>
				<label for=freight_template_id class="col-sm-2 control-label">运费模板</label>
				<div class=col-sm-10>
					<?php if ( empty($biz_freight_templates) ): ?>
					<p class="help-block">您目前没有可用的运费模板，仅可包邮</p>
					<a class="col-xs-12 col-sm-6 col-md-3 btn btn-primary btn-lg" href="<?php echo base_url('freight_template_biz/create') ?>">创建一个</a>
					<?php endif ?>

					<select class=form-control name=freight_template_id>
						<option value="">默认包邮</option>
						<?php
							$options = $biz_freight_templates;
							foreach ($options as $option):
						?>
							<option value="<?php echo $option['template_id'] ?>" <?php echo set_select('freight_template_id', $option['template_id']) ?>><?php echo $option['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>

	</form>

</div>