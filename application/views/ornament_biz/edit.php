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
		<p class="help-block">必填项以“※”符号标示</p>

		<fieldset>
			<legend>基本信息</legend>
            <div class=form-group>
                <label for=name class="col-sm-2 control-label">方案名称</label>
                <div class=col-sm-10>
                    <input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="方案名称" required>
                </div>
            </div>
            <div class=form-group>
                <label for=content_json class="col-sm-2 control-label">JSON格式内容，10-20000个字符</label>
                <div class=col-sm-10>
                    <input class=form-control name=content_json type=text value="<?php echo $item['content_json'] ?>" placeholder="JSON格式内容，10-20000个字符" required>
                </div>
            </div>
            <div class=form-group>
                <label for=content_html class="col-sm-2 control-label">HTML格式内容</label>
                <div class=col-sm-10>
                    <input class=form-control name=content_html type=text value="<?php echo $item['content_html'] ?>" placeholder="HTML格式内容" required>
                </div>
            </div>
            <div class=form-group>
                <label for=template_id class="col-sm-2 control-label">装修模板ID</label>
                <div class=col-sm-10>
                    <input class=form-control name=template_id type=text value="<?php echo $item['template_id'] ?>" placeholder="装修模板ID" required>
                </div>
            </div>
            <div class=form-group>
                <label for=home_slides class="col-sm-2 control-label">首页轮播图内容</label>
                <div class=col-sm-10>
                    <input class=form-control name=home_slides type=text value="<?php echo $item['home_slides'] ?>" placeholder="首页轮播图内容" required>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m1_ace_url class="col-sm-2 control-label">模块一形象图URL</label>
                <div class=col-sm-10>
                    <input class=form-control name=home_m1_ace_url type=text value="<?php echo $item['home_m1_ace_url'] ?>" placeholder="模块一形象图URL" required>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m1_ace_id class="col-sm-2 control-label">模块一首推商品ID</label>
                <div class=col-sm-10>
                    <input class=form-control name=home_m1_ace_id type=text value="<?php echo $item['home_m1_ace_id'] ?>" placeholder="模块一首推商品ID" required>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m1_ids class="col-sm-2 control-label">模块一陈列商品</label>
                <div class=col-sm-10>
                    <input class=form-control name=home_m1_ids type=text value="<?php echo $item['home_m1_ids'] ?>" placeholder="模块一陈列商品" required>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m2_ace_url class="col-sm-2 control-label">模块二形象图URL</label>
                <div class=col-sm-10>
                    <input class=form-control name=home_m2_ace_url type=text value="<?php echo $item['home_m2_ace_url'] ?>" placeholder="模块二形象图URL" required>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m2_ace_id class="col-sm-2 control-label">模块二首推商品ID</label>
                <div class=col-sm-10>
                    <input class=form-control name=home_m2_ace_id type=text value="<?php echo $item['home_m2_ace_id'] ?>" placeholder="模块二首推商品ID" required>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m2_ids class="col-sm-2 control-label">模块二陈列商品</label>
                <div class=col-sm-10>
                    <input class=form-control name=home_m2_ids type=text value="<?php echo $item['home_m2_ids'] ?>" placeholder="模块二陈列商品" required>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m3_ace_url class="col-sm-2 control-label">模块三形象图URL</label>
                <div class=col-sm-10>
                    <input class=form-control name=home_m3_ace_url type=text value="<?php echo $item['home_m3_ace_url'] ?>" placeholder="模块三形象图URL" required>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m3_ace_id class="col-sm-2 control-label">模块三首推商品ID</label>
                <div class=col-sm-10>
                    <input class=form-control name=home_m3_ace_id type=text value="<?php echo $item['home_m3_ace_id'] ?>" placeholder="模块三首推商品ID" required>
                </div>
            </div>
            <div class=form-group>
                <label for=home_m3_ids class="col-sm-2 control-label">模块三陈列商品</label>
                <div class=col-sm-10>
                    <input class=form-control name=home_m3_ids type=text value="<?php echo $item['home_m3_ids'] ?>" placeholder="模块三陈列商品" required>
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