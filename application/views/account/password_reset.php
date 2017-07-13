<style>
	#content {padding-top:2rem;}
	form {padding-top:2rem;}
	
	#captcha-image {padding:0;min-width:100px;}
		#captcha-image img {width:100%;height:100%;}

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

<script src="/js/form.js"></script>

<div id=content class=container>
	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>'; // 若有错误提示信息则显示
		$attributes = array('class' => 'form-password-reset col-xs-12 col-md-6 col-md-offset-3', 'role' => 'form');
		echo form_open('password_reset', $attributes);
	?>
		<fieldset>
			<input name=sms_id type=hidden>

			<div class=form-group>
				<label for=mobile>手机号</label>
				<div class=input-group>
					<span class="input-group-addon"><i class="fa fa-mobile fa-fw" aria-hidden=true></i></span>
					<input class=form-control name=mobile type=tel value="<?php echo $this->input->post('mobile')? set_value('mobile'): $this->input->cookie('mobile') ?>" size=11 pattern="\d{11}" placeholder="手机号" required>
				</div>
			</div>

			<div class=form-group>
				<label for=captcha_verify>图片验证码</label>
				<div class=input-group>
					<input id=captcha-verify class=form-control name=captcha_verify type=number max=9999 min=0001 step=1 size=4 placeholder="请输入图片验证码" required>
					<span id=captcha-image class="input-group-addon">
						<img src="<?php echo base_url('captcha') ?>">
					</span>
				</div>
			</div>

			<div class=form-group>
				<label for=captcha>短信验证码</label>
				<div class=input-group>
					<input id=captcha-input class=form-control name=captcha type=number max=999999 step=1 size=6 pattern="\d{6}" placeholder="请输入短信验证码" disabled required>
					<span class="input-group-addon">
						<a id=sms-send href="#">获取验证码</a>
					</span>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<div class=form-group>
				<label for=password>新密码</label>
				<div class=input-group>
					<span class=input-group-addon><i class="fa fa-key fa-fw" aria-hidden=true></i></span>
					<input class=form-control name=password type=password placeholder="新密码" required>
				</div>
			</div>

			<div class=form-group>
				<label for=password_confirm>确认密码</label>
				<div class=input-group>
					<span class="input-group-addon"><i class="fa fa-lock fa-fw" aria-hidden=true></i></span>
					<input class=form-control name=password_confirm type=password placeholder="确认新密码" required>
				</div>
			</div>
		</fieldset>

		<div class=row>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-block btn-lg" type=submit>确定</button>
		    </div>
		</div>
	</form>
</div>
