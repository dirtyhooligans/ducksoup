<div id="login-form" align="right">
  <div class="left">
  <div class="form-name">Login</div>
    <form id="loginForm" name="loginForm" action="ajax.login.php" method="post">
    <input name="loginUsername" type="text" id="loginUsername" size="10" class="login-input" /><br />
    <input id="loginPassword" name="loginPassword" type="password" size="10" class="login-input" /><input id="loginPasswordHidden" name="loginPassword" type="text" size="10" class="login-input" /><br />
    
    <div class="secondary">
      <div class="form_btn"><input type="submit" value="Login" id="send-btn" onclick="javascript:sendLogin();" /></div>
      <div class="alt_options"><a href="javascript:void(0);" onclick="javascript:loadForm('forgot');">forgot?</a>&nbsp;</div>
    </div>
    </form>
  </div>
  <div class="right" align="center">
	<span class="question">not a hooligan?</span><br />
    <span class="answer"><a href="javascript:void(0);" onclick="javascript:loadForm('register');">register</a>, it's free</span><br />
    <div class="explain" align="left">
    like cake at a birthday party<br />
    but not as sticky
    </div>
    
  </div>
</div>