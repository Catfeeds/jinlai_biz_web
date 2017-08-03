<style>
	#content {padding-top:2rem;}
	form {padding-top:2rem;}

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

<div id=content class=container>
	<div class=row>
		<?php
			if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>'; // 若有错误提示信息则显示
			$attributes = array('class' => 'form-password-change col-xs-12 col-md-6 col-md-offset-3', 'role' => 'form');
			echo form_open('password_change', $attributes);
		?>
			<fieldset>
				<div class=form-group>
					<label for=password_current class="col-sm-2 control-label">原密码</label>
					<div class=input-group>
						<span class="input-group-addon"><i class="fa fa-lock fa-fw" aria-hidden=true></i></span>
						<input class=form-control name=password_current type=password placeholder="请输入当前密码" autofocus required>
					</div>
				</div>

				<div class=form-group>
					<label for=password class="col-sm-2 control-label">新密码</label>
					<div class=input-group>
						<span class="input-group-addon"><i class="fa fa-lock fa-fw" aria-hidden=true></i></span>
						<input class=form-control name=password type=password placeholder="可设置6-20位密码，需与原密码不同" required>
					</div>
				</div>

				<div class=form-group>
					<label for=password_confirm>确认密码</label>
					<div class=input-group>
						<span class="input-group-addon"><i class="fa fa-lock fa-fw" aria-hidden=true></i></span>
						<input class=form-control name=password_confirm type=password placeholder="请再次输入密码进行确认" required>
					</div>
				</div>
			</fieldset>

			<div class=row>
			    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
					<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
			    </div>
			</div>

		</form>
	</div>
	
	<ul id=actions class="row">
		<li class="col-xs-12 col-sm-offset-2 col-sm-2"><a title="忘记密码" class="btn btn-default btn-block" href="<?php echo base_url('password_reset') ?>">忘记密码</a></li>
	</ul>
</div>
