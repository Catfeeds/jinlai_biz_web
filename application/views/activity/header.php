<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>进来商户中心</title>

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo CDN_URL ?>ace/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo CDN_URL ?>ace/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
<!-- 		<link rel="stylesheet" href="<?php echo CDN_URL ?>ace/css/fonts.googleapis.com.css" /> -->

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo CDN_URL ?>ace/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo CDN_URL ?>ace/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<!-- <link rel="stylesheet" href="<?php echo CDN_URL ?>ace/css/ace-skins.min.css" />
		<link rel="stylesheet" href="<?php echo CDN_URL ?>ace/css/ace-rtl.min.css" /> -->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo CDN_URL ?>ace/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?php echo CDN_URL ?>/ace/js/ace-extra.min.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="<?php echo CDN_URL ?>ace/js/html5shiv.min.js"></script>
		<script src="<?php echo CDN_URL ?>ace/js/respond.min.js"></script>
		<![endif]-->


        <!-- 图片移动/拖位
    
        -->
        <!-- 图片异步上传 -->
        
	</head>

	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default          ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">菜单</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="<?php echo BASE_URL("salor/index") ?>" class="navbar-brand">
						<small>
							<i class="fa fa-leaf"></i>
							进来商户中心
						</small>
					</a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="<?php echo CDN_URL ?>ace/images/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									<small>欢迎</small>
									<?php echo $name ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								
								<li>
									<a href="<?php echo BASE_URL('bizlogin/logout') ?>">
										<i class="ace-icon fa fa-power-off"></i>
										退出
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar responsive ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				<ul class="nav nav-list">
					<li class="<?php echo $index; ?>">
						<a href="<?php echo BASE_URL("salor/index");?>">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> 订单列表 </span>
						</a>
						<b class="arrow"></b>
					</li>

					<li class="<?php echo $verify; ?>">
						<a href="<?php echo BASE_URL("salor/verify");?>">
							<i class="menu-icon fa fa-picture-o"></i>
							<span class="menu-text"> 核销 </span>
						</a>
						<b class="arrow"></b>
					</li>

					<li class="<?php echo $refund; ?>">
						<a href="<?php echo BASE_URL("salor/refund");?>">
							<i class="menu-icon ace-icon fa fa-tasks"></i>
							<span class="menu-text"> 退款 </span>
						</a>
						<b class="arrow"></b>
					</li>
					<li class="<?php echo $chat; ?>">
						<a href="<?php echo BASE_URL("chat/index");?>">
							<i class="menu-icon ace-icon fa fa-envelope"></i>
							<span class="menu-text"> 聊天 </span>
						</a>
						<b class="arrow"></b>
					</li>

					<li class="<?php echo $activity; ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-list"></i>
							<span class="menu-text"> 活动 </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="<?php echo $activity_index; ?>">
								<a href="/activity/activity_index">
									<i class="menu-icon ace-icon glyphicon glyphicon-align-left"></i>
									<span class="menu-text"> 已领取列表 </span>
								</a>
								<b class="arrow"></b>
							</li>

							<li class="<?php echo $activity_verify; ?>">
								<a href="/activity/activity_verify">
									<i class="menu-icon ace-icon glyphicon glyphicon-ok"></i>
									<span class="menu-text"> 确认活动券 </span>
								</a>
								<b class="arrow"></b>
							</li>
							<?php if ($inWeb) :?>
							<li class="<?php echo $activity_add; ?>">
								<a href="/activity/activity_add">
									<i class="menu-icon ace-icon glyphicon glyphicon-plus"></i>
									<span class="menu-text"> 添加活动券 </span>
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php echo $activity_ticketlist; ?>">
								<a href="/activity/activity_ticketlist">
									<i class="menu-icon ace-icon glyphicon glyphicon-tags"></i>
									<span class="menu-text"> 活动券列表 </span>
								</a>
								<b class="arrow"></b>
							</li>
							<?php endif;?>
						</ul>
					</li>


					
				</ul><!-- /.nav-list -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="<?php echo BASE_URL("salor/index");?>"><?php echo $title;?></a>
							</li>

							<!-- <li>
								<a href="#">Other Pages</a>
							</li>
							<li class="active">Blank Page</li> -->
						</ul><!-- /.breadcrumb -->
					</div>

	
