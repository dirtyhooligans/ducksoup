<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>
<?=$this->page_title;?>
</title>
<script type="text/javascript" language="javascript">

var SiteDomain = '<?=BASE_URL;?>';
var changeForm = 'login';
<?php echo $this->core->init_js; ?>

</script>
<script type="text/javascript" src="<?=BASE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?=BASE_URL;?>/js/php.min.js"></script>
<script type="text/javascript" src="<?=BASE_URL;?>/js/login.js"></script>

<link href="<?=BASE_URL;?>/css/login.css" rel="stylesheet" type="text/css" />

</head>
<body>
<div id="main-cont" align="center">
<div id="error-cont" align="left">
	<span class="title">Error</span><br />
    <div class="reason">incorrect password,</div>
    <div class="taunt">find the sticky note your wrote it down on and try again</div>
</div>
  <div id="login-cont"></div>
</div>
<div id="login-footer">
  <div class="menu">
  about &nbsp; help
  </div>
  <div id="login-status-cont"><img src="<?=BASE_URL;?>/images/loaders/user-auth.gif" id="user-auth-loader" width="30" height="32" alt="Processing..." /><img src="<?=BASE_URL;?>/images/loaders/spinner-bg-ffffff.gif" id="progress-loader" width="16" height="16" alt="Processing..." /><span class="msg">This is where the progress message will go</span></div>
  <div class="copyright"><?=$this->core->properties['copyright']; ?></div>
</div>

</body>
</html>
