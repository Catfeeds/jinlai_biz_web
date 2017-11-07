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
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class="btn-group btn-group-justified" role=group>
		<a class="btn btn-primary" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">所有</a>
	  	<!--<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>-->
	</div>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>这里空空如也，快点添加<?php echo $this->class_name_cn ?>吧</p>
	</blockquote>

	<?php else: ?>
	<form method=get target=_blank>

		<table class="table table-condensed table-responsive table-striped sortable">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th><?php echo $this->class_name_cn ?>ID</th>
					<?php
						$thead = array_values($data_to_display);
						foreach ($thead as $th):
							echo '<th>' .$th. '</th>';
						endforeach;
					?>
					<th>操作</th>
				</tr>
			</thead>

			<tbody>
			<?php foreach ($items as $item): ?>
				<tr>
					<td>
						<input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
					</td>
					<td><?php echo $item[$this->id_name] ?></td>
					<?php
						$tr = array_keys($data_to_display);
						foreach ($tr as $td):
							echo '<td>' .$item[$td]. '</td>';
						endforeach;
					?>
					<td>
						<ul class=list-unstyled>
							<?php
							// 仅可修改自己的信息
							if ( $item['user_id'] === $this->session->user_id ):
							?>
							<li class="color_primary"><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank>编辑</a></li>
							<?php endif ?>
						</ul>
					</td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>

	</form>
	<?php endif ?>
</div>