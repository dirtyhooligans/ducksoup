<?php Error::user('message', 'description');?>
<div id="debug">
	<pre><?php print_r(Error::getErrors())?></pre>
</div>