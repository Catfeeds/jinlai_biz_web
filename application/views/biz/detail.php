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
	
	<ul class=list-unstyled>
		<?php
		// 需要特定角色和权限进行该操作
		if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
		?>
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
		<?php endif ?>
	</ul>

	<dl id=list-info class=dl-horizontal>
		<dt>商家ID</dt>
		<dd><?php echo $item['biz_id'] ?></dd>
		<dt>全称</dt>
		<dd><?php echo $item['name'] ?></dd>
		<dt>简称</dt>
		<dd><?php echo $item['brief_name'] ?></dd>
		<dt>店铺域名</dt>
		<dd><?php echo $item['url_name'] ?></dd>
		<dt>LOGO</dt>
		<dd><?php echo $item['url_logo'] ?></dd>
		<dt>宣传语</dt>
		<dd><?php echo $item['slogan'] ?></dd>
		<dt>简介</dt>
		<dd><?php echo $item['description'] ?></dd>
		<dt>店铺公告</dt>
		<dd><?php echo $item['notification'] ?></dd>
		<dt>消费者联系电话</dt>
		<dd><?php echo $item['tel_public'] ?></dd>
		<dt>商务联系手机号</dt>
		<dd><?php echo $item['tel_protected_biz'] ?></dd>
		<dt>财务联系手机号</dt>
		<dd><?php echo $item['tel_protected_fiscal'] ?></dd>
		<dt>订单通知手机号</dt>
		<dd><?php echo $item['tel_protected_order'] ?></dd>
		<dt>每笔订单运费（元）</dt>
		<dd><?php echo $item['freight'] ?></dd>
		<dt>免邮费起始金额（元）</dt>
		<dd><?php echo $item['freight_free_subtotal'] ?></dd>
		<dt>免邮费起始份数（份）</dt>
		<dd><?php echo $item['freight_free_count'] ?></dd>
		<dt>最低小计金额（元）</dt>
		<dd><?php echo $item['min_order_subtotal'] ?></dd>
		<dt>配送起始时间</dt>
		<dd><?php echo $item['delivery_time_start'] ?></dd>
		<dt>配送结束时间</dt>
		<dd><?php echo $item['delivery_time_end'] ?></dd>
		<dt>国家</dt>
		<dd><?php echo $item['country'] ?></dd>
		<dt>省</dt>
		<dd><?php echo $item['province'] ?></dd>
		<dt>市</dt>
		<dd><?php echo $item['city'] ?></dd>
		<dt>区</dt>
		<dd><?php echo $item['county'] ?></dd>
		<dt>详细地址</dt>
		<dd><?php echo $item['detail'] ?></dd>
		<dt>经度</dt>
		<dd><?php echo $item['longitude'] ?></dd>
		<dt>纬度</dt>
		<dd><?php echo $item['latitude'] ?></dd>
		<dt>开户行名称</dt>
		<dd><?php echo $item['bank_name'] ?></dd>
		<dt>开户行账号</dt>
		<dd><?php echo $item['bank_account'] ?></dd>
		<dt>统一社会信用代码</dt>
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
		<dt>授权书</dt>
		<dd><?php echo $item['url_image_auth_doc'] ?></dd>
		<dt>官方网站</dt>
		<dd><?php echo $item['url_web'] ?></dd>
		<dt>官方微博</dt>
		<dd><?php echo $item['url_weibo'] ?></dd>
		<dt>淘宝/天猫店铺</dt>
		<dd><?php echo $item['url_taobao'] ?></dd>
		<dt>微信二维码</dt>
		<dd><?php echo $item['url_wechat'] ?></dd>
		<dt>产品</dt>
		<dd><?php echo $item['url_image_product'] ?></dd>
		<dt>工厂/产地</dt>
		<dd><?php echo $item['url_image_produce'] ?></dd>
		<dt>门店/柜台</dt>
		<dd><?php echo $item['url_image_retail'] ?></dd>
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