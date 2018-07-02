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

<!--<script defer src="/js/create.js"></script>-->

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
				<label for=title class="col-sm-2 control-label">名称 ※</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="最多30个字符"  required>
				</div>
			</div>
            <div class=form-group>
                <label for=title class="col-sm-2 control-label">页面URL</label>
                <div class=col-sm-10>
                    <input class=form-control name=url_name type=text value="<?php echo set_value('url_name') ?>" placeholder="最多30个字符">
                </div>
            </div>
            <div class=form-group>
                <label for=title class="col-sm-2 control-label">说明</label>
                <div class=col-sm-10>
                    <input class=form-control name=url_name type=text value="<?php echo set_value('url_name') ?>" placeholder="最多255个字符">
                </div>
            </div>
            <div class=form-group>
                <label for=title class="col-sm-2 control-label">内容形式</label>
                <div class=col-sm-10>
                    <!-- <input class=form-control name=url_name type=text value="<?php echo set_value('url_name') ?>" placeholder="最多30个字符"  required> -->
                    <select class=form-control name=url_name>
                        <option></option>
                        <option value="HTML">HTML</option>
                        <option value="文件">文件</option>
                    </select>
                </div>
            </div>
      
            <div class=form-group>
                <label for=title class="col-sm-2 control-label">页面文件</label>
                <div class=col-sm-10>
                    <input class=form-control name=content_file type=text value="<?php echo set_value('content_file') ?>" placeholder="最多30个字符" >
                </div>
            </div>
			<div class=form-group>
                <label for=title class="col-sm-2 control-label">相关商品id</label>
                <div class=col-sm-10>
                     <textarea class=form-control name=item_ids rows=5 placeholder="最多255个字符"><?php echo set_value('item_ids') ?></textarea>
                </div>
            </div>
			<div class=form-group>
				<label for=content_html class="col-sm-2 control-label">内容 ※</label>
				<div class=col-sm-10>
                    <textarea class=form-control name=content_html rows=10 placeholder="10 - 20000个字符" ><?php echo set_value('content_html') ?></textarea>

                    <?php
                    require_once(APPPATH. 'views/templates/simditor.php');
                    $name_to_upload = 'content_html';
                    ?>
                    <script>
                        $(function(){
                            var editor = new Simditor({
                                textarea: $('textarea[name=content_html]'), // 若只使用属性选择器，有可能误选中meta等其它含有相应属性的DOM
                                placeholder: '10 - 20000个字符',
                                toolbar: ['title', 'bold', 'italic', 'underline', 'strikethrough', 'fontScale', 'color', '|', 'hr', 'ol', 'ul', 'blockquote', 'table', '|', 'link', 'image', '|', 'indent', 'outdent', 'alignment'],
                                cleanPaste: true,
                                upload: {
                                    url: '<?php echo base_url('/simditor?target='.$this->class_name.'/'.$name_to_upload) ?>',
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

		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>

</div>