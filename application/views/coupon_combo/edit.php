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
				maxDate: new Date("<?php echo date('Y-m-d H:i', strtotime("+366 days")) ?>"),
				timepicker: true, // 时间选择器
				timeFormat: "hh:ii"
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
		<fieldset>
			<p class=help-block>必填项以“※”符号标示</p>
			
			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称 ※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="最多20个字符" required>
				</div>
			</div>

			<div class=form-group>
				<label for=template_ids class="col-sm-2 control-label">所含优惠券 ※</label>
				<div class=col-sm-10>
                    <?php $input_name = 'template_ids[]' ?>
                    <select class=form-control name="<?php echo $input_name ?>" multiple required>
                        <?php
                        $options = $coupon_templates;
                        $current_array = explode(',', $item['template_ids']);
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                                ?>
                                <option value="<?php echo $option['template_id'] ?>" <?php if ( in_array($option['template_id'], $current_array) ) echo 'selected' ?>><?php echo $option['name'] ?></option>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </select>

                    <p class=help-block>放入券包的优惠券，在优惠券包被领取时将忽视总限量（若有）及单个用户限量（若有），以优惠券包的总限量（若有）为准；作为单个优惠券被领取时不受影响。</p>
				</div>
			</div>

            <div class=form-group>
                <label for=max_amount class="col-sm-2 control-label">总限量（份）</label>
                <div class=col-sm-10>
                    <input class=form-control name=max_amount type=number step=1 min=0 max=999999 value="<?php echo $item['max_amount'] ?>" placeholder="最高999999，留空或0为不限">
                    <p class=help-block>券包总共可被领取数量上限；对于每位用户来说，每个优惠券包仅可领取一次</p>
                </div>
            </div>
		</fieldset>

        <!--
        <fieldset>
			<legend>高级功能（免费试用）</legend>

			<div class=form-group>
				<label for=time_start class="col-sm-2 control-label">领取开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_start type=datetime value="<?php echo empty($item['time_start'])? NULL: date('Y-m-d H:i', $item['time_start']); ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+2days')) ?>；留空则马上开放领取">
				</div>
			</div>
			<div class=form-group>
				<label for=time_end class="col-sm-2 control-label">领取结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_end type=datetime value="<?php echo empty($item['time_end'])? NULL: date('Y-m-d H:i', $item['time_end']); ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+5days')) ?>；留空则长期有效">
				</div>
			</div>
		</fieldset>
		-->

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>

</div>