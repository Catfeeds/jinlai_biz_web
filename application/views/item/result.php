<style>


	/* 宽度在768像素以上的设备 */
	@media only screen and (min-width:769px)
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
	<section><?php echo $content ?></section>
	
	<a class="btn btn-default" title="个人中心" href="<?php echo base_url('mine') ?>">我的</a>
	<a class="btn btn-primary" title="首页" href="<?php echo base_url() ?>">首页</a>
</div>