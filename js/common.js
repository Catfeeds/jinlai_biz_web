/* 全局通用JavaScript */
/* 需要在此文件之外调用的方法，必须以变量声明的方式提升该方法的作用域为全局 */

$(function(){
    // AJAX参数
    var ajax_root = 'https://api.517ybang.com/'; // AJAX根URL
    $.ajaxSetup({
        dataType: "JSON",
        global: false
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