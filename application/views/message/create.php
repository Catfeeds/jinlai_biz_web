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
            <div class=form-group>
                <?php $input_name = 'receiver_type' ?>
                <label for="<?php echo $input_name ?>" class="col-sm-2 control-label">收信端类型</label>
                <div class=col-sm-10>
                    <select class=form-control name="<?php echo $input_name ?>" required>
                        <option value="" <?php echo set_select($input_name, '') ?>>请选择</option>
                        <?php
                        $options = array('admin','biz','client');
                        foreach ($options as $option):
                            ?>
                            <option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>><?php echo $option ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class=form-group>
                <label for=user_id class="col-sm-2 control-label">用户ID</label>
                <div class=col-sm-10>
                    <input class=form-control name=user_id type=number min=1 step=1 value="<?php echo set_value('user_id') ?>" placeholder="用户ID">
                </div>
            </div>
            <div class=form-group>
                <label for=biz_id class="col-sm-2 control-label">商家ID</label>
                <div class=col-sm-10>
                    <input class=form-control name=biz_id type=number min=1 step=1 value="<?php echo set_value('biz_id') ?>" placeholder="商家ID">
                </div>
            </div>
            <div class=form-group>
                <label for=stuff_id class="col-sm-2 control-label">员工ID</label>
                <div class=col-sm-10>
                    <input class=form-control name=stuff_id type=number min=1 step=1 value="<?php echo set_value('stuff_id') ?>" placeholder="员工ID">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <div class=form-group>
                <?php $input_name = 'type' ?>
                <label for="<?php echo $input_name ?>" class="col-sm-2 control-label">消息类型</label>
                <div class=col-sm-10>
                    <select class=form-control name="<?php echo $input_name ?>" required>
                        <?php
                        $options = array(
                            'address' => '收货地址',
                            'article' => '平台文章',
                            'article_biz' => '店内文章',
                            'audio' => '音频',
                            'branch' => '门店',
                            'coupon_template' => '优惠券',
                            'coupon_combo' => '优惠券包',
                            'item' => '商品',
                            'image' => '图片',
                            'location' => '位置',
                            'order' => '订单',
                            'promotion' => '平台活动',
                            'promotion_biz' => '店内活动',
                            'text' => '文字',
                            'video' => '视频',
                        );
                        $option_keys = array_keys($options);
                        foreach ($option_keys as $option):
                            ?>
                            <option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>><?php echo $options[$option] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class=form-group>
                <label for=ids class="col-sm-2 control-label">内容ID们</label>
                <div class=col-sm-10>
                    <input class=form-control name=ids type=text value="<?php echo set_value('ids') ?>" placeholder="内容ID们">
                </div>
            </div>

            <div class=form-group>
                <label for=content class="col-sm-2 control-label">内容</label>
                <div class=col-sm-10>
                    <textarea class=form-control name=content rows=10 placeholder="最多5000个字符"><?php echo set_value('content') ?></textarea>
                </div>
            </div>

            <div class=form-group>
                <label for=longitude class="col-sm-2 control-label">经度</label>
                <div class=col-sm-10>
                    <input class=form-control name=longitude type=text value="<?php echo set_value('longitude') ?>" placeholder="经度，小数点后保留5位">
                </div>
            </div>
            <div class=form-group>
                <label for=latitude class="col-sm-2 control-label">纬度</label>
                <div class=col-sm-10>
                    <input class=form-control name=latitude type=text value="<?php echo set_value('latitude') ?>" placeholder="纬度，小数点后保留5位">
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