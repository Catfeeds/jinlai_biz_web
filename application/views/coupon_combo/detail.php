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
            <li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a></li>
        <?php endif ?>
    </ul>

	<dl id=list-info class=dl-horizontal>
        <dt>优惠券包ID</dt>
        <dd><?php echo $item['combo_id'] ?></dd>

        <?php
        // 当前项客户端URL
        $item_url = WEB_URL.$this->class_name.'/detail?id='.$item[$this->id_name];
        ?>

        <dt><?php echo $this->class_name_cn ?>链接</dt>
        <dd>
            <span><?php echo $item_url ?></span>
            <a href="<?php echo $item_url ?>" target=_blank>查看</a>
        </dd>

        <dt><?php echo $this->class_name_cn ?>二维码</dt>
        <dd>
            <figure class="qrcode col-xs-12 col-sm-6 col-md-3" data-qrcode-string="<?php echo $item_url ?>"></figure>
        </dd>

		<dt>名称</dt>
		<dd><?php echo $item['name'] ?></dd>

		<dt>总限量</dt>
		<dd><?php echo empty($item['max_amount'])? '不限': $item['max_amount'].'份'; ?></dd>

		<dt>开放领取时间</dt>
		<dd>
			<?php echo empty($item['time_start'])? '自即日起': date('Y-m-d H:i:s', $item['time_start']); ?> <?php echo empty($item['time_end'])? '始终开放': ' 至 '.date('Y-m-d H:i:s', $item['time_end']); ?>
		</dd>

        <dt>所含优惠券</dt>
        <dd>
            <ul class=margined-list>
                <?php foreach ($templates as $template): ?>
                    <li>
                        <a href="<?php echo base_url('coupon_template/detail?id='.$template['template_id']) ?>" target=_blank><?php echo $template['name'] ?> <i class="far fa-search"></i></a>
                    </li>
                <?php endforeach ?>
            </ul>
        </dd>
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