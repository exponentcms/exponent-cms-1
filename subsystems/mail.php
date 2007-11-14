<?php

##################################################
#
# Copyright (c) 2004-2007 Ron Miller, OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################


/* 
exponentMail is an integrator class, bringing the flexibility of SwiftMail into Exponent gracefully.

More docs to follow as I become more familiar with SwiftMail. :-)
*/
if (!defined('EXPONENT')) exit('');

if ( version_compare ( phpversion(), "5.2.0", ">=" ) ) {
	require_once(BASE.'subsystems/mail/Swift.php');

// Create the class.
class exponentMail extends Swift {
	
	private $log = null;
	private $errStack = null;
	public $to = null;
	public $from = SMTP_FROMADDRESS;
	private $message = null;
	private $precallfunction = null;
	private $precalldata = null;
	private $postcallfunction = null;
	private $postcalldata = null;
	
	function __construct($params = array()) {
		Swift_CacheFactory::setClassName("Swift_Cache_Disk");
		Swift_Cache_Disk::setSavePath(BASE."/tmp");
		// Set up the mailer method.  Won't be using this anytime soon but its nice to have.
		if (array_key_exists('method',$params)) {
			switch ($params['method']) {
				case "multi":
					require_once(BASE.'subsystems/mail/Swift/Connection/Multi.php');
					require_once(BASE.'subsystems/mail/Swift/Connection/SMTP.php');
					require_once(BASE.'subsystems/mail/Swift/Connection/NativeMail.php');
					require_once(BASE.'subsystems/mail/Swift/Connection/Sendmail.php');
				break;
				case "smtp":
					require_once(BASE.'subsystems/mail/Swift/Connection/SMTP.php');
					if (array_key_exists('connections', $params)) {
						if (is_array($params['connections'])) {
							$conn = new Swift_Connection_SMTP($params['connections']['host'], $params['connections']['port'], $params['connections']['option']);
						} else {
							$conn = new Swift_Connection_SMTP($params['connections']);
						}
					} else {
						$conn = new Swift_Connection_SMTP(SMTP_SERVER, SMTP_PORT);
						$conn->setUsername(SMTP_USERNAME);
						$conn->setpassword(SMTP_PASSWORD);
					}
				break;
				case "native":
					require_once(BASE.'subsystems/mail/Swift/Connection/NativeMail.php');
					if ( isset($params['connections']) && !is_array($params['connections']) && $params['connections'] != '' ) {
						// Allow custom mail parameters.
						$conn = new Swift_Connection_NativeMail($params['connections']);
					} else {
						// Use default Mail parameters.
						$conn = new Swift_Connection_NativeMail();
					}
				break;
				case "sendmail":
					require_once(BASE.'subsystems/mail/Swift/Connection/Sendmail.php');
					if ( isset($params['connections']) && !is_array($params['connections']) && $params['connections'] != '' ) {
						// Allow a custom sendmail command to be run.
						$conn = new Swift_Connection_Sendmail($params['connections']);
					} else {
						// Attempt to auto-detect.
						$conn = new Swift_Connection_Sendmail(Swift_Conection_Sendmail::AUTO_DETECT);
					}
				break;
				case "rotator":
					require_once(BASE.'subsystems/mail/Swift/Connection/Rotator.php');
					require_once(BASE.'subsystems/mail/Swift/Connection/SMTP.php');
					require_once(BASE.'subsystems/mail/Swift/Connection/NativeMail.php');
					require_once(BASE.'subsystems/mail/Swift/Connection/Sendmail.php');
					if ( is_array ($params['connections']) ) {
						$conn = new Swift_Connection_Rotator($params['connections']);
					} else {
						$this->errStack['Connection'] = '$params[\'connections\'] must be an array to use the connection rotator.';
						$this->__destruct();
					}
					
				break;
			}
			// Use our current config vars 
		} else if (SMTP_USE_PHP_MAIL) {
			require_once(BASE.'subsystems/mail/Swift/Connection/NativeMail.php');
			if ( isset($params['connections']) && !is_array($params['connections']) && $params['connections'] != '' ) {
				// Allow custom mail parameters.
				$conn = new Swift_Connection_NativeMail($params['connections']);
			} else {
				// Use default Mail parameters.
				$conn = new Swift_Connection_NativeMail();
			}
		} else {
			require_once(BASE.'subsystems/mail/Swift/Connection/SMTP.php');
			if (array_key_exists('connections', $params)) {
				if (is_array($params['connections'])) {
					$conn = new Swift_Connection_SMTP($params['connections']['host'], $params['connections']['port'], $params['connections']['option']);
				} else {
					$conn = new Swift_Connection_SMTP($params['connections']);
				}
			} else {
				$conn = new Swift_Connection_SMTP(SMTP_SERVER, SMTP_PORT);
				$conn->setUsername(SMTP_USERNAME);
				$conn->setpassword(SMTP_PASSWORD);
			}
		}
		parent::__construct($conn);
		$this->message = new Swift_Message();
		
		switch (DEVELOPMENT) {
			case 1:
				$this->log = Swift_LogContainer::getLog();
				$this->log->setLogLevel(1);
			break;
			case 2:
				$this->log = Swift_LogContainer::getLog();
				$this->log->setLogLevel(5);
			break;
		}

	}
	// End Constructor
	
	//Override the parent send function so we can set up the send to be cleaner.
	public function send () {
		return parent::send($this->message, $this->to, $this->from);
	}
	
	public function batchSend() {
		require_once(BASE.'subsystems/mail/Swift/Plugin/AntiFlood.php');
		$this->attachPlugin(new Swift_Plugin_AntiFlood(200, 5));
		$batch = new Swift_BatchMailer($this);
		$batch->setSleepTime(1);
		$batch->send($this->message, $this->to, $this->from);
		return $batch->getFailedRecipients();
	}
	
	public function addHeaders($headers) {
		foreach ($headers as $header=>$value) {
			$this->message->headers->set($header, $value);
		}
	}
	
	public function addHTML($html) {
		$this->message->attach(new Swift_Message_Part($html, "text/html"));
	}
	
	public function addText($text) {
		$this->message->attach(new Swift_Message_Part($text, "text/plain"));
	}
	
	public function addRaw($body) {
		$this->message->setBody($body);
	}
	
	public function addTo ($a = '', $b = '') {
		if (!is_object($this->to)) {
			$this->to = new Swift_RecipientList();
		}
		if (is_array($a)) {
			foreach ($a as $addr) {
				$this->to->addTo($addr);
			}
		} else {
			if ($b != '') {
				$this->to->addTo($a, $b);
			} else {
				$this->to->addTo($a);
			}
		}	
	}
	
	public function messageId() {
		if (!is_object($this->message)) {
			$this->message = new Swift_Message();
		}
		return $this->message->generateId();
	}
	
	public function subject ($subj) { 
		if (!is_object($this->message)) {
			$this->message = new Swift_Message();
		}
		$this->message->headers->set("Subject", $subj);
	}
	
	public function clearBody () {
		$this->message->setBody("");
	}
	
	function __destruct() {
	/*
		if (DEVELOPMENT != 0) {
			eLog($this->log->dump(true));
		}
	*/
		if ($this->errStack != null) {
			eDebug($error);
		}
	}
}
// End Mail class.
// Pre-send processing class. (Incomplete)
/*
class preSend implements Swift_Events_BeforeSendListener {
	
	public function beforeSendPerformed(Swift_Events_SendEvent $e) {
		
	}
}
*/
} else {
	eDebug ("You must be using PHP 5.2 or greater to use the exponent mail subsystem.");
}
?>
