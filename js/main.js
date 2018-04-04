$(function(){


	// AJAX程序范例
	function ajax_sample(api_url)
	{
        // 初始化参数
		params = new Object();
        params.id = $(this).attr('data-id');

        // 拼合完整API路径
		var api_url = ajax_root + 'account/user_exist';

		// AJAX获取结果并生成相关HTML
		$.post(
		    api_url,
            params,
            function(result)
		    {
                console.log(result); // 输出回调数据到控制台

                if (result.status == 200)
                {
                    // 若成功，进行后续处理
                    alert(result.status);
                }
                else
                {
                    // 若失败，进行提示
                    alert(result.content.error.message);
                }
		    }
		);

        return false;
	}
	
	// 删除（关注商家、收藏商品、TODO:地址 等）
	$('a[data-op-name=delete]').click(function(){
		var is_confirm = confirm('确定要删除此项？');
		console.log(is_confirm);

		if (is_confirm == true)
		{
			var op_class = $(this).attr('data-op-class');
			var op_name = $(this).attr('data-op-name');
			var api_url = op_class + '/' + op_name;

			params = new Object();
			params.ids = $(this).attr('data-id');
			
			// AJAX获取结果并生成相关HTML
			$.getJSON(ajax_root+api_url, params, function(data)
			{
				console.log(data); // 输出回调数据到控制台

				if (data.status == 200)
				{
					// 移除DOM
					$('[data-item-id='+ params.ids +']').remove();
				}
				else // 若失败，进行提示
				{
					alert(data.content.error.message);
				}
			});
		}

		return false;
	});

});