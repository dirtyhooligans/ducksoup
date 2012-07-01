<div align="left">
Faberge:
<pre>
<?php

$apache_user = exec('whoami');
echo '<br>';
$file_list = exec('ll');
echo '<br>';
echo $this->core->env->www_user;
echo '<br>';
echo ini_get('session.save_path');
//$db = new Db($this->core, 'adbuilder');
//print_r($db);

?>
</pre>
</div>