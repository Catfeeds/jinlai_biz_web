/**
 * Ajaxupload类
 *
 * 处理AJAX文件上传
 *
 * @version beta20180125
 * @author Kamas 'Iceberg' Lau <https://github.com/kamaslau/ajaxupload>
 * @copyright Kamas 'Iceberg' Lau <kamaslau@outlook.com>
 */

// AJAX文件上传服务器端URL；上传目标文件夹名稍后通过上传按钮的相关属性获取
var api_url = '//biz.517ybang.com/ajaxupload?target=';

// 最大可上传文件数量，默认为4；可被上传按钮的data-max-count属性覆盖
var max_count = 4;

// 当前相应字段已上传文件数
var current_count = 0;

/* 从此处起请谨慎修改 */
$(function(){
	// 获取所有图片上传按钮
	var upload_fields = $('button.file-upload');
    // 为每个上传按钮对应的字段进行已上传项计数并决定是否显示上传按钮
	upload_fields.each(function(){
        selector_toggle( $(this) );
	});

	// 以样式可定制的虚拟选择按钮替代原生文件选择器
    $('.file_selector').click(function(){
            $(this).siblings('[type=file]').click();
        });

	// 可选：选择文件后自动上传
	$('input[type=file]').change(function(){
            $(this).closest('.selector_zone').siblings('.file-upload').click();
		});

	// 文件上传主处理方法
	$('.file-upload').click(function(){
		// 检查当前浏览器是否支持AJAX文件上传
		check_support_formdata();

		var button = $(this);

		button_disable(button); // 禁用上传按钮

		// 处理上传
		file_upload( button );
	});

	// 禁用上传按钮
	function button_disable(button)
	{
		button.attr('disabled', 'disabled');
		button.html('<i class="fa fa-refresh" aria-hidden=true></i> 正在上传');
		console.log('正在上传');
	}

	// 激活上传按钮
	function button_restore(button)
	{
		button.removeAttr('disabled');
		button.html('<i class="fa fa-upload" aria-hidden=true></i> 上传');
		console.log('结束上传');
	}

	// 检查浏览器是否支持完成文件上传必须的XHR2（FormData）功能
	function check_support_formdata()
	{
		if ( ! window.hasOwnProperty('FormData') )
		{
			alert('您正在使用安全性差或者已过时的浏览器；请使用谷歌或火狐浏览器。');
			return false;
		}
	}

	// 获取文件大小
	function file_size(file)
	{
		return (file.files[0].size / 1024).toFixed(2); // 保留两位小数
	}

	// 处理文件上传
	function file_upload(button)
	{
		// 创建FormData对象
		var formData = new FormData();

		// 获取文件选择器对象
		var file_selector = $( '#' + button.attr('data-selector-id') );

		// 获取待上传的文件数量（HTML中可通过type=file表单项中添加multiple属性对多文件上传提供支持）
		var file_count = file_selector[0].files.length;
		// 若无任何文件被选中，进行提示
		if (file_count == 0)
		{
			alert('请选择文件');
			button_restore(button); // 激活上传按钮
			return;
		}

		// 检查是否设置了最大可上传文件数；若已设置，覆盖默认max_count值
		if (button.attr('data-max-count') != undefined)
		{
			max_count = button.attr('data-max-count');
		}

        // 表单项名称及表单值
        var input_name = button.attr('data-input-name');
        var current_value = $('[name=' + input_name + ']').val();
        var current_array = $.grep(
            current_value.split(','),
            function(n) {return $.trim(n).length > 0;}
        ); // 清除空数组元素，后同
        //console.log(current_array);
        var current_count = current_array.length; // 当前已上传项数

		// 若超出最大文件数量，进行提示
        var available_count = max_count - current_count;
        // console.log('最大可上传文件数：' + max_count);
        // console.log('已上传文件数：' + current_count);
        // console.log('可上传文件数：' + available_count);
        // console.log('待上传文件数：' + file_count);
		if (file_count > available_count)
		{
			alert('最多可再上传'+ available_count +'个文件');
			button_restore(button); // 激活上传按钮
			return;
		}

		// 将所有需上传的文件信息放入formData对象
		for (var i=0; i<file_count; i++)
		{
			formData.append('file'+i, file_selector[0].files[ i ] );
		}

		// 获取上传目标文件夹名
		var dir_target = button.attr('data-target-dir');

		$.ajax({
	        url: api_url + dir_target, // 处理上传的后端URL
	        type: 'POST',
			cache: false, // 上传文件不需要缓存
	        data: formData,

	        processData: false,  // 不处理发送的数据
	        contentType: false // 不设置Content-Type请求头

	    }).then(function(data){
			// 输出响应值以便测试
			//console.log(data);
			
			// 进行总体提示
			if (data.status == 200)
			{
				alert('上传成功');
			}
			else // 若上传失败，进行提示
			{
				alert('上传失败：' + data.content.error.message);
				console.log(data.content.error.message);
			}
			
			button_restore(button); // 激活上传按钮

			// 初始化表单值
			var input_value = '';

			// 初始化预览区
			var file_previewer = button.siblings('.upload_preview');

			// 轮番显示上传结果
			$.each(
				data.content.items,
				function(i, item)
				{
					// 若上传成功，显示预览；若上传失败，显示源文件信息及错误描述
					if (item.status == 200)
					{
						// 更新预览区
						var item_content =
						'<li class=col-xs-3 data-input-name="' + button.attr('data-input-name') + '" data-item-url="'+ item.content + '">' +
						'	<i class="remove fa fa-minus"></i>' +
						'	<i class="left fa fa-arrow-left"></i>' +
						'	<i class="right fa fa-arrow-right"></i>' +
						'	<figure>' +
						'		<img src="' + item.content +'">' +
						'	</figure>' +
						'</li>';

						// 更新表单值
						input_value += item.content;
						// 为多图字段增加分隔符
						if (file_count > 1)
						{
							input_value += ',';
						}
					}
					else
					{
						console.log('上传失败：' + item.content.file.name + ' - ' + item.content.error.message);
                        return;
						/*
						TODO 失败信息描述
						// 更新预览区
						var item_content =
						'<li class="col-xs-12">' +
						'	<dl>' +
						'		<dt>失败原因</dt><dd>' + item.content.error.message + '</dd>' +
						'		<dt>源文件名</dt><dd>' + item.content.file.name + '</dd>' +
						'		<dt>源文件类型</dt><dd>' + item.content.file.type + '</dd>' +
						'		<dt>源文件大小</dt><dd>' + (item.content.file.size / 1024).toFixed(2) + 'kb</dd>' +
						'	</dl>' +
						'</li>';
						*/
					}

					// 在预览区显示预览
					file_previewer.append(item_content);
				}
			); //end $.each

			// 向相应表单项赋值
			input_value = $.trim(input_value);
			if (current_value == '' || current_value == 'undefined')
			{
				$('[name=' + input_name + ']').val(input_value);
			} else {
                $('[name=' + input_name + ']').val(current_value + ',' + input_value);
			}

            selector_toggle( button );
	    });

	} // end file_upload

    // 删除已上传图片
    $(document).on(
        {
            'click':
                function () {
                    delete_single($(this));
                }
        },
        '.upload_preview .remove'
    );

    // 向左调整排序
    $(document).on(
        {
            'click':
                function () {
                    left_single($(this));
                }
        },
        '.upload_preview .left'
    );

    // 向右调整排序
    $(document).on(
        {
            'click':
                function () {
                    right_single($(this));
                }
        },
        '.upload_preview .right'
    );

	// 切换选择器显示与否
	function selector_toggle( button )
	{
        var input_name = button.attr('data-input-name');
        var current_value = $('[name='+ input_name +']').val(); // 相应字段值
        var current_array = current_value.split(','); // 相应字段值数组
        //console.log(current_array);
        current_array = $.grep(
        	current_array,
			function(n) {return $.trim(n).length > 0;}
		);

		if (current_array.length < button.attr('data-max-count')){
            button.siblings('.selector_zone').show();
		} else {
            button.siblings('.selector_zone').hide();
		}
    } // end selector_toggle

	function delete_single(item)
	{
		var item_url = item.closest('li').attr('data-item-url');
        var input_name = item.closest('li').attr('data-input-name');
        var button = item.closest('.upload_preview').siblings('button.file-upload');

		// 删除相应字段值
		var current_value = $('[name='+ input_name +']').val();
		current_value = current_value.replace(item_url, '');
        var current_array = $.grep(
            current_value.split(','),
            function(n) {return $.trim(n).length > 0;}
        );
		$('[name='+ input_name +']').val(current_array.join(','));

		// 删除相应dom
		item.closest('li').remove();
		console.log('deleted a item, of which value is '+ item_url);

		// 若所余项数少于最大可上传文件数量，显示选择器
        selector_toggle( button );
	} // end delete_single

    function left_single(item)
    {
        var item_url = item.closest('li').attr('data-item-url'); // 当前DOM相应值
        var input_name = item.closest('li').attr('data-input-name'); // 当前DOM对应字段名
        var current_array = $('[name='+ input_name +']').val().split(','); // 相应字段值数组
		var current_index = current_array.indexOf(item_url); // 当前值在字段值中的序号（从0开始）

        // 修改字段值数组
        current_array.splice(current_index-1, 0, item_url); // 添加到新位置
        current_array.splice(current_index+1, 1); // 从原位置删除
		// 生成结果字段值并赋值到字段
        var current_value = $.unique( current_array ).join(',');
        $('[name='+ input_name +']').val(current_value);

        // 调整相应dom顺序
		var current_dom = item.closest('li');
        var dom_to_react = current_dom.closest('ul').find('li').eq(current_index-1);
        current_dom.insertBefore(dom_to_react);
    } // end left_single

    function right_single(item)
    {
        var item_url = item.closest('li').attr('data-item-url'); // 当前DOM相应值
        var input_name = item.closest('li').attr('data-input-name'); // 当前DOM对应字段名
        var current_array = $('[name='+ input_name +']').val().split(','); // 相应字段值数组
        var current_index = current_array.indexOf(item_url); // 当前值在字段值中的序号（从0开始）

        // 修改字段值数组
        current_array.splice(current_index+2, 0, item_url); // 添加到新位置
        current_array.splice(current_index, 1); // 从原位置删除
        // 生成结果字段值并赋值到字段
        var current_value = $.unique( current_array ).join(',');
        $('[name='+ input_name +']').val(current_value);

        // 调整相应dom顺序
        var current_dom = item.closest('li');
        var dom_to_react = current_dom.closest('ul').find('li').eq(current_index+1);
        current_dom.insertAfter(dom_to_react);
    } // end right_single

});