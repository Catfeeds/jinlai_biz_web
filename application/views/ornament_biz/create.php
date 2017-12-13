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
		<p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<legend>基本信息</legend>

            <div class=form-group>
                <label for=name class="col-sm-2 control-label">方案名称</label>
                <div class=col-sm-10>
                    <input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="例如：圣诞节店铺装饰" required>
                </div>
            </div>
            <!--
            <div class=form-group>
                <label for=template_id class="col-sm-2 control-label">装修模板ID</label>
                <div class=col-sm-10>
                    <input class=form-control name=template_id type=text value="<?php echo set_value('template_id') ?>" placeholder="装修模板ID">
                </div>
            </div>
            -->

            <div class=form-group>
                <label for=vi_color_first class="col-sm-2 control-label">第一识别色</label>
                <div class="col-sm-10 input-group">
                    <div class=input-group-addon>#</div>
                    <input class=form-control name=vi_color_first type=text value="<?php echo set_value('vi_color_first') ?>" placeholder="16进制颜色码，例如红色为cc0000；亦可使用缩写形式c00">
                </div>
            </div>

            <div class=form-group>
                <label for=vi_color_second class="col-sm-2 control-label">第二识别色</label>
                <div class="col-sm-10 input-group">
                    <div class=input-group-addon>#</div>
                    <input class=form-control name=vi_color_second type=text value="<?php echo set_value('vi_color_second') ?>" placeholder="16进制颜色码，例如红色为cc0000；亦可使用缩写形式c00">
                </div>
            </div>

            <div class=form-group>
                <label for=main_figure_url class="col-sm-2 control-label">主形象图</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'main_figure_url' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone style="width:670px;height:322px;">
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" >

                        <div class=file_selector style="line-height:322px;"><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/<?php echo $name_to_upload ?>" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count=1 type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

                    <p class=help-block>用于商家店铺首页、商家会员卡详情页</p>
                </div>
            </div>

            <div class=form-group>
                <label for=member_logo_url class="col-sm-2 control-label">会员卡LOGO</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'member_logo_url' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" >

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/<?php echo $name_to_upload ?>" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count=1 type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

                    <p class=help-block>用于会员卡相关页面；若不上传，则默认以商家LOGO作为会员卡LOGO</p>
                </div>
            </div>

            <div class=form-group>
                <label for=member_figure_url class="col-sm-2 control-label">会员卡封图</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'member_figure_url' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone style="width:670px;height:322px;">
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" >

                        <div class=file_selector style="line-height:322px;"><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/<?php echo $name_to_upload ?>" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count=1 type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

                    <p class=help-block>用于会员卡领取页、详情页</p>
                </div>
            </div>

            <div class=form-group>
                <label for=member_thumb_url class="col-sm-2 control-label">会员卡列表图</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'member_thumb_url' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone style="width:670px;height:322px;">
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" >

                        <div class=file_selector style="line-height:322px;"><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/<?php echo $name_to_upload ?>" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count=1 type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

                    <p class=help-block>用于会员卡列表</p>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>顶部模块</legend>
            <p class=help-block>请上传尺寸正确、大小合适的图片；过大的图片将导致页面打开缓慢，过小的图片则影响显示效果。</p>

            <div class=form-group>
                <label for=home_slides class="col-sm-2 control-label">顶部模块轮播图内容</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'home_slides' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" >

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/<?php echo $name_to_upload ?>" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count="4" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
            </div>

            <div class=form-group>
                <label for=home_m1_ids class="col-sm-2 control-label">顶部模块陈列商品</label>
                <div class=col-sm-10>
                    <?php $input_name = 'home_m0_ids[]' ?>
                    <select class=form-control name="<?php echo $input_name ?>" multiple>
                        <?php
                        $options = $comodities;
                        $current_array = explode(',', $item['home_m0_ids']);
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                                ?>
                                <option value="<?php echo $option['item_id'] ?>" <?php if ( in_array($option['item_id'], $current_array) ) echo 'selected'; ?>><?php echo $option['name'] ?></option>
                            <?php
                            endif;
                        endforeach;
                        ?>
                    </select>

                    <p class=help-block>需要进行展示的1-3款商品，下同；桌面端按住Ctrl或⌘键可多选；如果选择了3款以上，将仅示前3款</p>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>模块一</legend>

            <div class=form-group>
                <label for=home_m1_ace_url class="col-sm-2 control-label">模块一形象图</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'home_m1_ace_url' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone style="width:670px;height:322px;">
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" >

                        <div class=file_selector style="line-height:322px;"><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/<?php echo $name_to_upload ?>" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count="1" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m1_ace_id class="col-sm-2 control-label">模块一首推商品</label>
                <div class=col-sm-10>
                    <?php $input_name = 'home_m1_ace_id' ?>
                    <select class=form-control name="<?php echo $input_name ?>">
                        <?php
                        $options = $comodities;
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                                ?>
                                <option value="<?php echo $option['item_id'] ?>" <?php echo set_select($input_name, $option['item_id']) ?>><?php echo $option['name'] ?></option>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </select>

                    <p class=help-block>点击形象图后跳转到的商品，下同</p>
                </div>
            </div>

            <div class=form-group>
                <label for=home_m1_ids class="col-sm-2 control-label">模块一陈列商品</label>
                <div class=col-sm-10>
                    <?php $input_name = 'home_m1_ids[]' ?>
                    <select class=form-control name="<?php echo $input_name ?>" multiple>
                        <?php
                        $options = $comodities;
                        $current_array = explode(',', $item['home_m1_ids']);
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                                ?>
                                <option value="<?php echo $option['item_id'] ?>" <?php if ( in_array($option['item_id'], $current_array) ) echo 'selected'; ?>><?php echo $option['name'] ?></option>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </select>

                    <p class=help-block>需要进行展示的1-3款商品，下同；桌面端按住Ctrl或⌘键可多选；如果选择了3款以上，将仅示前3款</p>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>模块二</legend>

            <div class=form-group>
                <label for=home_m2_ace_url class="col-sm-2 control-label">模块二形象图</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'home_m2_ace_url' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone style="width:670px;height:322px;">
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" >

                        <div class=file_selector style="line-height:322px;"><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/<?php echo $name_to_upload ?>" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count="1" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m2_ace_id class="col-sm-2 control-label">模块二首推商品</label>
                <div class=col-sm-10>
                    <?php $input_name = 'home_m2_ace_id' ?>
                    <select class=form-control name="<?php echo $input_name ?>">
                        <?php
                        $options = $comodities;
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                                ?>
                                <option value="<?php echo $option['item_id'] ?>" <?php echo set_select($input_name, $option['item_id']) ?>><?php echo $option['name'] ?></option>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m2_ids class="col-sm-2 control-label">模块二陈列商品</label>
                <div class=col-sm-10>
                    <?php $input_name = 'home_m2_ids[]' ?>
                    <select class=form-control name="<?php echo $input_name ?>" multiple>
                        <?php
                        $options = $comodities;
                        $current_array = explode(',', $item['home_m2_ids']);
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                                ?>
                                <option value="<?php echo $option['item_id'] ?>" <?php if ( in_array($option['item_id'], $current_array) ) echo 'selected'; ?>><?php echo $option['name'] ?></option>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>模块三</legend>

            <div class=form-group>
                <label for=home_m3_ace_url class="col-sm-2 control-label">模块三形象图</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'home_m3_ace_url' ?>
                    <ul class="upload_preview"></ul>

                    <div class=selector_zone style="width:670px;height:322px;">
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" >

                        <div class=file_selector style="line-height:322px;"><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/<?php echo $name_to_upload ?>" data-selector-id="<?php echo $name_to_upload ?>" data-input-name="<?php echo $name_to_upload ?>" data-max-count="1" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m3_ace_id class="col-sm-2 control-label">模块三首推商品</label>
                <div class=col-sm-10>
                    <?php $input_name = 'home_m3_ace_id' ?>
                    <select class=form-control name="<?php echo $input_name ?>">
                        <?php
                        $options = $comodities;
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                                ?>
                                <option value="<?php echo $option['item_id'] ?>" <?php echo set_select($input_name, $option['item_id']) ?>><?php echo $option['name'] ?></option>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m3_ids class="col-sm-2 control-label">模块三陈列商品</label>
                <div class=col-sm-10>
                    <?php $input_name = 'home_m3_ids[]' ?>
                    <select class=form-control name="<?php echo $input_name ?>" multiple>
                        <?php
                        $options = $comodities;
                        $current_array = explode(',', $item['home_m3_ids']);
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                                ?>
                                <option value="<?php echo $option['item_id'] ?>" <?php if ( in_array($option['item_id'], $current_array) ) echo 'selected'; ?>><?php echo $option['name'] ?></option>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>
		</fieldset>

        <fieldset>
            <legend>开发者功能（高级功能，免费试用）</legend>
            <p class=help-block>请勿更改此处内容，除非您清楚地知道自己在做什么</p>

            <div class=form-group>
                <label for=home_json class="col-sm-2 control-label">首页内容（JSON）</label>
                <div class=col-sm-10>
                    <textarea class="form-control" name="home_json" rows=5 placeholder="JSON格式内容，10-20000个字符"><?php echo set_value('home_json') ?></textarea>
                </div>
            </div>
            <div class=form-group>
                <label for=home_html class="col-sm-2 control-label">首页内容（HTML）</label>
                <div class=col-sm-10>
                    <textarea class="form-control" name="home_html" rows=5 placeholder="HTML格式内容，10-20000个字符"><?php echo set_value('home_html') ?></textarea>
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