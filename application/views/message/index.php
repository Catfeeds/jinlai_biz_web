<link rel=stylesheet media=all href="/css/message.css">

<ul id="dialog">

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
    // 分页参数
    var limit = <?php echo $this->limit ?>;
    var offset = <?php echo $this->offset ?>;

    // 本地身份类型
    var local_role = '<?php echo $this->app_type ?>';
    // 聊天消息列表容器
    var viewer_dom_id = 'dialog';
    var viewer = document.getElementById(viewer_dom_id);
    // 间隔多久后重新显示时间
    var show_time_again = 60; // 秒

    // 用户信息
    var user = <?php echo json_encode($user) ?>;
    // 商家信息
    var biz = <?php echo json_encode($biz) ?>;

    // 用户头像DOM
    var avatar_user = '<div class="message-avatar"><figure><img src="'+ media_url+'user/'+user.avatar +'"></figure></div>';
    // 商家头像DOM
    var avatar_biz = '<div class="message-avatar"><figure><img src="'+ media_url+'biz/'+biz.url_logo +'"></figure></div>';

    // 获取页面初始下内边距
    var maincontainer_padding_bottom = $('#maincontainer').css('padding-bottom');

    // 建立EventStream连接
    var es_params =
        'app_type=' + local_role +
        '&biz_id=' + biz.biz_id +
        '&user_id=' + user.user_id;
    var es = new EventSource(api_url + 'es.php?' + es_params); // 测试用API
    //var es = new EventSource(api_url + 'messages?' + es_params);

    // 连接建立
    es.onopen = function(){
        console.log('onopen');
    }

    // 报错
    es.onerror = function(event) {
        console.log('onerror');
        console.log(event);
    }

    // 处理接收到的消息
    es.onmessage = function(event)
    {
        console.log(event.data) // 输出返回值
        console.log('origin:' + event.origin)
        console.log('last_event_id:' + event.lastEventId)

        // 将返回值存入本地存储
        localStorage.event_data = JSON.stringify(event.data);

        // 解析JSON格式的返回内容
        try
        {
            var data_in_json = JSON.parse(event.data);

            // 生成对话体DOM
            genereate_dom_chat(data_in_json)
        }
        catch(error)
        {
            console.log(error);
            return;
        }
    }

    // 生成对话体DOM
    function genereate_dom_chat(data)
    {
        // 判断信息来源
        var sender = (data.sender_type == 'client')? 'user': data.sender_type;

        // 判断信息来源是否为当前用户
        var is_self = (data.sender_type === local_role);

        // 拼合待显示的消息体DOM
        var message = '<li class=message-item>';

        // 根据上一条[同类(可选）]消息创建时间，决定是否显示时间信息
        var time_to_compare = (sender == 'user')? localStorage.latest_time_create_user: localStorage.latest_time_create_biz;
        //var time_to_compare = localStorage.latest_time_create;
        var time_from_latest = Date.parse(new Date())/1000 - time_to_compare;
        if (time_from_latest > show_time_again || time_to_compare == undefined)
        {
            message += '<div class=message-time><span>' + time_formater(data.time_create) + '</span></div>';
        }

        // 更新localstorage最近接受消息的创建时间
        localStorage.latest_time_create = data.time_create;
        if (sender == 'user')
        {
            localStorage.latest_time_create_user = data.time_create
        }
        else
        {
            localStorage.latest_time_create_biz = data.time_create
        }

        // 判断消息来源身份类型
        var type = data.type;
        message += '<div class="message-body '+ (is_self? 'self': 'other') +' body-'+ type +'">';

        // 生成消息头像
        message += (sender == 'user')? avatar_user: avatar_biz;

        // 准备消息内容
        var message_content = '';
        if (type === 'text')
        {
            message_content = '<p>ID' + data.message_id + ' ' + data.content + '</p>';
        }
        else if (type === 'image')
        {
            message_content = '<figure><img src="<?php echo MEDIA_URL.'message/' ?>' + data.content + '"></figure>';
        }
        else if (type === 'location')
        {
            message_content = '<a href="<?php echo base_url('location/detail?content=') ?>'+ data.content +'"><div>省市区<br>路楼户</div></a>';
        }
        else if (type === 'address')
        {
            message_content = '收货地址啥啥的';
        }

        // 生成消息内容DOM
        message += '    <div class="message-content type-' + type + '">';
        message += message_content;
        message += '    </div>'; // end div.message-content

        // 完成消息体DOM
        message += '</div>'; // end div.message-body
        message += '</li>'; // end li.message-item

        // 输出消息体DOM到容器
        viewer.innerHTML += message + "\n";

        // 将聊天窗口底部移入视界
        document.getElementById('page-bottom').scrollIntoView(true)
    } // end genereate_dom_chat

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
    } // end time_formater

    // 输入文本消息后发送到服务器
    var text_input = document.getElementById('text-input');
    text_input.onchange = function(){
        var content = text_input.value;
        //console.log(content);

        // 若无内容，则忽略
        if (content == ''){return false}

        // 发送消息
        var params = common_params
        params.creator_id = user_id // 发送者ID，即当前登陆用户ID
        params.receiver_type = 'client'
        params.user_id = user.user_id
        params.type = 'text' // 消息类型
        params.content = content // 消息内容
        //console.log(params);
        $.post(
            api_url + 'message/create',
            params,
            function(result)
            {
                console.log(result); // 输出回调数据到控制台
                if (result.status == 200)
                {
                    console.log(result.content);
                } else {
                    console.log(result.content.error.message);
                }
            }
        );

        // 清空并将焦点移入文本字段
        text_input.value = '';
        text_input.focus();

        return false;
    }

    // 点击切换消息选择器/文本输入框
    $('#action li').click(function(){
        selectors_toggle(true);
    });

    // 点击对话区的空白处时若选择器已展开，则收起选择器，且不将焦点移入文本字段
    $('#dialog').click(function(){
        if ($('#selectors_panel').css('display') === 'block')
        {
            $('#maincontainer').css('padding-bottom', maincontainer_padding_bottom);
            $('#selectors_panel, #selectors_panel *').hide();

            // 将聊天窗口底部移入视界
            document.getElementById('page-bottom').scrollIntoView(true)
        }
    });

    /**
     * 切换选择器显示与否
     *
     * @param focus_to_input boolean 是否需要将焦点移入文本字段
     */
    function selectors_toggle(focus_to_input)
    {
        $('#action li, #selectors_panel').toggle();

        if ($('#selectors_panel').css('display') === 'block'){
            $('#selectors_panel *').show();
            var action_height = $('#action').css('height');
            $('#maincontainer').css('padding-bottom', action_height);
            text_input.blur();
        }
        else
        {
            $('#maincontainer').css('padding-bottom', maincontainer_padding_bottom);
            $('#selectors_panel *').hide();

            focus_to_input = focus_to_input || false;
            if (focus_to_input)
            {
                text_input.focus();
            }
        }

        // 将聊天窗口底部移入视界
        document.getElementById('page-bottom').scrollIntoView(true)
    }
