/* 全局通用JavaScript */
/* 需要在此文件之外调用的方法，必须以变量声明的方式提升该方法的作用域为全局 */

$(function(){
    // AJAX参数
    var ajax_root = 'https://api.517ybang.com/'; // AJAX根URL
    $.ajaxSetup({
        dataType: "JSON",
        global: false
    });

    // 多级选择器
    $('.multi-selector').on('change', '[data-ms-level]', function(){
        // 获取必要参数
        var current_id = $(this).find('option:selected').val(); // 当前已选中值，将作为待操作选项的parent_id
        var current_level = $(this).attr('data-ms-level'); // 操作中选项等级
        var next_level = Number(current_level) + 1; // 待操作选项等级

        var selector = $(this).closest('select'); // 操作中选择器
        var ms_selector = $(this).closest('.multi-selector'); // 当前多级选择器
        var next_selector = ms_selector.find('[data-ms-level='+ next_level +']'); // 待操作选择器
        var ms_api_url = ms_selector.attr('data-ms-api_url'); // 当前多级选择器数据源
        var ms_name = ms_selector.attr('data-ms-name'); // 当前多级选择器对应字段name属性

        var ms_min_level = ms_selector.attr('data-ms-min_level'); // 可提交表单的最低层级
        var ms_max_level = ms_selector.attr('data-ms-max_level'); // 最高层级

        // 若选择了空选项，则清空/删除所有下级选择器
        if (current_id === '' && current_level < ms_min_level)
        {
            alert('请选择');
            next_selector.closest('div').remove();
        }
        else if (current_level == ms_max_level || current_level >= ms_min_level) // 若为最大级别选择器，赋值到相应字段
        {
            $('[name='+ ms_name +']').val(current_id);
        }
        else
        {
            // 初始化参数
            params = common_params;
            params.level = next_level;
            params.parent_id = current_id;

            // AJAX获取结果并生成相关HTML
            $.post(
                ajax_root + ms_api_url, // 拼合完整API路径
                params,
                function(result)
                {
                    //console.log(result); // 输出回调数据到控制台

                    if (result.status == 200)
                    {
                        var content = result.content

                        // 生成数据
                        var html_options = '<div class=col-xs-4>' +
                            '   <select class=form-control data-ms-level='+ next_level +'>' +
                            '<option value="">可选择</option>';
                        $.each(
                            content,
                            function(i, item){
                                html_options += '<option value=' + item.category_id + '>' + item.name + '</option>'
                            }
                        );

                        html_options += '   </select>' +
                            '</div>';

                        // 清除现有下级选择器（若有）并生成数据
                        next_selector.closest('div').remove();
                        ms_selector.append(html_options);
                    }
                    else
                    {
                        // 若失败，进行提示
                        alert(result.content.error.message);
                    }
                },
                "JSON"
            );
        }

        return false;
    });

    /**
     * 格式化：以分隔符格式化文本
     *
     * @param string class_name 待格式化文本所在dom的class属性值
     * @param array seperate_after 需添加分隔符的位置；例如大陆身份证号码可使用[6,14]
     * @param string seperator 需添加的分隔；默认一个空格' '
     */
    seperate_string = function seperate_string(class_name, seperate_after, seperator)
    {
        // 默认以一个空格做分隔
        var seperator = seperator || ' ';

        $('.'+class_name).each(function(){
            var target_text = this.innerText;

            // 添加分隔符
            var seperator_behind = seperate_after.sort(sort_number_desc); // 确保处理顺序为从后向前，否则被添加的分隔符将产生干扰
            function sort_number_desc (a, b)
            {
                return b - a;
            }
            seperator_behind.forEach(seperate_this);
            function seperate_this(index)
            {
                var text_front = target_text.substr(0, index);
                var text_behind = target_text.substr(index);
                target_text = text_front + seperator + text_behind;
                //console.log(target_text);
            }

            this.innerText = target_text;
        });
    }
    seperate_string();

});