// JiWire:Labs Login
// 2009.06.29 v 0.1.0

var usernameDefault    = "username/email";
var passwordDefault    = "password";
var forgotunameDefault = "email, username or id";

var registerunameDefault = "username";
var registeremailDefault = "email";

var loginDestination = base_url + '/user/validate';

var progressLineBreak = "<br> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";

var forgotSentMsg = 'A new password as been sent <br>to your E-Mail. <span style="font-size:0.9em;">* it might take a couple minutes for the E-Mail to get to you, do some jumping jacks if you get bored</span>';

var sending_reg    = false;
var sending_login  = false;
var sending_forgot = false;

$(document).ready(function (){

	
	
	$("#forgotUsername").focus(function() {
		$(this).css("color","#5a5a5a");
		if($(this).val() == forgotunameDefault)
		{
		  $(this).val('');
		}
	});
	$("#forgotUsername").blur(function() {
		$(this).css("color","#c0c0c0");
		if($(this).val() == '')
		{
		  $(this).val(forgotunameDefault);
		}
	});
	
	$('form[name=activateForm]').submit(function () {
		sendActivate();
		return false;			
	 });
	
	$('form[name=forgotForm]').submit(function () {
	 	sendForgot();
		return false;			
	 });
	
	$('form[name=registerForm]').submit(function () {
	 	sendRegister();
		return false;			
	 });
	
	$("#loginForm").hide();
	$("#activateForm").hide();
	$("#forgotForm").hide();
	
	
	$("#login-status-cont").hide();
	
	$("#user-auth-loader").hide();
	$("#progress-loader").hide();
	
	$("#error-cont").hide();
	//reportAuthError('', '', '');
	
	
	setActiveForm(changeForm);
	
	loadForm('login');
	
});

function initLoginForm()
{
	$("#loginPassword").hide();
	$("#newPassword").hide();
	$("#confirmPassword").hide();	
	
	$("#loginUsername").css("color","#c0c0c0");
	$("#loginPasswordHidden").css("color","#c0c0c0");
	$("#loginUsername").val(usernameDefault);
	$("#loginPasswordHidden").val(passwordDefault);
	
		$("#loginUsername").focus(function() {
		$(this).css("color","#5a5a5a");
		if($(this).val() == usernameDefault)
		{
		  $(this).val('');
		}
	});
	$("#loginUsername").blur(function() {
		$(this).css("color","#c0c0c0");
		if($(this).val() == '')
		{
		  $(this).val(usernameDefault);
		}
	});	
	
	$("#loginPasswordHidden").focus(function() {
		$("#loginPasswordHidden").hide();
		$("#loginPassword").show();
		$("#loginPassword").focus();
		if($("#loginPassword").val() == passwordDefault)
		{
		  $("#loginPassword").val('');  
		}
	});
	
	$("#loginPassword").blur(function() {
		if($(this).val() == '')
		{
		  $("#loginPasswordHidden").show();
		  $("#loginPassword").hide();
		  $("#loginPasswordHidden").val(passwordDefault);
		}
		else
		{
		  $(this).css("color","#c0c0c0");
		}
	});	
	
	$("#loginPassword").focus(function() {
		$(this).css("color","#5a5a5a");
		if($("#loginPassword").val() == passwordDefault)
		{
		  $("#loginPassword").val('');  
		}
	});
	
	$('#loginForm').submit(
		function (){
			
			sendLogin();
		
			return false;			
	 });

}

function initActivateForm(user_id)
{
	$("#newPassword").hide();
	$("#confirmPassword").hide();
	
	$("#newPasswordHidden").css("color","#c0c0c0");
	$("#newPasswordHidden").val('new password');
	$("#confirmPasswordHidden").css("color","#c0c0c0");
	$("#confirmPasswordHidden").val('confirm password');
	
	//activate new password
	$("#newPasswordHidden").focus(function() {
		$("#newPasswordHidden").hide();
		$("#newPassword").show();
		$("#newPassword").focus();
		if($("#newPassword").val() == 'new password')
		{
		  $("#newPassword").val('');  
		}
	});
	
	$("#newPassword").blur(function() {
		if($(this).val() == '')
		{
		  $("#newPasswordHidden").show();
		  $("#newPassword").hide();
		  $("#newPasswordHidden").val('new password');
		}
		else
		{
		  $(this).css("color","#c0c0c0");
		}
	});	
	
	$("#newPassword").focus(function() {
		$(this).css("color","#5a5a5a");
		if($("#newPassword").val() == 'new password')
		{
		  $("#newPassword").val('');  
		}
	});
	
	//activate confirm password
	$("#confirmPasswordHidden").focus(function() {
		$("#confirmPasswordHidden").hide();
		$("#confirmPassword").show();
		$("#confirmPassword").focus();
		if($("#confirmPassword").val() == 'confirm password')
		{
		  $("#confirmPassword").val('');  
		}
	});
	
	$("#confirmPassword").blur(function() {
		if($(this).val() == '')
		{
		  $("#confirmPasswordHidden").show();
		  $("#confirmPassword").hide();
		  $("#confirmPasswordHidden").val('confirm password');
		}
		else
		{
		  $(this).css("color","#c0c0c0");
		}
	});	
	
	$("#confirmPassword").focus(function() {
		$(this).css("color","#5a5a5a");
		if($("#confirmPassword").val() == 'confirm password')
		{
		  $("#confirmPassword").val('');  
		}
	});
	
	$('#activateUserID').val(user_id);
	
	$('#activateForm').submit(
		function (){
			
			sendActivate();
		
			return false;			
	 });
	
}

