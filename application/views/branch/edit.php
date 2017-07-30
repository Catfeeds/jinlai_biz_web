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

<base href="<?php echo base_url('uploads/') ?>">

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
		<p class="bg-info text-info text-center">必填项以“※”符号标识</p>

		<fieldset>
			<div class=form-group>
				<label for=url_image_main class="col-sm-2 control-label">主图</label>
				<div class=col-sm-10>
					<?php if ( !empty($item['url_image_main']) ): ?>
					<div class=row>
						<figure class="col-xs-12 col-sm-6 col-md-4">
							<img src="<?php echo $item['url_image_main'] ?>">
						</figure>
					</div>
					<?php endif ?>

					<div>
						<p class=help-block>请上传大小在2M以内，边长不超过2048px的jpg/png图片</p>
						<?php $name_to_upload = 'url_image_main' ?>
					
						<input id=<?php echo $name_to_upload ?> class=form-control type=file>
						<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo $item[$name_to_upload] ?>">

						<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="branch/main" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

						<ul class="upload_preview list-inline row"></ul>
					</div>

				</div>
			</div>
			<div class=form-group>
				<label for=figure_image_urls class="col-sm-2 control-label">形象图</label>
				<div class=col-sm-10>
					<?php if ( !empty($item['figure_image_urls']) ): ?>
					<ul class=row>
						<?php
							$figure_image_urls = explode(',', $item['figure_image_urls']);
							foreach($figure_image_urls as $url):
						?>
						<li class="col-xs-6 col-sm-4 col-md-3">
							<img src="<?php echo $url ?>">
						</li>
						<?php endforeach ?>
					</ul>
					<?php endif ?>
					
					<div>
						<p class=help-block>最多可上传4张，选择时按住“ctrl”或“⌘”键可选多张</p>
						<?php $name_to_upload = 'figure_image_urls' ?>
					
						<input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
						<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo $item[$name_to_upload] ?>">

						<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="branch/image_figure" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

						<ul class="upload_preview list-inline row"></ul>
					</div>

				</div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>基本信息</legend>
			
			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="名称" required>
				</div>
			</div>
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明</label>
				<div class=col-sm-10>
					<input class=form-control name=description type=text value="<?php echo $item['description'] ?>" placeholder="说明">
				</div>
			</div>
			<div class=form-group>
				<label for=tel_public class="col-sm-2 control-label">消费者联系电话</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_public type=tel value="<?php echo $item['tel_public'] ?>" placeholder="消费者联系电话">
				</div>
			</div>
			<div class=form-group>
				<label for=tel_protected_biz class="col-sm-2 control-label">商务联系手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_protected_biz type=tel value="<?php echo $item['tel_protected_biz'] ?>" placeholder="商务联系手机号">
				</div>
			</div>
			<div class=form-group>
				<label for=tel_protected_order class="col-sm-2 control-label">订单通知手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_protected_order type=tel value="<?php echo $item['tel_protected_order'] ?>" placeholder="订单通知手机号">
				</div>
			</div>
			<div class=form-group>
				<label for=day_rest class="col-sm-2 control-label">休息日</label>
				<div class=col-sm-10>
					<input class=form-control name=day_rest type=text value="<?php echo $item['day_rest'] ?>" placeholder="休息日">
				</div>
			</div>
			<div class=form-group>
				<label for=time_open class="col-sm-2 control-label">营业/配送开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_open type=text value="<?php echo $item['time_open'] ?>" placeholder="开放时间">
				</div>
			</div>
			<div class=form-group>
				<label for=time_close class="col-sm-2 control-label">营业/配送结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_close type=text value="<?php echo $item['time_close'] ?>" placeholder="结束时间">
				</div>
			</div>
		</fieldset>
			
		<fieldset>
			<legend>地址</legend>

			<div class=form-group>
				<label for=nation class="col-sm-2 control-label">国别</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $item['country'] ?></p>
				</div>
			</div>
			<div class=form-group>
				<label for=province class="col-sm-2 control-label">省※</label>
				<div class=col-sm-10>
					<input class=form-control name=province type=text value="<?php echo $item['province'] ?>" placeholder="省" required>
				</div>
			</div>
			<div class=form-group>
				<label for=city class="col-sm-2 control-label">市※</label>
				<div class=col-sm-10>
					<input class=form-control name=city type=text value="<?php echo $item['city'] ?>" placeholder="市" required>
				</div>
			</div>
			<div class=form-group>
				<label for=county class="col-sm-2 control-label">区/县※</label>
				<div class=col-sm-10>
					<input class=form-control name=county type=text value="<?php echo $item['county'] ?>" placeholder="区/县" required>
				</div>
			</div>
			<div class=form-group>
				<label for=street class="col-sm-2 control-label">具体地址※</label>
				<div class=col-sm-10>
					<input class=form-control name=street type=text value="<?php echo $item['street'] ?>" placeholder="具体地址" required>
					<input name=longitude type=hidden value="<?php echo $item['longitude'] ?>">
					<input name=latitude type=hidden value="<?php echo $item['latitude'] ?>">
				</div>
			</div>
			<!--
			<div class=form-group>
				<label for=region_id class="col-sm-2 control-label">商圈</label>
				<div class=col-sm-10>
					<input class=form-control name=region_id type=text value="<?php echo $item['region_id'] ?>" placeholder="地区ID">
				</div>
			</div>
			<div class=form-group>
				<label for=poi_id class="col-sm-2 control-label">子商圈</label>
				<div class=col-sm-10>
					<input class=form-control name=poi_id type=text value="<?php echo $item['poi_id'] ?>" placeholder="兴趣点ID">
				</div>
			</div>
			-->
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>

</div>