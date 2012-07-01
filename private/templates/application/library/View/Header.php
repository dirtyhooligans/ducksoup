<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>
<?=$this->page_title;?>
</title>
<script type="text/javascript" language="javascript">
	var first_visit = false;
	<?=$this->core->init_js;?>
	<?php
	if( Auth::verify())
	{
	        $js_user = array();
	        $js_user['id']    = $this->user['id'];
	        $js_user['dname'] = $this->user['dname'];
	        echo 'var active_user = '.json_encode($js_user).';';
	}
	?>
				
</script>
<script type="text/javascript" src="<?=BASE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?=BASE_URL;?>/js/jquery.swfobject.js"></script>

<script type="text/javascript" src="<?=BASE_URL.'/app/'.$this->app['app'];?>/js/main.js"></script>
<script type="text/javascript" src="<?=BASE_URL.'/app/'.$this->app['app'];?>/js/footer.js"></script>

<?php 
if( Auth::verify() )
{
?>
<script type="text/javascript" src="<?=BASE_URL;?>/app/im/js/im.js"></script>
<?php 
}
else
{
?>
<script type="text/javascript" src="<?=BASE_URL;?>/js/php.min.js"></script>
<script type="text/javascript" src="<?=BASE_URL;?>/js/login.js"></script>
<?php 
}
?>
<?php 
echo Bootstrap::headerInclude($this->header_includes);
?>

<link href="<?=BASE_URL.'/app/'.$this->app['app'];?>/css/main.css" rel="stylesheet" type="text/css" />

</head>
<body>
<div id="header" class="header">
	<div class="logo"><a href="<?=BASE_URL;?>"><img src="<?=BASE_URL;?>/app/nexus/images/header-logo.png" /></a></div>
</div>

	<div id="main-cont" align="center">
	
	
		<div id="page-cont">
