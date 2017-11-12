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
        <li class="col-xs-12">
            <a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a>
        </li>
    <?php endif ?>
    </ul>

	<dl id=list-info class=dl-horizontal>
        <dt>装修方案ID</dt>
		<dd><?php echo $item['ornament_id'] ?></dd>
		<dt>所属商家ID</dt>
		<dd><?php echo $item['biz_id'] ?></dd>
		<dt>方案名称</dt>
		<dd><?php echo $item['name'] ?></dd>
		<dt>JSON格式内容，10-20000个字符</dt>
		<dd><?php echo $item['content_json'] ?></dd>
		<dt>HTML格式内容</dt>
		<dd><?php echo $item['content_html'] ?></dd>
		<dt>装修模板ID</dt>
		<dd><?php echo $item['template_id'] ?></dd>
		<dt>首页轮播图内容</dt>
		<dd><?php echo $item['home_slides'] ?></dd>
		<dt>模块一形象图URL</dt>
		<dd><?php echo $item['home_m1_ace_url'] ?></dd>
		<dt>模块一首推商品ID</dt>
		<dd><?php echo $item['home_m1_ace_id'] ?></dd>
		<dt>模块一陈列商品</dt>
		<dd><?php echo $item['home_m1_ids'] ?></dd>
		<dt>模块二形象图URL</dt>
		<dd><?php echo $item['home_m2_ace_url'] ?></dd>
		<dt>模块二首推商品ID</dt>
		<dd><?php echo $item['home_m2_ace_id'] ?></dd>
		<dt>模块二陈列商品</dt>
		<dd><?php echo $item['home_m2_ids'] ?></dd>
		<dt>模块三形象图URL</dt>
		<dd><?php echo $item['home_m3_ace_url'] ?></dd>
		<dt>模块三首推商品ID</dt>
		<dd><?php echo $item['home_m3_ace_id'] ?></dd>
		<dt>模块三陈列商品</dt>
		<dd><?php echo $item['home_m3_ids'] ?></dd>
		<dt>创建时间</dt>
		<dd><?php echo $item['time_create'] ?></dd>
		<dt>删除时间</dt>
		<dd><?php echo $item['time_delete'] ?></dd>
		<dt>最后操作时间</dt>
		<dd><?php echo $item['time_edit'] ?></dd>
		<dt>创建者ID</dt>
		<dd><?php echo $item['creator_id'] ?></dd>
		<dt>最后操作者ID</dt>
		<dd><?php echo $item['operator_id'] ?></dd>
	</dl>

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
</div>