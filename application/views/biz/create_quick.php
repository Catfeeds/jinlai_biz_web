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

<div id=content class=container>
	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create_quick', $attributes);
	?>
		<p class=help-block>必填项以“*”符号标示</p>

		<fieldset>
			<legend>基本资料</legend>

            <div class=form-group>
                <label for=url_logo class="col-sm-2 control-label">店铺LOGO</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'url_logo' ?>
                    <ul class=upload_preview></ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" >

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/<?php echo $name_to_upload ?>" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count=1 type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

                    <p class=help-block>正方形图片视觉效果最佳</p>
                </div>
            </div>

			<div class=form-group>
				<label for=brief_name class="col-sm-2 control-label">店铺名称 *</label>
				<div class=col-sm-10>
					<input class=form-control name=brief_name type=text value="<?php echo set_value('brief_name') ?>" placeholder="例如“SELECTED”" required>
				</div>
			</div>

            <div class=form-group>
                <label for=category_ids class="col-sm-2 control-label">主营商品类目</label>
                <div class=col-sm-10>
                    <?php $input_name = 'category_ids[]' ?>
                    <select class=form-control name="<?php echo $input_name ?>" multiple required>
                        <?php
                        $options = $item_categories;
                        $current_array = explode(',', $item['category_ids']);
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                                ?>
                                <option value="<?php echo $option['category_id'] ?>" <?php if ( in_array($option['category_id'], $current_array) ) echo 'selected'; ?>><?php echo $option['name'] ?></option>
                            <?php
                            endif;
                        endforeach;
                        ?>
                    </select>
                    <p class=help-block>您最多可以选择3种类别的商品进行售卖</p>
                </div>
            </div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<p class=help-block>请开店后及时进行认证；未通过认证的商家，货款将暂时由<?php echo SITE_NAME ?>进行存管。点击“开店”代表您已阅读并同意<a href="<?php echo base_url('article/agreement-admission') ?>" target=_blank><?php echo SITE_NAME ?>入驻协议。</a></p>
				<button class="btn btn-primary btn-lg btn-block" type=submit>开店</button>
		    </div>
		</div>
	</form>

</div>