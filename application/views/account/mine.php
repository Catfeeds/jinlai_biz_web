<style>
	#content h2 {font-size:20px;}
	#content li {text-align:center;}
		#content li i {display:block;}
	
		#general-actions li {text-align:left;}
	/* 宽度在640像素以上的设备 */
	@media only screen and (min-width:641px)
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
	<div id=user-info>
		<a title="我的用户资料" href="<?php echo base_url('user/mine') ?>">
			<?php $username = !empty($this->session->nickname)? $this->session->nickname: $this->session->mobile; ?>
			<h2><?php echo $username ?></h2>
		</a>
	</div>

	<section id=general-actions>
		<ul>
			<li><a title="资料修改" href="<?php echo base_url('user/mine') ?>">资料修改</a>
			<!--<li><a title="关于我们" href="<?php echo base_url('article/about-us') ?>">关于我们</a></li>-->
			<!--<li><a title="设置" href="<?php echo base_url('setup') ?>">设置</a></li>-->
			<li><a title="退出账户" id=logout class="btn btn-block btn-danger" href="<?php echo base_url('logout') ?>">退出</a></li>
		</ul>
	</section>
	
</div>