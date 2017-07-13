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

<div id=content class=container>
	<?php if ( !empty($content) ): ?>
	<p><?php echo $content ?></p>
	<?php endif ?>

	<a title="我的" class="btn btn-block btn-default" href="<?php echo base_url('mine') ?>">我的</a>
	<a title="首页" class="btn btn-block btn-primary" href="<?php echo base_url('home') ?>">首页</a>
</div>