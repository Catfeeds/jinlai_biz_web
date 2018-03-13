<link rel=stylesheet media=all href="/css/detail.css">
<style>
    .avatar {width:200px;height:200px;}

	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px)
	{
		
	}
	
	/* 宽度在960像素以上的设备 */
	@media only screen and (min-width:961px)
	{

	}

	/* 宽度在1280像素以上的设备 */
	@media only screen and (min-width:1281px)
	{

	}
</style>

<script defer src="/js/detail.js"></script>
<script>
    $(function(){
        // 重新格式化特殊类型文本
        seperate_string('ssn_cn', [6,14]); // 中国大陆身份证号码
        seperate_string('bank_account', [4,8,12,16,20]); // 银行卡号
        seperate_string('mobile', [3,7], '-'); // 手机号
    })
</script>

<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class=container>
	<ul id=item-actions class=list-unstyled>
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a></li>
	</ul>

	<dl id=list-info class=dl-horizontal>
		<dt>头像</dt>
		<?php if ( empty($item['avatar']) ): ?>
        <dd>未上传</dd>
        <?php else: ?>
		<dd>
			<figure class="avatar centered_xy">
				<img src="<?php echo $item['avatar'] ?>">
			</figure>
		</dd>
		<?php endif ?>

		<dt>用户ID</dt>
		<dd><?php echo $item['user_id'] ?></dd>
		<dt>昵称</dt>
		<dd><?php echo $item['nickname'] ?></dd>
        <dt>性别</dt>
        <dd><?php echo empty($item['gender'])? '未设置': $item['gender'] ?></dd>
        <dt>生日</dt>
        <dd><?php echo empty($item['dob'])? '未填写': $item['dob'] ?></dd>

		<dt>姓名</dt>
		<dd><?php echo $item['lastname'].$item['firstname'] ?></dd>
		<dt>身份证号</dt>
		<dd class="ssn_cn">
            <?php echo !empty($item['code_ssn'])? str_replace(substr($item['code_ssn'], -6), '******', $item['code_ssn']): NULL;  ?>
        </dd>
		<dt>身份证照片</dt>
        <?php if ( empty($item['url_image_id']) ): ?>
        <dd>未上传</dd>
        <?php else: ?>
        <dd class=row>
            <figure class="col-xs-12 col-sm-6 col-md-4">
                <img class=img-circle src="<?php echo $item['url_image_id'] ?>">
            </figure>
        </dd>

        <dt>手机号</dt>
		<dd class="mobile"><?php echo $item['mobile'] ?></dd>
		<dt>电子邮件地址</dt>
		<dd><?php echo $item['email'] ?></dd>
		<dt>开户行名称</dt>
		<dd><?php echo $item['bank_name'] ?></dd>
		<dt>开户行账号</dt>
		<dd class="bank_account">
            <?php echo !empty($item['bank_account'])? str_replace(substr($item['bank_account'], -6), '******', $item['bank_account']): NULL;  ?>
        </dd>
		<dt>注册时间</dt>
		<dd><?php echo $item['time_create'] ?></dd>

		<dt>最后登录信息</dt>
		<dd>
            <?php echo date('Y-m-d H:i:s', $item['last_login_timestamp']) ?>

            <?php if ( ! empty($item['last_login_ip'])): ?>
            （<?php echo $item['last_login_ip'] ?> <a href="//www.baidu.com/s?wd=<?php echo $item['last_login_ip'] ?>" target="_blank">查询</a>）
            <?php endif ?>
        </dd>
        <?php endif ?>
	</dl>
</div>