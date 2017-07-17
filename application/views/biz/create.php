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

<base href="<?php echo base_url('uploads/') ?>">

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
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-primary" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<fieldset>
			<p class="bg-info text-info text-center">带有“※”符号的为必填项</p>

			<div class=form-group>
				<label for=tel_protected_biz class="col-sm-2 control-label">商务联系手机号</label>
				<div class=col-sm-10>
					<p class="form-control-static">
						<?php echo $this->session->mobile ?> *我们将会通过此手机号与您取得联系；您可在入驻申请通过后申请修改商务联系手机号。
					</p>
				</div>
			</div>
			
			<div class=form-group>
				<label for=name class="col-sm-2 control-label">商家名称※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="请与营业执照保持一致" required>
				</div>
			</div>
			<div class=form-group>
				<label for=brief_name class="col-sm-2 control-label">简称※</label>
				<div class=col-sm-10>
					<input class=form-control name=brief_name type=text value="<?php echo set_value('brief_name') ?>" placeholder="例如“SELECTED”" required>
				</div>
			</div>
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">简介</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=5 placeholder="简介"><?php echo set_value('description') ?></textarea>
				</div>
			</div>
			<div class=form-group>
				<label for=tel_public class="col-sm-2 control-label">消费者联系电话※</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_public type=tel value="<?php echo set_value('tel_public') ?>" placeholder="400/800、手机号、带区号的固定电话号码均可" required>
				</div>
			</div>
		</fieldset>
		
		<fieldset>
			<div class=form-group>
				<label for=code_license class="col-sm-2 control-label">统一社会信用代码※</label>
				<div class=col-sm-10>
					<input class=form-control name=code_license type=number step=1 size=18 value="<?php echo set_value('code_license') ?>" placeholder="即三证合一后的营业执照编号" required>
				</div>
			</div>
			<div class=form-group>
				<label for=code_ssn_owner class="col-sm-2 control-label">法人身份证号※</label>
				<div class=col-sm-10>
					<input class=form-control name=code_ssn_owner type=number step=1 size=18 value="<?php echo set_value('code_ssn_owner') ?>" placeholder="法人身份证号" required>
				</div>
			</div>
			<div class=form-group>
				<label for=code_ssn_auth class="col-sm-2 control-label">经办人身份证号</label>
				<div class=col-sm-10>
					<input class=form-control name=code_ssn_auth type=number step=1 size=18 value="<?php echo set_value('code_ssn_auth') ?>" placeholder="如果负责业务对接的不是法人本人，请填写此项">
				</div>
			</div>
		</fieldset>

		<div class="jumbotron row">
			<p>继续填写可以让您的入驻申请得到优先受理；您也可以仅提供上述信息并<p>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-default btn-lg btn-block" type=submit>排队申请</button>
		    </div>
		</div>

		<fieldset>
			<legend>财务信息</legend>

			<div class=form-group>
				<label for=bank_name class="col-sm-2 control-label">开户行名称</label>
				<div class=col-sm-10>
					<input class=form-control name=bank_name type=text value="<?php echo set_value('bank_name') ?>" placeholder="须与营业执照一致">
				</div>
			</div>
			<div class=form-group>
				<label for=bank_account class="col-sm-2 control-label">开户行账号</label>
				<div class=col-sm-10>
					<input class=form-control name=bank_account type=number step=1 value="<?php echo set_value('bank_account') ?>" placeholder="开户行账号">
				</div>
			</div>
			<div class=form-group>
				<label for=tel_protected_fiscal class="col-sm-2 control-label">财务联系手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_protected_fiscal type=tel size=11 value="<?php echo set_value('tel_protected_fiscal') ?>" placeholder="财务联系手机号">
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>资质照片</legend>
			<div class=form-group>
				<label for=url_image_license class="col-sm-2 control-label">营业执照</label>
				<div class=col-sm-10>
					<p class=help-block>请上传大小在2M以内，边长不超过2048px的jpg/png图片</p>

					<?php $name_to_upload = 'url_image_license' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" required>

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir="biz/license" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>

			<div class=form-group>
				<label for=url_image_owner_id class="col-sm-2 control-label">法人身份证</label>
				<div class=col-sm-10>
					<?php $name_to_upload = 'url_image_owner_id' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" required>

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir="biz/owner_id" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>

			<div class=form-group>
				<label for=url_image_auth_id class="col-sm-2 control-label">经办人身份证</label>
				<div class=col-sm-10>
					<?php $name_to_upload = 'url_image_auth_id' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" required>

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir="biz/auth_id" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>
			
			<div class=form-group>
				<label for=url_image_auth_doc class="col-sm-2 control-label">授权书</label>
				<div class=col-sm-10>
					<?php $name_to_upload = 'url_image_auth_doc' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>" required>

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir="biz/auth_doc" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>经营照片</legend>
			<p>您可根据自身业态，向平台用户展现相应的经营情况。</p>

			<div class=form-group>
				<label for=url_image_product class="col-sm-2 control-label">产品</label>
				<div class=col-sm-10>
					<p class=help-block>最多可上传4张，选择时按住“ctrl”或“⌘”键可选多张</p>

					<?php $name_to_upload = 'url_image_product' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir="biz/product" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>

			<div class=form-group>
				<label for=url_image_produce class="col-sm-2 control-label">工厂/产地</label>
				<div class=col-sm-10>
					<?php $name_to_upload = 'url_image_produce' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir="biz/produce" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>

			<div class=form-group>
				<label for=url_image_retail class="col-sm-2 control-label">门店/柜台</label>
				<div class=col-sm-10>
					<?php $name_to_upload = 'url_image_retail' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir="biz/retail" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>提交入驻申请</button>
		    </div>
		</div>
	</form>

</div>