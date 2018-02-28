<link rel=stylesheet media=all href="/css/create.css">
<style>
    .params {display:none;}
    div.params {border-top:1px solid #aaa;}

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

<script>
	$(function(){
        $('[data-type="物流配送"]').show();

		// 根据所选类型显示相应参数
		$('select[name=type]').change(function(){
			var fieldset_to_show = $(this).find('option:selected').attr('value');
			$('div.params').hide();
			$('[data-type="' + fieldset_to_show + '"]').show();
		});
	});
</script>

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
			<p class=help-block>必填项以“※”符号表示</p>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称 ※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="例如：全场包邮、满2件包邮、满2公斤包邮等" required>
				</div>
			</div>
			<div class=form-group>
				<label for=type class="col-sm-2 control-label">类型 ※</label>
				<div class=col-sm-10>
					<?php $input_name = 'type' ?>
					<select class=form-control name="<?php echo $input_name ?>" required>
						<?php
							$options = array('物流配送', '电子凭证');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>><?php echo $option ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</fieldset>

        <div class=params data-type="电子凭证">
            <fieldset>
                <p class=help-block>若全部留空，则电子凭证自用户付款时起366个自然日内有效，逾期全额退款</p>

                <div class=form-group>
                    <label for=time_valid_from class="col-sm-2 control-label">有效期起始时间</label>
                    <div class=col-sm-10>
                        <input class=form-control name=time_valid_from type=datetime value="<?php echo set_value('time_valid_from') ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+2days')) ?>">
                    </div>
                </div>
                <div class=form-group>
                    <label for=time_valid_end class="col-sm-2 control-label">有效期结束时间</label>
                    <div class=col-sm-10>
                        <input class=form-control name=time_valid_end type=datetime value="<?php echo set_value('time_valid_end') ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+366days')) ?>">
                    </div>
                </div>
                <div class=form-group>
                    <label for=period_valid class="col-sm-2 control-label">有效期（天）</label>
                    <div class=col-sm-10>
                        <input class=form-control name=period_valid type=number step=1 max=366 value="<?php echo set_value('period_valid') ?>" placeholder="最短3天，最长366天；留空则默认为366天">
                        <p class=help-block>若填写了此项，则有效期将根据订单付款时间及此项数值自动计算，若超出上述“有效期结束时间”，则以“有效期结束时间”为准</p>
                    </div>
                </div>
                <div class=form-group>
                    <label for=expire_refund_rate class="col-sm-2 control-label">过期退款比例</label>
                    <div class=col-sm-10>
                        <input class=form-control name=expire_refund_rate type=number step=0.01 max=1 value="<?php echo set_value('expire_refund_rate') ?>" placeholder="例如100%为1，80%为0.8，以此类推；留空则默认为100%">
                        <p class=help-block>若电子凭证逾期未使用，系统将把该比例的用户实付款项原路退回给用户</p>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class=params data-type="物流配送">
            <fieldset>
                <legend>发货地区</legend>
                <!--
                <div class=form-group>
                    <label for=nation class="col-sm-2 control-label">国别</label>
                    <div class=col-sm-10>
                        <input class=form-control name=nation type=text value="<?php echo set_value('nation') ?>" placeholder="可留空，默认中国">
                    </div>
                </div>
                -->
                <div class=form-group>
                    <label for=province class="col-sm-2 control-label">省 ※</label>
                    <div class=col-sm-10>
                        <input class=form-control name=province type=text value="<?php echo set_value('province') ?>" placeholder="省" required>
                    </div>
                </div>
                <div class=form-group>
                    <label for=city class="col-sm-2 control-label">市 ※</label>
                    <div class=col-sm-10>
                        <input class=form-control name=city type=text value="<?php echo set_value('city') ?>" placeholder="市" required>
                    </div>
                </div>
                <div class=form-group>
                    <label for=county class="col-sm-2 control-label">区/县 ※</label>
                    <div class=col-sm-10>
                        <input class=form-control name=county type=text value="<?php echo set_value('county') ?>" placeholder="区/县" required>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <p class=help-block>若以下全部留空，则默认在收款后3个自然日内发货，包邮（运费计算方式为“计件”）</p>

                <div class=form-group>
                    <label for=time_latest_deliver class="col-sm-2 control-label">最晚发货时间</label>
                    <div class=col-sm-10>
                        <?php $input_name = 'time_latest_deliver' ?>
                        <select class=form-control name="<?php echo $input_name ?>">
                            <?php
                                $options = array(
                                    '3天内' => '259200',

                                    '1小时内' => '3600',
                                    '2小时内' => '7200',
                                    '4小时内' => '14400',
                                    '8小时内' => '28800',
                                    '12小时内' => '43200',
                                    '1天内' => '86400',
                                    '2天内' => '172800',
                                    '5天内' => '432000',
                                    '7天内' => '604800',
                                    '10天内' => '864000',
                                    '14天内' => '1209600',
                                    '30天内' => '2592000',
                                    '45天内' => '3888000',
                                );
                                foreach ($options as $name => $value):
                            ?>
                            <option value="<?php echo $value ?>" <?php echo set_select($input_name, $value) ?>><?php echo $name ?></option>
                            <?php endforeach ?>
                        </select>
                        <p class=help-block>若超时无法发货，则系统将自动全额退款</p>
                    </div>
                </div>

                <div class=form-group>
                    <label for=type_actual class="col-sm-2 control-label">运费计算方式</label>
                    <div class=col-sm-10>
                        <?php $input_name = 'type_actual' ?>
                        <select class=form-control name="<?php echo $input_name ?>">
                            <?php
                                $options = array('计件','净重','毛重','体积重');
                                foreach ($options as $option):
                            ?>
                            <option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>><?php echo $option ?></option>
                            <?php endforeach ?>
                        </select>
                        <p class=help-block>计量单位为“件”（计件时）、“KG”（计净重/毛重/体积重时）；商品需要填写相应信息。</p>
                    </div>
                </div>

                <div class=form-group>
                    <label for=max_amount class="col-sm-2 control-label">每单最高量</label>
                    <div class=col-sm-10>
                        <input class=form-control name=max_amount type=number step=1 max=9999 value="<?php echo set_value('max_amount') ?>" placeholder="最高9999">
                    </div>
                </div>
                <div class=form-group>
                    <label for=start_amount class="col-sm-2 control-label">首量</label>
                    <div class=col-sm-10>
                        <input class=form-control name=start_amount type=number step=1 max=9999 value="<?php echo set_value('start_amount') ?>" placeholder="最高9999">
                    </div>
                </div>
                <div class=form-group>
                    <label for=unit_amount class="col-sm-2 control-label">续量</label>
                    <div class=col-sm-10>
                        <input class=form-control name=unit_amount type=number step=1 max=9999 value="<?php echo set_value('unit_amount') ?>" placeholder="最高9999">
                    </div>
                </div>
                <div class=form-group>
                    <label for=fee_start class="col-sm-2 control-label">首量运费（元）</label>
                    <div class=col-sm-10>
                        <input class=form-control name=fee_start type=number step=1 max=999 value="<?php echo set_value('fee_start') ?>" placeholder="最高999">
                    </div>
                </div>
                <div class=form-group>
                    <label for=fee_unit class="col-sm-2 control-label">续量运费（元/续量）</label>
                    <div class=col-sm-10>
                        <input class=form-control name=fee_unit type=number step=1 max=999 value="<?php echo set_value('fee_unit') ?>" placeholder="最高999">
                    </div>
                </div>
                <div class=form-group>
                    <label for=exempt_amount class="col-sm-2 control-label">包邮量</label>
                    <div class=col-sm-10>
                        <input class=form-control name=exempt_amount type=number step=1 max=9999 value="<?php echo set_value('exempt_amount') ?>" placeholder="最高9999">
                        <p class=help-block>达到该量后全单免邮费</p>
                    </div>
                </div>
                <div class=form-group>
                    <label for=exempt_subtotal class="col-sm-2 control-label">包邮商品小计（元）</label>
                    <div class=col-sm-10>
                        <input class=form-control name=exempt_subtotal type=number step=1 max=9999 value="<?php echo set_value('exempt_subtotal') ?>" placeholder="最高9999">
                        <p class=help-block>达到该商品小计后全单免邮费</p>
                    </div>
                </div>
            </fieldset>
        </div>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>

</div>