<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>消息中心</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <script src="https://cdn-remote.517ybang.com/js/rem.js"></script>
    <link rel="stylesheet" href="https://cdn-remote.517ybang.com/css/fontStyle.css"/>
    <link rel="stylesheet" href="https://cdn-remote.517ybang.com/css/normal.css"/>
    <link rel="stylesheet" href="https://cdn-remote.517ybang.com/css/chat/cartNewsCenter.css"/>
</head>
<script type="text/javascript">
            // 当前用户信息
            var user_id = '<?php echo $this->session->user_id ?>';

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
</script>
<body>

<div class="content">
    <header class="header">
        <a class="chatback" href="javascript:history.back()">
            <i class="icon-Back"></i>

        </a>
        

        <h5 class="tit">消息中心</h5>

    </header>
    <a href="notification_message.html" class="notice" style="padding: 0.18rem 0 0.14rem 0">
        <div class="notice-image">
            <img class="notice-header-img" src="../media/chatimages/images/tongzhi@3x.png" alt=""/>
            <div class="newNews"></div>
        </div>
        <div class="notice-text" style="border-bottom: none">
            <p class="notice-title">通知消息</p>
            <p class="notice-remind">现金券即将到期提醒</p>
        </div>
        <div class="notice-time" style="border-bottom: none">12:30</div>
    </a>
    <div class="friends">
        <div class="notice">
            <div class="notice-image">
                <img class="notice-header-img" src="../media/chatimages/images/tongzhi@3x.png" alt=""/>
                <div class="newNews"></div>
            </div>
            <div class="notice-text">
                <p class="notice-title">水果超市</p>
                <p class="notice-remind">今天就发货</p>
            </div>
            <div class="notice-time">12:30</div>
        </div>
        <div class="notice">
            <div class="notice-image">
                <img class="notice-header-img" src="../media/chatimages/images/tongzhi@3x.png" alt=""/>
                <div class="newNews"></div>
            </div>
            <div class="notice-text">
                <p class="notice-title">水果超市</p>
                <p class="notice-remind">今天就发货</p>
            </div>
            <div class="notice-time">12:30</div>
        </div>
    </div>
</div>

</body>
</html>