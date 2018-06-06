    <style>
        #dialog {color:#3f3f3f;font-size:28px;padding:32px 26px;}
        #dialog>li.message-item {width:100%;overflow:hidden;display:block;margin-top:30px;position:relative;}

        .message-time {position:absolute;left:50%;}
            .message-time>span {color:#fff;background-color:#cecece;height:50px;line-height:50px;border-radius:25px;padding:0 16px;display:block;position:relative;right:50%;}
        .message-avatar {background-color:#fff;width:90px;height:90px;border-radius:12px;overflow:hidden;display:flex;justify-content:center;align-items:center;}

        .self .message-avatar {margin-left:16px;float:right;}
        .other .message-avatar {margin-right:16px;float:left;}

        .message-time+.message-body {margin-top:86px;}

        .message-content {border:1px solid #eec240;border-radius:12px;overflow:hidden;}
        .type-text {padding:25px 30px;}

        .self .message-content {float:right;background-color:#ffd968;border-color:#eec240;}
        .other .message-content {float:left;background-color:#fff;border-color:#e9e9e9;}

        .type-text {}
        .type-text a {color:#4cb5ff;}
        .type-image {}
        .type-image img {max-width:280px;max-height:280px;}

        #page-bottom {text-align:center;}
        #page-bottom hr {border-color:transparent;}

        #action {background-color:#fff;position:fixed;left:0;right:0;bottom:0;z-index:100;padding-top:15px;overflow:hidden;}
            #tools {display:table;width:100%;height:100%;margin-bottom:15px;}
                #tools>* {display:table-cell;vertical-align:middle;overflow:hidden;}
                form {padding-left:26px;}
                    input[name=content] {background-color:#f4f4f4;width:100%;line-height:80px;border:1px solid #e0dfdf;border-radius:14px;padding:0 20px;}
                #tools ul {width:118px;overflow:hidden;}
                    #tools li {float:left;color:#a9a9a9;font-size:66px;width:66px;height:66px;line-height:66px;padding:0 26px;display:inline-block;text-align:center;cursor:pointer;} /* 将与页面右侧、输入框间的空间交给菜单按钮的padding实现，以增大可点击区域 */
                    li#hide_selectors {display:none;}
            #selectors_panel {color:#848484;font-size:26px;text-align:center;border-top:1px solid #e0dfdf;padding:18px 4px 12px;display:none;}
                #selectors_panel li {width:25%;margin-bottom:42px;}
                    .selector-figure {color:#b3b3b3;font-size:58px;width:108px;height:108px;line-height:108px;margin-bottom:12px;border:2px solid #e1e1e1;display:inline-block;}
    </style>

<ul id=dialog>
    <!--<li>-->
    <!--<div class="message-body self">-->
    <!--<figure class="message-avatar"><img alt="用户头像" src="https://jinlaisandbox-images.b0.upaiyun.com/user/avatar/201801/0129/1407221.jpg"></figure>-->
    <!--<div class="message-content type-image">-->
    <!--<figure>-->
    <!--<img src="https://jinlaisandbox-images.b0.upaiyun.com/user/avatar/201801/0129/1407221.jpg">-->
    <!--</figure>-->
    <!--</div>-->
    <!--</div>-->
    <!--</li>-->
</ul>

<div id=page-bottom>
    <hr>
    <!--<p>我虽然只是个页面，但也是有底线的</p>-->
</div>

<div id=action>
    <div id=tools>
        <form>
            <input id=text-input name=content type=text placeholder="输入框暂不可用" autofocus required>
        </form>
        <!--<i class="action far fa-image"></i>-->
        <ul>
            <li id=show_selectors><i class="fal fa-plus-circle"></i></li>
            <li id=hide_selectors><i class="fal fa-times-circle"></i></li>
        </ul>
    </div>

    <div id=selectors_panel>
        <ul class="horizontal">
            <li>
                <div class=selector-figure>
                    <i></i>
                </div>
                <p>图片</p>
            </li>
            <li>
                <div class=selector-figure>
                    <i></i>
                </div>
                <p>商品</p>
            </li>
            <li>
                <div class=selector-figure>
                    <i></i>
                </div>
                <p>服务</p>
            </li>
            <li>
                <div class=selector-figure>
                    <i></i>
                </div>
                <p>门店</p>
            </li>
            <li>
                <div class=selector-figure>
                    <i></i>
                </div>
                <p>优惠券</p>
            </li>
            <li>
                <div class=selector-figure>
                    <i></i>
                </div>
                <p>优惠券包</p>
            </li>
            <li>
                <div class=selector-figure>
                    <i></i>
                </div>
                <p>店内活动</p>
            </li>
            <li>
                <div class=selector-figure>
                    <i></i>
                </div>
                <p>店内文章</p>
            </li>
        </ul>
    </div>
</div>

<script>
    // 本地身份类型
    var local_role = '<?php echo $this->app_type ?>';
    // 聊天消息列表容器
    var viewer_dom_id = 'dialog';
    var viewer = document.getElementById(viewer_dom_id);
    // 间隔多久后重新显示时间
    var show_time_again = 60; // 秒

    // API地址根路径
    var url_api = '<?php echo API_URL ?>';
    // 图片地址根路径
    var url_image_root = '<?php echo MEDIA_URL ?>';

    // 用户信息
    var user = <?php echo json_encode($user) ?>;
    // 商家信息
    var biz = <?php echo json_encode($biz) ?>;

    var avatar_user = '<figure class="message-avatar"><img alt="用户头像" src="' + url_image_root + 'user/' + user.avatar + '"></figure>'; // 用户头像DOM
    var avatar_biz = '<figure class="message-avatar"><img alt="商家头像" src="' + url_image_root + 'biz/' + biz.url_logo + '"></figure>'; // 商家头像DOM

    // 建立EventStream连接
    var es = new EventSource(url_api + 'es.php?user_id=' + user.user_id + '&biz_id=' + biz.biz_id);

    // 连接建立
    // es.onopen = function(){
    //     console.log('onopen');
    // }

    // 报错
    es.onerror = function(event) {
        console.log('onerror');
        //console.log(event);
    }

    // 处理接收到的消息
    es.onmessage = function(event){
        // 将返回值存入本地存储
        localStorage.event_data = JSON.stringify(event.data);

        // 解析JSON格式的返回内容
        try {
            var event_data = JSON.parse(event.data);
        } catch(error){
            console.log(error);
            return;
        }

        // 判断信息来源
        //alert(event_data.sender_type);
        var sender = (event_data.sender_type == 'client')? 'user': event_data.sender_type;

        // 判断信息来源是否为当前用户
        var is_self = (event_data.sender_type === local_role);

        // 拼合待显示的消息体DOM
        var message = '<li class=message-item>';

        // 根据上一条[同类(可选）]消息创建时间，决定是否显示时间信息
        var time_to_compare = (sender == 'user')? localStorage.latest_time_create_user: localStorage.latest_time_create_biz;
        //var time_to_compare = localStorage.latest_time_create;
        var time_from_latest = Date.parse(new Date())/1000 - time_to_compare;
        if (time_from_latest > show_time_again || time_to_compare == undefined)
        {
            message += '<div class=message-time><span>' + time_formater(event_data.time_create) + '</span></div>';
        }

        // 更新localstorage最近接受消息的创建时间
        localStorage.latest_time_create = event_data.time_create;
        if (sender == 'user')
        {
            localStorage.latest_time_create_user = event_data.time_create;
        }
        else
        {
            localStorage.latest_time_create_biz = event_data.time_create;
        }

        // 判断消息来源身份类型
        var from = (is_self)? 'self': 'other';
        message += '<div class="message-body ' + from + '">';

        // 生成消息头像
        message += (sender == 'user')? avatar_user: avatar_biz;

        // 生成消息体容器
        message += '    <div class="message-content type-' + event_data.type + '">';

        // 生成消息内容
        if (event_data.type === 'text')
        {
            message += '<p>ID' + event_data.message_id + ' ' + event_data.content + '</p>';
        } else if (event_data.type == 'image') {
            message += '<figure><img src="' + event_data.url_image + '"></figure>';
        }

        message += '    </div>'; // end div.message-content
        message += '</div>'; // end div.message-body
        message += '</li>'; // end li.message-item

        // 输出消息体DOM到容器
        viewer.innerHTML += message + "\n";

        // 将聊天窗口底部移入视界
        document.getElementById('page-bottom').scrollIntoView(true)
    }

    // 将时间戳格式化为可读日期
    function time_formater(timestamp)
    {
        var time = new Date(timestamp * 1000); // 秒级时间戳转毫秒级时间戳

        // 生成日期字符串
        var result = '';
        //result += time.getFullYear() + '年';
        result += (time.getMonth()+1) + '月' + time.getDate() + '日';
        result += ' ' + ((time.getHours().toString().length == 1)? '0'+time.getHours(): time.getHours());
        result += ':' + ((time.getMinutes().toString().length == 1)? '0'+time.getMinutes(): time.getMinutes());
        //result += ':' + ((time.getSeconds().toString().length == 1)? '0'+time.getSeconds(): time.getSeconds());

        return result;
    }

    // TODO 输入文本消息后发送到服务器
    text_input = document.getElementById('text-input');
    text_input.onchange = function(){
        console.log(text_input.value);
        text_input.value = '';
        text_input.focus();
    }

    // 点击切换消息选择器/文本输入框
    $('#action li').click(function(){
        selectors_toggle();
    });

    // 收起/显示选择器面板，并调整页面下内边距
    var maincontainer_padding_bottom = $('#maincontainer').css('padding-bottom');
    function selectors_toggle()
    {
        $('#action li, #selectors_panel').toggle();
        if ($('#selectors_panel').css('display') === 'block'){
            $('#selectors_panel *').show();
            var action_height = $('#action').css('height');
            $('#maincontainer').css('padding-bottom', action_height);

        } else {
            $('#maincontainer').css('padding-bottom', maincontainer_padding_bottom);
            $('#selectors_panel *').hide();
        }

        // 将聊天窗口底部移入视界
        document.getElementById('page-bottom').scrollIntoView(true)
    }
</script>