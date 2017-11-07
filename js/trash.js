/* 回收站页通用JavaScript */

$(function(){
    // 全选
    $('#bulk_selector').click(function(){
        if ($(this).attr('data-bulk-selector') == 'off')
        {
            $(this).attr('data-bulk-selector', 'on');
            $("form :checkbox").prop("checked", true);
            //console.log('已全选');
            //get_checked();
        }
        else
        {
            $(this).attr('data-bulk-selector', 'off');
            $("form :checkbox").prop("checked", false);
            //console.log('已全不选');
            //get_checked();
        }
    });
    // 测试全选功能
    function get_checked()
    {
        var ids_selected = new Array;
        $('form :checkbox:checked').each(function(i){
            ids_selected[i] = $(this).val();
        });
        console.log(ids_selected);
        console.log(ids_selected.join(','));
    }
});