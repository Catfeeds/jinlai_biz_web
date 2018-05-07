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
				<label for=url_image_main class="col-sm-2 control-label">主图</label>
				<div class=col-sm-10>
                    <?php
                    require_once(APPPATH. 'views/templates/file-uploader.php');
                    $name_to_upload = 'url_image_main';
                    generate_html($name_to_upload, $this->class_name);
                    ?>
				</div>
			</div>

			<div class=form-group>
				<label for=figure_image_urls class="col-sm-2 control-label">形象图</label>
				<div class=col-sm-10>
                    <?php
                    require_once(APPPATH. 'views/templates/file-uploader.php');
                    $name_to_upload = 'url_image_main';
                    generate_html($name_to_upload, $this->class_name, FALSE, 4);
                    ?>
				</div>
			</div>
			
			<div class=form-group>
				<?php $input_name = 'delivery' ?>
				<label for="<?php echo $input_name ?>" class="col-sm-2 control-label">库存状态</label>
				<div class=col-sm-10>
                    <select class=form-control name="<?php echo $input_name ?>" required>
						<option value="" <?php echo set_select($input_name, '') ?>>请选择</option>
						<?php
							$options = array('现货','期货');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>><?php echo $option ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			
            <div class=form-group>
				<?php $input_name = 'home_m1_ace_id' ?>
                <label for="<?php echo $input_name ?>" class="col-sm-2 control-label">模块一首推商品</label>
                <div class=col-sm-10>
                    <select class=form-control name="<?php echo $input_name ?>">
                        <?php
                        $options = $comodities;
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                                ?>
                                <option value="<?php echo $option['item_id'] ?>" <?php echo set_select($input_name, $option['item_id']) ?>><?php echo $option['name'] ?></option>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </select>

                    <p class=help-block>点击形象图后跳转到的商品，下同</p>
                </div>
            </div>
			
            <div class=form-group>
				<?php $input_name = 'home_m1_ids[]' ?>
                <label for="<?php echo $input_name ?>" class="col-sm-2 control-label">模块一陈列商品</label>
                <div class=col-sm-10>
                    <select class=form-control name="<?php echo $input_name ?>" multiple>
                        <?php
                        $options = $comodities;
                        $current_array = $this->input->post($input_name);
                        foreach ($options as $option):
                            if ( empty($option['time_delete']) ):
                        ?>
						<option value="<?php echo $option['item_id'] ?>" <?php if ( in_array($option['item_id'], $current_array) ) echo 'selected'; ?>><?php echo $option['name'] ?></option>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </select>

                    <p class=help-block>需要进行展示的1-3款商品，下同；桌面端按住Ctrl或⌘键可多选；如果选择了3款以上，将仅示前3款</p>
                </div>
            </div>
			
			<div class=form-group>
				<label for=private class="col-sm-2 control-label">需登录</label>
				<div class=col-sm-10>
					<label class=radio-inline>
						<input type=radio name=private value="是" required <?php echo set_radio('private', '是', TRUE) ?>> 是
					</label>
					<label class=radio-inline>
						<input type=radio name=private value="否" required <?php echo set_radio('private', '否') ?>> 否
					</label>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<div class=form-group>
				<label for=article_id class="col-sm-2 control-label">相关文章ID</label>
				<div class=col-sm-10>
					<input class=form-control name=article_id type=text value="<?php echo set_value('article_id') ?>" placeholder="相关文章ID" required>
				</div>
			</div>
			<div class=form-group>
				<label for=app_type class="col-sm-2 control-label">目标客户端类型</label>
				<div class=col-sm-10>
					<input class=form-control name=app_type type=text value="<?php echo set_value('app_type') ?>" placeholder="目标客户端类型" required>
				</div>
			</div>
			<div class=form-group>
				<label for=user_id class="col-sm-2 control-label">用户ID</label>
				<div class=col-sm-10>
					<input class=form-control name=user_id type=text value="<?php echo set_value('user_id') ?>" placeholder="用户ID" required>
				</div>
			</div>
			<div class=form-group>
				<label for=biz_id class="col-sm-2 control-label">商家ID</label>
				<div class=col-sm-10>
					<input class=form-control name=biz_id type=text value="<?php echo set_value('biz_id') ?>" placeholder="商家ID" required>
				</div>
			</div>
			<div class=form-group>
				<label for=title class="col-sm-2 control-label">标题</label>
				<div class=col-sm-10>
					<input class=form-control name=title type=text value="<?php echo set_value('title') ?>" placeholder="标题" required>
				</div>
			</div>
			<div class=form-group>
				<label for=excerpt class="col-sm-2 control-label">摘要</label>
				<div class=col-sm-10>
					<textarea class=form-control name=excerpt rows=5 placeholder="摘要" required><?php echo set_value('excerpt') ?></textarea>
				</div>
			</div>
			<div class=form-group>
				<label for=url_image class="col-sm-2 control-label">形象图</label>
				<div class=col-sm-10>
					<input class=form-control name=url_image type=text value="<?php echo set_value('url_image') ?>" placeholder="形象图" required>
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