<link rel=stylesheet media=all href="/css/create.css">
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

<script defer src="/js/create.js"></script>

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
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
        <p class=help-block>必填项以“※”符号标示</p>

		<fieldset>
			<div class=form-group>
				<label for=category_id class="col-sm-2 control-label">分类 ※</label>
				<div class=col-sm-10>
					<input class=form-control name=category_id type=text value="<?php echo set_value('category_id') ?>" placeholder="分类">
				</div>
			</div>
			<div class=form-group>
				<label for=title class="col-sm-2 control-label">标题 ※</label>
				<div class=col-sm-10>
					<input class=form-control name=title type=text value="<?php echo set_value('title') ?>" placeholder="4-30个字符"  required>
				</div>
			</div>
			<div class=form-group>
				<label for=excerpt class="col-sm-2 control-label">摘要</label>
				<div class=col-sm-10>
                    <textarea class=form-control name=excerpt rows=5 placeholder="10-100个字符"><?php echo set_value('excerpt') ?></textarea>
				</div>
			</div>
			<div class=form-group>
				<label for=content class="col-sm-2 control-label">内容 ※</label>
				<div class=col-sm-10>
                    <textarea class=form-control name=content rows=10 placeholder="10-20000个字符" required><?php echo set_value('content') ?></textarea>

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
				<label for=url_name class="col-sm-2 control-label">自定义域名</label>
				<div class=col-sm-10>
					<input class=form-control name=url_name type=text value="<?php echo set_value('url_name') ?>" placeholder="自定义域名">
				</div>
			</div>

			<div class=form-group>
				<label for=url_images class="col-sm-2 control-label">形象图</label>
                <div class=col-sm-10>
                    <?php
                    require_once(APPPATH. 'views/templates/file-uploader.php');
                    $name_to_upload = 'url_images';
                    generate_html($name_to_upload, $this->class_name, FALSE);
                    ?>

                    <p class=help-block>请上传大小在2M以内，边长不超过2048px的jpg/png图片</p>
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