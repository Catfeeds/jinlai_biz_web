<link rel=stylesheet media=all href="/css/edit.css">
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
<script defer src="/js/edit.js"></script>

<base href="<?php echo $this->media_root ?>">

<div id=content class=container>
    <div class="jumbotron row">
        <p class=help-block>该部分信息需通过您的专属顾问进行修改或分配</p>

        <dl id=core-info class=dl-horizontal>
            <dt>商家全称</dt>
            <dd><?php echo !empty($item['name'])? $item['name']: '未填写' ?></dd>
            <dt>店铺名称</dt>
            <dd><?php echo $item['brief_name'] ?></dd>
            <dt>店铺域名</dt>
            <dd><?php echo !empty($item['url_name'])? $item['url_name']: '未分配' ?></dd>
            <dt>商务联系手机号</dt>
            <dd><?php echo $item['tel_protected_biz'] ?></dd>
        </dl>
    </div>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<legend>基本资料</legend>

			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

			<div class=form-group>
				<label for=url_logo class="col-sm-2 control-label">店铺LOGO</label>
                <div class=col-sm-10>
                    <?php
                    require_once(APPPATH. 'views/templates/file-uploader.php');
                    $name_to_upload = 'url_logo';
                    generate_html($name_to_upload, $this->class_name, FALSE, 1, $item[$name_to_upload]);
                    ?>

                    <p class=help-block>正方形图片视觉效果最佳</p>
                </div>
			</div>

            <!--
            <div class=form-group>
                <label for=category_ids class="col-sm-2 control-label">主营商品类目</label>
                <div class=col-sm-10>
                    <?php $input_name = 'category_ids[]' ?>
                    <select class=form-control name="<?php echo $input_name ?>" multiple required>
                        <?php
                        $options = $item_categories;
                        $current_array = explode(',', $item['category_ids']);
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                                ?>
                                <option value="<?php echo $option['category_id'] ?>" <?php if ( in_array($option['category_id'], $current_array) ) echo 'selected'; ?>><?php echo $option['name'] ?></option>
                            <?php
                            endif;
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>
            -->

			<div class=form-group>
				<label for=slogan class="col-sm-2 control-label">宣传语</label>
				<div class=col-sm-10>
					<input class=form-control name=slogan type=text value="<?php echo $item['slogan'] ?>" placeholder="宣传语">
				</div>
			</div>
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">简介</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=5 placeholder="最多255个字符"><?php echo $item['description'] ?></textarea>
				</div>
			</div>
			<div class=form-group>
				<label for=notification class="col-sm-2 control-label">店铺公告</label>
				<div class=col-sm-10>
					<textarea class=form-control name=notification rows=5 placeholder="最多255个字符"><?php echo $item['notification'] ?></textarea>
				</div>
			</div>

			<div class=form-group>
				<label for=tel_public class="col-sm-2 control-label">消费者服务电话※</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_public type=tel value="<?php echo $item['tel_public'] ?>" placeholder="400、800、手机号、带区号的固定电话号码均可" required>
					<p class=help-block>即客服电话，不要加空格或其它符号，固定电话请填写区号</p>
				</div>
			</div>
			
			<div class=form-group>
				<label for=tel_protected_order class="col-sm-2 control-label">订单通知手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_protected_order type=tel size=11 value="<?php echo $item['tel_protected_order'] ?>" placeholder="请填写手机号">
				</div>
			</div>

            <div class=form-group>
                <label for=freight_template_id class="col-sm-2 control-label">默认运费模板</label>
                <div class=col-sm-10>
                    <a class="btn btn-default btn-lg btn-block" href="<?php echo base_url('freight_template_biz') ?>">管理运费模板</a>

                    <?php $input_name = 'category_biz_id' ?>
                    <select class=form-control name="<?php echo $input_name ?>">
                        <option>包邮</option>
                        <?php
                            if ( !empty($biz_freight_templates) ):
                                $options = $biz_freight_templates;
                                foreach ($options as $option):
                            ?>
                            <option value="<?php echo $option['template_id'] ?>" <?php if ($option['template_id'] === $item[$input_name]) echo 'selected' ?>><?php echo $option['name'] ?></option>
                        <?php
                                endforeach;
                            endif;
                        ?>
                    </select>

                </div>
            </div>
		</fieldset>

		<fieldset>
			<legend>资质信息</legend>

			<div class=form-group>
				<label for=code_license class="col-sm-2 control-label">工商注册号※</label>
				<div class=col-sm-10>
					<input class=form-control name=code_license type=text value="<?php echo $item['code_license'] ?>" placeholder="如为三证合一，请填写统一社会信用代码" required>
					<p class=help-block>请填写营业执照上的15位工商注册号；或三证合一后18位的统一社会信用代码。</p>
				</div>
			</div>
			<div class=form-group>
				<label for=fullname_owner class="col-sm-2 control-label">法人姓名※</label>
				<div class=col-sm-10>
					<input class=form-control name=fullname_owner type=text size=15 value="<?php echo $item['fullname_owner'] ?>" placeholder="需与身份证一致" required>
					<p class=help-block>按照营业执照上填写。如果属于分公司则填写工商营业执照上明确的负责人，个体工商户请填写经营者姓名，合伙企业请填写合伙人姓名，个人独资企业请填写投资人姓名，企业法人的非法人分支机构填写负责人姓名。 </p>
				</div>
			</div>
			<div class=form-group>
				<label for=code_ssn_owner class="col-sm-2 control-label">法人身份证号※</label>
				<div class=col-sm-10>
					<input class=form-control name=code_ssn_owner type=text value="<?php echo $item['code_ssn_owner'] ?>" placeholder="法人身份证号" required>
				</div>
			</div>

			<div class=form-group>
				<label for=fullname_auth class="col-sm-2 control-label">经办人姓名</label>
				<div class=col-sm-10>
					<input class=form-control name=fullname_auth type=text size=15 value="<?php echo $item['fullname_auth'] ?>" placeholder="需与身份证一致">
					<p class=help-block>如果负责业务对接的不是法人本人，请填写此项</p>
				</div>
			</div>
			<div class=form-group>
				<label for=code_ssn_auth class="col-sm-2 control-label">经办人身份证号</label>
				<div class=col-sm-10>
					<input class=form-control name=code_ssn_auth type=text value="<?php echo $item['code_ssn_auth'] ?>" placeholder="经办人身份证号">
					<p class=help-block>如果负责业务对接的不是法人本人，请填写此项</p>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>财务信息</legend>

			<div class=form-group>
				<label for=bank_name class="col-sm-2 control-label">对公账户开户行</label>
				<div class=col-sm-10>
					<input class=form-control name=bank_name type=text value="<?php echo $item['bank_name'] ?>" placeholder="基本户、一般户均可">
				</div>
			</div>
			<div class=form-group>
				<label for=bank_account class="col-sm-2 control-label">对公账户账号</label>
				<div class=col-sm-10>
					<input class=form-control name=bank_account type=number step=1 value="<?php echo $item['bank_account'] ?>" placeholder="基本户、一般户均可">
				</div>
			</div>
			<div class=form-group>
				<label for=tel_protected_fiscal class="col-sm-2 control-label">财务联系手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=tel_protected_fiscal type=tel size=11 value="<?php echo $item['tel_protected_fiscal'] ?>" placeholder="请填写手机号">
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>资质及授权证明</legend>
			<p class=help-block>以下资料需要彩色原件的扫描件或数码照</p>

			<div class=form-group>
				<label for=url_image_license class="col-sm-2 control-label">营业执照※</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'url_image_license' ?>
                    <ul class=upload_preview>
                        <?php if ( !empty($item[$name_to_upload]) ): ?>

                            <li data-input-name="<?php echo $name_to_upload ?>" data-item-url="<?php echo $item[$name_to_upload] ?>">
                                <i class="remove fa fa-minus"></i>
                                <i class="left fa fa-arrow-left"></i>
                                <i class="right fa fa-arrow-right"></i>
                                <figure>
                                    <img src="<?php echo $item[$name_to_upload] ?>">
                                </figure>
                            </li>

                        <?php endif ?>
                    </ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo $item[$name_to_upload] ?>" required>

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/license" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> data-max-count="1" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
			</div>

			<div class=form-group>
				<label for=url_image_owner_id class="col-sm-2 control-label">法人身份证※</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'url_image_owner_id' ?>
                    <ul class=upload_preview>
                        <?php if ( !empty($item[$name_to_upload]) ): ?>

                            <li data-input-name="<?php echo $name_to_upload ?>" data-item-url="<?php echo $item[$name_to_upload] ?>">
                                <i class="remove fa fa-minus"></i>
                                <i class="left fa fa-arrow-left"></i>
                                <i class="right fa fa-arrow-right"></i>
                                <figure>
                                    <img src="<?php echo $item[$name_to_upload] ?>">
                                </figure>
                            </li>

                        <?php endif ?>
                    </ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo $item[$name_to_upload] ?>" required>

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/owner_id" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> data-max-count="1" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
			</div>

			<div class=form-group>
				<label for=url_image_auth_id class="col-sm-2 control-label">经办人身份证</label>
                <div class=col-sm-10>
                    <p class=help-block>如果负责业务对接的不是法人本人，请上传经办人身份证</p>

                    <?php $name_to_upload = 'url_image_auth_id' ?>
                    <ul class=upload_preview>
                        <?php if ( !empty($item[$name_to_upload]) ): ?>

                            <li data-input-name="<?php echo $name_to_upload ?>" data-item-url="<?php echo $item[$name_to_upload] ?>">
                                <i class="remove fa fa-minus"></i>
                                <i class="left fa fa-arrow-left"></i>
                                <i class="right fa fa-arrow-right"></i>
                                <figure>
                                    <img src="<?php echo $item[$name_to_upload] ?>">
                                </figure>
                            </li>

                        <?php endif ?>
                    </ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo $item[$name_to_upload] ?>">

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/auth_id" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> data-max-count="1" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
			</div>

			<div class=form-group>
				<label for=url_image_auth_doc class="col-sm-2 control-label">经办人授权书</label>
                <div class=col-sm-10>
                    <p class=help-block>
                        如果负责业务对接的不是法人本人，请上传授权书
                        <small><a title="进来商城经办人授权书" href="<?php echo base_url('article/auth-doc-for-admission') ?>" target=_blank><i class="fa fa-info-circle" aria-hidden=true></i> 授权书示例</a></small>
                    </p>

                    <?php $name_to_upload = 'url_image_auth_doc' ?>
                    <ul class=upload_preview>
                        <?php if ( !empty($item[$name_to_upload]) ): ?>

                            <li data-input-name="<?php echo $name_to_upload ?>" data-item-url="<?php echo $item[$name_to_upload] ?>">
                                <i class="remove fa fa-minus"></i>
                                <i class="left fa fa-arrow-left"></i>
                                <i class="right fa fa-arrow-right"></i>
                                <figure>
                                    <img src="<?php echo $item[$name_to_upload] ?>">
                                </figure>
                            </li>

                        <?php endif ?>
                    </ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo $item[$name_to_upload] ?>">

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/auth_doc" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> data-max-count="1" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
			</div>
		</fieldset>

		<fieldset>
			<legend>形象展示</legend>
			<p class=help-block>您可根据自身情况上传合适的照片，向消费者展现企业形象，每种照片可上传4张</p>

			<div class=form-group>
				<label for=url_image_product class="col-sm-2 control-label">产品</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'url_image_product' ?>
                    <ul class=upload_preview>
                        <?php if ( !empty($item[$name_to_upload]) ): ?>

                            <?php
                            $figure_image_urls = explode(',', $item[$name_to_upload]);
                            foreach($figure_image_urls as $url):
                                ?>
                                <li data-input-name="<?php echo $name_to_upload ?>" data-item-url="<?php echo $url ?>">
                                    <i class="remove fa fa-minus"></i>
                                    <i class="left fa fa-arrow-left"></i>
                                    <i class="right fa fa-arrow-right"></i>
                                    <figure>
                                        <img src="<?php echo $url ?>">
                                    </figure>
                                </li>
                            <?php endforeach ?>

                        <?php endif ?>
                    </ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo $item[$name_to_upload] ?>">

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/product" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> data-max-count="4" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
			</div>

			<div class=form-group>
				<label for=url_image_produce class="col-sm-2 control-label">工厂/产地</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'url_image_produce' ?>
                    <ul class=upload_preview>
                        <?php if ( !empty($item[$name_to_upload]) ): ?>

                            <?php
                            $figure_image_urls = explode(',', $item[$name_to_upload]);
                            foreach($figure_image_urls as $url):
                                ?>
                                <li data-input-name="<?php echo $name_to_upload ?>" data-item-url="<?php echo $url ?>">
                                    <i class="remove fa fa-minus"></i>
                                    <i class="left fa fa-arrow-left"></i>
                                    <i class="right fa fa-arrow-right"></i>
                                    <figure>
                                        <img src="<?php echo $url ?>">
                                    </figure>
                                </li>
                            <?php endforeach ?>

                        <?php endif ?>
                    </ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo $item[$name_to_upload] ?>">

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/produce" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> data-max-count="4" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
			</div>

			<div class=form-group>
				<label for=url_image_retail class="col-sm-2 control-label">门店/柜台</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'url_image_retail' ?>
                    <ul class=upload_preview>
                        <?php if ( !empty($item[$name_to_upload]) ): ?>

                            <?php
                            $figure_image_urls = explode(',', $item[$name_to_upload]);
                            foreach($figure_image_urls as $url):
                                ?>
                                <li data-input-name="<?php echo $name_to_upload ?>" data-item-url="<?php echo $url ?>">
                                    <i class="remove fa fa-minus"></i>
                                    <i class="left fa fa-arrow-left"></i>
                                    <i class="right fa fa-arrow-right"></i>
                                    <figure>
                                        <img src="<?php echo $url ?>">
                                    </figure>
                                </li>
                            <?php endforeach ?>

                        <?php endif ?>
                    </ul>

                    <div class=selector_zone>
                        <input id=<?php echo $name_to_upload ?> class=form-control type=file multiple>
                        <input name=<?php echo $name_to_upload ?> type=hidden value="<?php echo $item[$name_to_upload] ?>">

                        <div class=file_selector><i class="fa fa-plus" aria-hidden=true></i></div>
                    </div>

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/retail" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> data-max-count="4" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>联系地址</legend>
            <p class=help-block>该信息将用于订单退换货等业务</p>

			<!--
            <div class=form-group>
				<label for=nation class="col-sm-2 control-label">国家</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $item['nation'] ?></p>
				</div>
			</div>
			-->
			<div class=form-group>
				<label for=province class="col-sm-2 control-label">省</label>
				<div class=col-sm-10>
					<input class=form-control name=province type=text value="<?php echo $item['province'] ?>" placeholder="省">
				</div>
			</div>
			<div class=form-group>
				<label for=city class="col-sm-2 control-label">市</label>
				<div class=col-sm-10>
					<input class=form-control name=city type=text value="<?php echo $item['city'] ?>" placeholder="市">
				</div>
			</div>
			<div class=form-group>
				<label for=county class="col-sm-2 control-label">区/县</label>
				<div class=col-sm-10>
					<input class=form-control name=county type=text value="<?php echo $item['county'] ?>" placeholder="区">
				</div>
			</div>
			<div class=form-group>
				<label for=street class="col-sm-2 control-label">具体地址</label>
				<div class=col-sm-10>
					<textarea class=form-control name=street rows=3 placeholder="具体地址"><?php echo $item['street'] ?></textarea>
				</div>
			</div>

			<div class=form-group>
				<figure class="col-sm-10 col-sm-offset-2">
					<figcaption>
						<p class=help-block>拖动地图可完善位置信息</p>
					</figcaption>
					<div id=map style="height:300px;background-color:#999"></div>
				</figure>
				<input name=longitude type=hidden value="<?php echo $item['longitude'] ?>">
				<input name=latitude type=hidden value="<?php echo $item['latitude'] ?>">
			</div>

			<script src="//webapi.amap.com/maps?v=1.3&key=bf0fd60938b2f4f40de5ee83a90c2e0e"></script>
			<script src="//webapi.amap.com/ui/1.0/main.js"></script>
			<script>
			    var map = new AMap.Map('map',{
					<?php if ( !empty($item['longitude']) && !empty($item['latitude']) ): ?>
					center: [<?php echo $item['longitude'] ?>, <?php echo $item['latitude'] ?>],
					<?php endif ?>
					zoom: 16,
		            scrollWheel: false,
					mapStyle: 'amap://styles/91f3dcb31dfbba6e97a3c2743d4dff88', // 自定义样式，通过高德地图控制台管理
			    });

				<?php if ( empty($item['longitude']) || empty($item['latitude']) ): ?>
				// 若未设置过经纬度信息，默认获取并定位到当前位置
				map.plugin('AMap.Geolocation', function() {
			        var geolocation = new AMap.Geolocation({
			            enableHighAccuracy: true,//是否使用高精度定位，默认:true
			            timeout: 10000,          //超过10秒后停止定位，默认：无穷大
			            buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
			            zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
			            buttonPosition:'RB'
			        });
			        map.addControl(geolocation);
			        geolocation.getCurrentPosition();
			        AMap.event.addListener(geolocation, 'complete', onComplete); //返回定位信息
			        AMap.event.addListener(geolocation, 'error', onError); //返回定位出错信息
			    });
			    // 解析定位结果
			    function onComplete(data)
				{
					// 提示用户确定修改
					var user_confirm = confirm("是否设置联系地址位置为当前地点");
				    if (user_confirm == true)
				    {
						document.getElementsByName('longitude')[0].value = data.position.getLng();
						document.getElementsByName('latitude')[0].value = data.position.getLat();
					}
			    }
				// 解析定位错误信息
			    function onError(data)
				{
			        alert('定位失败');
			    }
				<?php endif ?>

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

					function address_to_lnglat(address_text) {

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
		</fieldset>

        <fieldset>
            <legend>店铺装修（高级版功能，限时免费）</legend>

            <div class=form-group>
                <label for=ornament_id class="col-sm-2 control-label">店铺装修方案</label>
                <div class=col-sm-10>
                    <?php if ( empty($ornaments) ): ?>
                    <a class="btn btn-default btn-lg btn-block" href="<?php echo base_url('ornament_biz/create') ?>">创建装修方案</a>

                    <?php else: ?>
                    <a class="btn btn-default btn-lg btn-block" href="<?php echo base_url('ornament_biz') ?>">管理装修方案</a>
                    <?php $input_name = 'ornament_id' ?>
                    <select class=form-control name="<?php echo $input_name ?>">
                        <option>不装修，首页显示所有商品</option>
                        <?php
                        $options = $ornaments;
                        foreach ($options as $option):
                        ?>
                        <option value="<?php echo $option['ornament_id'] ?>" <?php if ($option['ornament_id'] === $item[$input_name]) echo 'selected'; ?>><?php echo $option['name'] ?></option>
                        <?php endforeach ?>
                    </select>

                    <?php endif ?>
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