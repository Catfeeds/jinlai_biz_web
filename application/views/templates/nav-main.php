<nav id=nav-main>
	<ul class=horizontal>

		<li class="home <?php if ($class === 'home') echo 'active' ?>"><a title="首页" href="<?php echo base_url('home') ?>">首页</a></li>
		<li class="order <?php if ($class === 'order index') echo 'active' ?>"><a title="订单" href="<?php echo base_url('order') ?>">订单</a></li>
		<li class="item <?php if ($class === 'item index') echo 'active' ?>"><a title="商品" href="<?php echo base_url('item') ?>">商品</a></li>
		<li class="mine <?php if ($class === 'account mine') echo 'active' ?>"><a title="我的" href="<?php echo base_url('mine') ?>">我的</a></li>

	</ul>
</nav>