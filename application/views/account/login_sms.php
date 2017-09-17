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
	<section class="row bg-info text-info">
		<p><em>RC0.9.2</em>当前系统为beta/RC版本，仅供技术研究及技术开发；样式将随时变动，布局将随时更改，数据将不定时清零、重置、非主动性变更；任何公告、订单、充值均无实际效力，任何信息均不构成合同要约或其它任何责任。</p>
	</section>

	<div class="btn-group btn-group-justified" role=group>
		<a class="btn btn-default" href="<?php echo base_url('login') ?>">密码登录</a>
		<span class="btn btn-primary">短信登录/注册</span>
	</div>
	
	<div class=row>
		<?php
			if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>'; // 若有错误提示信息则显示
			$attributes = array('class' => 'form-login-sms col-xs-12 col-md-6 col-md-offset-3', 'role' => 'form');
			echo form_open('login_sms', $attributes);
		?>
			<fieldset>
				<input name=sms_id type=hidden>
			
				<div class=form-group>
					<label for=mobile>手机号</label>
					<div class=input-group>
						<span class="input-group-addon"><i class="fa fa-mobile fa-fw" aria-hidden=true></i></span>
						<input class=form-control name=mobile type=tel value="<?php echo $this->input->post('mobile')? set_value('mobile'): $this->input->cookie('mobile') ?>" size=11 pattern="\d{11}" placeholder="若该号码未注册，系统将自动生成账户" required>
					</div>
				</div>

				<div class=form-group>
					<label for=captcha_verify>图片验证码</label>
					<div class=input-group>
						<input id=captcha-verify class=form-control name=captcha_verify type=number max=9999 step=1 size=4 placeholder="请输入图片验证码" required>
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

			<small class="text-center">点击“确定”，即表示您已完整阅读并同意最新版<a title="查看用户协议详细内容" href="<?php echo base_url('article/user-agreement') ?>" target=_blank>《用户协议》</a>。</small>
		
			<div class=row>
			    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
					<button class="btn btn-primary btn-lg btn-block" type=submit role=button>确定</button>
			    </div>
			</div>

		</form>
	</div>
</div>
