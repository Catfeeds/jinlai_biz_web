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
	<?php
		if ( !empty($error) ):
			echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';

		else:
    ?>
	<header>
		<h2><?php echo $item['title'] ?></h2>

		<ul class="list-horizontal row">
			<li class="col-xs-12 col-sm-6 col-md-3"><?php echo $item['time_edit'] ?></li>
		</ul>

        <?php if ( !empty($item['excerpt']) ): ?>
            <div class="excerpt well"><?php echo $item['excerpt'] ?></div>
        <?php endif ?>
	</header>

	<section>
        <?php echo $item['content'] ?>
    </section>
	<?php endif ?>
</div>