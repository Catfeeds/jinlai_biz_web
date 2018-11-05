	<div class="page-content">
		<div class="page-header">
			<h1>添加活动券 </h1>
		</div>
		<?php if (isset($msg)) : ?>
		<div class="row" id="alertmsg" >
			<div class="col-xs-12">
				<div class="alert alert-<?php echo  $status == 200 ? 'success' : 'danger' ?>">
					<button type="button" class="close" data-dismiss="alert">
						<i class="ace-icon fa fa-times"></i>
					</button>

					<strong>
						
					</strong>
						<span id="errmsg"><?php echo $msg ?> </span>
					<br>
				</div>
			</div>
		</div>
		<?php endif ?>
		<div class="row">
			<div class="col-xs-12">

				<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
										
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right">券名</label>

						<div class="col-sm-6">
							<input type="text" id="ticket_name" name="ticket_name" placeholder="券名" class="col-xs-10 col-sm-6">
						</div>
					</div>


					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right">显示数量</label>

						<div class="col-sm-6">
							<input type="text" id="show_count" name="show_count" placeholder="显示数量" class="col-xs-10 col-sm-6">
						</div>
					</div>


					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right">排序</label>

						<div class="col-sm-6">
							<input type="text" id="sort" name="sort" placeholder="从小到大排" class="col-xs-10 col-sm-6">
						</div>
					</div>

					<div class="space-6"></div>

					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right">图片链接</label>

						<div class="col-xs-12 col-sm-3">
		                    <span class="block input-icon input-icon-right">
		                        <input multiple="" type="file" id="img" placeholder="头图" name="picture" class="col-xs-12" value="" />
		                    </span>
		                </div>
		                <div class="help-block col-xs-12 col-sm-reset inline"> </div>
					</div>
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" type="submit">
								<i class="ace-icon fa fa-check bigger-110"></i>
								提交
							</button>

						</div>
					</div>
				</form>

			</div>
		</div>
	</div>