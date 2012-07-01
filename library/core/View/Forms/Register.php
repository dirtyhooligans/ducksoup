<div id="register-form" align="right">
  <div class="left">
  <div class="form-name">Register</div>
    <form id="registerForm" name="registerForm" action="ajax.register.php" method="post">
    <input name="registerUsername" type="text" id="registerUsername" size="10" class="login-input" /><br />
    <input id="registerEmail" name="registerEmail" type="text" size="10" class="login-input" /><br />
    <div class="secondary">
      <div class="form_btn"><input type="submit" value="register" id="send-btn" onclick="javascript:sendRegister();" /></div>
      <div class="alt_options">&nbsp;</div>
    </div>
    </form>
  </div>
  <div class="right" align="center">
	<span class="question">already a hooligan?</span><br />
    <span class="answer"><a href="javascript:void(0);" onclick="javascript:loadForm('login');">login</a>, it's simple</span><br />
    <div class="explain" align="left">
    like playing with a slinky<br />
    all the cool kids do it
    </div>
    
  </div>
</div>