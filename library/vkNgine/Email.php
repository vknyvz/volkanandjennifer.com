<?php 
class vkNgine_Email {
	
	/**
	 * sends a standard email
	 * 
	 * @param string $subject
	 * @param string $htmlMessage
	 * @param string $textMessage
	 * @param string $toName
	 * @param array $toEmails
	 * @param string $fromName
	 * @param string $fromEmail
	 */
	public function send($subject, $htmlMessage, $textMessage, $toName, $toEmails, $fromName, $fromEmail)
	{
		$config = Zend_Registry::get('config');
    	$logger = Zend_Registry::get('logger');
    	
	 	// check the email server type
	 	if ($config->mail->type == 'smtp') {
			$tr = new Zend_Mail_Transport_Smtp($config->mail->server, $config->mail->toArray());
	 	} else {
	 		$tr = new Zend_Mail_Transport_Sendmail();
	 	}
	 	
		Zend_Mail::setDefaultTransport($tr);
		
		foreach ($toEmails as $email) {
						
			if (APPLICATION_ENV=='development') {
				$email = $config->mail->debug;
			}
			
			$mail = new Zend_Mail();
			$mail->setBodyHtml($htmlMessage);
			$mail->setBodyText($textMessage);

			$mail->setFrom($fromEmail, $fromName);
			$mail->addTo($email);
			
			$mail->setSubject($subject);
				
			try {
				$mail->send();
			} catch (Zend_Mail_Protocol_Exception $e) {
    			$logger->log('MESSAGE_SEND_FAILED', 'Unable to send to ' . $email . ' - ' . $e->getMessage(), 1);
			}
		}  	
	}
}
	
	