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
                <label for=user_id class="col-sm-2 control-label">所属用户ID</label>
                <div class=col-sm-10>
                    <input class=form-control name=user_id type=text value="<?php echo set_value('user_id') ?>" placeholder="所属用户ID" required>
                </div>
            </div>
            <div class=form-group>
                <label for=template_id class="col-sm-2 control-label">所属优惠券模板ID</label>
                <div class=col-sm-10>
                    <input class=form-control name=template_id type=text value="<?php echo set_value('template_id') ?>" placeholder="所属优惠券模板ID" required>
                </div>
            </div>
            <div class=form-group>
                <label for=time_expire class="col-sm-2 control-label">失效时间</label>
                <div class=col-sm-10>
                    <input class=form-control name=time_expire type=text value="<?php echo set_value('time_expire') ?>" placeholder="失效时间">
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