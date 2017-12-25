<link rel=stylesheet media=all href="/css/result.css">
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
    <h2><?php echo $title ?></h2>
    <?php if ( !empty($content) ): ?>
        <section><?php echo $content ?></section>
    <?php endif ?>

    <ul class=row>
        <li class="col-xs-12 col-sm-6 col-sm-3"><a title="个人中心" class="btn btn-default btn-lg" href="<?php echo base_url('mine') ?>">个人中心</a></li>
        <li class="col-xs-12 col-sm-6 col-sm-3"><a title="首页" class="btn btn-primary btn-lg" href="<?php echo base_url('home') ?>">首页</a></li>
    </ul>
</div>