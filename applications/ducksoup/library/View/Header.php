<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$this->page_title;?></title>
<script type="text/javascript" language="javascript">
	var first_visit = false;
	<?=$this->core->init_js;?>
	<?php
	if( Auth::verify() )
	{
	        $js_user = array();
	        $js_user['id']    = $this->user['id'];
	        $js_user['dname'] = $this->user['dname'];
	        echo 'var active_user = '.json_encode($js_user).';';
	}
	?>		
</script>
<link href="<?=BASE_URL.'/app/'.$this->app['app'];?>/css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header">
    <a href="<?=BASE_URL;?>"><img src="<?=APP_BASE_URL;?>/images/top-header-logo.png" height="68" width="200" /></a>
</div>
<div id="top-menu">
	<ul>
		<li><a href="<?=APP_BASE_URL;?>/admin/apps" />Applications</a></li>
		<li><a href="<?=APP_BASE_URL;?>/admin/settings" />Settings</a></li>
	</ul>
</div>
<div id="page-wrapper">
	<div id="page-cont">
