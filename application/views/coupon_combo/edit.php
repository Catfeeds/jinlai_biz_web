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
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<fieldset>
			<legend>基本信息</legend>
			
			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

									<div class=form-group>
							<label for=combo_id class="col-sm-2 control-label">优惠券模板套餐ID</label>
							<div class=col-sm-10>
								<input class=form-control name=combo_id type=text value="<?php echo $item['combo_id'] ?>" placeholder="优惠券模板套餐ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=biz_id class="col-sm-2 control-label">所属商家ID</label>
							<div class=col-sm-10>
								<input class=form-control name=biz_id type=text value="<?php echo $item['biz_id'] ?>" placeholder="所属商家ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=name class="col-sm-2 control-label">名称</label>
							<div class=col-sm-10>
								<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="名称" required>
							</div>
						</div>
						<div class=form-group>
							<label for=template_ids class="col-sm-2 control-label">优惠券模板ID们</label>
							<div class=col-sm-10>
								<input class=form-control name=template_ids type=text value="<?php echo $item['template_ids'] ?>" placeholder="优惠券模板ID们" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_start class="col-sm-2 control-label">开始时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_start type=text value="<?php echo $item['time_start'] ?>" placeholder="开始时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_end class="col-sm-2 control-label">结束时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_end type=text value="<?php echo $item['time_end'] ?>" placeholder="结束时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_create class="col-sm-2 control-label">创建时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_create type=text value="<?php echo $item['time_create'] ?>" placeholder="创建时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_delete class="col-sm-2 control-label">删除时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_delete type=text value="<?php echo $item['time_delete'] ?>" placeholder="删除时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_edit class="col-sm-2 control-label">最后操作时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_edit type=text value="<?php echo $item['time_edit'] ?>" placeholder="最后操作时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=creator_id class="col-sm-2 control-label">创建者ID</label>
							<div class=col-sm-10>
								<input class=form-control name=creator_id type=text value="<?php echo $item['creator_id'] ?>" placeholder="创建者ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=operator_id class="col-sm-2 control-label">最后操作者ID</label>
							<div class=col-sm-10>
								<input class=form-control name=operator_id type=text value="<?php echo $item['operator_id'] ?>" placeholder="最后操作者ID" required>
							</div>
						</div>

		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>

</div>