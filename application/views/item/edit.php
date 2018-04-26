<link rel=stylesheet media=all href="/css/edit.css">
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
<script defer src="/js/edit.js"></script>

<base href="<?php echo $this->media_root ?>">

<!--
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
				timeFormat: "hh:ii",
				clearButton: true,
			}
		)
	});
</script>
-->

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
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

			<div class=form-group>
				<label for=category_id class="col-sm-2 control-label">平台分类</label>
				<div class=col-sm-10>
                    <p class=form-control-static>
                        <?php echo $category['name'] ?> <span class="text-muted">系统分类仅可在创建商品时指定</span>
                    </p>
				</div>
			</div>

			<div class=form-group>
				<label for=category_biz_id class="col-sm-2 control-label">店内分类</label>
                <div class="col-sm-10 input-group">
					<?php $input_name = 'category_biz_id' ?>
					<select class=form-control name="<?php echo $input_name ?>">
                        <option value="">不选择</option>
						<?php
                        if ( !empty($biz_categories) ):
							$options = $biz_categories;
							foreach ($options as $option):
						?>
						<option value="<?php echo $option['category_id'] ?>" <?php if ($option['category_id'] === $item[$input_name]) echo 'selected' ?>><?php echo $option['name'] ?></option>
                        <?php
                            endforeach;
                        endif;
                        ?>
					</select>

                    <div class="input-group-addon">
                        <a href="<?php echo base_url('item_category_biz') ?>">管理</a>
                    </div>
				</div>
			</div>

			<?php if ( !empty($brands) ): ?>
			<div class=form-group>
				<label for=brand_id class="col-sm-2 control-label">品牌</label>
				<div class=col-sm-10>
					<input class=form-control name=brand_id type=text value="<?php echo empty(set_value('brand_id'))? $item['brand_id']: set_value('brand_id') ?>" placeholder="所属品牌ID">
				</div>
			</div>
			<?php endif ?>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">商品名称 ※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo empty(set_value('name'))? $item['name']: set_value('name') ?>" placeholder="最多30个字符，中英文、数字，不可为纯数字" required>
				</div>
			</div>
			<div class=form-group>
				<label for=slogan class="col-sm-2 control-label">商品宣传语/卖点</label>
				<div class=col-sm-10>
					<input class=form-control name=slogan type=text value="<?php echo empty(set_value('slogan'))? $item['slogan']: set_value('slogan') ?>" placeholder="最多30个字符，中英文、数字，不可为纯数字">
				</div>
			</div>
			
			<div class=form-group>
				<label for=code_biz class="col-sm-2 control-label">商家商品编码</label>
				<div class=col-sm-10>
					<input class=form-control name=code_biz type=text value="<?php echo empty(set_value('code_biz'))? $item['code_biz']: set_value('code_biz') ?>" placeholder="最多20个英文大小写字母、数字">
				</div>
			</div>

            <div class=form-group>
                <label for=barcode class="col-sm-2 control-label">商品条形码</label>
                <div class=col-sm-10>
                    <input class=form-control name=barcode type=number step=1 size=13 value="<?php echo empty(set_value('barcode'))? $item['barcode']: set_value('barcode') ?>" placeholder="13位数字">
                </div>
            </div>

			<div class=form-group>
				<label for=url_image_main class="col-sm-2 control-label">主图 ※</label>
				<div class=col-sm-10>
                    <?php
                    require_once(APPPATH. 'views/templates/file-uploader.php');
                    $name_to_upload = 'url_image_main';
                    generate_html($name_to_upload, $this->class_name, TRUE, 1, $item[$name_to_upload]);
                    ?>

                    <p class=help-block>正方形图片视觉效果最佳</p>
				</div>
			</div>

			<div class=form-group>
				<label for=figure_image_urls class="col-sm-2 control-label">形象图</label>
				<div class=col-sm-10>
                    <?php
                    $name_to_upload = 'figure_image_urls';
                    generate_html($name_to_upload, $this->class_name, FALSE, 4, $item[$name_to_upload]);
                    ?>

                    <p class=help-block>最多可上传4张</p>
				</div>
			</div>

			<!--
			<div class=form-group>
				<label for=figure_video_urls class="col-sm-2 control-label">形象视频</label>
				<div class=col-sm-10>
					<input class=form-control name=figure_video_urls type=text value="<?php echo $item['figure_video_urls'] ?>" placeholder="形象视频">
				</div>
			</div>
			-->

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">商品描述</label>
				<div class=col-sm-10>
					<textarea id=detail_editior name=description rows=10 placeholder="可选，不超过20000个字符"><?php echo empty(set_value('description'))? $item['description']: set_value('description') ?></textarea>

                    <?php
                    require_once(APPPATH. 'views/templates/simditor.php');
                    $name_to_upload = 'description';
                    ?>
                    <script>
                        $(function(){
                            var editor = new Simditor({
                                textarea: $('textarea[name=description]'), // 若只使用属性选择器，有可能误选中meta等其它含有相应属性的DOM
                                placeholder: '10 - 20000个字符',
                                toolbar: ['title', 'bold', 'italic', 'underline', 'strikethrough', 'fontScale', 'color', '|', 'hr', 'ol', 'ul', 'blockquote', 'table', '|', 'link', 'image', '|', 'indent', 'outdent', 'alignment'],
                                cleanPaste: true,
                                upload: {
                                    url: '<?php echo base_url('/simditor?target='.$this->class_name.'/'.$name_to_upload) ?>',
                                    params: null,
                                    fileKey: 'file0',
                                    connectionCount: 4,
                                    leaveConfirm: '上传尚未结束，确定要中止？'
                                }
                            });
                        });
                    </script>
					
				</div>
			</div>
			<div class=form-group>
				<label for=tag_price class="col-sm-2 control-label">标签价/原价</label>
                <div class="input-group col-sm-10">
                    <div class="input-group-addon">￥</div>
					<input class=form-control name=tag_price type=number min=0 step=0.01 max=99999.99 value="<?php echo empty(set_value('tag_price'))? $item['tag_price']: set_value('tag_price') ?>" placeholder="留空或0则不显示，最高99999.99">
				</div>
			</div>
			<div class=form-group>
				<label for=price class="col-sm-2 control-label">商城价/现价 ※</label>
                <div class="input-group col-sm-10">
                    <div class="input-group-addon">￥</div>
					<input class=form-control name=price type=number min=1 step=0.01 max=99999.99 value="<?php echo empty(set_value('price'))? $item['price']: set_value('price') ?>" placeholder="1 ~ 99999.99" required>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<div class=form-group>
				<label for=unit_name class="col-sm-2 control-label">销售单位</label>
				<div class=col-sm-10>
					<input class=form-control name=unit_name type=text value="<?php echo empty(set_value('unit_name'))? $item['unit_name']: set_value('unit_name') ?>" placeholder="最多10个字符，例如斤、双、头、件等，默认“份”">
				</div>
			</div>
			<div class=form-group>
				<label for=stocks class="col-sm-2 control-label">库存量 ※</label>
				<div class=col-sm-10>
					<input class=form-control name=stocks type=number min=0 step=1 max=65535 value="<?php echo empty(set_value('stocks'))? $item['stocks']: set_value('stocks') ?>" placeholder="最高65535单位" required>
                    <p class=help-block>库存管理方案为付款减库存，商品或规格库存量低于1个单位（含）时将不可被下单/付款；极少数情况下可能出现超卖。</p>
				</div>
			</div>
			<div class=form-group>
				<label for=quantity_max class="col-sm-2 control-label">每单最高限量（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=quantity_max type=number min=0 step=1 max=50 value="<?php echo empty(set_value('quantity_max'))? $item['quantity_max']: set_value('quantity_max') ?>" placeholder="留空则默认为50，最高50">
				</div>
			</div>
			<div class=form-group>
				<label for=quantity_min class="col-sm-2 control-label">每单最低限量（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=quantity_min type=number min=1 step=1 max=50 value="<?php echo empty(set_value('quantity_min'))? $item['quantity_min']: set_value('quantity_min') ?>" placeholder="留空则默认为1，最高50">
				</div>
			</div>
		</fieldset>

		<fieldset>
            <div class=form-group>
                <label for=weight_gross class="col-sm-2 control-label">毛重</label>
                <div class="input-group col-sm-10">
                    <input class=form-control name=weight_gross type=number step=0.01 max=999.99 value="<?php echo empty(set_value('weight_gross'))? $item['weight_gross']: set_value('weight_gross') ?>" placeholder="最高999.99，运费计算将以运费模板为准">
                    <div class="input-group-addon">KG</div>
                </div>
            </div>

            <div class=form-group>
				<label for=weight_net class="col-sm-2 control-label">净重</label>
                <div class="input-group col-sm-10">
					<input class=form-control name=weight_net type=number step=0.01 max=999.99 value="<?php echo empty(set_value('weight_net'))? $item['weight_net']: set_value('weight_net') ?>" placeholder="最高999.99，运费计算将以运费模板为准">
                    <div class="input-group-addon">KG</div>
				</div>
			</div>

			<div class=form-group>
				<label for=weight_volume class="col-sm-2 control-label">体积重</label>
                <div class="input-group col-sm-10">
					<input class=form-control name=weight_volume type=number step=0.01 max=999.99 value="<?php echo empty(set_value('weight_volume'))? $item['weight_volume']: set_value('weight_volume') ?>" placeholder="最高999.99，运费计算将以运费模板为准">
                    <div class="input-group-addon">KG</div>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<div class=form-group>
				<label for=discount_credit class="col-sm-2 control-label">积分抵扣率</label>
				<div class=col-sm-10>
					<input class=form-control name=discount_credit type=number step=0.01 min=0.00 max=0.99 value="<?php echo empty(set_value('discount_credit'))? $item['discount_credit']: set_value('discount_credit') ?>" placeholder="留空则默认为0">
					<p class=help-block>若允许使用积分抵扣，则需填写此项；例如允许5%的金额使用积分抵扣则为0.05，10%为0.1，最高0.5</p>
				</div>
			</div>

			<div class=form-group>
				<label for=commission_rate class="col-sm-2 control-label">佣金比例/提成率</label>
				<div class=col-sm-10>
					<input class=form-control name=commission_rate type=number step=0.01 min=0.00 max=0.99 value="<?php echo empty(set_value('commission_rate'))? $item['commission_rate']: set_value('commission_rate') ?>" placeholder="留空则默认为0">
					<p class=help-block>若需向推广者返还佣金，则需填写此项；例如提成实际支付金额的5%则为0.05，10%为0.1，最高0.5</p>
				</div>
			</div>

			<div class=form-group>
				<label for=time_to_publish class="col-sm-2 control-label">预定上架时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_to_publish type=datetime value="<?php echo empty($item['time_to_publish'])? NULL: date('Y-m-d H:i', $item['time_to_publish']) ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+8days')) ?>">
                    <p class=help-block>需详细到分，且晚于当前时间1分钟后；若填写了此项，则商品将下架。</p>
				</div>
			</div>

			<div class=form-group>
				<label for=time_to_suspend class="col-sm-2 control-label">预定下架时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_to_suspend type=datetime value="<?php echo empty($item['time_to_suspend'])? NULL: date('Y-m-d H:i', $item['time_to_suspend']) ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+10days')) ?>">
                    <p class=help-block>需详细到分，且晚于当前时间1分钟后。</p>
				</div>
			</div>

            <div class=form-group>
                <label for=coupon_allowed class="col-sm-2 control-label">是否可用优惠券※</label>
                <div class=col-sm-10>
                    <?php $input_name = 'coupon_allowed' ?>
                    <label class=radio-inline>
                        <input type=radio name="<?php echo $input_name ?>" value="1" required <?php if ($item[$input_name] === '1') echo 'checked' ?>> 是
                    </label>
                    <label class=radio-inline>
                        <input type=radio name="<?php echo $input_name ?>" value="0" required <?php if ($item[$input_name] === '0') echo 'checked' ?>> 否
                    </label>
                </div>
            </div>

            <?php if ( !empty($biz_promotions) ): ?>
			<div class=form-group>
				<label for=promotion_id class="col-sm-2 control-label">店内活动</label>
				<div class=col-sm-10>
					<?php $input_name = 'promotion_id' ?>
					<select class=form-control name="<?php echo $input_name ?>">
						<option value="">不参加</option>
						<?php
							$options = $biz_promotions;
							foreach ($options as $option):
						?>
						<option value="<?php echo $option['promotion_id'] ?>" <?php if ($option['promotion_id'] === $item['promotion_id']) echo 'selected'; ?>><?php echo $option['name'] ?></option>
						<?php endforeach ?>
					</select>
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