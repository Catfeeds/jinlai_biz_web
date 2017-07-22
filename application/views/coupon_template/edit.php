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
			<p class="bg-info text-info text-center">必填项以“※”符号表示</p>

			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">
			
			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="名称" required>
				</div>
			</div>
			
			<div class=form-group>
				<label for=amount class="col-sm-2 control-label">面值（元）※</label>
				<div class=col-sm-10>
					<input class=form-control name=amount type=number step=1 min=1 max=999 value="<?php echo $item['amount'] ?>" placeholder="最高999" required>
				</div>
			</div>
			
			<div class=form-group>
				<label for=max_amount class="col-sm-2 control-label">限量（份）</label>
				<div class=col-sm-10>
					<input class=form-control name=max_amount type=number step=1 min=1 max=999999 value="<?php echo $item['max_amount'] ?>" placeholder="最高999999">
				</div>
			</div>
			
			<div class=form-group>
				<label for=min_subtotal class="col-sm-2 control-label">最低订单小计（元）</label>
				<div class=col-sm-10>
					<input class=form-control name=min_subtotal type=number step=1 min=1 max=999 value="<?php echo $item['min_subtotal'] ?>" placeholder="最高999">
				</div>
			</div>

			<div class=form-group>
				<label for=category_id class="col-sm-2 control-label">限用系统分类</label>
				<div class=col-sm-10>
					<?php $input_name = 'category_id' ?>
					<select class=form-control name="<?php echo $input_name ?>">
						<option value="">请选择</option>
						<?php
							$options = $biz_categories;
							foreach ($options as $option):
						?>
						<option value="<?php echo $option['category_id'] ?>" <?php if ($option['category_id'] === $item['category_id']) echo 'selected'; ?>><?php echo $option['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class=form-group>
				<label for=category_biz_id class="col-sm-2 control-label">限用店内分类</label>
				<div class=col-sm-10>
					<div class=col-sm-10>
						<?php $input_name = 'category_biz_id' ?>
						<select class=form-control name="<?php echo $input_name ?>">
							<option value="">请选择</option>
							<?php
								$options = $biz_categories;
								foreach ($options as $option):
							?>
							<option value="<?php echo $option['category_id'] ?>" <?php if ($option['category_id'] === $item['category_biz_id']) echo 'selected'; ?>><?php echo $option['name'] ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
			</div>

			<div class=form-group>
				<label for=item_id class="col-sm-2 control-label">限用商品</label>
				<div class=col-sm-10>
					<input class=form-control name=item_id type=text value="<?php echo $item['item_id'] ?>" placeholder="如仅限部分商品可用，请输入可用商品的商品ID，多个ID间用一个半角逗号“,”进行分隔">
				</div>
			</div>
			
			<div class=form-group>
				<label for=period class="col-sm-2 control-label">自领取时起有效期（秒）</label>
				<div class=col-sm-10>
					<input class=form-control name=period type=number step=1 size=10 value="<?php echo $item['period'] ?>" placeholder="自领取时起有效期（秒）">
				</div>
			</div>
			<div class=form-group>
				<label for=time_start class="col-sm-2 control-label">开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_start type=datetime value="<?php echo date('Y-m-d H:i:s', $item['time_start']) ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+2days')) ?>">
				</div>
			</div>
			<div class=form-group>
				<label for=time_end class="col-sm-2 control-label">结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_end type=datetime value="<?php echo date('Y-m-d H:i:s', $item['time_end']) ?>" placeholder="例如：<?php echo date('Y-m-d H:i:s', strtotime('+5days')) ?>">
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