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
        <p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
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
                    <textarea class=form-control name=template_ids placeholder="例如18|3,17|1"><?php echo empty(set_value('template_ids'))? $item['template_ids']: set_value('template_ids') ?></textarea>
                    <p class=help-block>优惠券模板ID|数量，多个优惠券间以半角逗号分隔，例如18|3,17|1</p>

                    <h3>可发放优惠券</h3>
                    <div class=well>
                        <ul>
                            <?php
                            $options = $coupon_templates;
                            foreach ($options as $option):
                                if ( empty($option['time_delete']) ):
                                    ?>
                                    <li>ID<?php echo $option['template_id'] ?> <?php echo $option['name'] ?></li>
                                <?php
                                endif;
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>

                <!--
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
				-->
			</div>

            <div class=form-group>
                <label for=max_amount class="col-sm-2 control-label">总限量（份）</label>
                <div class=col-sm-10>
                    <input class=form-control name=max_amount type=number step=1 min=0 max=999999 value="<?php echo $item['max_amount'] ?>" placeholder="最高999999，留空或0为不限">
                    <p class=help-block>券包总共可被领取数量上限；对于每位用户来说，每个优惠券包仅可领取一次</p>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>可领取时间</legend>

            <div class=form-group>
                <label for=period class="col-sm-2 control-label">可领时长</label>
                <div class=col-sm-10>
                    <?php $input_name = 'period' ?>
                    <select class=form-control name="<?php echo $input_name ?>">
                        <option value="" <?php if ( empty($item[$input_name]) ) echo 'selected' ?>>可选择</option>
                        <?php
                        $options = array(
                            '1小时' => '3600',
                            '2小时' => '7200',
                            '3小时' => '10800',
                            '4小时' => '14400',
                            '6小时' => '21600',
                            '8小时' => '28800',
                            '12小时' => '43200',
                            '24小时/1天' => '86400',
                            '2天' => '172800',
                            '3天' => '259200',
                            '7天' => '604800',
                            '10天' => '864000',
                            '14天' => '1209600',
                            '30天' => '2592000',
                            '45天' => '3888000',
                            '90天' => '7776000',
                            '120天' => '10368000',
                            '180天/半年' => '15552000',
                            '366天/1年' => '31622400',
                        );
                        foreach ($options as $name => $value):
                            ?>
                            <option value="<?php echo $value ?>" <?php if ($value === $item[$input_name]) echo 'selected'; ?>><?php echo $name ?></option>
                        <?php endforeach ?>
                    </select>
                    <p class=help-block>留空则默认为30天</p>
                </div>
            </div>

			<div class=form-group>
				<label for=time_start class="col-sm-2 control-label">领取开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_start type=datetime value="<?php echo empty($item['time_start'])? NULL: date('Y-m-d H:i', $item['time_start']); ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+1days')) ?>；留空则马上开放领取">
					<?php require_once(APPPATH. 'views/templates/time_start_hint.php') ?>
				</div>
			</div>
			<div class=form-group>
				<label for=time_end class="col-sm-2 control-label">领取结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_end type=datetime value="<?php echo empty($item['time_end'])? NULL: date('Y-m-d H:i', $item['time_end']); ?>" placeholder="例如：<?php echo date('Y-m-d H:i', strtotime('+31days')) ?>；留空则长期有效">
					<?php require_once(APPPATH. 'views/templates/time_end_hint.php') ?>
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