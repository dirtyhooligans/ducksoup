<?php
class Emails
{
	protected $Template;
	
	public static $core;
	
	public function __construct($CORE)
	{
		$this->core = $CORE;
	}
	
	public function buildEmail($EMAILTYPE = 'default', $SUBJECT, $MESSAGE)
	{ 
		$email_template_file = BASE_DIR.'/'.$this->core->config->email->template_path.'/'.$EMAILTYPE.'/index.html';
	
		if( is_file($email_template_file) )
		{
			$email_template = file_get_contents($email_template_file);
			
			$email = $email_template;
			$email = str_replace('###IMAGE_URL###', BASE_URL.'/'.$this->core->config->email->img_path.'/'.$EMAILTYPE, $email);
			$email = str_replace('###SITE_NAME###', $this->core->config->site_name, $email);
			$email = str_replace('###SUBJECT###',   $SUBJECT, $email);
			$email = str_replace('###MESSAGE###',   $MESSAGE, $email);
			$email = str_replace('###COPYRIGHT###', $this->core->properties['copyright'], $email);
			$email = str_replace('###MENU###',      '',  $email);
			
			$email = str_replace('{SITE_NAME}',     $this->core->config->site_name,  $email);
			
			return $email;
			
		}
		else
		{
			return array('error' => true, 'description' => 'Unable to open Email Template: '.$EMAILTYPE.' File: '.$email_template );	
		}
	
	}
	
	public function send($TO, $FROM_NAME, $FROM_EMAIL, $SUBJECT, $MESSAGE, $EMAILTYPE = 'labs', $BCC = false)
	{ // 0.1.0
		if (! is_array($TO) ) $to_string = $TO;
		  
		$Content = self::buildEmail($EMAILTYPE, $SUBJECT, $MESSAGE);
		
		if(is_array($Content) && !empty($Content['error']))
			return $Content;
			
		if(is_array($TO))
		{
			foreach($TO as $Recipient){
				$SendTo .= $Recipient['name'].' <'.$Recipient['email'].'>, ';
			}
			
			$safeSendTo = '';
		}
		else
		{
			$SendTo = $TO;
			$safeSendTo = $TO;
		}
		
		if( $BCC )
		{
			if( is_array( $BCC ) )
			{
				foreach($BCC as $BCC_Recipient){
					$SendBcc .= $BCC_Recipient['name'].' <'.$BCC_Recipient['email'].'>, ';
				}
			}
			else
			{
				$SendBcc = $BCC;
			}
		}

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'To: ';
		$headers .= $SendTo;
		$headers .= "\r\n";
		$headers .= 'From: '.str_replace(',', '', $FROM_NAME).' <'.$FROM_EMAIL.'>' . "\r\n";
		$headers .= 'Bcc: '. $this->core->config->email->admin;
		
		if ( $BCC )
		{
			$headers .= $SendBcc;
			$headers .= "\r\n";	
		}
		else
		{
			$headers .= "\r\n";	
		}
		
		$subject = str_replace('{SITE_NAME}', $this->core->config->site_name, $SUBJECT);
		
		$sendMail = mail($safeSendTo, $subject, $Content, $headers);
		
		if($sendMail)
		{
			return true;
		}
		else
		{
			return array('error' => true, 'description' => 'Unable to send E-Mail to: '.$SentTo.' Subject: '.$SUBJECT);	
		}
		
	}	
}

?>