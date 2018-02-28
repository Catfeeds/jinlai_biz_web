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
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create-quick form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create_quick', $attributes);
	?>
		<fieldset>
			<p class=help-block>必填项以“※”符号标示</p>

			<div class=form-group>
				<label for=category_id class="col-sm-2 control-label">系统分类 ※</label>
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
                        <option value="">不选择</option>
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
				<label for=url_image_main class="col-sm-2 control-label">主图 ※</label>
				<div class=col-sm-10>
                    <?php
                    require_once(APPPATH. 'views/templates/file-uploader.php');
                    $name_to_upload = 'url_image_main';
                    generate_html($name_to_upload, $this->class_name);
                    ?>

                    <p class=help-block>正方形图片视觉效果最佳</p>
				</div>
			</div>

            <div class=form-group>
                <label for=name class="col-sm-2 control-label">商品名称 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="最多30个字符，中英文、数字，不可为纯数字" required>
                </div>
            </div>

			<div class=form-group>
				<label for=price class="col-sm-2 control-label">商城价/现价（元）※</label>
				<div class=col-sm-10>
					<input class=form-control name=price type=number min=1 step=0.01 max=99999.99 value="<?php echo set_value('price') ?>" placeholder="1 ~ 99999.99" required>
				</div>
			</div>

			<div class=form-group>
				<label for=stocks class="col-sm-2 control-label">库存量（单位）※</label>
				<div class=col-sm-10>
                    <input class=form-control name=stocks type=number min=0 step=1 max=65535 value="<?php echo empty(set_value('stocks'))? 0: set_value('stocks') ?>" placeholder="最高65535单位" required>
                    <p class=help-block>库存管理方案为付款减库存，商品或规格库存量低于1个单位（含）时将不可被下单/付款；极少数情况下可能出现超卖。</p>
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