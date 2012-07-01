<?php

######################################
## 		Language: English (US) 		##
## 		Translated: 2008.06.12		##
##									##
## 			By:		 				##
##			 S. Partridge			##
##									##
##		Updated: 2008.06.12			##
##									##
######################################

$LANG['lang'] = 'English';
$LANG['country'] = 'United States';

// Login Page
$LANG['pages']['login']['page_title']			= 'Login';
$LANG['pages']['login']['table_title'] 			= 'Login ';
$LANG['pages']['login']['loggingin_msg'] 		= 'Logging In...';
$LANG['pages']['login']['loggingout_msg'] 		= 'Logging Out...';
$LANG['pages']['login']['not_registered'] 		= 'Not Registered?';
$LANG['pages']['login']['username'] 			= 'Username/Email:';
$LANG['pages']['login']['password'] 			= 'Password:';
$LANG['pages']['login']['forgot_password'] 		= 'Forgot Password?';
$LANG['pages']['login']['submit_button'] 		= 'Login';

// Register Page
$LANG['pages']['register']['page_title']		= 'Register';
$LANG['pages']['register']['page_title_go']		= 'Processing...';


// Forgot Page
$LANG['pages']['forgot']['page_title']			= 'Forgot';
$LANG['pages']['forgot']['table_title'] 		= ':: Forgot :.';
$LANG['pages']['forgot']['login']				= 'Login';
$LANG['pages']['forgot']['not_registered'] 		= 'Not Registered?';
$LANG['pages']['forgot']['forgot_msg'] 			= 'Please provide one of the following:<br>Username, Email or User ID';
$LANG['pages']['forgot']['submit_button'] 		= 'Send Request';

// Activate page
$LANG['pages']['activate']['page_title'] 	= 'Activate';
$LANG['pages']['activate']['table_title'] 	= ':: Activate :.';
$LANG['pages']['activate']['activate_msg'] 	= 'Your account needs to be activated. Please do so by completing the following steps.<br><br>First you need to set a password.';
$LANG['pages']['activate']['current_pass'] 	= 'Current Password:';
$LANG['pages']['activate']['new_pass'] 		= 'New Password:';
$LANG['pages']['activate']['confirm_pass'] 	= 'Confirm Password:';

#### Emails ####

// New User/Welcome Email
$LANG['emails']['new_user']['subject']			= 'Welcome to '.SITE_NAME_SHORT;
$LANG['emails']['new_user']['welcome']			= 'Welcome';
$LANG['emails']['new_user']['welcome_msg']		= 'You have been successfully registered with '.SITE_NAME.' and now have access to the user features of the site.  Please use the below information to access your account:';
$LANG['emails']['new_user']['uname'] 			= 'Username: ';
$LANG['emails']['new_user']['email'] 			= 'Email: ';
$LANG['emails']['new_user']['password'] 		= 'Password: ';
$LANG['emails']['new_user']['login_page'] 		= 'Login Page';
$LANG['emails']['new_user']['login_page_link'] 	= HTTPS.FULL_WWW;

// Existing User/Update Email
$LANG['emails']['existing_user']['subject']			= 'Account Updated at '.SITE_NAME;
$LANG['emails']['existing_user']['greeting']		= 'Hey ';
$LANG['emails']['existing_user']['update']			= 'Just letting you know that your Account has been Successfully Updated.';
$LANG['emails']['existing_user']['update_msg']		= 'Just letting you know that someone, theoretically you, has updated your account information. &nbsp;If this comes as a surprise to you please notify us.';

$LANG['emails']['existing_user']['login_page'] 		= 'Login Page';
$LANG['emails']['existing_user']['login_page_link'] = HTTPS.FULL_WWW;

// Forgot Password Email
$LANG['emails']['forgot_password']['subject']		= 'Password Reminder';
$LANG['emails']['forgot_password']['msg'] 			= 'Someone, theoretically you, requested a password reminder.  Please find your login information below:';
$LANG['emails']['forgot_password']['uname'] 		= 'Username: ';
$LANG['emails']['forgot_password']['email'] 		= 'Email: ';
$LANG['emails']['forgot_password']['password'] 		= 'Password: ';
$LANG['emails']['forgot_password']['login_page'] 	= 'Login Page';

#### End Emails ####

