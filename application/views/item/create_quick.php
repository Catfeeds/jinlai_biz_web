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

<script>
$(function(){
    // 管理商家级商品分类
    $('#api-item_category_biz-index').click(function(){
        // 初始化参数
        params = common_params;

        // AJAX获取结果并生成相关HTML
        $.post(
            ajax_root + ms_api_url, // 拼合完整API路径
            params,
            function(result)
            {
                console.log(result); // 输出回调数据到控制台

                if (result.status == 200)
                {
                    var content = result.content
                    alert(result.status);
                }
                else
                {
                    // 若失败，进行提示
                    alert(result.content.error.message);
                }
            },
            "JSON"
        );

        return false;
    });
});
</script>

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
        <p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<div class=form-group>
				<label for=category_id class="col-sm-2 control-label">平台分类 ※</label>
				<div class=col-sm-10>
                    <input name=category_id type=hidden value="" required>

                    <div
                            class="multi-selector row"
                            data-ms-name=category_id
                            data-ms-api_url="item_category/index"
                            data-ms-min_level=2
                            data-ms-max_level=3
                    >
                        <div class=col-xs-4>
                            <select class=form-control data-ms-level=1 required>
                                <option value="">请选择</option>
                                <?php foreach ($categories as $option): ?>
                                    <option value="<?php echo $option['category_id'] ?>" <?php echo set_select('category_id', $option['category_id']) ?>><?php echo $option['nature'].'-'.$option['name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
				</div>
			</div>

			<div class=form-group>
				<label for=category_biz_id class="col-sm-2 control-label">店内分类</label>
				<div class="col-sm-10 input-group">
                    <?php $input_name = 'category_biz_id' ?>
                    <select class=form-control name="<?php echo $input_name ?>">
                        <option value="">可选择</option>
                        <?php
                        if ( !empty($biz_categories) ):
                            $options = $biz_categories;
                            foreach ($options as $option):
                        ?>
							<option value="<?php echo $option['category_id'] ?>" <?php echo set_select($input_name, $option['category_id']) ?>><?php echo $option['name'] ?></option>
                        <?php
                            endforeach;
                        endif;
                        ?>
					</select>

                    <div class="input-group-addon">
                        <a id=api-item_category_biz-index href="<?php echo base_url('item_category_biz') ?>">管理</a>
                    </div>
				</div>
			</div>
			
			<?php if ( ! empty($brands) ): ?>
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
                    require_once(VIEWPATH. 'templates/file-uploader.php');
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
				<label for=price class="col-sm-2 control-label">商城价/现价 ※</label>
                <div class="input-group col-sm-10">
                    <div class="input-group-addon">￥</div>
					<input class=form-control name=price type=number min=1 step=0.01 max=99999.99 value="<?php echo set_value('price') ?>" placeholder="1 ~ 99999.99" required>
				</div>
			</div>

			<div class=form-group>
				<label for=stocks class="col-sm-2 control-label">库存量</label>
                <div class=col-sm-10>
                    <div class=input-group>
                        <input class=form-control name=stocks type=number min=0 step=1 max=65535 value="<?php echo empty(set_value('stocks'))? 10: set_value('stocks') ?>" placeholder="默认10，最高65535单位">
                        <div class="input-group-addon">单位</div>
                    </div>

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