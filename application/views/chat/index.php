<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title><?php echo $this->session->brief_name ?>  消息中心</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <script src="https://cdn-remote.517ybang.com/js/rem.js"></script>
    <link rel="stylesheet" href="https://cdn-remote.517ybang.com/css/fontStyle.css"/>
    <link rel="stylesheet" href="https://cdn-remote.517ybang.com/css/normal.css"/>
    <link rel="stylesheet" href="https://cdn-remote.517ybang.com/css/chat/cartNewsCenter.css"/>

    <script src="https://cdn-remote.517ybang.com/js/jquery-3.2.1.min.js"></script>
    <script src="https://cdn-remote.517ybang.com/js/chatjs/moment.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn-remote.517ybang.com/css/chat/chat.css" />
    <link href="https://cdn-remote.517ybang.com/css/common.css" rel="stylesheet">
    <link href="https://cdn-remote.517ybang.com/css/base.css" rel="stylesheet">
    <script src="https://cdn-remote.517ybang.com/js/jquery-3.2.1.min.js"></script>
    <script src="https://cdn-remote.517ybang.com/js/chatjs/flexible.js"></script>
    <script src="https://cdn-remote.517ybang.com/js/chatjs/chatjs.js"></script>
    <script src="https://cdn-remote.517ybang.com/js/hash.js"></script>

    <style>
            body{
                 max-width: 720px;
                 height:100%;
                 margin: 0 auto;
                 background: #f1f1f1;
                 color:#333;
                 font-size: 0.26rem;
             }
             #chat .message{height:78%;padding-top:0;margin-top:1.1rem}
            .icon-服务{
                color: #b3b3b3;
            }

            .icon-xl-tankuang:before {
                content: "\e9cd";
                color: #a9a9a9;
                font-size: .67rem;
                margin-left: .25rem;
                /* margin-top: 2.1rem; */
                position: relative;
                top: .3rem;
            }
            .icon-close2:before {
                content: "\e91d";
                color: #a9a9a9;
                font-size: .67rem;
                margin-left: .25rem;
                /* margin-top: 2.1rem; */
                position: relative;
                top: .3rem;
            }

            .icon-shoujihao:before {
                content: "\e9d2";
                color: #595959;
                font-size: .4rem;
                position: relative;
                top: -.05rem;
            }
            .icon-搜索:before {
                content: "\e9ac";
                position: relative;
                top: .05rem;
            }
            #chat{
                display: none;
                position: fixed;
                height: 100%;
                width: 100%;
            }
        </style>
</head>
<script type="text/javascript">
            // 当前用户信息
            var user_id = '<?php echo $this->session->user_id ?>';
            var biz_id = '<?php echo $this->session->biz_id ?>';
            var url_logo = '<?php echo $this->session->url_logo ?>';
            var brief_name = '<?php echo $this->session->brief_name ?>';



            // 全局参数
            var api_url = '<?php echo API_URL ?>'; // API根URL
            var base_url = '<?php echo BASE_URL ?>'; // 页面根URL
            var media_url = '<?php echo MEDIA_URL ?>'; // 媒体文件根URL
            var class_name = '<?php echo $this->class_name ?>';
            var class_name_cn = '<?php echo $this->class_name_cn ?>';

            // AJAX参数
            var ajax_root = '<?php echo API_URL ?>'
            var common_params = new Object()
            common_params.app_type = 'admin' // 默认为管理端请求
            common_params.user_id = user_id

            // UserAgent
            var user_agent = <?php echo json_encode($this->user_agent) ?>;

             var regUrl = RegExp(/http/);
             console.log(url_logo); // true
             if(regUrl.test(url_logo) !== true){
                  url_logo = media_url +'biz/' + url_logo;
             }else{

             }
             //console.log(media_url);


</script>
<body>

<div class="content">
    <header class="header">
        <a class="chatback" href="javascript:history.back()">
            <i class="icon-Back"></i>

        </a>


        <h5 class="tit">消息中心</h5>

    </header>
    <!--<a href="notification_message.html" class="notice" style="padding: 0.18rem 0 0.14rem 0">
        <div class="notice-image">
            <img class="notice-header-img" src="https://cdn-remote.517ybang.com/media/chatimages/images/tongzhi@3x.png" alt=""/>
            <div class="newNews"></div>
        </div>
        <div class="notice-text" style="border-bottom: none">
            <p class="notice-title">通知消息</p>
            <p class="notice-remind">现金券即将到期提醒</p>
        </div>
        <div class="notice-time" style="border-bottom: none">12:30</div>
    </a>-->
    <div class="friends">

    </div>
