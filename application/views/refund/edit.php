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

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<legend>基本信息</legend>

			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

            <div class=form-group>
                <label for=type class="col-sm-2 control-label">类型</label>
                <div class=col-sm-10>
                    <input class=form-control name=type type=text value="<?php echo $item['type'] ?>" placeholder="类型" required>
                </div>
            </div>

            <div class=form-group>
                <label for=cargo_status class="col-sm-2 control-label">货物状态</label>
                <div class=col-sm-10>
                    <input class=form-control name=cargo_status type=text value="<?php echo $item['cargo_status'] ?>" placeholder="货物状态" required>
                </div>
            </div>

            <div class=form-group>
                <?php $input_name = 'reason' ?>
                <label for="<?php echo $input_name ?>" class="col-sm-2 control-label">原因</label>
                <div class=col-sm-10>
                    <select class=form-control name="<?php echo $input_name ?>" required>
                        <?php
                        $options = array('无理由', '退运费', '未收到', '不开发票');
                        foreach ($options as $option):
                            ?>
                            <option value="<?php echo $option ?>" <?php if ($option === $item[$input_name]) echo 'selected'; ?>><?php echo $option ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class=form-group>
                <label for=description class="col-sm-2 control-label">补充说明</label>
                <div class=col-sm-10>
                    <textarea class=form-control name=description rows=10 placeholder="补充说明" required><?php echo $item['description'] ?></textarea>
                </div>
            </div>

            <div class=form-group>
                <?php $input_name = 'url_images' ?>
                <label for="<?php echo $input_name ?>" class="col-sm-2 control-label">相关图片</label>
                <div class=col-sm-10>
                    <p class=help-block>最多可上传4张</p>

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

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="refund/<?php echo $name_to_upload ?>" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> data-max-count=4 type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
                </div>
            </div>

            <!--
            <div class=form-group>
                <label for=total_applied class="col-sm-2 control-label">申请退款金额（元）</label>
                <div class=col-sm-10>
                    <input class=form-control name=total_applied type=number min="1" step="0.01" max="99999.99" value="<?php echo $item['total_applied'] ?>" placeholder="申请退款金额（元）" required>
                </div>
            </div>
            -->

            <div class=form-group>
                <label for=total_approved class="col-sm-2 control-label">实际退款金额（元）</label>
                <div class=col-sm-10>
                    <input class=form-control name=total_approved type=number min="1" step="0.01" max="99999.99" value="<?php echo $item['total_approved'] ?>" placeholder="实际退款金额（元）" required>
                </div>
            </div>

            <!--
            <div class=form-group>
                <label for=deliver_method class="col-sm-2 control-label">发货方式</label>
                <div class=col-sm-10>
                    <input class=form-control name=deliver_method type=text value="<?php echo $item['deliver_method'] ?>" placeholder="发货方式" required>
                </div>
            </div>
            <div class=form-group>
                <label for=deliver_biz class="col-sm-2 control-label">物流服务商</label>
                <div class=col-sm-10>
                    <input class=form-control name=deliver_biz type=text value="<?php echo $item['deliver_biz'] ?>" placeholder="物流服务商" required>
                </div>
            </div>
            <div class=form-group>
                <label for=waybill_id class="col-sm-2 control-label">物流运单号</label>
                <div class=col-sm-10>
                    <input class=form-control name=waybill_id type=text value="<?php echo $item['waybill_id'] ?>" placeholder="物流运单号" required>
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