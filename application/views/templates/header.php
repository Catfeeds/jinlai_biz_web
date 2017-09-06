<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	// 检查当前设备信息
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$is_wechat = strpos($user_agent, 'MicroMessenger')? TRUE: FALSE;
	$is_ios = strpos($user_agent, 'iPhone')? TRUE: FALSE;
	$is_android = strpos($user_agent, 'Android')? TRUE: FALSE;

	// 生成SEO相关变量，一般为页面特定信息与在config/config.php中设置的站点通用信息拼接
	$title = isset($title)? $title.' - '.SITE_NAME: SITE_NAME.' - '.SITE_SLOGAN;
	$keywords = isset($keywords)? $keywords.',': NULL;
	$keywords .= SITE_KEYWORDS;
	$description = isset($description)? $description: NULL;
	$description .= SITE_DESCRIPTION;
?>
<!doctype html>
<html lang=zh-cn>
	<head>
		<meta charset=utf-8>
		<meta http-equiv=x-dns-prefetch-control content=on>
		<!--<link rel=dns-prefetch href="https://cdn.key2all.com">-->
		<title><?php echo $title ?></title>
		<meta name=description content="<?php echo $description ?>">
		<meta name=keywords content="<?php echo $keywords ?>">
		<meta name=version content="revision20170906">
		<meta name=author content="刘亚杰Kamas,青岛意帮网络科技有限公司产品部&amp;技术部">
		<meta name=copyright content="进来商城,青岛意帮网络科技有限公司">
		<meta name=contact content="kamaslau@dingtalk.com">

		<meta name=viewport content="width=device-width,user-scalable=0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<?php //if ($is_wechat): ?>
		<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
		<script>
			<?php
				function curl($url, $params = NULL, $return = 'array', $method = 'get')
				{
				    $curl = curl_init();
				    curl_setopt($curl, CURLOPT_URL, $url);

				    // 设置cURL参数，要求结果保存到字符串中还是输出到屏幕上。
				    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				    curl_setopt($curl, CURLOPT_ENCODING, 'UTF-8');
			
					// 需要通过POST方式发送的数据
					if ($method === 'post'):
						$params['app_type'] = 'biz'; // 应用类型默认为biz
						curl_setopt($curl, CURLOPT_POST, count($params));
						curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
					endif;
			
				    // 运行cURL，请求API
					$result = curl_exec($curl);
			
					// 输出CURL请求头以便调试
					//var_dump(curl_getinfo($curl));

					// 关闭URL请求
				    curl_close($curl);

					// 转换返回的json数据为相应格式并返回
					if ($return === 'object'):
						$result = json_decode($result);
					elseif ($return === 'array'):
						$result = json_decode($result, TRUE);
					endif;

					return $result;
				}

				// 获取access_token
				function get_access_token()
				{
					$params = NULL;
					$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WECHAT_APP_ID.'&secret='.WECHAT_APP_SECRET;
					$result = curl($url, $params, 'array');
					return $result['access_token'];
				}

				// 获取jsapi_ticket
				function get_jsapi_ticket($access_token)
				{
					$params = NULL;
					$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
					$result = curl($url, $params, 'array');
					return $result['ticket'];
				}

				$access_token = get_access_token();
				$wesign['timestamp'] = time();
				$wesign['noncestr'] = 'Wm3WZYTPz0wzccnW';
				$wesign['jsapi_ticket'] = get_jsapi_ticket($access_token);
				$current_url = 'https://'. $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
				if (strpos($current_url, '#') !== FALSE) $current_url = substr($current_url, 0, strpos($current_url, '#'));
				$wesign['url'] = $current_url;

				// 微信JSAPI签名过程
				function wechat_sign_generate($params)
				{
					// 对参与签名的参数进行排序
					ksort($params);

					// 拼接字符串
					$param_string = '';
					foreach ($params as $key => $value)
						$param_string .= '&'. $key.'='.$value;
					$param_string = trim($param_string, '&'); // 清除开头的“&”
				
					// 计算字符串SHA1值
					$sign = SHA1($param_string);
					return $sign;
				}
			?>

			wx.config({
			    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
			    appId: '<?php echo WECHAT_APP_ID ?>', // 必填，公众号的唯一标识
			    timestamp: <?php echo $wesign['timestamp'] ?>, // 必填，生成签名的时间戳
			    nonceStr: '<?php echo $wesign['noncestr'] ?>', // 必填，生成签名的随机串
			    signature: '<?php echo wechat_sign_generate($wesign) ?>',// 必填，签名，见附录1
			    jsApiList: [
					'onMenuShareTimeline',
					'onMenuShareAppMessage',
					'hideMenuItems',
				] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2

			});

			wx.ready(function(){
				// 隐藏部分按钮
				wx.hideMenuItems({
				    menuList:[
				    	'menuItem:share:qq', 'menuItem:share:QZone', 'menuItem:share:facebook', 'menuItem:copyUrl', 'menuItem:readMode', 'menuItem:openWithQQBrowser', 'menuItem:openWithSafari', 'menuItem:share:email',
				    ] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
				});

				// 分享到朋友圈
				wx.onMenuShareTimeline({
				    title: '分享一个好平台 <?php echo $title ?>', // 分享标题
				    link: '<?php echo 'https://'. $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
				    imgUrl: null, // 分享图标
				    success: function () {
				        // 用户确认分享后执行的回调函数
						alert('谢谢分享');
				    },
				    cancel: function () {
				        // 用户取消分享后执行的回调函数
						alert('您未完成分享');
				    }
				});

				// 分享给朋友
				wx.onMenuShareAppMessage({
				    title: '分享一个好平台 <?php echo $title ?>', // 分享标题
				    desc: '<?php echo $description ?>', // 分享描述
				    link: '<?php echo 'https://'. $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
				    imgUrl: null, // 分享图标
				    type: '', // 分享类型,music、video或link，不填默认为link
				    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
				    success: function () {
				        // 用户确认分享后执行的回调函数
						alert('谢谢分享');
				    },
				    cancel: function () {
				        // 用户取消分享后执行的回调函数
						alert('您未完成分享');
				    }
				});

			});
			
			
		</script>
		<?php //endif ?>
		
		<script src="https://cdn.key2all.com/js/jquery/new.js"></script>
		<script defer src="https://cdn.key2all.com/js/jquery/jquery.cookie.js"></script>
		<script defer src="https://cdn.key2all.com/bootstrap/js/bootstrap-3_3_7.min.js"></script>
		<script defer src="/js/file-upload.js"></script>

		<link rel=stylesheet media=all href="https://cdn.key2all.com/css/reset.css">
		<link rel=stylesheet media=all href="https://cdn.key2all.com/bootstrap/css/bootstrap-3_3_7.min.css">
		<link rel=stylesheet media=all href="https://cdn.key2all.com/flat-ui/css/flat-ui.min.css">
		<link rel=stylesheet media=all href="https://cdn.key2all.com/font-awesome/css/font-awesome.min.css">
		<link rel=stylesheet media=all href="/css/style.css">

		<!--
		<link rel="shortcut icon" href="//images.ybslux.com/logos/logo_32x32.png">
		<link rel=apple-touch-icon href="//images.ybslux.com/logos/logo_120x120.png">
		-->

		<link rel=canonical href="<?php echo current_url() ?>">

		<meta name=format-detection content="telephone=yes, address=no, email=no">
		<meta name=apple-itunes-app content="app-id=1066224229">
	</head>
