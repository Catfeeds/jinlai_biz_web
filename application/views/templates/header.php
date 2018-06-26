<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

    // 生成SEO相关变量，一般为页面特定信息与在config/config.php中设置的站点通用信息拼接
    $title_prefix = (ENVIRONMENT !== 'production')? '[测试]': NULL;
    $title = isset($title)? $title: SITE_NAME.' - '.SITE_SLOGAN;
    $title = $title_prefix. $title;
    $keywords = (isset($keywords)? $keywords.',': NULL). SITE_KEYWORDS;
    $description = (isset($description)? $description: NULL). SITE_DESCRIPTION;

    // 生成body的class
    $body_class = ( isset($class) )? $class: NULL;
    $body_class .= ($this->user_agent['is_wechat'])? ' is_wechat': NULL;
    $body_class .= ($this->user_agent['is_ios'])? ' is_ios': NULL;
    $body_class .= ($this->user_agent['is_android'])? ' is_android': NULL;
    $body_class .= ($this->user_agent['is_mobile'])? ' is_mobile': NULL; // 移动端设备

    $body_class .= ($this->user_agent['is_macos'])? ' is_macos': NULL;
    $body_class .= ($this->user_agent['is_windows'])? ' is_windows': NULL;
    $body_class .= ($this->user_agent['is_desktop'])? ' is_desktop': NULL; // 非移动端设备
?>
<!doctype html>
<html lang=zh-cn>
	<head>
		<meta charset=utf-8>
		<meta http-equiv=x-dns-prefetch-control content=on>
		<link rel=dns-prefetch href="<?php echo CDN_URL ?>">
		<title><?php echo $title ?></title>
		<meta name=description content="<?php echo $description ?>">
		<meta name=keywords content="<?php echo $keywords ?>">
		<meta name=version content="revision20180627">
		<meta name=author content="刘亚杰Kamas,青岛意帮网络科技有限公司产品部&技术部">
		<meta name=copyright content="进来商城,青岛意帮网络科技有限公司">
		<meta name=contact content="kamaslau@dingtalk.com">

		<?php if ($this->user_agent['is_desktop']): ?>
        <meta name=viewport content="width=device-width,user-scalable=0">
        <?php else: ?>
		<meta name=viewport content="width=750,user-scalable=0">
        <?php endif ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

        <?php if ( ! empty($this->session->stuff_id) && empty($this->stuff)): ?>
        <script>
            alert('员工关系状态异常，请重新登录')
            location.href = "<?php echo base_url('logout') ?>"
        </script>
        <?php exit();endif; ?>

		<script src="<?php echo CDN_URL ?>js/jquery-3.3.1.min.js"></script>
        <script src="/js/common.js"></script>
		<script defer src="<?php echo CDN_URL ?>js/js.cookie.js"></script>
        <script defer src="<?php echo CDN_URL ?>bootstrap/v3.3.7/bootstrap.min.js"></script>
        <script defer src="<?php echo CDN_URL ?>font-awesome/v5.0.13/fontawesome-all.min.js"></script>
        <script defer src="<?php echo CDN_URL ?>font-awesome/v5.0.13/fa-v4-shims.min.js"></script>
        <script>
            // 当前用户信息
            var user_id = <?php echo empty($this->session->user_id)? 'null': $this->session->user_id ?>;
            var biz_id = <?php echo empty($this->session->biz_id)? 'null': $this->session->biz_id ?>;

            // 全局参数
            var api_url = '<?php echo API_URL ?>'; // API根URL
            var base_url = '<?php echo BASE_URL ?>'; // 页面根URL
            var media_url = '<?php echo MEDIA_URL ?>'; // 媒体文件根URL
            var class_name = '<?php echo $this->class_name ?>';
            var class_name_cn = '<?php echo $this->class_name_cn ?>';

            // AJAX参数
            var ajax_root = '<?php echo API_URL ?>'
            var common_params = new Object()
            common_params.app_type = 'biz' // 默认为商户端请求
            common_params.user_id = user_id
            common_params.biz_id = biz_id

            // UserAgent
            var user_agent = <?php echo json_encode($this->user_agent) ?>;
        </script>

        <link rel=stylesheet media=all href="<?php echo CDN_URL ?>css/reset.css">
        <link rel=stylesheet media=all href="<?php echo CDN_URL ?>bootstrap/v3.3.7/bootstrap.min.css">
        <link rel=stylesheet media=all href="<?php echo CDN_URL ?>css/flat-ui.min.css">
		<link rel=stylesheet media=all href="/css/style.css">

        <?php if ($this->session->time_expire_login > time()): ?>
        <script defer src="<?php echo CDN_URL ?>jquery/jquery.lazyload.min.js"></script>
        <script defer src="<?php echo CDN_URL ?>js/jquery.qrcode.min.js"></script>
        <script defer src="<?php echo CDN_URL ?>jquery/stupidtable.min.js"></script>
        <?php endif ?>

        <?php if ($this->user_agent['is_desktop']): ?>
            <link rel="shortcut icon" href="<?php echo CDN_URL ?>icon/jinlai_client/icon28@3x.png">
            <link rel=canonical href="<?php echo current_url() ?>">
        <?php else: ?>
            <link rel=apple-touch-icon href="<?php echo CDN_URL ?>icon/jinlai_client/icon120@3x.png">
            <meta name=format-detection content="telephone=yes, address=no, email=no">
        <?php endif ?>

        <?php if (!empty(IOS_APP_ID) && $this->user_agent['is_ios']): ?>
            <meta name=apple-itunes-app content="app-id=<?php echo IOS_APP_ID ?>">
        <?php endif ?>
	</head>
