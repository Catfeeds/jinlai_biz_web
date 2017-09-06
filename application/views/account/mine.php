<style>
	#content h2 {font-size:20px;}
	#content li {text-align:center;}
		#content li i {display:block;}
	
		#general-actions li {text-align:left;}

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
	<div id=user-info>
		<a title="我的用户资料" href="<?php echo base_url('user/edit') ?>">
			<h2><?php echo $this->session->nickname ?></h2>
		</a>
	</div>

	<section id=general-actions>
		<ul>
			<li><a title="我的资料" href="<?php echo base_url('user/edit') ?>">我的资料</a>
			<li><a title="修改密码" href="<?php echo base_url('password_change') ?>">修改密码</a></li>
			<li><a title="重置密码" href="<?php echo base_url('password_reset') ?>">重置密码</a></li>
			<li><a title="退出账户" id=logout class="btn btn-block btn-danger" href="<?php echo base_url('logout') ?>">退出</a></li>
		</ul>
	</section>

</div>