function initForgotForm()
{
	$("#forgotUsername").css("color","#c0c0c0");
	$("#forgotUsername").val(forgotunameDefault);
	
	$("#forgotUsername").focus(function() {
		$(this).css("color","#5a5a5a");
		if($(this).val() == forgotunameDefault)
		{
		  $(this).val('');
		}
	});
	$("#forgotUsername").blur(function() {
		$(this).css("color","#c0c0c0");
		if($(this).val() == '')
		{
		  $(this).val(forgotunameDefault);
		}
	});	
	
	$('#forgotForm').submit(
		function (){
			
			sendForgot();
		
			return false;			
	});
}

function initRegisterForm()
{
	$("#registerUsername").css("color","#c0c0c0");
	$("#registerUsername").val(registerunameDefault);
	
	$("#registerEmail").css("color","#c0c0c0");
	$("#registerEmail").val(registeremailDefault);
	
	$("#registerUsername").focus(function() {
		$(this).css("color","#5a5a5a");
		if($(this).val() == registerunameDefault)
		{
		  $(this).val('');
		}
	});
	$("#registerUsername").blur(function() {
		$(this).css("color","#c0c0c0");
		if($(this).val() == '')
		{
		  $(this).val(registerunameDefault);
		}
	});	
	
	$("#registerEmail").focus(function() {
		$(this).css("color","#5a5a5a");
		if($(this).val() == registeremailDefault)
		{
		  $(this).val('');
		}
	});
	$("#registerEmail").blur(function() {
		$(this).css("color","#c0c0c0");
		if($(this).val() == '')
		{
		  $(this).val(registeremailDefault);
		}
	});	
	
	$('#registerForm').submit(
		function (){
			
			sendRegister();
		
			return false;			
	 });
}

function sendLogin()
{
	if (! sending_login )
	{
		sending_login = true;
		validated = validateLoginForm();
		
		if(validated)
		{
			
			$("#login-status-cont .msg").html("Please hold for the goodness...");
			
			var passwd = $('[name=loginPassword]').val();
			var passwde = md5(passwd) ;
			
			$("#user-auth-loader").show();
			$("#progress-loader").hide();
			
			$("#loginForm").fadeOut("fast", 
									function(){
										$("#login-status-cont").fadeIn("slow", 
																			function(){
				$.post(SiteDomain + "/ajax/action/login", 
							{ 
							uname: $('[name=loginUsername]').val(), 
							pass: passwde
							}, 
							function(data) {
								
								
								if(data.error)
								{
									if(data.code == 'E002')
									{
										$('[name=loginPassword]').val('');
									}
									$("#error .reason").html(data.description).fadeIn();
									reportAuthError(data.description, "either you put in your info wrong or you don't exist.  whoa... deep.", 'error');
									$("#loginForm").fadeIn("fast");
									
									$("#login-cont .progress").fadeOut("slow", function(){$("#loginForm").fadeIn("slow");});
									
								}
								else
								{
									if(data.active == '1')
									{
										$("#login-cont").fadeOut("slow", function(){window.location = loginDestination;});
									}
									else
									{	
										loadForm('activate', data.id);
										
										
									}
								}
								sending_login = false;
							},
							"json"
								
				); //end jquery post
			});});
			
		} // end if validated
		else
		{
			sending_login = false;
		}
	}
	else
	{
		return;
	}

}

