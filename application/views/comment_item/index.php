<link rel=stylesheet media=all href="/css/index.css">
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

<script defer src="/js/index.js"></script>

<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li class=active><?php echo $this->class_name_cn ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
    <div class="btn-group btn-group-justified" role=group>
        <div class=btn-group role=group>
            <button type=button class="btn btn-default dropdown-toggle" data-toggle=dropdown aria-haspopup=true aria-expanded=false>
                全部 <span class="caret"></span>
            </button>
            <ul class=dropdown-menu>
                <li>
                    <?php $style_class = empty($this->input->get('status') )? 'btn-primary': 'btn-default'; ?>
                    <a class="btn <?php echo $style_class ?>" title="全部<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">全部</a>
                </li>

                <?php
                $status_to_mark = array('待付款',);
                foreach ($status_to_mark as $status):
                    // 页面URL
                    $url = ($status === NULL)? base_url($this->class_name): base_url($this->class_name. '?status='.$status);
                    // 链接样式
                    $style_class = ($this->input->get('status') !== $status)? 'btn-default': 'btn-primary';
                    echo '<li><a class="btn '. $style_class. '" title="'. $status. '订单" href="'. $url. '">'. $status. '</a> </li>';
                endforeach;
                ?>
            </ul>
        </div>

        <a class="btn <?php echo $this->input->get('status') === '待接单'? 'btn-primary': 'btn-default' ?>" title="待接单商品订单" href="<?php echo base_url($this->class_name. '?status=待接单') ?>">待接单</a>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>这里空空如也，快点添加<?php echo $this->class_name_cn ?>吧</p>
	</blockquote>

	<?php else: ?>
        <ul id=item-list class=row>
            <?php foreach ($items as $item): ?>
            <li>
                <span class=item-status><?php echo $item['score'] ?></span>
                <a href="<?php echo base_url($this->class_name.'/detail?id='.$item[$this->id_name]) ?>">
                    <p><?php echo $this->class_name_cn ?>ID <?php echo $item[$this->id_name] ?></p>
                    <p><?php echo $item['content'] ?></p>
                </a>
            </li>
            <?php endforeach ?>
        </ul>

	</form>
	<?php endif ?>
</div>