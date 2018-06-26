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
		$attributes = array('class' => 'form-'.$this->class_name.'-create-import form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create_import', $attributes);
	?>
        <p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<div class=form-group>
				<label for=url_image_main class="col-sm-2 control-label">待导入文件 ※</label>
				<div class=col-sm-10>
                    <input class=form-control name=file_to_upload type=file placeholder="请选择待导入文件" required>

                    <p class=help-block>可识别并导入xlsx格式的文件</p>
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

<script>
    $(function(){
        // 允许上传的文件格式
        var format_allowed = 'xlsx';

        $('[name=file_to_upload]').change(function(){
            var file = $(this).val();
            var file_format = file.substring(file.indexOf('.')+1).toLowerCase(); // 文件后缀名

            if (file_format != format_allowed)
            {
                console.log(file_format);
                alert('请上传' + format_allowed + '格式的文件');
                $(this).val(''); // 清空字段值
            }
        });
    });
</script>