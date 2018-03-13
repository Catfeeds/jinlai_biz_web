<?php
/**
 * 对结束时间填写方式的提示，一般用于表单中
 */
?>
<p class=help-block>请填写详细到分钟的时间，例如：<?php echo date('Y-m-d H:i', strtotime('+31days')) ?>；若留空，则默认为开始30天后结束</p>