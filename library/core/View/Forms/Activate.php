<div id="activate-form">
  <div class="left">
  <div class="form-name">activate</div>
    <form id="activateForm" name="loginForm" action="ajax.login.php" method="post">
  <input id="newPassword" name="newPassword" type="password" size="10" class="login-input" /><input id="newPasswordHidden" name="newPasswordHidden" type="text" size="10" class="login-input" /><br />
  <input id="confirmPassword" name="confirmPassword" type="password" size="10" class="login-input" /><input id="confirmPasswordHidden" name="confirmPasswordHidden" type="text" size="10" class="login-input" /><br /><input name="activateUserID" type="hidden" id="activateUserID" value="<?=$User->Info['id'];?>" /><input name="prevPassword" type="hidden" id="prevPassword" value="<?=Users::userPass($User->Info['id']);?>" />
    <div class="secondary">
      <div class="form_btn"><input type="submit" value="activate" id="send-btn" /></div>
      <div class="alt_options"><a href="javascript:void(0);" onclick="javascript:loadForm('login');"></a>&nbsp;&nbsp;</div>
    </div>
    </form>
    </div>
  </div>
  <div class="right" align="center">
	<span class="question">it's hooligan time!</span><br />
    <span class="answer">scared? <a href="javascript:void(0);" onclick="javascript:loadForm('login');">run away</a></span><br />
    <div class="explain" align="left">
    can't handle the tomfoolery, just shameful<br />
    
    </div>
    
  </div>
</div>
