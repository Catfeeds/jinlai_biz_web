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
		<fieldset>
			<legend>基本信息</legend>
			
			<input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

            <div class=form-group>
                <label for=parent_id class="col-sm-2 control-label">所属分类ID</label>
                <div class=col-sm-10>
                    <input class=form-control name=parent_id type=text value="<?php echo $item['parent_id'] ?>" placeholder="所属分类ID" required>
                </div>
            </div>

            <div class=form-group>
                <label for=name class="col-sm-2 control-label">名称</label>
                <div class=col-sm-10>
                    <input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="名称" required>
                </div>
            </div>

            <div class=form-group>
                <label for=url_name class="col-sm-2 control-label">自定义域名</label>
                <div class=col-sm-10>
                    <input class=form-control name=url_name type=text value="<?php echo $item['url_name'] ?>" placeholder="自定义域名">
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