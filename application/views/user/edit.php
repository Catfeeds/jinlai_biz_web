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
		$('[type=date]').datepicker(
			{
			    language: 'cn', // 本地化语言在js/main.js中
			    minDate: new Date("<?php echo date('Y-m-d', strtotime("-120years")) ?>"),
				maxDate: new Date("<?php echo date('Y-m-d', strtotime("-14years")) ?>"),
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
			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">
			
			<div class=form-group>
				<label for=avatar class="col-sm-2 control-label">头像</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'avatar' ?>
                    <ul class=upload_preview>
                        <?php if ( !empty($item[$name_to_upload]) ): ?>

                            <li data-input-name="<?php echo $name_to_upload ?>" data-item-url="<?php echo $item[$name_to_upload] ?>">
                                <i class="remove fa fa-minus"></i>
                                <i class="left fa fa-arrow-left"></i>
                                <i class="right fa fa-arrow-right"></i>
                                <figure>
                                    <img src="<?php echo $item[$name_to_upload] ?>">
                                </figure>
                            </li>

                        <?php endif ?>
                    </ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo $item[$name_to_upload] ?>">

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name.'/'.$name_to_upload ?>" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> data-max-count=1 type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

                    <p class=help-block>正方形图片视觉效果最佳</p>
                </div>
			</div>
			
			<div class=form-group>
				<label for=mobile class="col-sm-2 control-label">手机号</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $item['mobile'] ?></p>
				</div>
			</div>

			<div class=form-group>
				<label for=nickname class="col-sm-2 control-label">昵称</label>
				<div class=col-sm-10>
					<input class=form-control name=nickname type=text value="<?php echo $item['nickname'] ?>" placeholder="昵称">
				</div>
			</div>
			<div class=form-group>
				<label for=lastname class="col-sm-2 control-label">姓氏</label>
				<div class=col-sm-10>
					<input class=form-control name=lastname type=text value="<?php echo $item['lastname'] ?>" placeholder="姓氏">
				</div>
			</div>
			<div class=form-group>
				<label for=firstname class="col-sm-2 control-label">名</label>
				<div class=col-sm-10>
					<input class=form-control name=firstname type=text value="<?php echo $item['firstname'] ?>" placeholder="名">
				</div>
			</div>

			<div class=form-group>
				<label for=gender class="col-sm-2 control-label">性别</label>
				<div class=col-sm-10>
					<?php
						$input_name = 'gender';
						$options = array('女', '男');
						foreach ($options as $option):
					?>
					<label class=radio-inline>
						<input type=radio name="<?php echo $input_name ?>" value="<?php echo $option ?>" required <?php if ($item[$input_name] === $option) echo 'checked'; ?>> <?php echo $option ?>
					</label>
					<?php endforeach ?>
				</div>
			</div>

			<div class=form-group>
				<label for=dob class="col-sm-2 control-label">生日</label>
				<div class=col-sm-10>
					<input class="form-control" name=dob type=date min="<?php echo date('Y-m-d', strtotime("-120years")) ?>" max="<?php echo date('Y-m-d', strtotime("-14years")) ?>" value="<?php echo $item['dob'] ?>" placeholder="例如：<?php echo date('Y-m-d', strtotime("-24years")) ?>">
                    <p class=help-block>请输入公历/阳历生日</p>
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