// Errors
$LANG['errors']['E001'] = 'User not found';  
$LANG['errors']['E002'] = 'Password Invalid';
$LANG['errors']['E003'] = 'E-Mail Exists';
$LANG['errors']['E004'] = 'Could not find your entry as an email, username or user id in the db';
$LANG['errors']['E005'] = 'Current Password is Invalid';
$LANG['errors']['E006'] = 'Passwords do not match';
$LANG['errors']['E007'] = 'Username Exists';
$LANG['errors']['E008'] = 'Invalid Username Entry';
$LANG['errors']['E009'] = 'Invalid E-Mail Entry';
$LANG['errors']['E010'] = 'Invalid Zipcode Entry';



######################################
######################################

$LANG['censor'] = array('wtf','wop','whore','whoar','wetback','wank','vagina','twaty','twat','titty','titties','tits','testicles','teets','spunk','spic','snatch','smut','sluts','slut','sleaze','slag','shiz','shitty','shittings','shitting','shitters','shitter','shitted','shits','shitings','shiting','shitfull','shited','shit','shemale','sheister','sh!t','scrotum','screw','schlong','retard','qweef','queer','queef','pussys','pussy','pussies','pusse','punk','prostitute','pricks','prick','pr0n','pornos','pornography','porno','porn','pissoff','pissing','pissin','pisses','pissers','pisser','pissed','piss','pimp','phuq','phuks','phukking','phukked','phuking','phuked','phuk','phuck','phonesex','penis','pecker','orgasms','orgasm','orgasims','orgasim','niggers','nigger','nigga','nerd','muff','mound','motherfucks','motherfuckings','motherfucking','motherfuckin','motherfuckers','motherfucker','motherfucked','motherfuck','mothafucks','mothafuckings','mothafucking','mothafuckin','mothafuckers','mothafucker','mothafucked','mothafuckaz','mothafuckas','mothafucka','mothafuck','mick','merde','masturbate','lusting','lust','loser','lesbo','lesbian','kunilingus','kums','kumming','kummer','kum','kuksuger','kuk','kraut','kondums','kondum','kock','knob','kike','kawk','jizz','jizm','jiz','jism','jesus h christ', 'jesus fucking christ','jerk-off','jerk','jap','jackoff','jacking off','jackass','jack-off','jack off','hussy','hotsex','horny','horniest','hore','hooker','honkey','homo','hoer','hardcoresex','hard on','h4x0r','h0r','guinne','gook','gonads','goddamn','gazongers','gaysex','gay','gangbangs','gangbanged','gangbang','fux0r','furburger','fuks','fuk','fucks','fuckme','fuckings','fucking','fuckin','fuckers','fucker','fucked','fuck','foreskin','fistfucks','fistfuckings','fistfucking','fistfuckers','fistfucker','fistfucked','fistfuck','fingerfucks','fingerfucking','fingerfuckers','fingerfucker','fingerfucked','fingerfuck','fellatio','felatio','feg','feces','fcuk','fatso','fatass','farty','farts','fartings','farting','farted','fart','fags','fagots','fagot','faggs','faggot','faggit','fagging','fagget','fag','ejaculation','ejaculatings','ejaculating','ejaculates','ejaculated','ejaculate','dyke','dumbass','douche bag','dong','dipshit','dinks','dink','dildos','dildo','dike','dick','damn','cyberfucking','cyberfuckers','cyberfucker','cyberfucked','cyberfuck','cyberfuc','cunts','cuntlicking','cuntlicker','cuntlick','cunt','cunnilingus','cunillingus','cunilingus','cumshot','cums','cumming','cummer','cum','crap','cooter','cocksucks','cocksucking','cocksucker','cocksucked','cocksuck','cocks','cock','cobia','clits','clit','clam','circle jerk','chink','cawk','buttpicker','butthole','butthead','buttfucker','buttfuck','buttface','butt hair','butt fucker','butt breath','butt','butch','bung hole','bum','bullshit','bull shit','bucket cunt','browntown','browneye','brown eye','boner','bonehead','blowjobs','blowjob','blow job','bitching','bitchin','bitches','bitchers','bitcher','bitch','bestiality','bestial','belly whacker','beaver','beastility','beastiality','beastial','bastard','balls','asswipe','asskisser','assholes','asshole','asses','ass lick','ass');
?>