function validateLoginForm()
{
	
	if($('#loginUsername').val().length < 4 || $('#loginUsername').val() == usernameDefault)
	{
		reportAuthError("Invalid Username Entry", "it's not difficult to fail on this, so congradulations for failing at something simple (min 4 chars, btw)");
		return false;
	}
	else if($('#loginPassword').val().length < 4)
	{
		reportAuthError("Invalid Password Entry", "uh... wow, try again turbo (min 4 chars, btw)");
		return false;
	}
	else
	{
		return true;
	}
}

function sendRegister()
{
	if (! sending_reg )
	{
		sending_reg = true;
		validated = validateRegisterForm();
		
		if(validated)
		{
			$("#error-cont").fadeOut('fast');
			
			$("#login-status-cont .msg").html("Setting up new hooligan...");
			
			
			$("#user-auth-loader").show();
			$("#progress-loader").hide();
		
			$("#registerForm").fadeOut("fast", 
									function(){
										$("#login-status-cont").fadeIn("slow", 
																			function(){
				$.post(SiteDomain + "/ajax/action/register", 
							{ 
							uname: $('[name=registerUsername]').val(), 
							email: $('[name=registerEmail]').val()
							}, 
							function(data) {
								
								$("#login-status-cont .msg").fadeOut("slow", 
									function(){ 
										if(data.error)
										{
											if(data.code == 'E002')
											{
												$('[name=registerUsername]').val('');
											}
											
											reportAuthError(data.description, data.code);
											
											$("#registerForm").fadeIn("fast");

										}
										else
										{
											if ( data.id )
											{	
												loadForm('login');
												reportAuthError('Check your E-Mail', "be sure to check your spam, because that's what hooligans eat (no worries though because we don't share)", 'Registration Sent!');
												
											}
											else
											{
												reportAuthError('no clue', "not sure how you got this error, but please feel free to tell us, because you shouldn't see it");
											}
										}
								});
								sending_reg = false;
							},
							"json"
								
				); //end jquery post
			});});
			
		} // end if validated
		else
		{
			sending_reg = false;
		}
	}
	else
	{
		return;
	}

}

function validateRegisterForm()
{
	email = $('#registerEmail').val();
	
	email_pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
	
	if($('#registerUsername').val().length < 4 || $('#registerUsername').val() == registerunameDefault)
	{
		
		reportAuthError("Invalid Username", "alright, here's the deal, you need a username to be made fun of by, so provide one (min 4 chars, btw)");
		return false;
	}
	else if(! email_pattern.test(email) )
	{
		
		reportAuthError("Invalid Email", "a properly formatted email is something we like to refer as an important piece of information");
		return false;
	}
	else
	{
		return true;
	}
}

function sendActivate()
{
		
	var validated = validateActivateForm();
	
	if(validated)
	{
		$("#error-cont").fadeOut('fast');
		
		var opasse = $('#prevPassword').val();
		var npasse = $('#newPassword').val();
		var cpasse = md5($('#confirmPassword').val()) ;	
	
		$("#login-status-cont .msg").html("Updating your password, " + progressLineBreak + " then on to the goodness...");

		$("#activateForm").fadeOut("fast", 
							function(){
								$("#login-status-cont").fadeIn("slow", 
																	function(){

			$.post(SiteDomain + "/ajax/activate", { 
						uid: $('#activateUserID').val(), 
						opass: opasse,
						npass: npasse,
						cpass: cpasse
						}, 
						function(data) {
							if(data.error)
							{
								if(data.code == 'E002')
								{
									$('[name=loginPass]').val('');
								}
								reportAuthError(data.description, data.code);
								
								$("#login-cont .progress").fadeOut("slow", function(){$("#loginForm").fadeIn("slow");});
								
								loadForm('login');
								
							}
							else
							{
								window.location = loginDestination;
							}
							
						}, "json");
			});
				
		});	
	}
	return false;
}


function validateActivateForm()
{
	
	if($('#newPassword').val().length < 4)
	{
		reportAuthError("entry too short", "easy napoleon, we're talking about the character length (4 char min)");
		return false;
	}
	else if($('#confirmPassword').val() != $('#newPassword').val())
	{
		reportAuthError("Passwords don't match", "we're out of quarters to flip and make the decision for you");
		return false;
	}
	else
	{
		return true;
	}
}

