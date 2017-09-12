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

<div id=content class=container>
	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<legend>基本资料</legend>

			<div class=form-group>
				<label for=tel_protected_biz class="col-sm-2 control-label">商务联系手机号</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $this->session->mobile ?></p>
					<p class=help-block>我们将通过该号码与您取得联系，您可在入驻申请通过后通过专属顾问修改该信息。</p>
				</div>
			</div>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">商家名称※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="请与营业执照保持一致" required>
					<p class=help-block>只支持中国大陆工商局或市场监督管理局登记的企业。请填写工商营业执照上的企业全称，该名称将作为后续所有费用的发票抬头。</p>
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
					<textarea class=form-control name=description rows=5 placeholder="最多255个字符，请简述企业主要经营范围、主营产品等信息"><?php echo set_value('description') ?></textarea>
				</div>
			</div>

			<div class=form-group>
				<label for=tel_public class="col-sm-2 control-label">消费者服务电话※</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_public type=tel value="<?php echo set_value('tel_public') ?>" placeholder="400、800、手机号、带区号的固定电话号码均可" required>
					<p class=help-block>即客服电话，不要加空格或其它符号，固定电话请填写区号</p>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>资质信息</legend>

			<div class=form-group>
				<label for=code_license class="col-sm-2 control-label">工商注册号※</label>
				<div class=col-sm-10>
					<input class=form-control name=code_license type=text value="<?php echo set_value('code_license') ?>" placeholder="如为三证合一，请填写统一社会信用代码" required>
					<p class=help-block>请填写营业执照上的15位工商注册号；或三证合一后18位的统一社会信用代码。</p>
				</div>
			</div>
			<div class=form-group>
				<label for=fullname_owner class="col-sm-2 control-label">法人姓名※</label>
				<div class=col-sm-10>
					<input class=form-control name=fullname_owner type=text size=15 value="<?php echo set_value('fullname_owner') ?>" placeholder="需与身份证一致" required>
					<p class=help-block>按照营业执照上填写。如果属于分公司则填写工商营业执照上明确的负责人，个体工商户请填写经营者姓名，合伙企业请填写合伙人姓名，个人独资企业请填写投资人姓名，企业法人的非法人分支机构填写负责人姓名。 </p>
				</div>
			</div>
			<div class=form-group>
				<label for=code_ssn_owner class="col-sm-2 control-label">法人身份证号※</label>
				<div class=col-sm-10>
					<input class=form-control name=code_ssn_owner type=text value="<?php echo set_value('code_ssn_owner') ?>" placeholder="请输入18位有效身份证号" required>
				</div>
			</div>

			<div class=form-group>
				<label for=fullname_auth class="col-sm-2 control-label">经办人姓名</label>
				<div class=col-sm-10>
					<input class=form-control name=fullname_auth type=text size=15 value="<?php echo set_value('fullname_auth') ?>" placeholder="需与身份证一致">
					<p class=help-block>如果负责业务对接的不是法人本人，请填写此项</p>
				</div>
			</div>
			<div class=form-group>
				<label for=code_ssn_auth class="col-sm-2 control-label">经办人身份证号</label>
				<div class=col-sm-10>
					<input class=form-control name=code_ssn_auth type=text value="<?php echo set_value('code_ssn_auth') ?>" placeholder="请输入18位有效身份证号">
					<p class=help-block>如果负责业务对接的不是法人本人，请填写此项</p>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>财务信息</legend>

			<div class=form-group>
				<label for=bank_name class="col-sm-2 control-label">对公账户开户行</label>
				<div class=col-sm-10>
					<input class=form-control name=bank_name type=text value="<?php echo set_value('bank_name') ?>" placeholder="基本户、一般户均可">
				</div>
			</div>
			<div class=form-group>
				<label for=bank_account class="col-sm-2 control-label">对公账户账号</label>
				<div class=col-sm-10>
					<input class=form-control name=bank_account type=number step=1 value="<?php echo set_value('bank_account') ?>" placeholder="基本户、一般户均可">
					<p class=help-block>我们会向该对公帐户汇入一笔非常小的金额和备注信息，需要您后续与审核人员确认。 </p>
				</div>
			</div>
			<div class=form-group>
				<label for=tel_protected_fiscal class="col-sm-2 control-label">财务联系手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_protected_fiscal type=tel size=11 value="<?php echo set_value('tel_protected_fiscal') ?>" placeholder="财务联系手机号">
				</div>
			</div>
		</fieldset>

		<div class="jumbotron row">
			<p>继续完善更多信息将可优先申请；仅提供上述信息则需<p>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-default btn-lg btn-block" type=submit>排队申请</button>
		    </div>
		</div>

		<fieldset>
			<legend>资质及授权证明</legend>
			<p class=help-block>以下资料需要彩色原件的扫描件或数码照</p>

			<div class=form-group>
				<label for=url_image_license class="col-sm-2 control-label">营业执照</label>
				<div class=col-sm-10>
					<?php $name_to_upload = 'url_image_license' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="biz/license" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>

			<div class=form-group>
				<label for=url_image_owner_id class="col-sm-2 control-label">法人身份证</label>
				<div class=col-sm-10>
					<?php $name_to_upload = 'url_image_owner_id' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="biz/owner_id" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>

			<div class=form-group>
				<label for=url_image_auth_id class="col-sm-2 control-label">经办人身份证</label>
				<div class=col-sm-10>
					<p class=help-block>如果负责业务对接的不是法人本人，请上传经办人身份证</p>

					<?php $name_to_upload = 'url_image_auth_id' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="biz/auth_id" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>

			<div class=form-group>
				<label for=url_image_auth_doc class="col-sm-2 control-label">经办人授权书</label>
				<div class=col-sm-10>
					<p class=help-block>
						如果负责业务对接的不是法人本人，请上传授权书
						<small><a title="进来商城经办人授权书" href="<?php echo base_url('article/auth-doc-for-admission') ?>" target=_blank><i class="fa fa-info-circle" aria-hidden=true></i> 授权书示例</a></small>
					</p>

					<?php $name_to_upload = 'url_image_auth_doc' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="biz/auth_doc" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>形象展示</legend>
			<p class=help-block>您可根据自身情况上传合适的照片，向消费者展现企业形象，每种照片可上传4张</p>

			<div class=form-group>
				<label for=url_image_product class="col-sm-2 control-label">产品</label>
				<div class=col-sm-10>
					<?php $name_to_upload = 'url_image_product' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="biz/product" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>

			<div class=form-group>
				<label for=url_image_produce class="col-sm-2 control-label">工厂/产地</label>
				<div class=col-sm-10>
					<?php $name_to_upload = 'url_image_produce' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="biz/produce" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>

			<div class=form-group>
				<label for=url_image_retail class="col-sm-2 control-label">门店/柜台</label>
				<div class=col-sm-10>
					<?php $name_to_upload = 'url_image_retail' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="biz/retail" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<p class=help-block>点击“提交入驻申请”代表您已阅读并同意<a href="<?php echo base_url('article/agreement-admission') ?>" target=_blank>入驻协议</a></p>
				<button class="btn btn-primary btn-lg btn-block" type=submit>提交入驻申请</button>
		    </div>
		</div>
	</form>

</div>