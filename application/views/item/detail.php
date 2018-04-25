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

        <?php if ( empty($item['time_delete']) ): ?>
        <li><a title="删除" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank>删除</a></li>
        <?php endif ?>

        <?php if ( empty($item['time_publish']) ): ?>
        <li><a title="上架" href="<?php echo base_url($this->class_name.'/publish?ids='.$item[$this->id_name]) ?>" target=_blank>上架</a></li>
        <?php else: ?>
            <li><a title="下架" href="<?php echo base_url($this->class_name.'/suspend?ids='.$item[$this->id_name]) ?>" target=_blank>下架</a></li>
        <?php endif ?>

        <li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a></li>
		<?php endif ?>
	</ul>

	<dl id=list-info class=dl-horizontal>
        <dt>状态</dt>
        <dd><?php echo $item['status'] ?></dd>
        <?php if ( $item['status'] !== '待审核' && !empty($item['note_admin']) ): ?>
            <dt>审核意见</dt>
            <dd class="bg-info text-info"><?php echo $item['note_admin'] ?></dd>
        <?php endif ?>

        <dt>商品ID</dt>
        <dd><?php echo $item['item_id'] ?></dd>
        <dt>商家商品编码</dt>
        <dd><?php echo empty($item['code_biz'])? 'N/A': $item['code_biz'] ?></dd>
        <dt>商品条形码</dt>
        <dd><?php echo empty($item['barcode'])? 'N/A': $item['barcode'] ?></dd>

        <dt>系统分类</dt>
        <dd><?php echo $category['name'].' / ID'. $item['category_id'] ?></dd>

        <?php if ( !empty($item['category_biz_id']) ): ?>
            <dt>店内分类</dt>
            <dd><?php echo $category_biz['name'] ?></dd>
        <?php endif ?>

        <!--
        <dt>品牌</dt>
		<dd><?php echo !empty($item['brand_id'])? $brand['name']: '未设置'; ?></dd>
        -->

        <?php
        // 当前项客户端URL
        $item_url = WEB_URL.$this->class_name.'/detail?id='.$item[$this->id_name];
        ?>
        <dt><?php echo $this->class_name_cn ?>链接</dt>
        <dd>
            <span><?php echo $item_url ?></span>
            <a href="<?php echo $item_url ?>" target=_blank>查看</a>
        </dd>

        <dt><?php echo $this->class_name_cn ?>二维码</dt>
        <dd>
            <figure class="qrcode col-xs-12 col-sm-6 col-md-3" data-qrcode-string="<?php echo $item_url ?>"></figure>
        </dd>

		<dt>商品名称</dt>
		<dd><strong><?php echo $item['name'] ?></strong></dd>
		<dt>商品宣传语/卖点</dt>
		<dd><?php echo empty($item['slogan'])? 'N/A': $item['slogan'] ?></dd>
		<dt>标签价/原价</dt>
		<dd><?php echo ($item['tag_price'] !== '0.00')? '<del>￥ '.$item['tag_price'].'</del>': 'N/A'; ?></dd>
		<dt>商城价/现价</dt>
		<dd><strong>￥ <?php echo $item['price'] ?></strong></dd>

        <dt>库存量</dt>
        <dd>
            <?php $unit_name = !empty($item['unit_name'])? $item['unit_name']: '份（默认单位）' ?>
            <strong><?php echo $item['stocks'].' '. $unit_name ?></strong>
            <p class=help-block>若商品存在规格，则可销售库存量以各规格相应库存量为准</p>
        </dd>

        <dt>物流信息</dt>
        <dd>
            <ul class="list-horizontal row">
                <li class="col-xs-12 col-sm-4">毛重 <?php echo ($item['weight_gross'] !== '0.00')? $item['weight_gross'].' KG': 'N/A' ?></li>
                <li class="col-xs-12 col-sm-4">净重 <?php echo ($item['weight_net'] !== '0.00')? $item['weight_net'].' KG': 'N/A' ?></li>
                <li class="col-xs-12 col-sm-4">体积重 <?php echo ($item['weight_volume'] !== '0.00')? $item['weight_volume'].' KG': 'N/A' ?></li>
            </ul>
        </dd>

		<dt>每单最高限量</dt>
		<dd><?php echo $item['quantity_max'] ?></dd>
		<dt>每单最低限量</dt>
		<dd><?php echo $item['quantity_min'] ?></dd>

		<dt>积分抵扣率</dt>
		<dd><?php echo $item['discount_credit'] * 100 ?>%</dd>
		<dt>佣金比例</dt>
		<dd><?php echo $item['commission_rate'] * 100 ?>%</dd>

		<dt>预定上架时间</dt>
		<dd><?php echo empty($item['time_to_publish'])? 'N/A': date('Y-m-d H:i:s', $item['time_to_publish']); ?></dd>

		<dt>预定下架时间</dt>
		<dd><?php echo empty($item['time_to_suspend'])? 'N/A': date('Y-m-d H:i:s', $item['time_to_suspend']); ?></dd>

        <dt>可用优惠券</dt>
        <dd><?php echo ($item['coupon_allowed'] === '1')? '是': '否'; ?></dd>

        <dt>单品活动</dt>
		<dd>
            <?php echo empty($item['promotion_id'])? 'N/A': '<strong>'.$promotion['name'].'</strong>' ?>
            <p class=help-block>单品活动与其它非单品店内活动可以累加</p>
		</dd>
	</dl>

    <dl id=item_figure class=dl-horizontal>
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

        <!--
        <dt>形象视频</dt>
		<dd>
		    <p>高级功能，请联系品类负责人确认开通条件。</p>

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
    </dl>

	<section id=description class=well>
		<h2>商品描述</h2>

		<?php if ( !empty($item['description']) ): ?>
			<div id=description-content class=row>
				<?php echo $item['description'] ?>
			</div>
		<?php else: ?>
			<p>该商品尚未填写商品描述。</p>
		<?php endif ?>

        <a class="btn btn-default btn-lg btn-block" href="<?php echo base_url('item/edit_description?id='.$item['item_id']) ?>" target=_blank>修改详情</a>
	</section>

    <section id=skus class=well>
        <h2>商品规格</h2>

        <?php if ( !empty($skus) ): ?>
            <ul class=row>
                <?php foreach ($skus as $sku): ?>
                    <li class="col-xs-12 col-sm-6">
                        <a href="<?php echo base_url('sku/detail?id='.$sku['sku_id']) ?>">

                            <figure class="list-item-figure col-xs-4">
                                <?php if ( !empty($sku['url_image']) ): ?>
                                    <img src="<?php echo MEDIA_URL.'sku/'.$sku['url_image'] ?>">
                                <?php else: ?>
                                    <img src="<?php echo $item['url_image_main'] ?>">
                                <?php endif ?>
                            </figure>

                            <div class="list-item-info col-xs-8">
                                <h3><?php echo $sku['name_first'].' '.$sku['name_second'].' '.$sku['name_third'] ?></h3>
                                <p>￥<?php echo $sku['price'] ?> &times; <?php echo $sku['stocks'] ?>单位</p>
                            </div>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>

        <a class="btn btn-default btn-lg btn-block" href="<?php echo base_url('sku/index?item_id='.$item['item_id']) ?>" target=_blank>管理规格</a>
    </section>

	<dl id=list-record class=dl-horizontal>
		<dt>创建时间</dt>
		<dd>
			<?php echo $item['time_create'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['creator_id']) ?>" target=new>查看创建者</a>
		</dd>

        <dt>上架时间</dt>
        <dd><?php echo empty($item['time_publish'])? '未上架': $item['time_publish'] ?></dd>

        <?php if ( ! empty($item['time_publish']) ): ?>
        <dt>下架时间</dt>
        <dd><?php echo empty($item['time_suspend'])? '未下架': $item['time_suspend'] ?></dd>
        <?php endif ?>

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