<link rel=stylesheet media=all href="/css/detail.css">
<style>
    #content {background-color:#fff;}

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
        <?php
            // 当前项客户端URL
            $item_url = WEB_URL.$this->class_name.'/detail?id='.$item[$this->id_name];
        ?>

        <dt>链接</dt>
        <dd>
            <span><?php echo $item_url ?></span>
            <a href="<?php echo $item_url ?>">查看</a>
        </dd>

        <dt>二维码</dt>
        <dd>
            <figure id=qrcode class="col-xs-12 col-sm-6 col-md-3"></figure>
            <script>
                $(function(){
                    // 创建二维码并转换为图片格式，以使微信能识别该二维码
                    $('#qrcode').qrcode("<?php echo $item_url ?>");

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

		<dt>主图</dt>
		<dd>
            <?php $name_to_upload = 'url_image_main' ?>
            <ul class=upload_preview>
                <li>
                    <figure>
                        <img src="<?php echo $item[$name_to_upload] ?>">
                    </figure>
                </li>
            </ul>
		</dd>

		<dt>形象图</dt>
        <dd>
            <?php if ( !empty($item['figure_image_urls']) ): ?>
                <ul class=upload_preview>
                <?php
                $slides = explode(',', $item['figure_image_urls']);
                foreach($slides as $slide):
                    ?>
                    <li>
                        <figure>
                            <img src="<?php echo $slide ?>">
                        </figure>
                    </li>
                <?php endforeach ?>
                </ul>
            <?php else: ?>
                未上传
            <?php endif ?>
        </dd>

		<dt>形象视频</dt>
        <dd>高级功能，请联系品类负责人确认开通条件。</dd>
        <!--
		<dd>
			<?php if ( !empty($item['figure_video_urls']) ): ?>
			<ul class=row>
				<?php
					$figure_video_urls = explode(',', $item['figure_video_urls']);
					foreach($figure_video_urls as $url):
				?>
				<li class="col-xs-6 col-sm-4 col-md-3">
					<video src="<?php echo $url ?>" controls="controls">您的浏览器不支持视频播放</video>
				</li>
				<?php endforeach ?>
			</ul>
			<?php else: ?>
			未上传
			<?php endif ?>
		</dd>
		-->

		<dt>状态</dt>
		<dd><?php echo $item['status'] ?></dd>
		<?php if ( $item['figure_video_urls'] !== '待审核' && !empty($item['note_admin']) ): ?>
		<dt>审核意见</dt>
		<dd class="bg-info text-info"><?php echo $item['note_admin'] ?></dd>
		<?php endif ?>

		<dt>商品ID</dt>
		<dd><?php echo $item['item_id'] ?></dd>
		<dt>系统分类</dt>
		<dd><?php echo $category['name'] ?></dd>

		<?php if ( !empty($item['category_biz_id']) ): ?>
		<dt>店内分类</dt>
		<dd><?php echo $category_biz['name'] ?></dd>
		<?php endif ?>

		<dt>品牌</dt>
		<dd><?php echo !empty($item['brand_id'])? $brand['name']: '未设置'; ?></dd>

		<?php if ( !empty($item['code_biz']) ): ?>
		<dt>商家自定义货号</dt>
		<dd><?php echo $item['code_biz'] ?></dd>
		<?php endif ?>

		<dt>商品名称</dt>
		<dd><strong><?php echo $item['name'] ?></strong></dd>
		<dt>商品宣传语/卖点</dt>
		<dd><?php echo !empty($item['slogan'])? $item['slogan']: '未设置'; ?></dd>
		<dt>标签价/原价</dt>
		<dd><?php echo ($item['tag_price'] !== '0.00')? '<del>￥ '.$item['tag_price'].'</del>': '未设置'; ?></dd>
		<dt>商城价/现价</dt>
		<dd><strong>￥ <?php echo $item['price'] ?></strong></dd>

		<?php $unit_name = !empty($item['unit_name'])? $item['unit_name']: '份（默认单位）' ?>
		<dt>库存量</dt>
		<dd>
            <strong><?php echo $item['stocks'].' '. $unit_name ?></strong>
            <p class="help-block">若商品存在规格，则可销售库存量以各规格相应库存量为准</p>
        </dd>

		<dt>每单最高限量</dt>
		<dd><?php echo !empty($item['quantity_max'])? $item['quantity_max'].' 份': '不限'; ?></dd>
		<dt>每单最低限量</dt>
		<dd><?php echo !empty($item['quantity_min'])? $item['quantity_min'].' 份': 1; ?></dd>
		<dt>是否可用优惠券</dt>
		<dd><?php echo ($item['coupon_allowed'] === '1')? '是': '否'; ?></dd>
		<dt>积分抵扣率</dt>
		<dd><?php echo $item['discount_credit'] * 100 ?>%</dd>
		<dt>佣金比例/提成率</dt>
		<dd><?php echo $item['commission_rate'] * 100 ?>%</dd>

		<?php if ( ! empty($item['time_suspend']) ): ?>
		<dt>预定上架时间</dt>
		<dd><?php echo empty($item['time_to_publish'])? '未设置': date('Y-m-d H:i:s', $item['time_to_publish']); ?></dd>
		<?php endif ?>

		<?php if ( ! empty($item['time_publish']) ): ?>
		<dt>预定下架时间</dt>
		<dd><?php echo empty($item['time_to_suspend'])? '未设置': date('Y-m-d H:i:s', $item['time_to_suspend']); ?></dd>
		<?php endif ?>

		<dt>物流信息</dt>
		<dd>
			<ul class="list-horizontal row">
                <li class="col-xs-12 col-sm-4">毛重 <?php echo ($item['weight_gross'] !== '0.00')? $item['weight_gross'].' KG': '-' ?></li>
                <li class="col-xs-12 col-sm-4">净重 <?php echo ($item['weight_net'] !== '0.00')? $item['weight_net'].' KG': '-' ?></li>
				<li class="col-xs-12 col-sm-4">体积重 <?php echo ($item['weight_volume'] !== '0.00')? $item['weight_volume'].' KG': '-' ?></li>
			</ul>
		</dd>

		<dt>店内活动</dt>
		<dd>
			<?php if ( ! empty($item['promotion_id']) ): ?>
			<strong><?php echo $promotion['name'] ?></strong>
			<?php else: ?>
			不参加
			<?php endif ?>
		</dd>
	</dl>

	<section id=skus class=well>
		<h2>商品规格</h2>

        <?php if ( !empty($skus) ): ?>
		<ul class=row>
			<?php foreach ($skus as $sku): ?>
			<li class="col-xs-12 col-sm-6 col-md-4">
				<a href="<?php echo base_url('sku/detail?id='.$sku['sku_id']) ?>">

					<figure class="list-item-figure col-xs-4">
                        <?php if ( !empty($sku['url_image']) ): ?>
						<img src="<?php echo MEDIA_URL.'sku/'.$sku['url_image'] ?>">
                        <?php else: ?>
                        <img src="<?php echo $item['url_image_main'] ?>">
                        <?php endif ?>
					</figure>

                    <div class="list-item-info col-xs-8">
                        <h3><?php echo $sku['name_first'].' > '.$sku['name_second'].' > '.$sku['name_third'] ?></h3>
                        <p>￥<?php echo $sku['price'] ?> &times; <?php echo $sku['stocks'] ?>单位</p>
                    </div>
				</a>
			</li>
			<?php endforeach ?>
		</ul>
        <?php endif ?>

        <a class="btn btn-default btn-lg btn-block" href="<?php echo base_url('sku/index?item_id='.$item['item_id']) ?>" target=_blank>管理规格</a>
	</section>


	<section id=description class=well>
		<h2>商品描述</h2>
		<?php if ( !empty($item['description']) ): ?>
			<div class="bg-info text-info">
				<p class="text-center">以下仅为内容及格式预览，实际样式请以前台相应页面为准。</p>
			</div>
			<div id=description-content class=row>
				<?php echo $item['description'] ?>
			</div>
		<?php else: ?>
			<p>该商品尚未填写商品描述。</p>
		<?php endif ?>
	</section>

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