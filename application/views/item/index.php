<link rel=stylesheet media=all href="/css/index.css">
<style>
    #maincontainer {padding-bottom:98px;}

    .action_bottom {bottom:98px;}

	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px)
	{
        body {margin-bottom:0;}
        .action_bottom {bottom:0;}
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

<script>
    $(function(){
        // 分页参数
        var limit = <?php echo $this->limit ?>;
        var offset = <?php echo $this->offset ?>;
        var api_url  = '<?php echo $this->class_name.'/index' ?>';

        // 点击加载更多
        $('#load-more').click(function(){
            load_more(limit, offset, api_url);
            return false;
        });

        // 上拉加载更多
        /*
        var allow_load = true; // 是否允许可下拉加载，用于两次上拉刷新间稍作延时
        $(window).scroll(function()
        {
            if (allow_load == true)
            {
                var detail_distance = $('#load-more').offset().top;
                var trigger_position = $(this).scrollTop() + 400;

                if (trigger_position > detail_distance)
                {
                    load_more(limit, offset, api_url);

                    // TODO 避免重复加载
                }
            }
        });
        */

        /**
         * 加载更多
         *
         * @param limit 当前limit值
         * @param current_offset 当前offset值
         */
        function load_more(limit, current_offset, api_url)
        {
            // 初始化参数
            params = common_params;
            params.limit = limit;
            params.offset = current_offset + limit; // 新的偏移量等于当前偏移量加当前获取量

            // 拼合完整API路径
            api_url = ajax_root + api_url;

            // AJAX获取结果并生成相关HTML
            $.post(
                api_url,
                params,
                function(result)
                {
                    console.log(result); // 输出回调数据到控制台

                    if (result.status == 200)
                    {
                        //console.log(result.status);

                        var content = result.content

                        var list_html = generete_list(content);

                        $('#item-list').append(list_html);

                        // 更新全局分页参数
                        offset = params.offset;
                    }
                    else
                    {
                        // 若失败，进行提示
                        alert(result.content.error.message);
                    }
                },
                "JSON"
            );
        } // end load_more

        /**
         * 生成列表内容
         *
         * @param items 内容数组
         */
        function generete_list(items)
        {
            //console.log(items);

            // 初始化列表内容HTML
            var list_html = '';

            // 各列表项详情页根路径
            var item_root_url = '<?php echo base_url($this->class_name.'/detail?id=') ?>';

            // 生成各列表项HTML
            $.each(
                items,
                function(i, item) {
                    //console.log(item);

                    // 初始化当前列表项HTML
                    var item_html = '';
                    var item_id = item.<?php echo $this->id_name ?>;

                    // 生成列表项HTML
                    item_html = '<span class=item-status>' + item.status + '</span>' +
                        '<a href="' + item_root_url + item_id + '">' +
                        '   <p>ID ' + item_id +
                        (item.code_biz !== ''? ' / 货号 '+item.code_biz : '') +
                        '   </p>' +
                        '   <p>' + item.name + '</p>' +
                        '   <p>' +
                        '￥' + item.price +
                        (item.tag_price !== '0.00'? '<del>'+item.tag_price+'</del>': '') +
                        '   </p>' +
                        '</a>';

                    // 将当前列表项追加到列表内容中
                    item_html = '<li>' + item_html + '</li>';
                    list_html += item_html;
                }
            );

            // 生成列表项内容
            //console.log(list_html);
            return list_html;
        }
    });
</script>
<script defer src="/js/index.js"></script>

<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li class=active><?php echo $this->class_name_cn ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class="btn-group btn-group-justified" role=group>
        <?php $style_class = empty($this->input->get('status') )? 'btn-primary': 'btn-default'; ?>
        <a class="btn <?php echo $style_class ?>" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>">所有</a>
        <a class="btn <?php echo $this->input->get('status') === 'publish'? 'btn-primary': 'btn-default' ?>" title="已上架商品" href="<?php echo base_url('item?status=publish') ?>">在售中</a>
        <a class="btn <?php echo $this->input->get('status') === 'suspend'? 'btn-primary': 'btn-default' ?>" title="已下架商品" href="<?php echo base_url('item?status=suspend') ?>">已下架</a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>">回收站</a>
	</div>

    <div id=primary_actions class=action_bottom>
        <?php if (count($items) > 1): ?>
        <span id=enter_bulk>
            <i class="far fa-edit"></i>批量
        </span>
        <?php endif ?>

        <ul class=horizontal>
            <li>
                <a class=bg_second title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="far fa-plus"></i></a>
            </li>
            <li>
                <a class=bg_primary title="快速创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create_quick') ?>"><i class="far fa-plus"></i><sub><i class="far fa-bolt"></i></sub></a>
            </li>
        </ul>
    </div>
	<?php endif ?>

	<?php if ( empty($this->session->biz_id) ): ?>
	<blockquote>
		<p>您需要成为已入驻企业的员工，或者提交入驻申请，才可进行商品管理</p>
	</blockquote>

	<?php else: ?>
		<?php if ( empty($items) ): ?>
		<blockquote class=row>
			<p>您的货架空空如也，快点添加商品吧！</p>
		</blockquote>

		<?php else: ?>
		<form method=get target=_blank>
            <?php if (count($items) > 1): ?>
            <div id=bulk_action class=action_bottom>
                <span id="bulk_selector" data-bulk-selector=off>
                    <i class="far fa-circle"></i>全选
                </span>
                <span id=exit_bulk>取消</span>
                <ul class=horizontal>
                    <li>
                        <button class=bg_third formaction="<?php echo base_url($this->class_name.'/publish') ?>" type=submit>上架</button>
                    </li>
                    <li>
                        <button class=bg_second formaction="<?php echo base_url($this->class_name.'/suspend') ?>" type=submit>下架</button>
                    </li>
                    <li>
                        <button class=bg_primary formaction="<?php echo base_url($this->class_name.'/delete') ?>" type=submit>删除</button>
                    </li>
                </ul>
            </div>
            <?php endif ?>

            <ul id=item-list>
                <?php foreach ($items as $item): ?>
                <li>
                    <span class=item-status><?php echo $item['status'] ?></span>
                    <img src="<?php echo $item['url_image_main'] ?>" style="display: inline;float: left;width:10%;" />
                    <a href="<?php echo base_url($this->class_name.'/detail?id='.$item[$this->id_name]) ?>">
                        <p>
                            <?php echo $this->class_name_cn.'ID '.$item[$this->id_name] ?>
                            <?php if ( ! empty($item['code_biz'])) echo ' / 货号'.$item['code_biz'] ?>
                        </p>
                        <p><?php echo $item['name'] ?></p>
                        <p>
                            ￥<?php echo $item['price'] ?>
                            <?php if ($item['tag_price'] !== '0.00') echo '<del>￥ '.$item['tag_price'].'</del>' ?>
                                库存：<?=  '<strong> ' . $item['stocks'] .'</strong>' ?>
                        </p>
                    </a>

                    <div class=item-actions>
                        <span>
                            <input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
                        </span>

                        <ul class=horizontal>
                            <li><a href="<?php echo base_url('sku/index?item_id='.$item['item_id']) ?>" target=_blank>规格 <i class="far fa-angle-right"></i></a></li>

                        <?php
                            // 需要特定角色和权限进行该操作
                            if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
                        ?>
                            <?php if ( empty($item['time_delete']) ): ?>
                            <li><a href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank>删除</a></li>
                            <?php endif ?>

                            <?php if ( empty($item['time_publish']) ): ?>
                            <li><a href="<?php echo base_url($this->class_name.'/publish?ids='.$item[$this->id_name]) ?>" target=_blank>上架</a></li>
                            <?php else: ?>
                            <li><a href="<?php echo base_url($this->class_name.'/suspend?ids='.$item[$this->id_name]) ?>" target=_blank>下架</a></li>
                            <?php endif ?>

                            <li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank>编辑</a></li>
                        <?php endif ?>
                        </ul>
                    </div>

                </li>
                <?php endforeach ?>
            </ul>

		</form>

            <?php if (count($items) >= $this->limit): ?>
            <a
                id=load-more
                href="#"
                data-limit-current="<?php echo $this->limit ?>"
                data-offset-current="<?php echo $this->offset ?>"
            >点击或上拉载入更多</a>
            <?php endif ?>
		<?php endif ?>

	<?php endif ?>
</div>