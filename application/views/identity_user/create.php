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
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<legend>基本信息</legend>

            <div class=form-group>
                <label for=fullname class="col-sm-2 control-label">姓名</label>
                <div class=col-sm-10>
                    <input class=form-control name=fullname type=text value="<?php echo set_value('fullname') ?>" placeholder="姓名" required>
                </div>
            </div>
            <div class=form-group>
                <label for=code_ssn_owner class="col-sm-2 control-label">身份证号</label>
                <div class=col-sm-10>
                    <input class=form-control name=code_ssn_owner type=text value="<?php echo set_value('code_ssn_owner') ?>" placeholder="身份证号" required>
                </div>
            </div>
            <div class=form-group>
                <label for=url_image_owner_id class="col-sm-2 control-label">身份证照片</label>
                <div class=col-sm-10>
                    <input class=form-control name=url_image_owner_id type=text value="<?php echo set_value('url_image_owner_id') ?>" placeholder="身份证照片" required>
                </div>
            </div>
            <div class=form-group>
                <label for=url_verify_photo class="col-sm-2 control-label">用户持身份证照片</label>
                <div class=col-sm-10>
                    <input class=form-control name=url_verify_photo type=text value="<?php echo set_value('url_verify_photo') ?>" placeholder="用户持身份证照片" required>
                </div>
            </div>
            <div class=form-group>
                <label for=bank_name class="col-sm-2 control-label">开户行名称</label>
                <div class=col-sm-10>
                    <input class=form-control name=bank_name type=text value="<?php echo set_value('bank_name') ?>" placeholder="开户行名称" required>
                </div>
            </div>
            <div class=form-group>
                <label for=bank_account class="col-sm-2 control-label">开户行账号</label>
                <div class=col-sm-10>
                    <input class=form-control name=bank_account type=text value="<?php echo set_value('bank_account') ?>" placeholder="开户行账号" required>
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