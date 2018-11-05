
<div class="page-content">
	<div class="row" id="alertmsg" style="display: none;">
		<div class="col-xs-12">
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert">
					<i class="ace-icon fa fa-times"></i>
				</button>

				<strong>
					<i class="ace-icon fa fa-times"></i>
					ops，出错了
				</strong>
					<span id="errmsg">优惠码不对！</span>
				<br>
			</div>
		</div>
	</div>
	<div class="row" id="successmsg" style="display: none;">
		<div class="col-xs-12">
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert">
					<i class="ace-icon fa fa-times "></i>
				</button>
				<i class="ace-icon fa fa-check"></i>
					兑换成功～
				<strong>
				
				</strong>
				<br>
			</div>
		</div>
	</div>

	<div class="space-6"></div>
	<div class="space-6"></div>
	<div class="space-6"></div>
	<div class="row">
		<div class="col-xs-10">
			<form class="form-horizontal" role="form">
				<div class="form-group">

					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 请输入优惠券码 </label>

					<div class="col-sm-9">
						<input type="number" id="verify_code" placeholder="10位纯数字" class="col-xs-10 col-sm-5" maxlength="10">
					</div>
				</div>
			</form>
			
		</div>
	</div>

		<div id="mark"></div>
		<div class="clearfix form-actions" id="verify" style="display: none;">
			<div class="col-md-offset-3 col-md-9">
				<button class="btn btn-success" type="button" id="confirmsub">
					<i class="ace-icon fa fa-check bigger-110"></i>
					确认使用
				</button>
			</div>
		</div>
	
</div>