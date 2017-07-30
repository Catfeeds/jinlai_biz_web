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

<script src="/js/jquery.qrcode.min.js"></script>

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
		<dt>LOGO</dt>
		<dd>
			<?php if ( ! empty($item['url_logo']) ): ?>
			<figure class=row>
				<img class="col-xs-12 col-sm-6 col-md-3" src="<?php echo $item['url_logo'] ?>">
			</figure>

			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>
		
		
		<dt>状态</dt>
		<dd><?php echo $item['status'] ?></dd>
		<dt>商家ID</dt>
		<dd><?php echo $item['biz_id'] ?></dd>
		<dt>商家全称</dt>
		<dd><?php echo $item['name'] ?></dd>
		<dt>简称</dt>
		<dd><?php echo $item['brief_name'] ?></dd>
		<dt>店铺域名</dt>
		<dd><?php echo $item['url_name'] ?></dd>

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
		<dt>订单通知手机号</dt>
		<dd><?php echo $item['tel_protected_order'] ?></dd>
		<dt>财务联系手机号</dt>
		<dd><?php echo $item['tel_protected_fiscal'] ?></dd>
		<dt>官方网站</dt>
		<dd><?php echo $item['url_web'] ?></dd>
		<dt>官方微博</dt>
		<dd><?php echo $item['url_weibo'] ?></dd>

		<dt>微信二维码</dt>
		<dd>
			<?php if ( !empty($item['url_wechat']) ): ?>
			<figure id=qrcode class="col-xs-12 col-sm-6 col-md-3"></figure>
			<script>
			$(function(){
				$('#qrcode').qrcode("<?php echo $item['url_wechat'] ?>");
			})
			</script>

			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>

		<dt>产品</dt>
		<dd>
			<?php if ( !empty($item['url_image_product']) ): ?>
			<ul class=row>
				<?php
					$figure_image_urls = explode(',', $item['url_image_product']);
					foreach($figure_image_urls as $url):
				?>
				<li class="col-xs-6 col-sm-4 col-md-3">
					<img src="<?php echo $url ?>">
				</li>
				<?php endforeach ?>
			</ul>

			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>

		<dt>工厂/产地</dt>
		<dd>
			<?php if ( !empty($item['url_image_produce']) ): ?>
			<ul class=row>
				<?php
					$figure_image_urls = explode(',', $item['url_image_produce']);
					foreach($figure_image_urls as $url):
				?>
				<li class="col-xs-6 col-sm-4 col-md-3">
					<img src="<?php echo $url ?>">
				</li>
				<?php endforeach ?>
			</ul>

			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>

		<dt>门店/柜台</dt>
		<dd>
			<?php if ( !empty($item['url_image_retail']) ): ?>
			<ul class=row>
				<?php
					$figure_image_urls = explode(',', $item['url_image_retail']);
					foreach($figure_image_urls as $url):
				?>
				<li class="col-xs-6 col-sm-4 col-md-3">
					<img src="<?php echo $url ?>">
				</li>
				<?php endforeach ?>
			</ul>

			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>
	</dl>
	
	<h2>资质信息</h2>
	<dl class=dl-horizontal>
		<dt>统一社会信用代码/营业执照号</dt>
		<dd><?php echo $item['code_license'] ?></dd>
		<dt>法人身份证号</dt>
		<dd><?php echo $item['code_ssn_owner'] ?></dd>
		<dt>经办人身份证号</dt>
		<dd><?php echo $item['code_ssn_auth'] ?></dd>

		<dt>营业执照</dt>
		<dd>
			<?php if ( ! empty($item['url_image_license']) ): ?>
			<figure class=row>
				<img class="col-xs-12 col-sm-6 col-md-4" src="<?php echo $item['url_image_license'] ?>">
			</figure>

			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>
		
		<dt>法人身份证</dt>
		<dd>
			<?php if ( ! empty($item['url_image_owner_id']) ): ?>
			<figure class=row>
				<img class="col-xs-12 col-sm-6 col-md-4" src="<?php echo $item['url_image_owner_id'] ?>">
			</figure>
			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>

		<dt>经办人身份证</dt>
		<dd>
			<?php if ( ! empty($item['url_image_auth_id']) ): ?>
			<figure class=row>
				<img class="col-xs-12 col-sm-6 col-md-4" src="<?php echo $item['url_image_auth_id'] ?>">
			</figure>
			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>

		<dt>经办人授权书</dt>
		<dd>
			<?php if ( ! empty($item['url_image_auth_doc']) ): ?>
			<figure class=row>
				<img class="col-xs-12 col-sm-6 col-md-4" src="<?php echo $item['url_image_auth_doc'] ?>">
			</figure>
			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>
	</dl>
	
	<h2>财务信息</h2>
	<dl class=dl-horizontal>
		<dt>开户行名称</dt>
		<dd><?php echo $item['bank_name'] ?></dd>
		<dt>开户行账号</dt>
		<dd><?php echo $item['bank_account'] ?></dd>
	</dl>
	
	<h2>地址信息</h2>
	<dl class=dl-horizontal>
		<dt>地址</dt>
		<dd>
			<p>
				<?php echo $item['nation'] ?> <?php echo $item['province'] ?>省 <?php echo $item['city'] ?>市 <?php echo $item['county'] ?>区/县<br>
				<?php echo $item['street'] ?>
			</p>

			<?php if ( !empty($item['longitude']) && !empty($item['latitude']) ): ?>
			<figure class="row">
				<figcaption>
					<p class="bg-info text-info text-center">
						经纬度 <?php echo $item['longitude'] ?>, <?php echo $item['latitude'] ?>
					</p>
				</figcaption>
				<div id=map style="height:300px;background-color:#aaa"></div>
			</figure>
			
			<script src="https://webapi.amap.com/maps?v=1.3&key=d698fd0ab2d88ad11f4c6a2c0e83f6a8"></script>
			<script src="https://webapi.amap.com/ui/1.0/main.js"></script>
			<script>
				var lnglat = [<?php echo $item['longitude'] ?>, <?php echo $item['latitude'] ?>];
			    var map = new AMap.Map('map',{
					center: lnglat,
			        zoom: 16,
		            scrollWheel: false,
					mapStyle: 'amap://styles/2daddd87cfd0fa58d0bc932eed31b9d8', // 自定义样式
			    });
				marker = new AMap.Marker({
		            position: lnglat,
		        });
		        marker.setMap(map);

				// 为BasicControl设置DomLibrary，jQuery
				AMapUI.setDomLibrary($);
				AMapUI.loadUI(['control/BasicControl'], function(BasicControl) {
					// 缩放控件
				    map.addControl(new BasicControl.Zoom({
				        position: 'rb', // 右下角
				    }));
				});
			</script>
			<?php endif ?>
		</dd>
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