</script>

<!--
<button id="button">消息声音</button>

<script>
(function (){
    window.AudioContext = window.AudioContext || window.webkitAudioContext;
    if ( ! window.AudioContext){
        alert('当前浏览器不支持Web Audio API');
        return;
    }

// 按钮元素
var eleButton = document.getElementById('button');

// 创建新的音频上下文接口
var audioCtx = new AudioContext();

// 发出的声音频率数据，表现为音调的高低
var arrFrequency = [196.00, 220.00, 246.94, 261.63, 293.66, 329.63, 349.23, 392.00, 440.00, 493.88, 523.25, 587.33, 659.25, 698.46, 783.99, 880.00, 987.77, 1046.50];

// 音调依次递增或者递减处理需要的参数
var start = 0, direction = 1;

// 鼠标hover我们的按钮的时候
eleButton.addEventListener('mouseenter', function(){
    // 当前频率
    var frequency = arrFrequency[start];
    // 如果到头，改变音调的变化规则（增减切换）
    if ( ! frequency) {
    direction = -1 * direction;
    start = start + 2 * direction;
    frequency = arrFrequency[start];
    }
    // 改变索引，下一次hover时候使用
    start = start + direction;

    // 创建一个OscillatorNode, 它表示一个周期性波形（振荡），基本上来说创造了一个音调
    var oscillator = audioCtx.createOscillator();
    // 创建一个GainNode,它可以控制音频的总音量
    var gainNode = audioCtx.createGain();
    // 把音量，音调和终节点进行关联
    oscillator.connect(gainNode);
    // audioCtx.destination返回AudioDestinationNode对象，表示当前audio context中所有节点的最终节点，一般表示音频渲染设备
    gainNode.connect(audioCtx.destination);
    // 指定音调的类型，其他还有square|triangle|sawtooth
    oscillator.type = 'sine';
    // 设置当前播放声音的频率，也就是最终播放声音的调调
    oscillator.frequency.value = frequency;

    // 当前时间设置音量为0
    gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
    // 0.01秒后音量为1
    gainNode.gain.linearRampToValueAtTime(1, audioCtx.currentTime + 0.01);
    // 音调从当前时间开始播放
    oscillator.start(audioCtx.currentTime);
    // 1秒内声音慢慢降低，是个不错的停止声音的方法
    gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 1);
    // 1秒后完全停止声音
    oscillator.stop(audioCtx.currentTime + 1);
});
})();
</script>
-->