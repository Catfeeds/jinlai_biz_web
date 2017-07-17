$(function(){
	// 图片验证码URL
	var url_captcha = 'https://www.517ybang.com/captcha?';

	// 短信验证码URL
	var url_sms = 'https://api.517ybang.com/sms/create';

	// 清空cookie中的sms_id记录
	Cookies.remove('sms_id');

	// 更新图片验证码
	$('#captcha-image img').click(function(){
		// 获取当前时间戳以确保重新生成验证码图片
		var timestamp = Date.parse(new Date()) / 1000;
		$(this).attr('src', url_captcha + timestamp);
	});

	/**
	 * 检查手机号格式
	 * @param string mobile 需要接收短信的手机号
	 * @param boolean check_length 是否需要检查字符串长度
	 */
	function check_mobile(mobile, check_length)
	{
		if ( isNaN(mobile) )
		{
			alert('请输入有效手机号码');
			$('[name=mobile]').val('').focus();
		}
		if (check_length == true)
		{
			if (mobile.length != 11)
			{
				alert('请输入有效手机号码');
				$('[name=mobile]').val('').focus();
				return false;
			}
			else if (mobile.length == 11)
			{
				$('a#sms-send').removeAttr('disabled');
			}
		}
	}

	// 点击短信发送按钮后的业务流程
	var handler = function(){
		// 检查图片验证码是否已填写
		var captcha_verify = $('[name=captcha_verify]').val();
		if ( isNaN(captcha_verify) )
		{
			alert('请填写图片验证码');
			$('[name=captcha_verify]').val('').focus();
			return false;
		}

		// 获取当前时间戳及日期（日）
		var timestamp = Date.parse(new Date()) / 1000;
		var today = new Date();
		today = today.getDate();
		// 检查当日是否已发送超过5条短信
		if (Cookies.get('sms_today') == today && Cookies.get('sms_today_sent') === 5)
 		{
 			alert('今天短信获取过多，请明天再试');
 			return false;
 		}
 		if ((timestamp - Cookies.get('sms_last_sent')) < 59)
 		{
 			alert('短信发送过于频繁，请稍候再试');
 			return false;
 		}

		// 获取mobile字段值，验证该字段是否已被输入11位数字，设置sms_send按钮为不可用状态
		var mobile = $.trim( $('[name=mobile]').val() );
		if (check_mobile(mobile, true) == false)
		{
			return false;
		}

		var params = {
			'app_type' : 'client',
			'platform' : 'web',
			'mobile' : mobile,
			'captcha_verify' : captcha_verify
		};

		// 尝试发送短信并获取发送状态
		$.post(
			url_sms, // API URL
			params, // API参数
	        function(data){
				api_callback(data);
	        }, // 对返回数据进行处理
			"json" // 对返回数据以JSON格式进行解析
		); 
		return false;
	}
	
	// 发送验证码短信
	$('a#sms-send').click(handler);

	// 对API返回数据进行处理
	function api_callback(data)
	{
		if (data.status == 200) // 若成功，激活并将焦点移到captcha字段，激活确认按钮
		{
			alert(data.content.message);

			// 记录短信ID并赋值到相应字段
			Cookies.set('sms_id', data.content.sms_id);
			$('[name=sms_id]').val(data.content.sms_id);

			var timestamp = Date.parse(new Date()) / 1000;
			// 记录最后成功发送短信时间、本日日期，以及本日已发短信数
			Cookies.set('sms_last_sent', timestamp);
			var today = new Date();
			today = today.getDate();
			if (Cookies.get('sms_today') != today)
			{
				Cookies.set('sms_today', today);
				Cookies.set('sms_today_sent', 1);
			} else {
				Cookies.set('sms_today_sent', Cookies.get('sms_today_sent') + 1);
			}

			// 倒计时60秒后重新激活发送按钮
			$('a#sms-send').unbind('click').text('60');
		    var interval_id = setInterval(countdown, 1000);
			// 倒计时并更新短信发送按钮HTML
			function countdown()
			{
				var current_second = $('a#sms-send').text();
				console.log('from '+current_second+' to');
				if (current_second < 1)
				{
					clearInterval(interval_id);
					$('a#sms-send').text('获取验证码').bind('click', handler);
				} else {
					current_second = current_second - 1;
					$('a#sms-send').text(current_second);
				}
			}

			$('[name=captcha],button[type=submit]').removeAttr('disabled');
			$('[name=captcha]').focus();
		}
		else // 若失败，提示
		{
			alert(data.content.error.message);
		}
	}

});