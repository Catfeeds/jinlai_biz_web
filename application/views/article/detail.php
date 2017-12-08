<link rel=stylesheet media=all href="/css/detail.css">
<style>


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

<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
		if ( !empty($error) ):
			echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';

		else:
            // 需要特定角色和权限进行该操作
            $current_role = $this->session->role; // 当前用户角色
            $current_level = $this->session->level; // 当前用户级别
            $role_allowed = array('管理员', '经理');
            $level_allowed = 30;
        ?>
            <ul id=item-actions class=list-unstyled>
                <?php
                // 需要特定角色和权限进行该操作
                if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
                    ?>
                    <li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a></li>
                <?php endif ?>
            </ul>
	
	<header>
		<h2><?php echo $item['title'] ?></h2>
		<ul class="list-horizontal row">
			<li class="col-xs-12 col-sm-6 col-md-3"><?php echo $item['time_edit'] ?></li>
		</ul>
        <?php if ( !empty($item['excerpt']) ): ?>
            <div class="excerpt well"><?php echo $item['excerpt'] ?></div>
        <?php endif ?>
	</header>

	<section><?php echo $item['content'] ?></section>

	<dl id=list-info class=dl-horizontal>
        <?php
        // 当前项客户端URL
        $item_url = WEB_URL.$this->class_name.'/detail?id='.$item[$this->id_name];
        ?>

        <dt><?php echo $this->class_name_cn ?>链接</dt>
        <dd>
            <span><?php echo $item_url ?></span>
            <a href="<?php echo $item_url ?>">查看</a>
        </dd>

        <dt><?php echo $this->class_name_cn ?>二维码</dt>
        <dd>
            <figure id=qrcode class="col-xs-12 col-sm-6 col-md-3"></figure>
            <script>
                qrcode_generate("<?php echo $item_url ?>")
            </script>
        </dd>

		<!--
		<dt>文章ID</dt>
		<dd><?php echo $item['article_id'] ?></dd>
		<dt>分类</dt>
		<dd><?php echo $item['category_id'] ?></dd>
		<dt>自定义域名</dt>
		<dd><?php echo $item['url_name'] ?></dd>
		-->

        <dt>形象图</dt>
        <?php if ( !empty($item['url_images']) ): ?>
            <dd>
                <?php $name_to_upload = 'url_images' ?>
                <ul class=upload_preview>
                    <li>
                        <figure>
                            <img src="<?php echo $item[$name_to_upload] ?>">
                        </figure>
                    </li>
                </ul>
            </dd>
        <?php else: ?>
            <dd>未上传</dd>
        <?php endif ?>
	</dl>

	<!--
	<dl id=list-record class=dl-horizontal>
		<dt>创建时间</dt>
		<dd>
			<?php echo $item['time_create'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['creator_id']) ?>" target=new>查看创建者</a>
		</dd>

		<?php if ( ! empty($item['time_delete']) ): ?>
		<dt>删除时间</dt>
		<dd><?php echo $item['time_delete'] ?></dd>
		<?php endif ?>

		<?php if ( ! empty($item['operator_id']) ): ?>
		<dt>最后操作时间</dt>
		<dd>
			<?php echo $item['time_edit'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['operator_id']) ?>" target=new>查看最后操作者</a>
		</dd>
		<?php endif ?>
	</dl>
	-->

	<?php endif ?>
</div>