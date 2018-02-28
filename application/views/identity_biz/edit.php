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
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<legend>基本信息</legend>

			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

            <div class=form-group>
                <label for=name class="col-sm-2 control-label">主体名称 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="主体名称" required>
                </div>
            </div>
            <div class=form-group>
                <label for=fullname_owner class="col-sm-2 control-label">法人姓名 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=fullname_owner type=text value="<?php echo $item['fullname_owner'] ?>" placeholder="法人姓名" required>
                </div>
            </div>
            <div class=form-group>
                <label for=fullname_auth class="col-sm-2 control-label">经办人姓名 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=fullname_auth type=text value="<?php echo $item['fullname_auth'] ?>" placeholder="经办人姓名" required>
                </div>
            </div>
            <div class=form-group>
                <label for=code_license class="col-sm-2 control-label">工商注册号 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=code_license type=text value="<?php echo $item['code_license'] ?>" placeholder="工商注册号" required>
                </div>
            </div>
            <div class=form-group>
                <label for=code_ssn_owner class="col-sm-2 control-label">法人身份证号 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=code_ssn_owner type=text value="<?php echo $item['code_ssn_owner'] ?>" placeholder="法人身份证号" required>
                </div>
            </div>
            <div class=form-group>
                <label for=code_ssn_auth class="col-sm-2 control-label">经办人身份证号 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=code_ssn_auth type=text value="<?php echo $item['code_ssn_auth'] ?>" placeholder="经办人身份证号" required>
                </div>
            </div>

            <div class=form-group>
                <label for=url_image_license class="col-sm-2 control-label">营业执照 ※</label>
                <div class=col-sm-10>
                    <?php
                    require_once(APPPATH. 'views/templates/file-uploader.php');
                    $name_to_upload = 'url_image_license';
                    generate_html($name_to_upload, $this->class_name, TRUE, 1, $item[$name_to_upload]);
                    ?>
                </div>
            </div>
            <div class=form-group>
                <label for=url_image_owner_ssn class="col-sm-2 control-label">法人身份证 ※</label>
                <div class=col-sm-10>
                    <?php
                    $name_to_upload = 'url_image_owner_ssn';
                    generate_html($name_to_upload, $this->class_name, TRUE, 1, $item[$name_to_upload]);
                    ?>
                </div>
            </div>
            <div class=form-group>
                <label for=url_image_auth_ssn class="col-sm-2 control-label">经办人身份证 ※</label>
                <div class=col-sm-10>
                    <?php
                    $name_to_upload = 'url_image_auth_ssn';
                    generate_html($name_to_upload, $this->class_name, TRUE, 1, $item[$name_to_upload]);
                    ?>
                </div>
            </div>
            <div class=form-group>
                <label for=url_image_auth_doc class="col-sm-2 control-label">经办人授权书 ※</label>
                <div class=col-sm-10>
                    <?php
                    $name_to_upload = 'url_image_auth_doc';
                    generate_html($name_to_upload, $this->class_name, TRUE, 1, $item[$name_to_upload]);
                    ?>
                </div>
            </div>
            <div class=form-group>
                <label for=url_verify_photo class="col-sm-2 control-label">经办人持身份证照片 ※</label>
                <div class=col-sm-10>
                    <?php
                    $name_to_upload = 'url_verify_photo';
                    generate_html($name_to_upload, $this->class_name, TRUE, 1, $item[$name_to_upload]);
                    ?>
                </div>
            </div>
            <!--
            <div class=form-group>
                <label for=nation class="col-sm-2 control-label">国家 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=nation type=text value="<?php echo $item['nation'] ?>" placeholder="国家" required>
                </div>
            </div>
            -->
            <div class=form-group>
                <label for=province class="col-sm-2 control-label">省 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=province type=text value="<?php echo $item['province'] ?>" placeholder="省" required>
                </div>
            </div>
            <div class=form-group>
                <label for=city class="col-sm-2 control-label">市 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=city type=text value="<?php echo $item['city'] ?>" placeholder="市" required>
                </div>
            </div>
            <div class=form-group>
                <label for=county class="col-sm-2 control-label">区 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=county type=text value="<?php echo $item['county'] ?>" placeholder="区" required>
                </div>
            </div>
            <div class=form-group>
                <label for=street class="col-sm-2 control-label">具体地址 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=street type=text value="<?php echo $item['street'] ?>" placeholder="具体地址" required>
                </div>
            </div>
            <div class=form-group>
                <label for=bank_name class="col-sm-2 control-label">开户行名称 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=bank_name type=text value="<?php echo $item['bank_name'] ?>" placeholder="开户行名称" required>
                </div>
            </div>
            <div class=form-group>
                <label for=bank_account class="col-sm-2 control-label">开户行账号 ※</label>
                <div class=col-sm-10>
                    <input class=form-control name=bank_account type=text value="<?php echo $item['bank_account'] ?>" placeholder="开户行账号" required>
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