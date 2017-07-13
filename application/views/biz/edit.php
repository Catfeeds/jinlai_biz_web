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
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<fieldset>
			<legend>基本信息</legend>
			
			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

			<div class=form-group>
				<label for=url_logo class="col-sm-2 control-label">LOGO</label>
				<div class=col-sm-10>
					<input class=form-control name=url_logo type=text value="<?php echo $item['url_logo'] ?>" placeholder="LOGO" required>
				</div>
			</div>
			<div class=form-group>
				<label for=slogan class="col-sm-2 control-label">宣传语</label>
				<div class=col-sm-10>
					<input class=form-control name=slogan type=text value="<?php echo $item['slogan'] ?>" placeholder="宣传语" required>
				</div>
			</div>
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">简介</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=10 placeholder="简介" required><?php echo $item['description'] ?></textarea>
				</div>
			</div>
			<div class=form-group>
				<label for=notification class="col-sm-2 control-label">店铺公告</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=10 placeholder="店铺公告" required><?php echo $item['notification'] ?></textarea>
				</div>
			</div>
			<div class=form-group>
				<label for=tel_public class="col-sm-2 control-label">消费者联系电话</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_public type=tel value="<?php echo $item['tel_public'] ?>" placeholder="消费者联系电话" required>
				</div>
			</div>
			<div class=form-group>
				<label for=tel_protected_fiscal class="col-sm-2 control-label">财务联系手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_protected_fiscal type=tel value="<?php echo $item['tel_protected_fiscal'] ?>" placeholder="财务联系手机号" required>
				</div>
			</div>
			<div class=form-group>
				<label for=tel_protected_order class="col-sm-2 control-label">订单通知手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_protected_order type=tel value="<?php echo $item['tel_protected_order'] ?>" placeholder="订单通知手机号" required>
				</div>
			</div>
			<div class=form-group>
				<label for=freight class="col-sm-2 control-label">每笔订单运费（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=freight type=text value="<?php echo $item['freight'] ?>" placeholder="每笔订单运费（元）" required>
				</div>
			</div>
			<div class=form-group>
				<label for=freight_free_subtotal class="col-sm-2 control-label">免邮费起始金额（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=freight_free_subtotal type=text value="<?php echo $item['freight_free_subtotal'] ?>" placeholder="免邮费起始金额（元）" required>
				</div>
			</div>
			<div class=form-group>
				<label for=freight_free_count class="col-sm-2 control-label">免邮费起始份数（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=freight_free_count type=text value="<?php echo $item['freight_free_count'] ?>" placeholder="免邮费起始份数（份）" required>
				</div>
			</div>
			<div class=form-group>
				<label for=min_order_subtotal class="col-sm-2 control-label">最低小计金额（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=min_order_subtotal type=text value="<?php echo $item['min_order_subtotal'] ?>" placeholder="最低小计金额（元）" required>
				</div>
			</div>
			<div class=form-group>
				<label for=delivery_time_start class="col-sm-2 control-label">配送起始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=delivery_time_start type=text value="<?php echo $item['delivery_time_start'] ?>" placeholder="配送起始时间" required>
				</div>
			</div>
			<div class=form-group>
				<label for=delivery_time_end class="col-sm-2 control-label">配送结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=delivery_time_end type=text value="<?php echo $item['delivery_time_end'] ?>" placeholder="配送结束时间" required>
				</div>
			</div>
			<div class=form-group>
				<label for=country class="col-sm-2 control-label">国家</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $item['country'] ?></p>
				</div>
			</div>
			<div class=form-group>
				<label for=province class="col-sm-2 control-label">省</label>
				<div class=col-sm-10>
					<input class=form-control name=province type=text value="<?php echo $item['province'] ?>" placeholder="省" required>
				</div>
			</div>
			<div class=form-group>
				<label for=city class="col-sm-2 control-label">市</label>
				<div class=col-sm-10>
					<input class=form-control name=city type=text value="<?php echo $item['city'] ?>" placeholder="市" required>
				</div>
			</div>
			<div class=form-group>
				<label for=county class="col-sm-2 control-label">区</label>
				<div class=col-sm-10>
					<input class=form-control name=county type=text value="<?php echo $item['county'] ?>" placeholder="区" required>
				</div>
			</div>
			<div class=form-group>
				<label for=detail class="col-sm-2 control-label">详细地址</label>
				<div class=col-sm-10>
					<input class=form-control name=detail type=text value="<?php echo $item['detail'] ?>" placeholder="详细地址" required>
				</div>
			</div>
			<div class=form-group>
				<label for=longitude class="col-sm-2 control-label">经度</label>
				<div class=col-sm-10>
					<input class=form-control name=longitude type=text value="<?php echo $item['longitude'] ?>" placeholder="经度" required>
				</div>
			</div>
			<div class=form-group>
				<label for=latitude class="col-sm-2 control-label">纬度</label>
				<div class=col-sm-10>
					<input class=form-control name=latitude type=text value="<?php echo $item['latitude'] ?>" placeholder="纬度" required>
				</div>
			</div>
			<div class=form-group>
				<label for=bank_name class="col-sm-2 control-label">开户行名称</label>
				<div class=col-sm-10>
					<input class=form-control name=bank_name type=text value="<?php echo $item['bank_name'] ?>" placeholder="开户行名称" required>
				</div>
			</div>
			<div class=form-group>
				<label for=bank_account class="col-sm-2 control-label">开户行账号</label>
				<div class=col-sm-10>
					<input class=form-control name=bank_account type=text value="<?php echo $item['bank_account'] ?>" placeholder="开户行账号" required>
				</div>
			</div>
			<div class=form-group>
				<label for=code_license class="col-sm-2 control-label">统一社会信用代码</label>
				<div class=col-sm-10>
					<input class=form-control name=code_license type=text value="<?php echo $item['code_license'] ?>" placeholder="统一社会信用代码" required>
				</div>
			</div>
			<div class=form-group>
				<label for=code_ssn_owner class="col-sm-2 control-label">法人身份证号</label>
				<div class=col-sm-10>
					<input class=form-control name=code_ssn_owner type=text value="<?php echo $item['code_ssn_owner'] ?>" placeholder="法人身份证号" required>
				</div>
			</div>
			<div class=form-group>
				<label for=code_ssn_auth class="col-sm-2 control-label">经办人身份证号</label>
				<div class=col-sm-10>
					<input class=form-control name=code_ssn_auth type=text value="<?php echo $item['code_ssn_auth'] ?>" placeholder="经办人身份证号" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_image_license class="col-sm-2 control-label">营业执照</label>
				<div class=col-sm-10>
					<input class=form-control name=url_image_license type=text value="<?php echo $item['url_image_license'] ?>" placeholder="营业执照" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_image_owner_id class="col-sm-2 control-label">法人身份证</label>
				<div class=col-sm-10>
					<input class=form-control name=url_image_owner_id type=text value="<?php echo $item['url_image_owner_id'] ?>" placeholder="法人身份证" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_image_auth_id class="col-sm-2 control-label">经办人身份证</label>
				<div class=col-sm-10>
					<input class=form-control name=url_image_auth_id type=text value="<?php echo $item['url_image_auth_id'] ?>" placeholder="经办人身份证" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_image_auth_doc class="col-sm-2 control-label">授权书</label>
				<div class=col-sm-10>
					<input class=form-control name=url_image_auth_doc type=text value="<?php echo $item['url_image_auth_doc'] ?>" placeholder="授权书" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_web class="col-sm-2 control-label">官方网站</label>
				<div class=col-sm-10>
					<input class=form-control name=url_web type=text value="<?php echo $item['url_web'] ?>" placeholder="官方网站" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_weibo class="col-sm-2 control-label">官方微博</label>
				<div class=col-sm-10>
					<input class=form-control name=url_weibo type=text value="<?php echo $item['url_weibo'] ?>" placeholder="官方微博" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_taobao class="col-sm-2 control-label">淘宝/天猫店铺</label>
				<div class=col-sm-10>
					<input class=form-control name=url_taobao type=text value="<?php echo $item['url_taobao'] ?>" placeholder="淘宝/天猫店铺" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_wechat class="col-sm-2 control-label">微信二维码</label>
				<div class=col-sm-10>
					<input class=form-control name=url_wechat type=text value="<?php echo $item['url_wechat'] ?>" placeholder="微信二维码" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_image_product class="col-sm-2 control-label">产品</label>
				<div class=col-sm-10>
					<input class=form-control name=url_image_product type=text value="<?php echo $item['url_image_product'] ?>" placeholder="产品" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_image_produce class="col-sm-2 control-label">工厂/产地</label>
				<div class=col-sm-10>
					<input class=form-control name=url_image_produce type=text value="<?php echo $item['url_image_produce'] ?>" placeholder="工厂/产地" required>
				</div>
			</div>
			<div class=form-group>
				<label for=url_image_retail class="col-sm-2 control-label">门店/柜台</label>
				<div class=col-sm-10>
					<input class=form-control name=url_image_retail type=text value="<?php echo $item['url_image_retail'] ?>" placeholder="门店/柜台" required>
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