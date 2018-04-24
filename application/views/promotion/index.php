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
    ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>暂时没有可报名的<?php echo $this->class_name_cn ?></p>
	</blockquote>

	<?php else: ?>
    <ul id=item-list class=row>
        <?php foreach ($items as $item): ?>
        <li>
            <a href="<?php echo base_url($this->class_name.'/detail?id='.$item[$this->id_name]) ?>">
                <p><?php echo $item['name'] ?></p>
                <p><?php echo $item['description'] ?></p>
            </a>

            <div class="item-actions">
                <span>
                    <input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
                </span>

                <ul class=horizontal>
                    <?php
                    // 需要特定角色和权限进行该操作
                    if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
                        ?>
                    <li><a title="报名" href="<?php echo base_url($this->class_name.'/apply?id='.$item[$this->id_name]) ?>" target=_blank>报名</a></li>
                    <?php endif ?>
                </ul>
            </div>

        </li>
        <?php endforeach ?>
    </ul>

	<?php endif ?>
</div>