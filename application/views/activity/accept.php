
<div class="page-content">
	<?php if (isset($msg)): ?>
	<div class="row" id="alertmsg">
		<div class="col-xs-12">
			<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert">
					<i class="ace-icon fa fa-times"></i>
				</button>

				<strong>
					<i class="ace-icon fa fa-times"></i>
					<?php echo $msg; ?>
				</strong>
					
				<br>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<div class="space-6"></div>
	<div class="space-6"></div>
	<div class="space-6"></div>
	<form class="form-horizontal" role="form" method="post">
	<div class="row">
		<div class="col-xs-10">
			
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 退款金额 </label>

					<div class="col-sm-9">
						<input value="<?php echo $record['total_applied'] ?>" id="total_approved" name="total_approved" placeholder="退款金额" class="col-xs-10 col-sm-5" maxlength="5">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 密码 </label>

					<div class="col-sm-9">
						<input type="number" id="password" name="password"  class="col-xs-10 col-sm-5" maxlength="10">
					</div>
				</div>
			
			
		</div>
	</div>

		<?php if (!isset($msg)): ?>
			<div class="clearfix form-actions">
				<div class="col-md-offset-3 col-md-9">
					<button class="btn btn-success" type="submit">
						<i class="ace-icon fa fa-check bigger-110"></i>
						确认退款
					</button>
				</div>
			</div>
		<?php endif; ?>
	</form>
</div>