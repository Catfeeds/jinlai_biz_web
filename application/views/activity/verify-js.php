<script type="text/javascript">
	$(function(){
		var isRequesting = false;
		var vcode = '', rid = '';
		$("#verify_code").on('keyup', function(){
			$("#alertmsg").hide();
			$("#verify").hide();
			if(this.value.length == 10 && !isRequesting){
				isRequesting = true;
				vcode = this.value;
				getdetail(true)
			} else {
				$("#mark").html('')
			}
		});
		function getdetail(showerr){
			$.getJSON('/activity/ajaxdetail?verify_code=' + vcode, function(res){
				console.log(res['status'])
				isRequesting = false;
				if (res['status'] == '200'){
					$("#mark").html(res['html']);
					if (res['allowcheck'] == 'yes') {
						$("#verify").show();
						rid = res['rid'];
						allow()
					} else {
						if (showerr) {
							$("#errmsg").text(res['msg']);
							$("#alertmsg").show();
						}
						
					}
				} else {
					$("#errmsg").text(res['msg']);
					$("#alertmsg").show();
				}
			})
		}
		function allow(){
			$("#confirmsub").on('click', function(){
				console.log(vcode)
				if(vcode){
					$.get('/activity/confirm?verify_code=' + vcode, function(res){
						if (res == 'success') {
							$("#verify").hide();
							$("#successmsg").show();
							getdetail(false)
						} else {
							$("#errmsg").text('核销失败,请稍后重试');
							$("#alertmsg").show();
						}
						
						
					});
				}
			})
		}
		
	})
</script>
	</body>
</html>