</div>
<div id="chat" style="max-width:7.5rem;">
    <header class="header" style="max-width:7.5rem;">
        <a class="chatback" id="closeChat">
            <i class="icon-Back"></i>
        </a>
        <h5 class="tit" id="userName"> </h5>
        <div class="right">
            <i class="icon-person-icon">
            </i>
        </div>
    </header>
    <div id="more" style="width:100%;display:none;text-align:center;margin-top:1rem;padding:0.2rem 0;">点击加载更多聊天记录</div>
    <div class="message" id="message">


    </div>
    <div class="footer" style="max-width:7.5rem;">
    	<!--<input type="text" />-->
    	<div contenteditable="true" class="chatInput" style="-webkit-user-select: auto"></div>
    	<i class="icon-xl-tankuang chatbtn">
           </i>
    	<!--<p>发送</p>-->
    </div>
    <!--底部附加面板-->
    <div class="additionalpanels">
    	<ul>
    		<li class="khdtp">
    		    <input  id='fileupload' type='file' multiple="multiple" name='file' onchange="uploadImg(this)" style="display: block;height: 1rem;width: 1rem;position: absolute;overflow: hidden;opacity: 0;"/>
    			<span>
    				<i class="icon-tp"></i>
    			</span>
    			<em>图片</em>
    		</li>
    		<!--<li>
    			<span>
    				<i class="icon-liulan"></i>
    			</span>
    			<em>浏览历史</em>
    		</li>
    		<li>
    			<span>
    				<i class="icon-sc"></i>
    			</span>
    			<em>收藏宝贝</em>
    		</li>
    		<li style="margin-right: 0px;" class="shdz">
    			<span>
    				<i class="icon-sh-dizhi"></i>
    			</span>
    			<em>收货地址</em>
    		</li>
    		<li class="khdsjdd">
    			<span>
    				<i class="icon-sp-dingdan"></i>
    			</span>
    			<em>商品订单</em>
    		</li>
    		<li>
    			<span>
    				<i class="icon-fw-dingdan"></i>
    			</span>
    			<em>服务订单</em>
    		</li>
    		<li class="dqdz">
    			<span>
    				<i class="icon-dq-weizhi"></i>
    			</span>
    			<em>当前位置</em>
    		</li>-->
    	</ul>
    </div>
