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

<script src="/js/jquery.qrcode.min.js"></script>

<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class=container>

	<?php if ( empty($item) ): ?>
	<p><?php echo $error ?></p>

	<?php
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
                <li class="col-xs-12">
                    <a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a>
                </li>
            <?php endif ?>
        </ul>

	<div class="jumbotron row">
		<dl id=core-info class=dl-horizontal>
			<dt>状态</dt>
			<dd><?php echo $item['status'] ?></dd>
			<dt>商家ID</dt>
			<dd><?php echo $item['biz_id'] ?></dd>
			<dt>商家全称</dt>
            <dd><?php echo !empty($item['name'])? $item['name']: '未填写' ?></dd>
			<dt>店铺名称</dt>
			<dd><?php echo $item['brief_name'] ?></dd>
			<dt>店铺域名</dt>
			<dd><?php echo !empty($item['url_name'])? $item['url_name']: '未分配' ?></dd>
			<dt>消费者联系电话</dt>
			<dd><?php echo $item['tel_public'] ?></dd>
			<dt>商务联系手机号</dt>
			<dd><?php echo $item['tel_protected_biz'] ?></dd>
			<dt>订单通知手机号</dt>
			<dd><?php echo $item['tel_protected_order'] ?></dd>
            <dt>财务联系手机号</dt>
            <dd><?php echo $item['tel_protected_fiscal'] ?></dd>
		</dl>
	</div>

	<dl id=list-info class=dl-horizontal>
        <dt>店铺链接</dt>
        <dd>
            <a class="btn btn-info btn-lg btn-block" href="<?php echo WEB_URL.'biz/detail?id='.$item['biz_id'] ?>">查看店铺</a>
        </dd>

		<dt>商家LOGO</dt>
		<dd>
			<?php if ( ! empty($item['url_logo']) ): ?>
			<figure class=row>
				<img class="col-xs-12 col-sm-6 col-md-3" src="<?php echo $item['url_logo'] ?>">
			</figure>

			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>

		<dt>宣传语</dt>
		<dd><?php echo empty($item['slogan'])? '未填写': $item['slogan'] ?></dd>
		<dt>简介</dt>
		<dd><?php echo empty($item['description'])? '未填写': $item['description'] ?></dd>
		<dt>店铺公告</dt>
		<dd><?php echo empty($item['notification'])? '未填写': $item['notification'] ?></dd>

		<dt>店铺二维码</dt>
		<dd>
			<figure id=qrcode class="col-xs-12 col-sm-6 col-md-3"></figure>
			<script>
			$(function(){
				// 创建二维码并转换为图片格式，以使微信能识别该二维码
				$('#qrcode').qrcode("<?php echo WEB_URL.'biz/detail?id='.$item['biz_id'] ?>");
				
				// 将canvas转换为Base64格式的图片内容
				function convertCanvasToImage(canvas)
				{
					// 新Image对象，可以理解为DOM
					var image = new Image();
					// canvas.toDataURL 返回的是一串Base64编码的URL，当然,浏览器自己肯定支持 
					// 指定格式 PNG 
					image.src = canvas.toDataURL("image/png"); 
					return image;
				} 

				//获取网页中的canvas对象 
				var mycanvas = document.getElementsByTagName('canvas')[0];

				//将转换后的img标签插入到html中 
				var img = convertCanvasToImage(mycanvas); 
				$('#qrcode').append(img);
				$('#qrcode canvas').remove(); // 移除canvas格式的二维码
			})
			</script>
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

	<!--
    <h2>资质信息</h2>
	<dl class=dl-horizontal>
		<dt>工商注册号</dt>
		<dd><?php echo $item['code_license'] ?></dd>
		<dt>法人</dt>
		<dd>
			姓名 <?php echo $item['fullname_owner'] ?><br>
			身份证号码 <?php echo substr_replace($item['code_ssn_owner'], '****', -4) ?>
		</dd>
		<dt>经办人</dt>
		<dd>
			姓名 <?php echo empty($item['fullname_auth'])? '未填写': $item['fullname_auth'] ?><br>
			身份证号码 <?php echo empty($item['code_ssn_auth'])? '未填写': substr_replace($item['code_ssn_auth'], '****', -4) ?>
		</dd>

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
		<dd><?php echo empty($item['bank_name'])? '未填写': $item['bank_name'] ?></dd>
		<dt>开户行账号</dt>
		<dd><?php echo empty($item['bank_account'])? '未填写': $item['bank_account'] ?></dd>
	</dl>
	-->

	<h2>联系地址</h2>
	<dl class=dl-horizontal>
		<dt>地址</dt>
		<dd>
			<p>
				<?php echo $item['nation'] ?> <?php echo $item['province'] ?> <?php echo $item['city'] ?> <?php echo $item['county'] ?><br>
				<?php echo $item['street'] ?>
			</p>

			<?php if ( !empty($item['longitude']) && !empty($item['latitude']) ): ?>
			<figure class="row">
				<!--
				<figcaption>
					<p class="bg-info text-info text-center">
						经纬度 <?php echo $item['longitude'] ?>, <?php echo $item['latitude'] ?>
					</p>
				</figcaption>
				-->
				<div id=map style="height:300px;background-color:#999"></div>
			</figure>

			<script src="https://webapi.amap.com/maps?v=1.3&key=bf0fd60938b2f4f40de5ee83a90c2e0e"></script>
			<script src="https://webapi.amap.com/ui/1.0/main.js"></script>
			<script>
				var lnglat = [<?php echo $item['longitude'] ?>, <?php echo $item['latitude'] ?>];
			    var map = new AMap.Map('map',{
					center: lnglat,
			        zoom: 16,
		            scrollWheel: false,
					mapStyle: 'amap://styles/91f3dcb31dfbba6e97a3c2743d4dff88', // 自定义样式
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

	<?php endif ?>
</div>