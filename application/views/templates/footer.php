            <section id=beta-reminder>
                <p>RC0.16.1 当前系统仅供技术研究及开发；数据、功能、页面样式等的变更均可能不做任何告知；任何数据、业务、功能均无实际意义，不构成任何责任、义务，或权利的侵犯。</p>
            </section>

        </main>
<!-- End #maincontainer -->

		<footer id=footer role=contentinfo>
			<div id=copyright>
				<div class=container>
					<p>&copy;<?php echo date('Y') ?>

					<a title="<?php echo SITE_DESCRIPTION ?>" href="<?php echo base_url() ?>"><?php echo SITE_NAME ?></a>

					<?php if ( !empty(ICP_NUMBER)): ?>
					<a title="工业和信息化部网站备案系统" href="http://www.miitbeian.gov.cn/" target=_blank rel=nofollow><?php echo ICP_NUMBER ?></a>
					<?php endif ?>
				</div>
			</div>

			<a id=totop title="回到页首" href="#"><i class="far fa-chevron-up" aria-hidden=true></i></a>
		</footer>

		<script>
			$(function(){
				// 回到页首按钮
				$('a#totop').click(function()
				{
					$('body,html').stop(false, false).animate({scrollTop:0}, 800);
					return false;
				});
			});
		</script>
	</body>
</html>