<?php
	// 将head内容立即输出，让用户浏览器立即开始请求head中各项资源，提高页面加载速度
	ob_flush();flush();
?>

<!-- 内容开始 -->
	<body<?php echo (isset($class))? ' class="'.$class.'"': NULL; ?>>
		<noscript class="bg-info text-info">
			<p>您的浏览器功能加载出现问题，请刷新浏览器重试；如果仍然出现此提示，请考虑更换浏览器。</p>
		</noscript>

<?php
	/**
	 * APP中调用webview时配合URL按需显示相应部分
	 * 此处以在APP中以WebView打开页面时不显示页面header部分为例
	 */
	//if ($is_wechat === FALSE):
?>
<?php //endif ?>
		<header id=header class="navbar navbar-default navbar-fixed-top" role=navigation>
			<nav class=container-fluid>
				<div class=navbar-header>
					<h1>
						<a id=logo class=navbar-brand title="<?php echo SITE_NAME ?>" href="<?php echo base_url() ?>"><?php echo SITE_NAME ?></a>
					</h1>
					<button class=navbar-toggle data-toggle=collapse data-target=".navbar-collapse">
						<span class=sr-only>展开/收起菜单</span>
						<span class=icon-bar></span>
						<span class=icon-bar></span>
						<span class=icon-bar></span>
					</button>
				</div>
				<div class="navbar-collapse collapse">
				    <ul class="nav navbar-nav">
						<li><a title="回到首页" href="<?php echo base_url() ?>">首页</a></li>

						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>我的<b class=caret></b></a>
							<ul class=dropdown-menu>
								<li><a title="个人中心" href="<?php echo base_url('user/edit') ?>">我的资料</a></li>
								<li><a title="密码修改" href="<?php echo base_url('password_change') ?>">密码修改</a></li>
								<li><a title="密码重置" href="<?php echo base_url('password_reset') ?>">密码重置</a></li>
								<?php if ( empty($this->session->password) ): ?>
								<li><a title="密码设置" href="<?php echo base_url('password_set') ?>">密码设置</a></li>
								<?php endif ?>
							</ul>
						</li>
				<?php if ( !empty($this->session->biz_id) ): ?>

						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>商家<b class=caret></b></a>
							<ul class=dropdown-menu>
								<li><a title="我的店铺" href="<?php echo base_url('biz/detail?id='.$this->session->biz_id) ?>">店铺资料</a></li>
							</ul>
						</li>

						<?php
						// 仅获得大于10的权限的管理员可以管理员工
						if ($this->session->role === '管理员' && $this->session->level > 10):
						?>
						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>员工<b class=caret></b></a>
							<ul class=dropdown-menu>
								<li><a title="员工列表" href="<?php echo base_url('stuff') ?>">员工列表</a></li>
								<li><a title="创建员工" href="<?php echo base_url('stuff/create') ?>">创建员工</a></li>
							</ul>
						</li>
						<?php endif ?>

						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown><i class="fa fa-database" aria-hidden=true></i> 商品<b class=caret></b></a>
							<ul class=dropdown-menu>
								<li><a title="店内分类列表" href="<?php echo base_url('item_category_biz') ?>">店内分类</a></li>
								<li><a title="创建店内分类" href="<?php echo base_url('item_category_biz/create') ?>">创建店内分类</a></li>
								<li role="separator" class="divider"></li>
								<li><a title="商品列表" href="<?php echo base_url('item') ?>">商品列表</a></li>
								<li><a title="创建商品" href="<?php echo base_url('item/create') ?>">创建商品</a></li>
								<li><a title="快速创建" href="<?php echo base_url('item/create_quick') ?>">快速创建 <i class="fa fa-bolt" aria-hidden="true"></i></a></li>
							</ul>
						</li>

						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown><i class="fa fa-money" aria-hidden=true></i> 订单<b class=caret></b></a>
							<ul class=dropdown-menu>
								<li><a title="商品订单列表" href="<?php echo base_url('order') ?>">订单列表</a></li>
							</ul>
						</li>

						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>营销活动<b class=caret></b></a>
							<ul class=dropdown-menu>
								<li><a title="店内活动列表" href="<?php echo base_url('promotion_biz') ?>">店内活动列表</a></li>
								<li><a title="创建店内活动" href="<?php echo base_url('promotion_biz/create') ?>">创建店内活动</a></li>
								<li role="separator" class="divider"></li>
								<li><a title="平台活动列表" href="<?php echo base_url('promotion') ?>">平台活动列表</a></li>
								<!--<li><a title="平台活动" href="<?php echo base_url('promotion/create') ?>">申请平台活动</a></li>-->
							</ul>
						</li>

						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>优惠券<b class=caret></b></a>
							<ul class=dropdown-menu>
								<li><a title="优惠券模板" href="<?php echo base_url('coupon_template') ?>">优惠券模板</a></li>
								<li><a title="创建优惠券模板" href="<?php echo base_url('coupon_template/create') ?>">创建优惠券模板</a></li>
								<li role="separator" class="divider"></li>
								<li><a title="优惠券包" href="<?php echo base_url('coupon_combo') ?>">优惠券包</a></li>
								<li><a title="创建优惠券包" href="<?php echo base_url('coupon_combo/create') ?>">创建优惠券包</a></li>
							</ul>
						</li>

						<!--
						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown>余额<b class=caret></b></a>
							<ul class=dropdown-menu>
								<li><a title="余额列表" href="<?php echo base_url('balance') ?>">余额列表</a></li>
							</ul>
						</li>
						
						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown><i class="fa fa-file-image-o" aria-hidden=true></i> 素材<b class=caret></b></a>
							<ul class=dropdown-menu>
								<li><a title="素材列表" href="<?php echo base_url('material') ?>">素材列表</a></li>
							</ul>
						</li>
						-->
					
						<?php if ( $this->session->role === '管理员' && $this->session->level > 30): ?>
						<!--
						<li class=dropdown>
							<a href=# class=dropdown-toggle data-toggle=dropdown><i class="fa fa-money" aria-hidden=true></i> 积分<b class=caret></b></a>
							<ul class=dropdown-menu>
								<li><a title="积分列表" href="<?php echo base_url('credit') ?>">积分列表</a></li>
							</ul>
						</li>
						-->
						<?php endif ?>
			<?php endif ?>
					</ul>

					<ul class="nav navbar-nav navbar-right">
						<?php if ( !isset($this->session->time_expire_login) ): ?>
						<li><a title="登录" href="<?php echo base_url('login') ?>">登录</a></li>
						<?php else: ?>
						<li><a title="个人中心" href="<?php echo base_url('mine') ?>">个人中心</a></li>
						<li><a title="退出" href="<?php echo base_url('logout') ?>">退出</a></li>
						<?php endif ?>
					</ul>

				</div>
			</nav>
		</header>

		<main id=maincontainer role=main>
