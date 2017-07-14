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
							<label for=user_id class="col-sm-2 control-label">用户ID</label>
							<div class=col-sm-10>
								<input class=form-control name=user_id type=text value="<?php echo $item['user_id'] ?>" placeholder="用户ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=password class="col-sm-2 control-label">密码</label>
							<div class=col-sm-10>
								<input class=form-control name=password type=text value="<?php echo $item['password'] ?>" placeholder="密码" required>
							</div>
						</div>
						<div class=form-group>
							<label for=nickname class="col-sm-2 control-label">昵称</label>
							<div class=col-sm-10>
								<input class=form-control name=nickname type=text value="<?php echo $item['nickname'] ?>" placeholder="昵称" required>
							</div>
						</div>
						<div class=form-group>
							<label for=lastname class="col-sm-2 control-label">姓氏</label>
							<div class=col-sm-10>
								<input class=form-control name=lastname type=text value="<?php echo $item['lastname'] ?>" placeholder="姓氏" required>
							</div>
						</div>
						<div class=form-group>
							<label for=firstname class="col-sm-2 control-label">名</label>
							<div class=col-sm-10>
								<input class=form-control name=firstname type=text value="<?php echo $item['firstname'] ?>" placeholder="名" required>
							</div>
						</div>
						<div class=form-group>
							<label for=code_ssn class="col-sm-2 control-label">身份证号</label>
							<div class=col-sm-10>
								<input class=form-control name=code_ssn type=text value="<?php echo $item['code_ssn'] ?>" placeholder="身份证号" required>
							</div>
						</div>
						<div class=form-group>
							<label for=url_image_id class="col-sm-2 control-label">身份证照片</label>
							<div class=col-sm-10>
								<input class=form-control name=url_image_id type=text value="<?php echo $item['url_image_id'] ?>" placeholder="身份证照片" required>
							</div>
						</div>
						<div class=form-group>
							<label for=gender class="col-sm-2 control-label">性别</label>
							<div class=col-sm-10>
								<input class=form-control name=gender type=text value="<?php echo $item['gender'] ?>" placeholder="性别" required>
							</div>
						</div>
						<div class=form-group>
							<label for=dob class="col-sm-2 control-label">出生日期</label>
							<div class=col-sm-10>
								<input class=form-control name=dob type=text value="<?php echo $item['dob'] ?>" placeholder="出生日期" required>
							</div>
						</div>
						<div class=form-group>
							<label for=avatar class="col-sm-2 control-label">头像</label>
							<div class=col-sm-10>
								<input class=form-control name=avatar type=text value="<?php echo $item['avatar'] ?>" placeholder="头像" required>
							</div>
						</div>
						<div class=form-group>
							<label for=mobile class="col-sm-2 control-label">手机号</label>
							<div class=col-sm-10>
								<input class=form-control name=mobile type=text value="<?php echo $item['mobile'] ?>" placeholder="手机号" required>
							</div>
						</div>
						<div class=form-group>
							<label for=email class="col-sm-2 control-label">电子邮件地址</label>
							<div class=col-sm-10>
								<input class=form-control name=email type=text value="<?php echo $item['email'] ?>" placeholder="电子邮件地址" required>
							</div>
						</div>
						<div class=form-group>
							<label for=wechat_union_id class="col-sm-2 control-label">微信用户的union_id</label>
							<div class=col-sm-10>
								<input class=form-control name=wechat_union_id type=text value="<?php echo $item['wechat_union_id'] ?>" placeholder="微信用户的union_id" required>
							</div>
						</div>
						<div class=form-group>
							<label for=address_id class="col-sm-2 control-label">默认地址ID</label>
							<div class=col-sm-10>
								<input class=form-control name=address_id type=text value="<?php echo $item['address_id'] ?>" placeholder="默认地址ID" required>
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
							<label for=time_create class="col-sm-2 control-label">创建时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_create type=text value="<?php echo $item['time_create'] ?>" placeholder="创建时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_delete class="col-sm-2 control-label">删除时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_delete type=text value="<?php echo $item['time_delete'] ?>" placeholder="删除时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_edit class="col-sm-2 control-label">最后操作时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_edit type=text value="<?php echo $item['time_edit'] ?>" placeholder="最后操作时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=last_login_timestamp class="col-sm-2 control-label">最后登录时间</label>
							<div class=col-sm-10>
								<input class=form-control name=last_login_timestamp type=text value="<?php echo $item['last_login_timestamp'] ?>" placeholder="最后登录时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=last_login_ip class="col-sm-2 control-label">最后登录IP地址</label>
							<div class=col-sm-10>
								<input class=form-control name=last_login_ip type=text value="<?php echo $item['last_login_ip'] ?>" placeholder="最后登录IP地址" required>
							</div>
						</div>
						<div class=form-group>
							<label for=operator_id class="col-sm-2 control-label">最后操作者ID</label>
							<div class=col-sm-10>
								<input class=form-control name=operator_id type=text value="<?php echo $item['operator_id'] ?>" placeholder="最后操作者ID" required>
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