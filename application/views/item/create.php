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

<link href="<?php echo CDN_URL ?>css/datepicker.min.css" rel="stylesheet">
<script src="<?php echo CDN_URL ?>js/datepicker.min.js"></script>
<script>
	$(function(){
		// 初始化日期选择器
		$('[type=datetime]').datepicker(
			{
			    language: 'cn', // 本地化语言在js/main.js中
			    minDate: new Date("<?php echo date('Y-m-d H:i') ?>"),
				maxDate: new Date("<?php echo date('Y-m-d H:i', strtotime("+31 days")) ?>"),
				timepicker: true, // 时间选择器
				timeFormat: "hh:ii"
			}
		)
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
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
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
							<option value="<?php echo $option['category_id'] ?>" <?php echo set_select('category_biz_id', $option['category_id']) ?>><?php echo $option['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<?php endif ?>

			<?php if ( !empty($brands) ): ?>
			<div class=form-group>
				<label for=brand_id class="col-sm-2 control-label">品牌</label>
				<div class=col-sm-10>
					<select class=form-control name=brand_id required>
						<option value="">请选择</option>
						<?php foreach ($categories as $option): ?>
							<option value="<?php echo $option['brand_id'] ?>" <?php echo set_select('brand_id', $option['brand_id']) ?>><?php echo $option['name'] ?></option>
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
					<p class=help-block>正方形图片视觉效果最佳</p>

					<?php $name_to_upload = 'url_image_main' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" required>

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

					<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/image_main" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count="1" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
				</div>
			</div>

			<div class=form-group>
				<label for=figure_image_urls class="col-sm-2 control-label">形象图</label>
				<div class=col-sm-10>
					<p class=help-block>最多可上传4张，选择时按住“ctrl”或“⌘”键可选多张</p>

                    <?php $name_to_upload = 'figure_image_urls' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

					<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/image_figure" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count="4" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
				</div>
			</div>

			<!--
			<div class=form-group>
				<label for=figure_video_urls class="col-sm-2 control-label">形象视频</label>
				<div class=col-sm-10>
					<input class=form-control name=figure_video_urls type=text value="<?php echo set_value('figure_video_urls') ?>" placeholder="形象视频">
				</div>
			</div>
			-->

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">商品描述</label>
				<div class=col-sm-10>
					<?php
						$user_agent = $_SERVER['HTTP_USER_AGENT'];
						$is_wechat = strpos($user_agent, 'MicroMessenger')? TRUE: FALSE;
						if ( !$is_wechat):
					?>
					<textarea id=detail_editior name=description rows=10 placeholder="可选，不超过20000个字符"><?php echo set_value('description') ?></textarea>
					<!-- ueditor 1.4.3.3 -->
					<link rel="stylesheet" media=all href="<?php echo base_url('ueditor/themes/default/css/ueditor.min.css') ?>">
					<script src="<?php echo base_url('ueditor/ueditor.config.js') ?>"></script>
					<script src="<?php echo base_url('ueditor/ueditor.all.min.js') ?>"></script>
					<script>
						var ue = UE.getEditor(
							'detail_editior',
							{
								serverUrl: '<?php echo base_url('ueditor/php/controller.php?target='.$this->class_name) ?>',
							}
						);
					</script>

					<?php else: ?>
					<p class="help-block">在电脑上编辑可添加更丰富内容</p>
					<textarea class=form-control name=description rows=10 placeholder="可选，不超过20000个字符"><?php echo set_value('description') ?></textarea>

					<?php endif ?>
				</div>
			</div>

			<div class=form-group>
				<label for=tag_price class="col-sm-2 control-label">标签价/原价（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=tag_price type=number step=0.01 min=0.00 max=99999.99 value="<?php echo set_value('tag_price') ?>" placeholder="留空则不显示，最高99999.99">
				</div>
			</div>

			<div class=form-group>
				<label for=price class="col-sm-2 control-label">商城价/现价（元）※</label>
				<div class=col-sm-10>
					<input class=form-control name=price type=number step=0.01 min=0.01 max=99999.99 value="<?php echo set_value('price') ?>" placeholder="最高99999.99" required>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<div class=form-group>
				<label for=unit_name class="col-sm-2 control-label">销售单位</label>
				<div class=col-sm-10>
					<input class=form-control name=unit_name type=text value="<?php echo set_value('unit_name') ?>" placeholder="最多10个字符，例如斤、双、头、件等，默认“份”">
				</div>
			</div>

			<div class=form-group>
				<label for=stocks class="col-sm-2 control-label">库存量※</label>
				<div class=col-sm-10>
					<input class=form-control name=stocks type=number step=1 max=65535 value="<?php echo set_value('stocks') ?>" placeholder="最多65535，仅在用户支付后会减少库存" required>
				</div>
			</div>

			<div class=form-group>
				<label for=quantity_max class="col-sm-2 control-label">每单最高限量（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=quantity_max type=number min=0 step=1 max=50 value="<?php echo set_value('quantity_max') ?>" placeholder="留空则默认为50，最高50">
				</div>
			</div>

			<div class=form-group>
				<label for=quantity_min class="col-sm-2 control-label">每单最低限量（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=quantity_min type=number min=1 step=1 max=50 value="<?php echo set_value('quantity_min') ?>" placeholder="留空则默认为1，最高50">
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

		<fieldset>
			<div class=form-group>
				<label for=coupon_allowed class="col-sm-2 control-label">是否可用优惠券※</label>
				<div class=col-sm-10>
					<?php $input_name = 'coupon_allowed' ?>
					<label class=radio-inline>
						<input type=radio name="<?php echo $input_name ?>" value="1" required <?php echo set_radio($input_name, 1, TRUE) ?>> 是
					</label>
					<label class=radio-inline>
						<input type=radio name="<?php echo $input_name ?>" value="0" required <?php echo set_radio($input_name, 0) ?>> 否
					</label>
				</div>
			</div>

			<div class=form-group>
				<label for=discount_credit class="col-sm-2 control-label">积分抵扣率</label>
				<div class=col-sm-10>
					<input class=form-control name=discount_credit type=number step=0.01 min=0.00 max=0.50 value="<?php echo set_value('discount_credit') ?>" placeholder="留空则默认为0">
					<p class=help-block>若允许使用积分抵扣，则需填写此项；例如允许5%的金额使用积分抵扣则为0.05，10%为0.1，最高0.5</p>
				</div>
			</div>
			<div class=form-group>
				<label for=commission_rate class="col-sm-2 control-label">佣金比例/提成率</label>
				<div class=col-sm-10>
					<input class=form-control name=commission_rate type=number step=0.01 min=0.00 max=0.50 value="<?php echo set_value('commission_rate') ?>" placeholder="留空则默认为0">
					<p class=help-block>若需向推广者返还佣金，则需填写此项；例如提成实际支付金额的5%则为0.05，10%为0.1，最高0.5</p>
				</div>
			</div>

            <!--
			<div class=form-group>
				<label for=time_to_publish class="col-sm-2 control-label">预定上架时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_to_publish type=datetime value="<?php echo set_value('time_to_publish') ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+8days')) ?>">
					<p class=help-block>最小可限定到分钟级别；若填写了此项，则商品在创建后将处于下架状态</p>
				</div>
			</div>
			<div class=form-group>
				<label for=time_to_suspend class="col-sm-2 control-label">预定下架时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_to_suspend type=datetime value="<?php echo set_value('time_to_suspend') ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+10days')) ?>">
					<p class=help-block>最小可限定到分钟级别；若填写了此项，则商品在创建后将处于上架状态</p>
				</div>
			</div>
			-->

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
                    <p class=help-block>您目前没有可用的运费模板，仅可包邮</p>
                    <a class="col-xs-12 col-sm-6 col-md-3 btn btn-default btn-lg" href="<?php echo base_url('freight_template_biz') ?>">创建运费模板</a>

                    <?php else: ?>
                    <select class=form-control name=freight_template_id>
                        <option value="">默认包邮</option>
                        <?php
                        $options = $biz_freight_templates;
                        foreach ($options as $option):
                            ?>
                            <option value="<?php echo $option['template_id'] ?>" <?php echo set_select('freight_template_id', $option['template_id']) ?>><?php echo $option['name'] ?></option>
                        <?php endforeach ?>
                    </select>

					<?php endif ?>

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