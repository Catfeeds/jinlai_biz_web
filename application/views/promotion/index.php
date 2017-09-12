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
	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>暂时没有可报名的<?php echo $this->class_name_cn ?></p>
	</blockquote>

	<?php else: ?>
	<table class="table table-condensed table-responsive table-striped sortable">
		<thead>
			<tr>
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
				<td><?php echo $item[$this->id_name] ?></td>
				<?php
					$tr = array_keys($data_to_display);
					foreach ($tr as $td):
						echo '<td>' .$item[$td]. '</td>';
					endforeach;
				?>
				<td>
					<ul class=list-unstyled>
						<li><a title="查看" href="<?php echo base_url($this->view_root.'/detail?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-fw fa-eye"></i> 查看</a></li>
						<?php
						// 需要特定角色和权限进行该操作
						if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
						?>
						<li><a title="报名" href="<?php echo base_url($this->class_name.'/apply?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-fw fa-edit"></i> 报名</a></li>
						<?php endif ?>
					</ul>
				</td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>

	<?php endif ?>
</div>