</div>
<script>
    var ws = '';
    var objUserId = '';
    var mediaUrl=media_url;
    var moreId = '';
    $(function(){
     //获取token
                        var token = '';
                        var params = {};
                        params.app_type = 'biz';
                        params.biz_id = biz_id;//要获取token的id商家端传商家客户端传userid？
                        $.post({
                            async:false,
                            url:  api_url + 'wsmessage/getverify',
                            data: params,
                            success: function(result){
                            		//console.log(result); // 输出回调数据到控制台
                                 if (result.status == 200)
                                 {
                                    token = result.content.token;

                                 } else {
                                    alert(result.content.error.message);
                                 }
                            },
                            error:function(result){
                            	console.log(result);
                            },
                            dataType: 'json'
                        });
                        ws = new WebSocket('wss:biz.517ybang.com/jinlai_chat?token='+token);
                        ws.onopen = function () {


                                             //alert("数据发送中...");
                        };

        $('.message').scroll(function() {
            if ($('.message').scrollTop()<=0){
                //alert("滚动条已经到达顶部");
                $('#more').show();
            }else{
                $('#more').hide();
            }

        });
        $('#more').on('click',function(){
            $.post({
                 url:  api_url + 'wsmessage/index',
                 data: {app_type:'biz',user_id:objUserId,biz_id:biz_id,message_id:moreId},
                 async:false,
                 success: function(result){
                 		console.log(result); // 输出回调数据到控制台
                      if (result.status == 200 && result.content[0].list.length > 0)
                      {

                         imgUrl = result.content[0].avatar;
                         var reg = RegExp(/http/);
                         //console.log(reg.test(imgUrl)); // true
                         if(reg.test(imgUrl) !== true){
                              imgUrl = media_url+'user/' + imgUrl;
                         }else{
                              imgUrl = result.content[0].avatar;
                         }

                         var arr = [];
                         arr = result.content[0].list;

                         moreId = arr[0].message_id;


                         console.log(moreId);
                         arr = arr.reverse();
                         console.log(arr);

                         //console.log(arr);
                         for(var i=0; i<arr.length; i++){
                             var time1 = '';
                             var time2 = '';
                            if(i < arr.length-1){

                                 time1 = arr[i].time_create*1000;
                                 time2 = arr[i+1].time_create*1000;
                            }else{
                                 time1 = arr[i].time_create*1000;
                                 time2 = arr[i].time_create*1000;
                            }


                            if(arr[i].chat == "receive"){
                                //我发送的
                                if(arr[i].type == 'text'){

                                     if((time1 - (5*60*1000)) > time2){
                                         var strTime =arr[i].time_create*1000;
                                         strTime = timeFormat(strTime);
                                         strTime = strTime.substring(5,16);
                                         var timeHtml = "<div class='time'>"+strTime+"</div>";
                                         //console.log(timeHtml);
                                         showMore(url_logo,arr[i].content,timeHtml);
                                     }else{
                                         showMore(url_logo,arr[i].content,'');
                                     }
                                }else if(arr[i].type == 'image'){
                                     if(reg.test(arr[i].content) !== true){
                                         //console.log('no');
                                         sendkhdPicMore(url_logo,'<img src="'+mediaUrl+arr[i].content+'">');
                                     }else{
                                         //console.log('yes');
                                         sendkhdPicMore(url_logo,'<img src="'+arr[i].content+'">');
                                     }

                                }

                            }else if(arr[i].chat == "send"){
                                 //我接收到的
                                if(arr[i].type == 'text'){
                                     sendMore(imgUrl,arr[i].content);
                                }else if(arr[i].type == 'image'){
                                    if(reg.test(arr[i].content) !== true){
                                        sendPicMore(imgUrl,'<img src="'+ mediaUrl+arr[i].content+'">');
                                    }else{
                                        sendPicMore(imgUrl,'<img src="'+arr[i].content+'">');
                                    }

                                }else if(arr[i].type == "item"){

                                    var thisContent = JSON.parse(arr[i].content);
                                     var thisURL = thisContent.url_image_main;
                                     var reg = RegExp(/http/);
                                     if(reg.test(thisURL) !== true){
                                          thisURL = media_url + 'item/' + thisContent.url_image_main;
                                     }else{
                                          thisURL = thisContent.url_image_main;
                                     }
                                    sendSp(imgUrl,'<img src="'+thisURL+'" style="width:1.2rem;height:1.2rem;display:block;float:left"><span style="display:block;width:2.2rem;float:left;font-size:.24rem;color:rgb(62,58,57);height:.8rem;overflow:hidden;margin-left:.1rem;">'+ thisContent.name+'</span><a style="display:block;width:2.2rem;margin-left:.1rem;margin-top:.1rem;float:left;"><i class="fl" style="font-size:.28rem;color:rgb(255,54,73)">¥'+ thisContent.price+'</i></a>');


                                }
                            }


                         }


                      } else {
                         $('#more').text('没有更多记录了');
                      }
                 },
                 error:function(result){
                 	console.log(result);
                 },
                 dataType: 'json'
            });
        });


        $('.content').on('click','.friends .notice',function(){
            $('#chat').show();//聊天窗口与消息通知切换
            $('.content').hide();
                    //获取当前点击聊天好友的userID和最后一条聊天内容id获取聊天记录

                    objUserId = $(this).attr('data-id');
                    var currentUserName = $(this).attr('data-userName');
                    $('#userName').text(currentUserName);
                    let str = JSON.stringify({"s_user_id": objUserId,"type":"read"});
                    ws.send(str);
                    var imgUrl = '';
                    console.log(objUserId);
                    var objList = {};
                    objList.app_type = 'biz';
                    objList.user_id = objUserId;
                    objList.biz_id = biz_id;
                    $.post({
                        url:  api_url + 'wsmessage/index',
                        data: objList,
                        async:false,
                        success: function(result){
                        		console.log(result); // 输出回调数据到控制台
                             if (result.status == 200)
                             {

                                imgUrl = result.content[0].avatar;
                                var reg = RegExp(/http/);
                                //console.log(reg.test(imgUrl)); // true
                                if(reg.test(imgUrl) !== true){
                                     imgUrl = media_url+'user/' + imgUrl;
                                }else{
                                     imgUrl = result.content[0].avatar;
                                }

                                var arr = [];
                                arr = result.content[0].list;
                                moreId = arr[0].message_id;
                                //console.log(arr);
                                for(var i=0; i<arr.length; i++){
                                    var time1 = '';
                                    var time2 = '';
                                   if(i < arr.length-1){

                                        time1 = arr[i].time_create*1000;
                                        time2 = arr[i+1].time_create*1000;
                                   }else{
                                        time1 = arr[i].time_create*1000;
                                        time2 = arr[i].time_create*1000;
                                   }


                                   if(arr[i].chat == "send"){
                                       //我发送的
                                       if(arr[i].type == 'text'){

                                            if((time2 - (5*60*1000)) > time1){
                                                var strTime =arr[i].time_create*1000;
                                                strTime = timeFormat(strTime);
                                                strTime = strTime.substring(5,16);
                                                var timeHtml = "<div class='time'>"+strTime+"</div>";
                                                //console.log(timeHtml);
                                                show(url_logo,arr[i].content,timeHtml);
                                            }else{
                                                show(url_logo,arr[i].content,'');
                                            }
                                       }else if(arr[i].type == 'image'){
                                            if(reg.test(arr[i].content) !== true){
                                                //console.log('no');
                                                sendkhdPic(url_logo,'<img src="'+mediaUrl+arr[i].content+'">');
                                            }else{
                                                //console.log('yes');
                                                sendkhdPic(url_logo,'<img src="'+arr[i].content+'">');
                                            }

                                       }

                                   }else if(arr[i].chat == "receive"){
                                        //我接收到的
                                       if(arr[i].type == 'text'){
                                            send(imgUrl,arr[i].content);
                                       }else if(arr[i].type == 'image'){
                                           if(reg.test(arr[i].content) !== true){
                                               sendPic(imgUrl,'<img src="'+ mediaUrl+arr[i].content+'">');
                                           }else{
                                               sendPic(imgUrl,'<img src="'+arr[i].content+'">');
                                           }

                                       }else if(arr[i].type == "item"){

                                          console.log(arr[i].content);
                                          var thisContent = JSON.parse(arr[i].content);

                                          var thisURL = thisContent.url_image_main;
                                                                          var reg = RegExp(/http/);
                                                                          if(reg.test(thisURL) !== true){
                                                                               thisURL = media_url + 'item/' + thisContent.url_image_main;
                                                                          }else{
                                                                               thisURL = thisContent.url_image_main;
                                                                          }
                                          sendSp(imgUrl,'<img src="'+thisURL+'" style="width:1.2rem;height:1.2rem;display:block;float:left"><span style="display:block;width:2.2rem;float:left;font-size:.24rem;color:rgb(62,58,57);height:.8rem;overflow:hidden;margin-left:.1rem;">'+ thisContent.name+'</span><a style="display:block;width:2.2rem;margin-left:.1rem;margin-top:.1rem;float:left;"><i class="fl" style="font-size:.28rem;color:rgb(255,54,73)">¥'+ thisContent.price+'</i></a>');


                                      }
                                   }


                                }
                                var div = document.getElementById('message');
                                div.scrollTop = div.scrollHeight;

                             } else {
                                alert(result.content.error.message);
                             }
                        },
                        error:function(result){
                        	console.log(result);
                        },
                        dataType: 'json'
                    });

                    //下拉加载更多








                         console.log('连接成功');
                        document.onkeyup = function (e) {
                                         	var code = e.charCode || e.keyCode;
                                         	if (code == 13) {
                                         		//debugger;
                                         		var content = $('#chat .footer .chatInput').text();
                                         		var oMessage = document.getElementById('message').scrollHeight + 500;
                                          		$(".message").animate({scrollTop:oMessage}, 50);
                                         		show(url_logo,$('#chat .footer .chatInput').text(),'');
                                         		$('#chat .footer .chatInput').text('');
                                         		var timestamp = (new Date()).getTime();
                                         		//console.log(content);
                                         		let str = JSON.stringify({"user_id": objUserId,"type":"text","content":content, "time_create":timestamp})
                                         		console.log(str);
                                         		ws.send(str);
                                         	}
                        }




                    ws.onmessage = function (evt) {
                       //var received_msg = evt.data;
                       //{"status":200,"result":"success","content":{"time_create":"1588876977","message_id":69,"user_id":19},"msg":"成功收到消息","no":1}
                       console.log(evt.data);
                       var data = JSON.parse(evt.data);

                       console.log(objUserId);
                       if(data.msg == '新的消息'){
                            var thisId = data.content[0].user_id;
                            if( thisId == objUserId){
                            var img = data.content[0].avatar;
                            var reg = RegExp(/http/);
                            //console.log(reg.test(imgUrl)); // true
                            if(reg.test(img) !== true){
                                 img = media_url+'user/' + img;
                            }else{
                                 img = data.content[0].avatar;
                            }
                            var currentType = data.content[0].list.type;
                            var currentContent = data.content[0].list.content;
                            if(currentType == 'text'){
                                send(img,currentContent);
                            }else if(currentType == 'image'){

                                if(reg.test(currentContent) !== true){
                                    sendPic(img,'<img src="'+mediaUrl+currentContent+'">');
                                }else{
                                    sendPic(img,'<img src="'+currentContent+'">');
                                }
                            }else if(currentType == 'item'){
                                //var thisContent = JSON.parse(currentContent);
                                var thisURL = thisContent.url_image_main;
                                var reg = RegExp(/http/);
                                if(reg.test(thisURL) !== true){
                                     thisURL = media_url + 'item/' + thisContent.url_image_main;
                                }else{
                                     thisURL = thisContent.url_image_main;
                                }
                                sendSp(img,'<img src="'+thisURL+'" style="width:1.2rem;height:1.2rem;display:block;float:left"><span style="display:block;width:2.2rem;float:left;font-size:.24rem;color:rgb(62,58,57);height:.8rem;overflow:hidden;margin-left:.1rem;">'+ thisContent.name+'</span><a style="display:block;width:2.2rem;margin-left:.1rem;margin-top:.1rem;float:left;"><i class="fl" style="font-size:.28rem;color:rgb(255,54,73)">¥'+ thisContent.price+'</i></a>');


                            }
                            }

                            let str = JSON.stringify({"s_user_id": objUserId,"type":"read"});
                            ws.send(str);

                       }
                    };
                    ws.onerror = function (e) {
                        console.log(e)
                    }


        });

        $('#closeChat').on('click',function(){
            $('#chat').hide();
            $('.content').show();
            $('#message').html('');
            /* ws.onclose = function(){
                console.log('close')
             }
             */
             location.reload();
        });

        //有没有未读消息
        var params = {};
        params.app_type = 'biz';
        params.biz_id = biz_id;
        $.post({
            url:  api_url + 'wsmessage/sync',
            data: params,
            success: function(result){
            		console.log(result); // 输出回调数据到控制台
                 if (result.status == 200)
                 {
                 var item = result.content;
                 for(var key in item){
                    var list = item[key].list;
                    var newText = list[0].content;
                    var thisType = list[0].type;
                    //判断content类型
                    if(thisType == 'image'){
                        newText = '[图片]';
                    }else if(thisType == 'item'){
                        newText = '[item]';
                    }
                    var timeCreate = list[0].time_create;
                    timeCreate = timeFormat(parseInt(timeCreate*1000));
                    var imgUrl = item[key].avatar;
                    var reg = RegExp(/http/);
                    //console.log(reg.test(imgUrl)); // true
                    if(reg.test(imgUrl) !== true){
                         imgUrl = media_url+'user/' + item[key].avatar;
                    }else{
                         imgUrl = item[key].avatar;
                    }
                    var friendsHtml = '<div class="notice" data-userName="'+item[key].nickname+'" data-id="'+ item[key].user_id +'">'+
                                                  '<div class="notice-image">'+
                                                      '<img class="notice-header-img" src="'+imgUrl+'" alt=""/>'+
                                                      '<div class="newNews"></div>'+
                                                  '</div>'+
                                                  '<div class="notice-text">'+
                                                      '<p class="notice-title">'+ item[key].nickname +'</p>'+
                                                      '<p class="notice-remind">'+newText+'</p>'+
                                                  '</div>'+
                                                  '<div class="notice-time">'+timeCreate+'</div>'+
                                              '</div>';
                    $('.friends').append(friendsHtml);
                 }

                 } else {
                    //alert(result.content.error.message);
                 }
            },
            error:function(result){
            	console.log(result);
            },
            dataType: 'json'
        });


        function add0(m){return m<10?'0'+m:m }
        //时间戳转化成时间格式
        function timeFormat(timestamp){
          //timestamp是整数，否则要parseInt转换,不会出现少个0的情况
            var time = new Date(timestamp);
            var year = time.getFullYear();
            var month = time.getMonth()+1;
            var date = time.getDate();
            var hours = time.getHours();
            var minutes = time.getMinutes();
            var seconds = time.getSeconds();
            return year+'-'+add0(month)+'-'+add0(date)+' '+add0(hours)+':'+add0(minutes)+':'+add0(seconds);
        }
    });
var biz = user_id;//传入
</script>
</body>
</html>