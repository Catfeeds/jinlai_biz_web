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

<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li><a href="create.php" id="" title="create">create</a>
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
		<p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<div class=form-group>
				<label for=url_image_main class="col-sm-2 control-label">主图</label>
				<div class=col-sm-10>
					<p class=help-block>推荐上传正方形图片以达到最佳视觉效果</p>

					<?php $name_to_upload = 'url_image_main' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="branch/main" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>
			<div class=form-group>
				<label for=figure_image_urls class="col-sm-2 control-label">形象图</label>
				<div class=col-sm-10>
					<p class=help-block>最多可上传4张</p>

					<?php $name_to_upload = 'figure_image_urls' ?>
					<input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
					<input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo set_value($name_to_upload) ?>">

					<button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="branch/figure_image" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
				</div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>基本信息</legend>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="名称"  required>
				</div>
			</div>
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明</label>
				<div class=col-sm-10>
					<input class=form-control name=description type=text value="<?php echo set_value('description') ?>" placeholder="说明">
				</div>
			</div>
			<div class=form-group>
				<label for=tel_public class="col-sm-2 control-label">消费者联系电话</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_public type=tel value="<?php echo set_value('tel_public') ?>" placeholder="消费者联系电话">
				</div>
			</div>
			<div class=form-group>
				<label for=tel_protected_biz class="col-sm-2 control-label">商务联系手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_protected_biz type=tel value="<?php echo set_value('tel_protected_biz') ?>" placeholder="商务联系手机号">
				</div>
			</div>
			<div class=form-group>
				<label for=tel_protected_order class="col-sm-2 control-label">订单通知手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_protected_order type=tel value="<?php echo set_value('tel_protected_order') ?>" placeholder="订单通知手机号">
				</div>
			</div>
			<div class=form-group>
				<label for=day_rest class="col-sm-2 control-label">休息日</label>
				<div class=col-sm-10>
					<input class=form-control name=day_rest type=text value="<?php echo set_value('day_rest') ?>" placeholder="休息日">
				</div>
			</div>

			<div class=form-group>
				<label for=time_open class="col-sm-2 control-label">营业/配送开始时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_open type=number min=0 step=1 max=22 value="<?php echo set_value('time_open') ?>" placeholder="请填写整点，例如上午8点为8">
				</div>
			</div>
			<div class=form-group>
				<label for=time_close class="col-sm-2 control-label">营业/配送结束时间</label>
				<div class=col-sm-10>
					<input class=form-control name=time_close type=number min=1 step=1 max=23 value="<?php echo set_value('time_close') ?>" placeholder="请填写整点，例如下午10点为22">
				</div>
			</div>

			<div class=form-group>
				<label for=range_deliver class="col-sm-2 control-label">配送范围（公里）</label>
				<div class=col-sm-10>
					<input class=form-control name=range_deliver type=number min=0 step=1 max=99 value="<?php echo set_value('range_deliver') ?>" placeholder="最高99">
					<p class=help-block>若提供本地配送，可填写此项</p>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>地址</legend>

			<!--
			<div class=form-group>
				<label for=nation class="col-sm-2 control-label">国别</label>
				<div class=col-sm-10>
					<input class=form-control name=nation type=text value="<?php echo set_value('nation') ?>" placeholder="可留空，默认中国">
				</div>
			</div>
			-->
			<div class=form-group>
				<label for=province class="col-sm-2 control-label">省※</label>
				<div class=col-sm-10>
					<input class=form-control name=province type=text value="<?php echo set_value('province') ?>" placeholder="省" required>
				</div>
			</div>
			<div class=form-group>
				<label for=city class="col-sm-2 control-label">市※</label>
				<div class=col-sm-10>
					<input class=form-control name=city type=text value="<?php echo set_value('city') ?>" placeholder="市" required>
				</div>
			</div>
			<div class=form-group>
				<label for=county class="col-sm-2 control-label">区/县</label>
				<div class=col-sm-10>
					<input class=form-control name=county type=text value="<?php echo set_value('county') ?>" placeholder="区/县">
				</div>
			</div>
			<div class=form-group>
				<label for=street class="col-sm-2 control-label">具体地址※</label>
				<div class=col-sm-10>
					<input class=form-control name=street type=text value="<?php echo set_value('street') ?>" placeholder="具体地址" required>
				</div>
			</div>

			<div class=form-group>
				<figure class="col-sm-10 col-sm-offset-2">
					<figcaption>
						<p class=help-block>拖动地图可完善位置信息</p>
					</figcaption>
					<div id=map class="col-xs-12" style="height:300px;background-color:#aaa"></div>
				</figure>
				<input name=longitude type=hidden value="<?php echo set_value('longitude') ?>">
				<input name=latitude type=hidden value="<?php echo set_value('latitude') ?>">
			</div>

			<script src="https://webapi.amap.com/maps?v=1.3&key=d698fd0ab2d88ad11f4c6a2c0e83f6a8"></script>
			<script src="https://webapi.amap.com/ui/1.0/main.js"></script>
			<script>
			    var map = new AMap.Map('map',{
					<?php if ( !empty(set_value('longitude')) && !empty(set_value('latitude')) ): ?>
					center: [<?php echo set_value('longitude') ?>, <?php echo set_value('latitude') ?>],
					<?php endif ?>
					zoom: 16,
		            scrollWheel: false,
					mapStyle: 'amap://styles/2daddd87cfd0fa58d0bc932eed31b9d8', // 自定义样式，通过高德地图控制台管理
			    });

				// 为BasicControl设置DomLibrary，jQuery
				AMapUI.setDomLibrary($);
				AMapUI.loadUI(['control/BasicControl', 'misc/PositionPicker'], function(BasicControl, PositionPicker) {
					// 缩放控件
				    map.addControl(new BasicControl.Zoom({
				        position: 'rb', // 右下角
				    }));

				    var positionPicker = new PositionPicker({
				        mode: 'dragMap',//设定为拖拽地图模式，可选'dragMap'、'dragMarker'，默认为'dragMap'
				        map: map//依赖地图对象
				    });

				    // 获取定位点经纬度并写入相应字段
					positionPicker.on('success', function(positionResult){
						// 忽略首次拖拽选址（即防止页面载入时提示修改定位点）
						if (times_picked != 0){
							// 提示用户确定修改
							var user_confirm = confirm("是否修改位置为图中地点");
						    if (user_confirm == true)
						    {
								document.getElementsByName('longitude')[0].value = positionResult.position.lng;
								document.getElementsByName('latitude')[0].value = positionResult.position.lat;
							}
						}

						times_picked++;
					});
					positionPicker.on('fail', function(positionResult) {
					    // 海上或海外无法获得地址信息
					    document.getElementsByName('longitude')[0].value = '';
					    document.getElementsByName('latitude')[0].value = '';
					});

					// 忽略首次拖拽选址（即防止页面载入时提示修改定位点）
			    	times_picked = 0;

			        positionPicker.start();
			        //map.panBy(0, 1);

					// 根据详细地址获取经纬度
					document.getElementsByName('street')[0].onchange =
						function(){
							// 忽略首次拖拽选址（即防止页面载入时提示修改定位点）
							times_picked = 0;

							var address_text =
								document.getElementsByName('province')[0].value +
								document.getElementsByName('city')[0].value +
								document.getElementsByName('county')[0].value +
								document.getElementsByName('street')[0].value;
							address_to_lnglat(address_text);
						};

					function address_to_lnglat(address_text){

						AMap.service('AMap.Geocoder',function(){//回调函数
					        var geocoder = new AMap.Geocoder({
					            radius: 1000 //范围，默认：500
					        });

					        // 返回经纬度，并将地图中心重置
							geocoder.getLocation(address_text, function(status, result){
							    if (status === 'complete' && result.info === 'OK') {
									console.log(result.geocodes[0].formattedAddress);
									document.getElementsByName('longitude')[0].value = result.geocodes[0].location.lng;
									document.getElementsByName('latitude')[0].value = result.geocodes[0].location.lat;
									//map.setFitView();
									map.setCenter(
										[result.geocodes[0].location.lng, result.geocodes[0].location.lat]
									);
							    }
							});
						});
						
				    }
				});
			</script>

			<!--
			<div class=form-group>
				<label for=region_id class="col-sm-2 control-label">商圈</label>
				<div class=col-sm-10>
					<input class=form-control name=region_id type=text value="<?php echo set_value('region_id') ?>" placeholder="商圈">
				</div>
			</div>
			<div class=form-group>
				<label for=poi_id class="col-sm-2 control-label">子商圈</label>
				<div class=col-sm-10>
					<input class=form-control name=poi_id type=text value="<?php echo set_value('poi_id') ?>" placeholder="子商圈">
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