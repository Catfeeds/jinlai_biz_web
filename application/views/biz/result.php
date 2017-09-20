<style>
	#content {padding-top:70px;}
	h2 {font-size:50px;text-align:center;}
	section {color:#666464;margin:60px 0 100px;padding:0 110px;}

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
	<h2><?php echo $title ?></h2>
	<?php if ( !empty($content) ): ?>
	<section>
		<p><?php echo $content ?></p>
	</section>
	<?php endif ?>
	
	<ul class=row>
		<li class="col-xs-12 col-sm-6 col-sm-3"><a class="btn btn-default" title="返回首页" href="<?php echo base_url() ?>">首页</a></li>

	<?php if ( isset($operation) && $operation === 'edit' ): ?>
		<li class="col-xs-12 col-sm-6 col-sm-3"><a class="btn btn-primary" title="查看<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/detail?id='.$id) ?>">确认一下</a></li>
	<?php endif ?>
	</ul>
</div>