function sendForgot()
{
		
	var validated = validateForgotForm();

	if(validated)
	{
		sending_forgot = true;
		
		$("#error-cont").fadeOut('fast');
		
		$("#user-auth-loader").show();
		$("#progress-loader").hide();
		$("#login-status-cont .msg").html("Looking up hooligan...");

		$("#forgotForm").fadeOut("fast", 
						function(){
							$("#login-status-cont").fadeIn("slow", 
																function(){

			$.post(SiteDomain + "/ajax/forgot", { 
						uid: $('#forgotUsername').val()
						}, 
						function(data) {
							
							if(data.error)
							{
								
								loadForm('forgot');
								
								reportAuthError(data.description, data.code);

							}
							else
							{
								loadForm('login');
								
								reportAuthError("check your email", forgotSentMsg, "new login info sent!");
								
							}
							sending_forgot = false;
						}, "json");
			});
				
		});	
		
	}
	else
	{
		sending_forgot = false;
	}
	return false;	
}

function validateForgotForm()
{
	if($('#forgotUsername').val().length < 3 || $('#forgotUsername').val() == forgotunameDefault)
	{
		reportAuthError("Invalid Entry", "it's a simple concept really, email, username or user id... baby steps");
		return false;
	}
	else
	{
		return true;	
	}
}

function setActiveForm(form)
{
	if(changeForm != form)
	{
		$("#"+changeForm+"Form").fadeOut("fast", function(){  });	
	}
	if(form == 'activate')
	{
		if($('#loginPassword').val() == "")
		{
			changeForm = form;
			setActiveForm('login');
			return true;
		}
		else
		{
			if(changeForm != form)
			{
				$("#"+changeForm+"Form").fadeOut("slow", function(){
								$("#"+form+"Form").fadeIn("fast");
							});	
			}
			else
			{
				$("#loginForm").hide();
				$("#forgotForm").hide();
			}
			
			$("#sendLoginBtn").html('');
			$("#sendForgotBtn").html('');
			$("#sendActivateBtn").html('<input type="submit" value="Send" style="display:none;" />');
		}
	}
	else if(form == 'forgot')
	{
		if(changeForm != form)
		{
			$("#"+changeForm+"Form").fadeOut("slow", function(){
							$("#"+form+"Form").fadeIn("fast");
						});		
		}
		else
		{
			$("#activateForm").hide();
			$("#loginForm").hide();
		}
	}
	else
	{
		if(changeForm != form)
		{
			$("#"+changeForm+"Form").fadeOut("slow", function(){
							$("#"+form+"Form").fadeIn("fast");
						});	
		}
		else
		{
			$("#activateForm").hide();
			$("#forgotForm").hide();
			$("#"+form+"Form").fadeIn("slow");
		}
		
	}
	changeForm = form;
}

function loadForm(form, uid)
{
	$("#error-cont").fadeOut('fast');
	
	$("#user-auth-loader").hide();
	$("#progress-loader").show();
	
	$("#login-status-cont .msg").html("building "+ form +" form...");
	
	$("#login-status-cont").fadeIn("fast", 
		function(){
		
		$("#login-cont").fadeOut("fast", 
			function(){
				$.ajax({
				  url: SiteDomain+"/ajax/forms/"+form,
				  cache: false,
				  success: function(html){
							
							
							
							$("#login-status-cont").fadeOut('slow', 
								function(){
									$("#login-cont").html(html).fadeIn('fast');
									
									if ( form == 'login' )
									{
										initLoginForm();
									}
									
									if ( form == 'register' )
									{
										initRegisterForm();
									}
									
									if ( form == 'forgot' )
									{
										initForgotForm();
									}
									
									if ( form == 'activate' )
									{
										initActivateForm(uid);
									}
									$("#version-info").show();
								});
							
						 }
			   });
					  
		});														 
	});
	
	return false;
}

function reportAuthError(err, taunt, title)
{
	var err_title = 'Error';
	var err_reason = 'Something went wrong';
	var err_taunt  = 'so you should probably do something about not doing it again';
	
	if ( title != undefined)
	{
		if ( title.length > 0 )
		{
			err_title = title;
		}
	}

	if ( err.length > 0 )
	{
		err_reason = err;
	}
	
	if ( taunt.length > 0 )
	{
		err_taunt = taunt;
	}
	
	$("#error-cont").fadeOut('fast', 
			function(){
				$("#error-cont .title").hide();
				$("#error-cont .reason").hide();
				$("#error-cont .taunt").hide();
				$("#error-cont").show();
				
				$("#error-cont .title").html(err_title);
				$("#error-cont .reason").html(err_reason);
				$("#error-cont .taunt").html(err_taunt);
				
				$("#error-cont .title").fadeIn('fast',
					function(){
						$("#error-cont .reason").fadeIn('fast',
						function(){
							$("#error-cont .taunt").fadeIn('fast',
							function(){
								return;
							});
						});
					});
			});
}