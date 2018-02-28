<link rel=stylesheet media=all href="/css/create.css">
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

<script defer src="/js/create.js"></script>

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
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<fieldset>
			<p class=helper-block>必填项以“※”符号标示</p>

			<div class=form-group>
				<label for=mobile class="col-sm-2 control-label">手机号 ※</label>
				<div class=col-sm-10>
					<input class=form-control name=mobile type=tel size=11 value="<?php echo set_value('mobile') ?>" size=11 pattern="\d{11}" placeholder="手机号" required>
					<p class=help-block>该手机号必须已注册过本平台的账号（即通过短信登录过），且未被其它商家绑定为员工</p>
				</div>
			</div>

			<div class=form-group>
				<label for=fullname class="col-sm-2 control-label">姓名 ※</label>
				<div class=col-sm-10>
					<input class=form-control name=fullname type=text value="<?php echo set_value('fullname') ?>" placeholder="姓名" required>
				</div>
			</div>

			<div class=form-group>
				<label for=role class="col-sm-2 control-label">角色 ※</label>
				<div class=col-sm-10>
					<?php $input_name = 'role' ?>
					<select class=form-control name="<?php echo $input_name ?>" required>
						<option value="" <?php echo set_select($input_name, '') ?>>请选择</option>
						<?php
							$options = array('管理员', '经理', '成员',);
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>><?php echo $option ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class=form-group>
				<label for=level class="col-sm-2 control-label">级别 ※</label>
				<div class=col-sm-10>
					<input class=form-control name=level type=number step=1 max=30 value="<?php echo set_value('level') ?>" placeholder="0暂不授权，1普通员工，10门店级，20品牌级，30企业级" required>
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