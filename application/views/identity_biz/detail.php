<link rel=stylesheet media=all href="/css/detail.css">
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
		if ( !empty($error) ):
			echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';

		else:
            // 需要特定角色和权限进行该操作
            $current_role = $this->session->role; // 当前用户角色
            $current_level = $this->session->level; // 当前用户级别
            $role_allowed = array('管理员', '经理');
            $level_allowed = 30;
        ?>
	
	<ul id=item-actions class=list-unstyled>
		<?php
		// 需要特定角色和权限进行该操作
		if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
		?>
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
		<?php endif ?>
	</ul>

	<dl id=list-info class=dl-horizontal>
				<dt>认证ID</dt>
		<dd><?php echo $item['identity_id'] ?></dd>
		<dt>所属商家ID</dt>
		<dd><?php echo $item['biz_id'] ?></dd>
		<dt>主体名称</dt>
		<dd><?php echo $item['name'] ?></dd>
		<dt>法人姓名</dt>
		<dd><?php echo $item['fullname_owner'] ?></dd>
		<dt>经办人姓名</dt>
		<dd><?php echo $item['fullname_auth'] ?></dd>
		<dt>工商注册号</dt>
		<dd><?php echo $item['code_license'] ?></dd>
		<dt>法人身份证号</dt>
		<dd><?php echo $item['code_ssn_owner'] ?></dd>
		<dt>经办人身份证号</dt>
		<dd><?php echo $item['code_ssn_auth'] ?></dd>
		<dt>营业执照</dt>
		<dd><?php echo $item['url_image_license'] ?></dd>
		<dt>法人身份证</dt>
		<dd><?php echo $item['url_image_owner_id'] ?></dd>
		<dt>经办人身份证</dt>
		<dd><?php echo $item['url_image_auth_id'] ?></dd>
		<dt>经办人授权书</dt>
		<dd><?php echo $item['url_image_auth_doc'] ?></dd>
		<dt>经办人持身份证照片</dt>
		<dd><?php echo $item['url_verify_photo'] ?></dd>
		<dt>国家</dt>
		<dd><?php echo $item['nation'] ?></dd>
		<dt>省</dt>
		<dd><?php echo $item['province'] ?></dd>
		<dt>市</dt>
		<dd><?php echo $item['city'] ?></dd>
		<dt>区</dt>
		<dd><?php echo $item['county'] ?></dd>
		<dt>具体地址</dt>
		<dd><?php echo $item['street'] ?></dd>
		<dt>开户行名称</dt>
		<dd><?php echo $item['bank_name'] ?></dd>
		<dt>开户行账号</dt>
		<dd><?php echo $item['bank_account'] ?></dd>
		<dt>创建时间</dt>
		<dd><?php echo $item['time_create'] ?></dd>
		<dt>删除时间</dt>
		<dd><?php echo $item['time_delete'] ?></dd>
		<dt>最后操作时间</dt>
		<dd><?php echo $item['time_edit'] ?></dd>
		<dt>创建者ID</dt>
		<dd><?php echo $item['creator_id'] ?></dd>
		<dt>最后操作者ID</dt>
		<dd><?php echo $item['operator_id'] ?></dd>
		<dt>状态</dt>
		<dd><?php echo $item['status'] ?></dd>

	</dl>

	<dl id=list-record class=dl-horizontal>
		<dt>创建时间</dt>
		<dd>
			<?php echo $item['time_create'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['creator_id']) ?>" target=new>查看创建者</a>
		</dd>

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