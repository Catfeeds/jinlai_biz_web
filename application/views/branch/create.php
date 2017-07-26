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
		<a class="btn btn-primary" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<p class="bg-info text-info text-center">必填项以“※”符号表示</p>

		<fieldset>
			<legend>基本信息</legend>

									<div class=form-group>
							<label for=branch_id class="col-sm-2 control-label">门店ID</label>
							<div class=col-sm-10>
								<input class=form-control name=branch_id type=text value="<?php echo set_value('branch_id') ?>" placeholder="门店ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=biz_id class="col-sm-2 control-label">所属商家ID</label>
							<div class=col-sm-10>
								<input class=form-control name=biz_id type=text value="<?php echo set_value('biz_id') ?>" placeholder="所属商家ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=name class="col-sm-2 control-label">名称</label>
							<div class=col-sm-10>
								<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="名称" required>
							</div>
						</div>
						<div class=form-group>
							<label for=description class="col-sm-2 control-label">说明</label>
							<div class=col-sm-10>
								<input class=form-control name=description type=text value="<?php echo set_value('description') ?>" placeholder="说明" required>
							</div>
						</div>
						<div class=form-group>
							<label for=tel_public class="col-sm-2 control-label">消费者联系电话</label>
							<div class=col-sm-10>
								<input class=form-control name=tel_public type=text value="<?php echo set_value('tel_public') ?>" placeholder="消费者联系电话" required>
							</div>
						</div>
						<div class=form-group>
							<label for=tel_protected_biz class="col-sm-2 control-label">商务联系手机号</label>
							<div class=col-sm-10>
								<input class=form-control name=tel_protected_biz type=text value="<?php echo set_value('tel_protected_biz') ?>" placeholder="商务联系手机号" required>
							</div>
						</div>
						<div class=form-group>
							<label for=tel_protected_order class="col-sm-2 control-label">订单通知手机号</label>
							<div class=col-sm-10>
								<input class=form-control name=tel_protected_order type=text value="<?php echo set_value('tel_protected_order') ?>" placeholder="订单通知手机号" required>
							</div>
						</div>
						<div class=form-group>
							<label for=day_rest class="col-sm-2 control-label">休息日</label>
							<div class=col-sm-10>
								<input class=form-control name=day_rest type=text value="<?php echo set_value('day_rest') ?>" placeholder="休息日" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_open class="col-sm-2 control-label">开放时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_open type=text value="<?php echo set_value('time_open') ?>" placeholder="开放时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_close class="col-sm-2 control-label">结束时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_close type=text value="<?php echo set_value('time_close') ?>" placeholder="结束时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=url_image_main class="col-sm-2 control-label">主图</label>
							<div class=col-sm-10>
								<input class=form-control name=url_image_main type=text value="<?php echo set_value('url_image_main') ?>" placeholder="主图" required>
							</div>
						</div>
						<div class=form-group>
							<label for=figure_image_urls class="col-sm-2 control-label">形象图</label>
							<div class=col-sm-10>
								<input class=form-control name=figure_image_urls type=text value="<?php echo set_value('figure_image_urls') ?>" placeholder="形象图" required>
							</div>
						</div>
						<div class=form-group>
							<label for=nation class="col-sm-2 control-label">国别</label>
							<div class=col-sm-10>
								<input class=form-control name=nation type=text value="<?php echo set_value('nation') ?>" placeholder="国别" required>
							</div>
						</div>
						<div class=form-group>
							<label for=province class="col-sm-2 control-label">省</label>
							<div class=col-sm-10>
								<input class=form-control name=province type=text value="<?php echo set_value('province') ?>" placeholder="省" required>
							</div>
						</div>
						<div class=form-group>
							<label for=city class="col-sm-2 control-label">市</label>
							<div class=col-sm-10>
								<input class=form-control name=city type=text value="<?php echo set_value('city') ?>" placeholder="市" required>
							</div>
						</div>
						<div class=form-group>
							<label for=county class="col-sm-2 control-label">区/县</label>
							<div class=col-sm-10>
								<input class=form-control name=county type=text value="<?php echo set_value('county') ?>" placeholder="区/县" required>
							</div>
						</div>
						<div class=form-group>
							<label for=street class="col-sm-2 control-label">具体地址</label>
							<div class=col-sm-10>
								<input class=form-control name=street type=text value="<?php echo set_value('street') ?>" placeholder="具体地址" required>
							</div>
						</div>
						<div class=form-group>
							<label for=region_id class="col-sm-2 control-label">地区ID</label>
							<div class=col-sm-10>
								<input class=form-control name=region_id type=text value="<?php echo set_value('region_id') ?>" placeholder="地区ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=region class="col-sm-2 control-label">地区</label>
							<div class=col-sm-10>
								<input class=form-control name=region type=text value="<?php echo set_value('region') ?>" placeholder="地区" required>
							</div>
						</div>
						<div class=form-group>
							<label for=poi_id class="col-sm-2 control-label">兴趣点ID</label>
							<div class=col-sm-10>
								<input class=form-control name=poi_id type=text value="<?php echo set_value('poi_id') ?>" placeholder="兴趣点ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=poi class="col-sm-2 control-label">兴趣点</label>
							<div class=col-sm-10>
								<input class=form-control name=poi type=text value="<?php echo set_value('poi') ?>" placeholder="兴趣点" required>
							</div>
						</div>
						<div class=form-group>
							<label for=longitude class="col-sm-2 control-label">经度</label>
							<div class=col-sm-10>
								<input class=form-control name=longitude type=text value="<?php echo set_value('longitude') ?>" placeholder="经度" required>
							</div>
						</div>
						<div class=form-group>
							<label for=latitude class="col-sm-2 control-label">纬度</label>
							<div class=col-sm-10>
								<input class=form-control name=latitude type=text value="<?php echo set_value('latitude') ?>" placeholder="纬度" required>
							</div>
						</div>
						<div class=form-group>
							<label for=status class="col-sm-2 control-label">状态</label>
							<div class=col-sm-10>
								<input class=form-control name=status type=text value="<?php echo set_value('status') ?>" placeholder="状态" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_create class="col-sm-2 control-label">创建时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_create type=text value="<?php echo set_value('time_create') ?>" placeholder="创建时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_delete class="col-sm-2 control-label">删除时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_delete type=text value="<?php echo set_value('time_delete') ?>" placeholder="删除时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_edit class="col-sm-2 control-label">最后操作时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_edit type=text value="<?php echo set_value('time_edit') ?>" placeholder="最后操作时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=creator_id class="col-sm-2 control-label">创建者ID</label>
							<div class=col-sm-10>
								<input class=form-control name=creator_id type=text value="<?php echo set_value('creator_id') ?>" placeholder="创建者ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=operator_id class="col-sm-2 control-label">最后操作时间</label>
							<div class=col-sm-10>
								<input class=form-control name=operator_id type=text value="<?php echo set_value('operator_id') ?>" placeholder="最后操作时间" required>
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