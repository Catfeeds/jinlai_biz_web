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
	  	<!--<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>-->
	</div>
	<?php endif ?>
	
	<ul class=list-unstyled>
		<?php
		// 仅可修改自己的信息
		if ( $item['user_id'] === $this->session->user_id ):
		?>
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
		<?php endif ?>
	</ul>

	<dl id=list-info class=dl-horizontal>
		<dt>头像</dt>
		<?php if ( !empty($item['avatar']) ): ?>
		<dd class=row>
			<figure class="col-xs-12 col-sm-6 col-md-4">
				<img class=img-circle src="<?php echo $item['avatar'] ?>">
			</figure>
		</dd>
		<?php else: ?>
		<dd>未上传</dd>
		<?php endif ?>
		<dt>用户ID</dt>
		<dd><?php echo $item['user_id'] ?></dd>
		<dt>昵称</dt>
		<dd><?php echo $item['nickname'] ?></dd>
		<dt>姓氏</dt>
		<dd><?php echo $item['lastname'] ?></dd>
		<dt>名</dt>
		<dd><?php echo $item['firstname'] ?></dd>
		<dt>身份证号</dt>
		<dd><?php echo $item['code_ssn'] ?></dd>
		<dt>身份证照片</dt>
		<dd><?php echo $item['url_image_id'] ?></dd>
		<dt>性别</dt>
		<dd><?php echo $item['gender'] ?></dd>
		<dt>出生日期</dt>
		<dd><?php echo $item['dob'] ?></dd>

		<dt>手机号</dt>
		<dd><?php echo $item['mobile'] ?></dd>
		<dt>电子邮件地址</dt>
		<dd><?php echo $item['email'] ?></dd>
		<dt>默认地址</dt>
		<dd><?php echo $item['address_id'] ?></dd>
		<dt>开户行名称</dt>
		<dd><?php echo $item['bank_name'] ?></dd>
		<dt>开户行账号</dt>
		<dd><?php echo $item['bank_account'] ?></dd>
		<dt>注册时间</dt>
		<dd><?php echo $item['time_create'] ?></dd>
		<dt>最后登录时间</dt>
		<dd><?php echo $item['last_login_timestamp'] ?></dd>
		<dt>最后登录IP地址</dt>
		<dd><?php echo $item['last_login_ip'] ?></dd>
	</dl>

	<dl id=list-record class=dl-horizontal>
		<?php if ( ! empty($item['time_delete']) ): ?>
		<dt>删除时间</dt>
		<dd><?php echo $item['time_delete'] ?></dd>
		<?php endif ?>

		<?php if ( ! empty($item['operator_id']) ): ?>
		<dt>最后操作时间</dt>
		<dd>
			<?php echo $item['time_edit'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['operator_id']) ?>" target=new>查看最后操作者</a>
		</dd>
		<?php endif ?>
	</dl>
</div>