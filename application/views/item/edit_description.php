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
    <section id=item-info class=row>
        <a href="<?php echo base_url('item/detail?id='.$item['item_id']) ?>">
            <figure class="col-xs-4">
                <img src="<?php echo MEDIA_URL.'/item/'.$item['url_image_main'] ?>">
            </figure>

            <div class="col-xs-8">
                <h3><?php echo $item['name'] ?></h3>
                <p>￥<?php echo $item['price'] ?></p>
            </div>
        </a>
    </section>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit_description?id='.$item[$this->id_name], $attributes);
	?>
		<fieldset>
			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">商品描述</label>
				<div class=col-sm-10>
					<textarea id=detail_editior name=description rows=10 placeholder="可选，不超过20000个字符"><?php echo empty(set_value('description'))? $item['description']: set_value('description') ?></textarea>

                    <?php
                    require_once(APPPATH. 'views/templates/simditor.php');
                    $name_to_upload = 'description';
                    ?>
                    <script>
                        $(function(){
                            var editor = new Simditor({
                                textarea: $('textarea[name=description]'), // 若只使用属性选择器，有可能误选中meta等其它含有相应属性的DOM
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