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

        // 选中默认配送方式及服务商（若有）
        var default_deliver_method = Cookies.get('default_deliver_method');
        //console.log(default_deliver_method);
        if (default_deliver_method != undefined){
            deliver_method_tab = $('[data-deliver_method-value='+ default_deliver_method +']');
            deliver_method_clicked( deliver_method_tab );

            // 选中默认服务商
            var default_deliver_biz = Cookies.get('default_deliver_biz');
            //console.log(default_deliver_biz);
            if (default_deliver_biz != undefined){
                deliver_biz_tab = $('[name=deliver_biz] option[value='+ default_deliver_biz +']');
                deliver_biz_tab.attr('selected', true);
                deliver_biz_clicked( deliver_biz_tab );
            }
        }

        // 若用户自提，不需要填写服务商和运单号
        $('#deliver_method li').click(function(){
            deliver_method_clicked( $(this) );
        });

        function deliver_method_clicked(object)
        {
            // 突出显示被选中项
            object.siblings('li').removeClass('btn-primary');
            object.addClass('btn-primary');

            // 获取被选值
            var deliver_method = object.attr('data-deliver_method-value');
            $('[name=deliver_method]').val(deliver_method);
            $('[for=deliver_biz]').text(deliver_method + '商'); // 更新服务商字段label文案

            // 保存被选值为默认配送方式
            Cookies.set('default_deliver_method', deliver_method);

            $('.optional-input').hide(); // 隐藏所有可选字段
            if (deliver_method === '用户自提')
            {
                $('[name=password]').focus();
            } else {
                $('#deliver_biz, #waybill_id').show();
                $('[name=waybill_id]').focus();
            }

            $('#password').show();
        }

        // 若同城配送商为自营，提示不需要填写运单号
        $('#waybill_notice').hide();
        $('[name=deliver_biz]').change(function(){
            deliver_biz_clicked( $(this) );
        });
        function deliver_biz_clicked(object)
        {
            var deliver_method = $('#deliver_method li.btn-primary').attr('data-deliver_method-value');
            var deliver_biz = object.val();

            // 保存被选值为默认服务商
            Cookies.set('default_deliver_biz', deliver_biz);

            $('#waybill_notice').hide(); // 隐藏运单号提示
            if (deliver_method === '同城配送' && deliver_biz === '自营')
            {
                $('#waybill_notice').show();
            }

            $('[name=waybill_id]').focus();
        }
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
		<p>确定要发货？</p>
        <p>请选择发货方式，并填写相应信息。</p>
	</div>

    <?php
    if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
    $attributes = array('class' => 'form-'.$this->class_name.'-'.$op_name.' form-horizontal', 'role' => 'form');
    echo form_open($this->class_name.'/'.$op_name, $attributes);
    ?>
    <fieldset>
        <input name=ids type=hidden value="<?php echo $ids ?>">

			<div class=form-group>
				<label for=deliver_method class="col-sm-2 control-label">发货方式</label>
				<div class=col-sm-10>
                    <input name=deliver_method type=hidden>

                    <ul id=deliver_method class="btn-group btn-group-justified" role=group>
					<?php
						$input_name = 'deliver_method';
						$options = array('物流快递', '同城配送', '用户自提',);
						foreach ($options as $option):
					?>
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
							$options = array('进来物流', '顺丰速运', '韵达', '达达', '蜂鸟', '圆通', '中通', '申通', '百世汇通', '天天');
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
					<input class=form-control name=waybill_id type=text placeholder="请输入运单号">
					<p id=waybill_notice class=help-block>同城配送商选择自营时可留空</p>
				</div>
			</div>

		<!--
            <div id=password class="form-group optional-input">
				<label for=password class="col-sm-2 control-label">密码</label>
				<div class=col-sm-10>
					<input class=form-control name=password type=password placeholder="请输入您的登录密码" required>
				</div>
			</div>
		-->
    </fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-warning btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>

	</form>
</div>