<link rel=stylesheet media=all href="/css/index.css">
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

<script defer src="/js/index.js"></script>

<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li class=active><?php echo $this->class_name_cn ?></li>
	</ol>
</div>

<div id=content class=container>
    <div class="btn-group btn-group-justified" role=group>
        <?php $style_class = empty($this->input->get('score_max') )? 'btn-primary': 'btn-default' ?>
        <a class="btn <?php echo $style_class ?>" title="全部<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">全部</a>

        <a class="btn <?php echo $this->input->get('score_max') === '3'? 'btn-primary': 'btn-default' ?>" title="中评商品订单" href="<?php echo base_url($this->class_name. '?score_max=3&score_min=2') ?>">中评</a>
        <a class="btn <?php echo $this->input->get('score_max') === '1'? 'btn-primary': 'btn-default' ?>" title="差评商品订单" href="<?php echo base_url($this->class_name. '?score_max=1') ?>">差评</a>
    </div>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>没有<?php echo (empty($this->input->get('score_max') )? NULL: '该种'). $this->class_name_cn ?></p>
	</blockquote>

	<?php else: ?>
    <form method=get target=_blank>

        <ul id=item-list class=row>
            <?php foreach ($items as $item): ?>
            <li>
                <span class=item-status><?php echo $item['score'] ?></span>
                <a href="<?php echo base_url($this->class_name.'/detail?id='.$item[$this->id_name]) ?>">
                    <p><?php echo $this->class_name_cn ?>ID <?php echo $item[$this->id_name] ?></p>
                    <p><?php echo $item['content'] ?></p>
                </a>
            </li>
            <?php endforeach ?>
        </ul>

	</form>
	<?php endif ?>
</div>