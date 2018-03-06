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
			<legend>基本信息</legend>

			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">
			
			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="名称" required>
				</div>
			</div>
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">描述</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=10 placeholder="描述"><?php echo $item['description'] ?></textarea>
				</div>
			</div>
			<div class=form-group>
				<label for=url_image class="col-sm-2 control-label">形象图</label>
				<div class=col-sm-10>
                    <?php
                    require_once(APPPATH. 'views/templates/file-uploader.php');
                    $name_to_upload = 'url_image';
                    generate_html($name_to_upload, $this->class_name, FALSE, 1, $item[$name_to_upload]);
                    ?>
				</div>
			</div>
			<!--
			<div class=form-group>
				<label for=url_video class="col-sm-2 control-label">形象视频</label>
				<div class=col-sm-10>
					<input class=form-control name=url_video type=text value="<?php echo $item['url_video'] ?>" placeholder="形象视频URL" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_audio class="col-sm-2 control-label">背景音乐</label>
				<div class=col-sm-10>
					<input class=form-control name=url_audio type=text value="<?php echo $item['url_audio'] ?>" placeholder="背景音乐URL" required>
				</div>
			</div>
			-->
			<div class=form-group>
				<label for=url_name class="col-sm-2 control-label">URL名称</label>
				<div class=col-sm-10>
					<input class=form-control name=url_name type=text value="<?php echo $item['url_name'] ?>" placeholder="URL名称" required>
				</div>
			</div>
			<div class=form-group>
				<label for=signup_allowed class="col-sm-2 control-label">可报名</label>
				<div class=col-sm-10>
					<input class=form-control name=signup_allowed type=text value="<?php echo $item['signup_allowed'] ?>" placeholder="可报名" required>
				</div>
			</div>
			
			<div class=form-group>
				<label for=max_user_total class="col-sm-2 control-label">每选民最高总选票数</label>
				<div class=col-sm-10>
					<input class=form-control name=max_user_total type=number min=0 max=999 step=1 value="<?php echo $item['max_user_total'] ?>" placeholder="每选民最高总选票数" required>
				</div>
			</div>
			<div class=form-group>
				<label for=max_user_daily class="col-sm-2 control-label">每选民最高日选票数</label>
				<div class=col-sm-10>
					<input class=form-control name=max_user_daily type=number min=1 max=99 step=1 value="<?php echo $item['max_user_daily'] ?>" placeholder="每选民最高日选票数" required>
				</div>
			</div>
			<div class=form-group>
				<label for=max_user_daily_each class="col-sm-2 control-label">每选民同选项最高日选票数</label>
				<div class=col-sm-10>
					<input class=form-control name=max_user_daily_each type=number min=1 max=99 step=1 value="<?php echo $item['max_user_daily_each'] ?>" placeholder="每选民同选项最高日选票数" required>
				</div>
			</div>
			
			<div class=form-group>
				<label for=time_start class="col-sm-2 control-label">开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_start type=text value="<?php echo $item['time_start'] ?>" placeholder="开始时间" required>
				</div>
			</div>
			<div class=form-group>
				<label for=time_end class="col-sm-2 control-label">结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_end type=text value="<?php echo $item['time_end'] ?>" placeholder="结束时间" required>
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