<?php
    // 将head内容立即输出，让用户浏览器立即开始请求head中各项资源，提高页面加载速度
    ob_flush();flush();
?>

<!-- 内容开始 -->
	<body<?php echo ( !empty($body_class) )? ' class="'.$body_class.'"': NULL ?>>
		<noscript class="bg-info text-info">
			<p>您的浏览器功能加载出现问题，请刷新浏览器重试；如果仍然出现此提示，请考虑更换浏览器。</p>
		</noscript>

		<header id=header class="navbar navbar-fixed-top" role=navigation>
            <?php
                // 首页不显示返回按钮
                if (strpos($class,'home') === FALSE && $class !== 'success'):
            ?>
			<a id=return href="javascript:" onclick="history.back()">
                <i class="far fa-chevron-left"></i>
			</a>
            <?php endif ?>

			<nav class=container-fluid>
				<div class=navbar-header>
					<h1>
                        <?php
                            // 移动端仅显当前页面标题，不跳转
                            if ($this->user_agent['is_mobile']):
                                echo $title;
                            else:
                        ?>
                        <a id=logo title="<?php echo SITE_NAME ?>" href="<?php echo base_url() ?>">
                            <?php echo SITE_NAME ?>
                        </a>
                        <?php endif ?>
					</h1>
					<button class=navbar-toggle data-toggle=collapse data-target=".navbar-collapse">
						<span class=sr-only>展开/收起菜单</span>
                        <i class="far fa-ellipsis-h"></i>
					</button>
				</div>
				<div class="navbar-collapse collapse">
				    <ul class="nav navbar-nav">
						<li><a title="回到首页" href="<?php echo base_url() ?>">首页</a></li>

				<?php if ( !empty($this->session->biz_id) ): ?>
						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>商家 <i class="far fa-angle-down"></i></a>
							<ul class=dropdown-menu>
								<li><a href="<?php echo base_url('biz/detail?id='.$this->session->biz_id) ?>">店铺资料</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="<?php echo base_url('ornament_biz') ?>">店铺装修</a></li>

                                <li role="separator" class="divider"></li>
                                <li><a href="<?php echo base_url('branch') ?>">门店</a></li>
                                <li><a href="<?php echo base_url('branch/create') ?>">创建门店</a></li>

                                <?php
                                    // 仅获得大于10的权限的管理员可以管理员工
                                    if ($this->session->role === '管理员' && $this->session->level > 10):
                                ?>
                                <li role="separator" class="divider"></li>
                                <li><a href="<?php echo base_url('stuff') ?>">员工</a></li>
                                <li><a href="<?php echo base_url('stuff/create') ?>">创建员工</a></li>
                                <?php endif ?>

                                <li role=separator class=divider></li>
                                <li><a href="<?php echo base_url('notice') ?>">系统通知</a></li>

                                <li role=separator class=divider></li>
                                <li><a href="<?php echo base_url('message') ?>">聊天消息</a></li>
                                <li><a href="<?php echo base_url('message/create') ?>">创建聊天消息</a></li>
							</ul>
						</li>

						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>商品 <i class="far fa-angle-down"></i></a>
							<ul class=dropdown-menu>
								<li><a href="<?php echo base_url('item_category_biz') ?>">店内分类</a></li>
								<li><a href="<?php echo base_url('item_category_biz/create') ?>">创建店内分类</a></li>

								<li role="separator" class="divider"></li>
								<li><a href="<?php echo base_url('freight_template_biz') ?>">运费模板</a></li>
								<li><a href="<?php echo base_url('freight_template_biz/create') ?>">创建运费模板</a></li>

								<li role="separator" class="divider"></li>
								<li><a href="<?php echo base_url('item') ?>">商品</a></li>
								<li><a href="<?php echo base_url('item/create') ?>">创建商品</a></li>
								<li><a href="<?php echo base_url('item/create_quick') ?>">快速创建</a></li>
                                <li><a href="<?php echo base_url('item/create_import') ?>">导入</a></li>
							</ul>
						</li>

						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>订单 <i class="far fa-angle-down"></i></a>
							<ul class=dropdown-menu>
								<li><a href="<?php echo base_url('order') ?>">所有订单</a></li>
                                <li><a href="<?php echo base_url('order?status=待接单') ?>">待接单订单</a></li>
                                <li><a href="<?php echo base_url('order?status=待发货') ?>">待发货订单</a></li>

                                <li role="separator" class="divider"></li>
                                <li><a href="<?php echo base_url('refund') ?>">退款/售后</a></li>
							</ul>
						</li>

						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>营销 <i class="far fa-angle-down"></i></a>
							<ul class=dropdown-menu>
                                <li><a href="<?php echo base_url('article_biz') ?>">文章</a></li>
                                <li><a href="<?php echo base_url('article_biz/create') ?>">创建文章</a></li>

                                <li role="separator" class="divider"></li>
                                <li><a href="<?php echo base_url('coupon_template') ?>">优惠券模板</a></li>
                                <li><a href="<?php echo base_url('coupon_template/create') ?>">创建优惠券模板</a></li>

                                <li role="separator" class="divider"></li>
                                <li><a href="<?php echo base_url('coupon_combo') ?>">优惠券包</a></li>
                                <li><a href="<?php echo base_url('coupon_combo/create') ?>">创建优惠券包</a></li>

                                <li role="separator" class="divider"></li>
                                <li><a href="<?php echo base_url('promotion_biz') ?>">店内活动</a></li>
								<li><a href="<?php echo base_url('promotion_biz/create') ?>">创建店内活动</a></li>

								<li role="separator" class="divider"></li>
								<li><a href="<?php echo base_url('promotion') ?>">平台活动</a></li>
							</ul>
						</li>

						<!--
						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>余额 <i class="far fa-angle-down"></i></a>
							<ul class=dropdown-menu>
								<li><a href="<?php echo base_url('balance') ?>">所有余额</a></li>
							</ul>
						</li>
						
						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>素材 <i class="far fa-angle-down"></i></a>
							<ul class=dropdown-menu>
								<li><a href="<?php echo base_url('material') ?>">所有素材</a></li>
							</ul>
						</li>
						-->

                        <!--
                        <?php if ( $this->session->role === '管理员' && $this->session->level >= 30): ?>
						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>积分 <i class="far fa-angle-down"></i></a>
							<ul class=dropdown-menu>
								<li><a href="<?php echo base_url('credit') ?>">所有积分</a></li>
							</ul>
						</li>

						<?php endif ?>
						-->
			<?php endif ?>
					</ul>

                    <ul class="nav navbar-nav navbar-right">
                    <?php
                    if ( $this->session->time_expire_login > time() ):
                        $display_name = !empty($this->session->nickname)? $this->session->nickname: $this->session->lastname.$this->session->firstname;
                        ?>
                        <li>
                            <a href="<?php echo base_url('mine') ?>">
                                <i class="far fa-user-circle"></i>
                                <?php echo $display_name ?>
                                <?php echo $this->session->role. 'lv.'. $this->session->level ?>
                            </a>
                        </li>
                        <li><a href="<?php echo base_url('logout') ?>"><i class="far fa-sign-out"></i></a></li>

                    <?php else: ?>
                        <li><a href="<?php echo base_url('login') ?>"><i class="far fa-sign-in"></i> 登录</a></li>

                    <?php endif ?>
                    </ul>

				</div>
			</nav>
		</header>

		<main id=maincontainer role=main>
