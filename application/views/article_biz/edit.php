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
<!--<script defer src="/js/edit.js"></script>-->

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
			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

			<div class=form-group>
				<label for=title class="col-sm-2 control-label">标题※</label>
				<div class=col-sm-10>
					<input class=form-control name=title type=text value="<?php echo $item['title'] ?>" placeholder="标题" required>
				</div>
			</div>
			<div class=form-group>
				<label for=excerpt class="col-sm-2 control-label">摘要</label>
				<div class=col-sm-10>
                    <textarea class=form-control name=excerpt rows=5 placeholder="摘要，最多255个字符"><?php echo $item['excerpt'] ?></textarea>
				</div>
			</div>
			<div class=form-group>
				<label for=content class="col-sm-2 control-label">内容※</label>
				<div class=col-sm-10>
                    <textarea class=form-control name=content rows=10 placeholder="文章内容，最多20000个字符" required><?php echo $item['content'] ?></textarea>

                    <link rel=stylesheet media=all href="<?php echo base_url('/css/simditor.css') ?>">
                    <script src="<?php echo base_url('/js/module.js') ?>"></script>
                    <script src="<?php echo base_url('/js/hotkeys.js') ?>"></script>
                    <script src="<?php echo base_url('/js/uploader.js') ?>"></script>
                    <script src="<?php echo base_url('/js/simditor.js') ?>"></script>
                    <script>
                        $(function(){
                            var toolbar = ['title', 'bold', 'italic', 'underline', 'strikethrough', 'fontScale', 'color', '|', 'hr', 'ol', 'ul', 'blockquote', 'code', 'table', '|', 'link', 'image', '|', 'indent', 'outdent', 'alignment'];
                            var editor = new Simditor({
                                textarea: $('[name=content]'),
                                cleanPaste: true,
                                toolbar: toolbar,
                                upload: {
                                    url: '<?php echo base_url('/simditor?target=article_biz/url_images') ?>',
                                    params: null,
                                    fileKey: 'file0',
                                    connectionCount: 4,
                                    leaveConfirm: '上传尚未结束，确定要中止？'
                                }
                            });
                        });
                    </script>
				</div>
			</div>

			<div class=form-group>
				<label for=url_images class="col-sm-2 control-label">形象图</label>
                <div class=col-sm-10>
                    <?php $name_to_upload = 'url_images' ?>
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

                    <button class="file-upload btn btn-default btn-lg col-xs-12 col-md-3" data-target-dir="<?php echo $this->class_name ?>/<?php echo $name_to_upload ?>" data-selector-id=<?php echo $name_to_upload ?> data-input-name=<?php echo $name_to_upload ?> data-max-count=1 type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
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