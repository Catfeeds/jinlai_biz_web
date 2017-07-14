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

<nav id=nav-main>
	<ul class="horizontal">
		<li><a <?php if ($class === 'home') echo 'class=active' ?> title="首页" href="<?php echo base_url('home') ?>">首页</a></li>
		<li><a <?php if ($class === 'order index') echo 'class=active' ?> title="订单" href="<?php echo base_url('order') ?>">订单</a></li>
		<li><a <?php if ($class === 'item index') echo 'class=active' ?> title="商品" href="<?php echo base_url('item') ?>">商品</a></li>
		<li><a <?php if ($class === 'account mine') echo 'class=active' ?> title="我的" href="<?php echo base_url('mine') ?>">我的</a></li>
	</ul>
</nav>