<script type="text/javascript">
	$(function(){
		var isRequesting = false;
		var vcode = '', rid = '', oid='';
		$("#verify_code").on('keyup', function(){
			$("#alertmsg").hide();
			$("#verify").hide();
			if(this.value.length == 10 && !isRequesting){
				isRequesting = true;
				vcode = this.value;
				$.getJSON('/salor/ajaxdetail?verify_code=' + vcode, function(res){
					console.log(res['status'])
					isRequesting = false;
					if (res['status'] == '200'){
						$("#mark").html(res['html']);
						if (res['allowcheck'] == 'yes') {
							$("#verify").show();
							rid = res['rid'];
							oid = res['oid'];
							console.log(res)
							allow()
						} else {
							$("#errmsg").text(res['msg']);
							$("#alertmsg").show();
						}
					} else {
						$("#errmsg").text(res['msg']);
						$("#alertmsg").show();
					}
				})
			} else {
				$("#mark").html('')
			}
		})
		function allow(){
			$("#confirmsub").on('click', function(){
				console.log(vcode)
				if(vcode){
					$.get('/salor/confirm?verify_code=' + vcode + '&oid=' + oid, function(res){
						if (res == 'success'){
							window.location = '<?php echo BASE_URL("salor/detail?done=1&record_id=") ?>' + rid
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