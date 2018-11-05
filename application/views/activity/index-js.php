<script type="text/javascript">
	$(function(){
		let status = "<?php echo $status;?>";
		$("." + status).addClass("active");

		
		$(".cancel_coupon").on('click', function(){
			let id = $(this).attr('data');
			console.log(id)
			$.get('/activity/cancel?id=' + id, function(res){
				if (res == 'success') {
					alert('操作成功');
					location.reload();
				}
				
				
			});
		})
	})
</script>
	</body>
</html>