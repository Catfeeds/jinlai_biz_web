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
        $('.optional-input').hide();

        // 若用户自提，不需要填写服务商和运单号
        $('#deliver_method li').click(function(){
            // 突出显示被选中项
            $(this).siblings('li').removeClass('btn-primary');
            $(this).addClass('btn-primary');

            // 获取被选值
            var deliver_method = $(this).attr('data-deliver_method-value');
            $('[for=deliver_biz]').text(deliver_method + '商'); // 更新服务商字段label文案

            $('.optional-input').hide(); // 隐藏所有可选字段
            if (deliver_method !== '用户自提')
            {
                $('#deliver_biz, #waybill_id').show();
            }
        });

        // 若同城配送商为自营，提示不需要填写运单号
        $('#waybill_notice').hide();
        $('[name=deliver_biz]').change(function(){
            var deliver_method = $('#deliver_method li.btn-primary').attr('data-deliver_method-value');
            var deliver_biz = $(this).val();

            $('#waybill_notice').hide(); // 隐藏运单号提示
            if (deliver_method === '同城配送' && deliver_biz === '自营')
            {
                $('#waybill_notice').show();
            }
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
	<table class="table table-striped table-condensed table-responsive">
		<thead>
			<tr>
				<th><?php echo $this->class_name_cn ?>ID</th>
				<?php
					$thead = array_values($data_to_display);
					foreach ($thead as $th):
						echo '<th>' .$th. '</th>';
					endforeach;
				?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo $item[$this->id_name] ?></td>
				<?php
					$tr = array_keys($data_to_display);
					foreach ($tr as $td):
						echo '<td>' .$item[$td]. '</td>';
					endforeach;
				?>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<div class="alert alert-warning" role=alert>
		<p>确定要发货？请选择发货方式，并填写相应信息。</p>
	</div>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-deliver form-horizontal', 'role' => 'form');
		echo form_open($this->class_name.'/deliver', $attributes);
	?>
		<fieldset>
			<input name=ids type=hidden value="<?php echo implode(',', $ids) ?>">

			<div class=form-group>
				<label for=deliver_method class="col-sm-2 control-label">发货方式</label>
				<div class=col-sm-10>
                    <ul id=deliver_method class="btn-group btn-group-justified" role=group>
					<?php
						$input_name = 'deliver_method';
						$options = array('物流快递', '同城配送', '用户自提',);
						foreach ($options as $option):
					?>
					<!--
                    <label class=radio-inline>
						<input type=radio name="<?php echo $input_name ?>" value="<?php echo $option ?>" required <?php echo set_radio($input_name, $option, TRUE) ?>> <?php echo $option ?>
					</label>
					-->
                    <li class="btn btn-default" data-<?php echo $input_name ?>-value="<?php echo $option ?>"><?php echo $option ?></li>

					<?php endforeach ?>
                    </ul>
				</div>
			</div>

			<div id=deliver_biz class="form-group optional-input">
				<label for=deliver_biz class="col-sm-2 control-label"><span>服务商</span></label>
				<div class=col-sm-10>
					<?php $input_name = 'deliver_biz' ?>
					<select class=form-control name="<?php echo $input_name ?>">
						<option value="" <?php echo set_select($input_name, '') ?>>请选择</option>
						<?php
							$options = array('自营', '达达', '蜂鸟', '顺丰速运', '圆通', '中通', '申通', '百世汇通', '天天',);
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>><?php echo $option ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div id=waybill_id class="form-group optional-input">
				<label for=waybill_id class="col-sm-2 control-label">运单号</label>
				<div class=col-sm-10>
					<input class=form-control name=waybill_id type=text placeholder="请输入运单号" autofocus>
					<p id=waybill_notice class=help-block>同城配送商选择自营时可留空</p>
				</div>
			</div>

			<div class=form-group>
				<label for=password class="col-sm-2 control-label">密码</label>
				<div class=col-sm-10>
					<input class=form-control name=password type=password placeholder="请输入您的登录密码" required>
				</div>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-warning btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>

	</form>
</div>