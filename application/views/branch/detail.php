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

	<dl id=list-info class=dl-horizontal>
		<dt>主图</dt>
		<dd class=row>
			<?php if ( !empty($item['url_image_main']) ): ?>
			<figure class="col-xs-12 col-sm-6 col-md-4">
				<img src="<?php echo $item['url_image_main'] ?>">
			</figure>
			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>

		<dt>形象图</dt>
		<dd>
			<?php if ( !empty($item['figure_image_urls']) ): ?>
			<ul class=row>
				<?php
					$figure_image_urls = explode(',', $item['figure_image_urls']);
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

		<dt>状态</dt>
		<dd><?php echo $item['status'] ?></dd>
		<dt>门店ID</dt>
		<dd><?php echo $item['branch_id'] ?></dd>
		<dt>名称</dt>
		<dd><?php echo $item['name'] ?></dd>
		<dt>说明</dt>
		<dd><?php echo $item['description'] ?></dd>
		<dt>消费者联系电话</dt>
		<dd><?php echo $item['tel_public'] ?></dd>
		<dt>商务联系手机号</dt>
		<dd><?php echo $item['tel_protected_biz'] ?></dd>
		<dt>订单通知手机号</dt>
		<dd><?php echo $item['tel_protected_order'] ?></dd>
		<dt>休息日</dt>
		<dd><?php echo $item['day_rest'] ?></dd>
		<dt>营业/配送时间</dt>
		<dd><?php echo $item['time_open'] ?>:00 - <?php echo $item['time_close'] ?>:00</dd>
		<dt>配送范围</dt>
		<dd><?php echo !empty($item['range_deliver'])? $item['range_deliver']. ' 公里': '不支持本地配送' ?></dd>

		<dt>地址</dt>
		<dd>
			<p>
				<?php echo $item['nation'] ?> <?php echo $item['province'] ?>省 <?php echo $item['city'] ?>市 <?php echo $item['county'] ?>区/县<br>
				<?php echo $item['street'] ?>
			</p>
			
			<?php if ( !empty($item['longitude']) && !empty($item['latitude']) ): ?>
			<figure class="row">
				<figcaption>
					<p class=help-block>经纬度 <?php echo $item['longitude'] ?>, <?php echo $item['latitude'] ?></p>
				</figcaption>
				<div id=map style="height:300px;background-color:#aaa"></div>
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
		<!--
		<dt>商圈</dt>
		<dd><?php echo $item['region'] ?></dd>
		<dt>子商圈</dt>
		<dd><?php echo $item['poi'] ?></dd>
		-->
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