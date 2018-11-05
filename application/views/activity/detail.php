	<div class="page-content">
		<?php if (isset($_GET['done'])): ?>
			<div class="row">
				<div class="col-xs-12">
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>
						</button>
						<strong>
							核销成功
						</strong>
						<br>
					</div>
				</div>
			</div>
		<?php endif ?>
      
	</div>

	<div class="row">
		<div class="<?php echo $large; ?> <?php echo $offset; ?>">
			<div class="row">
										
					<div class="widget-box">
						<div class="widget-header widget-header-flat">
							<h4 class="widget-title smaller">订单详情</h4>

							<div class="widget-toolbar">
								<label>
									<small class="green">
										<b><?php echo $res['status']?></b>
									</small>

								</label>
							</div>
						</div>

						<div class="widget-body">
							<div class="widget-main">
								<code class="pull-right" id="dt-list-code"></code>

								
									<h4>[<?php echo $res['coupon_name']?>] </h4>
								

									<?php if ($res['status'] == '已使用'): ?>
									<h4>优惠码</h4>
									<p><strong><?php echo $res['verify_code']?></strong></p>

									<h4>使用时间</h4>
									<p><?php echo date("Y-m-d H:i:s",$res['time_verify'])?></p>
									<?php endif; ?>

									<hr>

									<?php if (!empty($user)): ?>

									<h4>用户ID</h4>
									<p><?php echo $user['user_id'];?></p>

									<h4>用户姓名</h4>
									<p><?php echo $user['nickname'];?></p>

									<?php if ($res['mobile']) :?>
										<h4>用户电话</h4>
										<p><?php echo $res['mobile']; ?></p>
									<?php endif ;?>

									<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
								

		</div>
	</div>
