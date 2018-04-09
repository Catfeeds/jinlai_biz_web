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

<script defer src="/js/detail.js"></script>

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

	<?php else: ?>
	<dl id=list-info class=dl-horizontal>
		<dt><?php echo $this->class_name_cn ?>ID</dt>
		<dd><?php echo $item[$this->id_name] ?></dd>
		
		<dt>主图</dt>
		<dd class=row>
			<?php
				$column_image = 'url_image_main';
				if ( empty($item[$column_image]) ):
			?>
			<p>未上传</p>
			<?php else: ?>
			<figure class="col-xs-12 col-sm-6 col-md-4">
				<img src="<?php echo $item[$column_image] ?>">
			</figure>
			<?php endif ?>
		</dd>
		
		<dt>形象图</dt>
		<dd>
			<?php
				$column_images = 'url_image_main';
				if ( empty($item[$column_images]) ):
			?>
			<p>未上传</p>
			<?php else: ?>
			<ul class=row>
				<?php
					$image_urls = explode(',', $item[$column_images]);
					foreach($image_urls as $url):
				?>
				<li class="col-xs-6 col-sm-4 col-md-3">
					<img src="<?php echo $url ?>">
				</li>
				<?php endforeach ?>
			</ul>
			<?php endif ?>
		</dd>
		
				<dt>消息ID</dt>
		<dd><?php echo $item['message_id'] ?></dd>
		<dt>用户ID</dt>
		<dd><?php echo $item['user_id'] ?></dd>
		<dt>商家ID</dt>
		<dd><?php echo $item['biz_id'] ?></dd>
		<dt>员工ID</dt>
		<dd><?php echo $item['stuff_id'] ?></dd>
		<dt>发信人身份</dt>
		<dd><?php echo $item['from_type'] ?></dd>
		<dt>收信人身份</dt>
		<dd><?php echo $item['to_type'] ?></dd>
		<dt>类型</dt>
		<dd><?php echo $item['type'] ?></dd>
		<dt>内容</dt>
		<dd><?php echo $item['content'] ?></dd>
		<dt>图片URL</dt>
		<dd><?php echo $item['url_image'] ?></dd>
		<dt>商品ID</dt>
		<dd><?php echo $item['item_id'] ?></dd>
		<dt>订单ID</dt>
		<dd><?php echo $item['order_id'] ?></dd>
		<dt>网页URL</dt>
		<dd><?php echo $item['url_page'] ?></dd>
		<dt>网页标题</dt>
		<dd><?php echo $item['title'] ?></dd>

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