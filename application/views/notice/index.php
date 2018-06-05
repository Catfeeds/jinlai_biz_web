<link rel=stylesheet media=all href="/css/index.css">
<style>
    #content {background-color:transparent;}
    .notice-item {margin-bottom:20px;}

    .notice-time {font-size:24px;color:#a6a6a6;text-align:center;height:24px;line-height:24px;margin:44px auto 16px;}
    .notice-content {background-color:#fff;border:1px solid #e9e9e9;border-radius:30px;padding:38px 38px 40px;overflow:hidden;}
        .notice-content h2 {font-size:30px;color:#3f3f3f;}
        .notice-body {overflow:hidden;margin-top:18px;}
            .notice-figure {float:left;width:100px;height:100px;border-radius:10px;}
            .notice-excerpt {font-size:26px;color:#a6a6a6;margin-top:18px;max-height:96px;line-height:48px;text-overflow:ellipsis;word-break:break-all;}
                .has-figure .notice-excerpt {padding-left:134px;max-height:80px;line-height:40px;}

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

<!--<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php /*echo base_url() */?>">首页</a></li>
		<li class=active><?php /*echo $this->class_name_cn */?></li>
	</ol>
</div>-->

<div id=content class=container>
	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>未收到<?php echo $this->class_name_cn ?></p>
	</blockquote>

	<?php
        else:
            // 记录上一条信息时间
            $last_time_create = 0;

            // 根据时间间隔生成时间格式
            function generate_notice_time($time_to_compare, $time_to_display)
            {
                $time_string = '';

                // 计算当前消息与上一条消息创建时间相隔多久，以决定消息时间如何显示
                $time_diff = $time_to_compare - $time_to_display;

                if ($time_to_compare === 0 || $time_diff > 60):
                    if (date('Y-m-d') === date('Y-m-d', $time_to_display)):
                        $time_string = date('H:i', $time_to_display);
                    elseif (date('Y') === date('Y', $time_to_display)):
                        $time_string = date('m-d H:i', $time_to_display);
                    else:
                        $time_string = date('Y-m-d H:i', $time_to_display);
                    endif;
                endif;

                return $time_string;
            }
    ?>
    <ul>
        <?php
            foreach ($items as $item):

            $time_string = generate_notice_time($last_time_create, $item['time_create']);
        ?>
        <li class=notice-item>
            <?php if ( ! empty($time_string)): ?>
            <div class=notice-time><?php echo $time_string ?></div>
            <?php endif ?>

            <?php $content_class = empty($item['url_image'])? 'notice-content': 'notice-content has-figure' ?>
            <div class="<?php echo $content_class ?>">
                <?php if ( ! empty($item['article_id'])): ?>
                <a href="<?php echo base_url('article/detail?id='.$item['article_id']) ?>">
                <?php endif ?>

                    <h2><?php echo $item['title'] ?> [<?php echo $item[$this->id_name] ?>]</h2>
                    <div class="notice-body">
                        <?php if ( ! empty($item['url_image'])): ?>
                        <figure class="notice-figure centered_xy">
                            <img src="<?php echo $item['url_image'] ?>">
                        </figure>
                        <?php endif ?>

                        <?php if ( ! empty($item['excerpt'])): ?>
                        <p class=notice-excerpt><?php echo $item['excerpt'] ?></p>
                        <?php endif ?>
                    </div>

                <?php if ( ! empty($item['article_id'])): ?>
                </a>
                <?php endif ?>
            </div>

        </li>
        <?php
                // 记录当前消息创建时间
                $last_time_create = $item['time_create'];
            endforeach;
        ?>
    </ul>
	<?php endif ?>
</div>