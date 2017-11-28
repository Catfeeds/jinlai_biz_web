<style>
	#content {padding-top:2rem;}
	form {padding-top:2rem;}
	fieldset {padding:0;}
	
	#captcha-image {padding:0;min-width:100px;}
		#captcha-image img {width:100%;height:100%;display:block;}
	
	#actions {margin-top:4rem;}
		#actions>li {margin-bottom:2rem;}

	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:750px)
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
	<section class="bg-info text-info">
		<p><em>RC0.13.0</em>当前系统为beta/RC版本，仅供技术研究及开发；在不以任何形式告知的情况下，数据、功能、页面样式都将不定时变更；任何数据、业务、功能均无实际意义，且均不构成任何责任。</p>
	</section>
	
	<div class="btn-group btn-group-justified" role=group>
		<span class="btn btn-primary">密码登录</span>
		<a class="btn btn-default" href="<?php echo base_url('login_sms') ?>">短信登录/注册</a>
	</div>

	<div class=row>
		<?php
			if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>'; // 若有错误提示信息则显示
			$attributes = array('class' => 'form-login col-xs-12 col-md-6 col-md-offset-3', 'role' => 'form');
			echo form_open('login', $attributes);
		?>
			<fieldset>
				<div class=form-group>
					<label for=mobile>手机号</label>
					<div class=input-group>
						<span class=input-group-addon><i class="fa fa-mobile fa-fw" aria-hidden=true></i></span>
						<input class=form-control name=mobile type=tel value="<?php echo $this->input->post('mobile')? set_value('mobile'): $this->input->cookie('mobile') ?>" size=11 pattern="\d{11}" placeholder="手机号" required>
					</div>
				</div>

				<div class=form-group>
					<label for=captcha_verify>图片验证码</label>
					<div class=input-group>
						<input id=captcha-verify class=form-control name=captcha_verify type=number max=9999 step=1 size=4 placeholder="请输入图片验证码" required>
						<span id=captcha-image class=input-group-addon>
							<img src="<?php echo base_url('captcha') ?>">
						</span>
					</div>
				</div>

				<div class=form-group>
					<label for=password>密码</label>
					<div class=input-group>
						<span class=input-group-addon><i class="fa fa-key fa-fw" aria-hidden=true></i></span>
						<input class=form-control name=password type=password <?php if ($this->input->cookie('mobile')) echo 'autofocus'; ?> placeholder="密码" required>
					</div>
				</div>
			</fieldset>
		
			<small class=text-center>点击“确定”，即表示您已完整阅读并同意最新版<a title="查看用户协议详细内容" href="<?php echo base_url('article/user-agreement') ?>" target=_blank>《用户协议》</a>。</small>

			<div class=row>
			    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
					<button class="btn btn-primary btn-block" type=submit role=button>确定</button>
			    </div>
			</div>
		</form>
	</div>
	
	<ul id=actions class=row>
		<li class="col-xs-12 col-sm-4 col-md-3"><a title="注册" class="btn btn-default" href="<?php echo base_url('login_sms') ?>">短信登录/注册</a></li>
		<li class="col-xs-12 col-sm-4 col-md-3"><a title="忘记密码" class="btn btn-default" href="<?php echo base_url('password_reset') ?>">忘记密码</a></li>
	</ul>
	
</div>