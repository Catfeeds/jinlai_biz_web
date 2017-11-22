<link rel=stylesheet media=all href="/css/create.css">
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

<script defer src="/js/create.js"></script>

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
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<fieldset>
			<p class=help-block>必填项以“※”符号标示</p>

			<input name=item_id type=hidden value="<?php echo $comodity['item_id'] ?>">

			<div class="form-group well">
				<label for=item_id class="col-sm-2 control-label">所属商品</label>
				<div class=col-sm-10>
					<section id=item-info class=row>
						<figcaption><?php echo $comodity['name'] ?></figcaption>
						<figure class="col-xs-12 col-sm-6 col-md-4">
							<img src="<?php echo MEDIA_URL.'/item/'.$comodity['url_image_main'] ?>">
						</figure>
					</section>
				</div>
			</div>

			<div class=form-group>
				<label for=url_image class="col-sm-2 control-label">图片</label>
                <div class=col-sm-10>
                    <p class=help-block>正方形图片视觉效果最佳</p>

                    <?php $name_to_upload = 'url_image' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" >

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/<?php echo $name_to_upload ?>" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count="1" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
			</div>

			<div class=form-group>
				<label for=name_first class="col-sm-2 control-label">一级规格※</label>
				<div class=col-sm-10>
					<input class=form-control name=name_first type=text value="<?php echo set_value('name_first') ?>" placeholder="一级规格" required>
				</div>
			</div>
			<div class=form-group>
				<label for=name_second class="col-sm-2 control-label">二级规格</label>
				<div class=col-sm-10>
					<input class=form-control name=name_second type=text value="<?php echo set_value('name_second') ?>" placeholder="二级规格">
				</div>
			</div>
			<div class=form-group>
				<label for=name_third class="col-sm-2 control-label">三级规格</label>
				<div class=col-sm-10>
					<input class=form-control name=name_third type=text value="<?php echo set_value('name_third') ?>" placeholder="三级规格">
				</div>
			</div>

			<div class=form-group>
				<label for=price class="col-sm-2 control-label">商城价/现价（元）※</label>
				<div class=col-sm-10>
					<input class=form-control name=price type=number step=0.01 min=1 max=99999.99 value="<?php echo empty(set_value('price'))? $comodity['price']: set_value('price') ?>" placeholder="商城价/现价（元）" required>
				</div>
			</div>
			<div class=form-group>
				<label for=stocks class="col-sm-2 control-label">库存量（单位）※</label>
				<div class=col-sm-10>
					<input class=form-control name=stocks type=number step=1 max=65535 value="<?php echo set_value('stocks') ?>" placeholder="库存量（单位）" required>
				</div>
			</div>
		</fieldset>
		
		<fieldset>
			<p class=help-block>以下3项择一填写即可；若填写多项，将按毛重、净重、体积重的顺序取首个有效值计算运费。</p>
			
			<div class=form-group>
				<label for=weight_net class="col-sm-2 control-label">净重（KG）</label>
				<div class=col-sm-10>
					<input class=form-control name=weight_net type=number step=0.01 max=999.99 value="<?php echo set_value('weight_net') ?>" placeholder="最高999.99">
				</div>
			</div>
			<div class=form-group>
				<label for=weight_gross class="col-sm-2 control-label">毛重（KG）</label>
				<div class=col-sm-10>
					<input class=form-control name=weight_gross type=number step=0.01 max=999.99 value="<?php echo set_value('weight_gross') ?>" placeholder="最高999.99">
				</div>
			</div>
			<div class=form-group>
				<label for=weight_volume class="col-sm-2 control-label">体积重（KG）</label>
				<div class=col-sm-10>
					<input class=form-control name=weight_volume type=number step=0.01 max=999.99 value="<?php echo set_value('weight_volume') ?>" placeholder="最高999.99">
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