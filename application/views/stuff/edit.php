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
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<fieldset>
			<p class=helper-block>必填项以“※”符号标示</p>

			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

			<div class=form-group>
				<label for=mobile class="col-sm-2 control-label">手机号</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $item['mobile'] ?></p>
					<p class=help-block>员工更改自己的手机号时，此处将同步更新，不可修改</p>
				</div>
			</div>

			<div class=form-group>
				<label for=fullname class="col-sm-2 control-label">姓名※</label>
				<div class=col-sm-10>
					<input class=form-control name=fullname type=text value="<?php echo $item['fullname'] ?>" placeholder="姓名" required>
				</div>
			</div>

			<div class=form-group>
				<label for=role class="col-sm-2 control-label">角色※</label>
				<?php if ($item['user_id'] !== $this->session->user_id): ?>
				<div class=col-sm-10>
					<?php $input_name = 'role' ?>
					<select class=form-control name="<?php echo $input_name ?>" required>
						<?php
							$options = array('管理员', '经理', '成员');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php if ($option === $item[$input_name]) echo 'selected'; ?>><?php echo $option ?></option>
						<?php endforeach ?>
					</select>
				</div>

				<?php else: ?>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $item['role'] ?></p>
					<input name=role type=hidden value="<?php echo $item['role'] ?>" required>
				</div>

				<?php endif ?>
			</div>

			<div class=form-group>
				<label for=level class="col-sm-2 control-label">级别※</label>
				<div class=col-sm-10>
					<?php if ($item['user_id'] !== $this->session->user_id): ?>
					<input class=form-control name=level type=number step=1 max=30 value="<?php echo $item['level'] ?>" placeholder="0暂不授权，1普通员工，10门店级，20品牌级，30企业级" required>
					<?php else: ?>
					<p class="form-control-static"><?php echo $item['level'] ?></p>
					<input name=level type=hidden value="<?php echo $item['level'] ?>" required>
					<?php endif ?>
				</div>
			</div>

			<div class=form-group>
				<label for=status class="col-sm-2 control-label">状态※</label>
				<div class=col-sm-10>
					<?php $input_name = 'status' ?>
					<select class=form-control name="<?php echo $input_name ?>" required>
						<?php
							$options = array('正常', '冻结',);
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php if ($option === $item[$input_name]) echo 'selected'; ?>><?php echo $option ?></option>
						<?php endforeach ?>
					</select>
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