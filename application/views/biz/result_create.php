<style>
	section {margin:60px 0 100px;padding:0 110px;}

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
	<h2>申请已提交</h2>
	<?php if ( !empty($content) ): ?>
	<section>
		<p><?php echo $content ?></p>
		<!--<p>若您填写的财务信息真实有效，我们将在3个工作日内向该账户汇入一笔1元以下的款项进行资质验证，请在收到打款通知后在验证页面填写打款数额进行商家身份验证。</p>-->
	</section>
	<?php endif ?>

	<ul class=row>
		<li class="col-xs-12 col-sm-6 col-sm-3">
			<a class="btn btn-default btn-lg" title="返回首页" href="<?php echo base_url() ?>">返回首页</a>
		</li>
        <li class="col-xs-12 col-sm-6 col-sm-3">
            <a class="btn btn-primary btn-lg" title="快速创建商品" href="<?php echo base_url('item/create-quick') ?>">去添加第一个商品</a>
        </li>
	</ul>
</div>