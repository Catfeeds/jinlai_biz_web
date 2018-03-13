<?php
/**
 * 对开始时间填写方式的提示，一般用于表单中
 */
?>
<p class=help-block>请填写详细到分钟的时间，例如：<?php echo date('Y-m-d H:i', strtotime('+1days')) ?>；若留空，则默认为立即开始</p>