<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

/**
 * SMTP Subsystem
 *
 * Allows efficient mass-mailing by making a direct socket
 * connection to a mail server on port 25 and negotiating SMTP
 * directly.
 *
 * @package		Subsystems
 * @subpackage	SMTP
 *
 * @author		James Hunt
 * @copyright		2004 James Hunt and the OIC Group, Inc.
 * @version		0.95
 */

/**
 * SYS flag
 *
 * The definition of this constant lets other parts of the system know 
 * that the subsystem has been included for use.
 */
define("SYS_SMTP",1);

/**
 * Replacement for PHP's mail() function
 *
 * Sends mail through a raw SMTP socket connection, to one or
 * more recipients.  This function is optimized for multiple recipients.
 *
 * @param array $to_r An array of recipient email address, or a single email address string.
 * @param string $from The from header string, usually an email address.
 * @param string $subject The subject of the message to send.  The same subject is used for all messages.
 * 	The subject cannot have newlines or carriage returns in it. (currently not checked)
 * @param string $message The message to send.  The same message is used for all messages.  Newlines
 * 	will be converted as needed.
 * @param array $headers An associative array of header fields for the email.
 * @param string $callback The name of a callback function for processing each message
 * @return boolean True if all mail messages were sent.  Returns false if anything failed.
 */
function pathos_smtp_mail($to_r,$from,$subject,$message,$headers=array(),$callback="") {
	if (!is_array($to_r)) $to_r = array($to_r);
	if (!is_array($headers)) {
		$headers = explode("\r\n",trim($headers));
	}
	$headers["Subject"] = $subject;
	if (!isset($headers["From"])) $headers["From"] = $from;
	if (!isset($headers["Date"])) $headers["Date"] = strftime("%a, %b %d %Y %R:%S %Z",time());
	if (!isset($headers["Reply-to"])) $headers["Reply-to"] = $from;
	
	$message = str_replace("\n","\r\n",str_replace("\r\n","\n",$message));
	
	$errno = 0;
	$error = "";
	$socket = @fsockopen(SMTP_SERVER, 25, $errno, $error, 1);
	if ($socket === false) {
		return false;
	}
	
	if (!pathos_smtp_checkResponse($socket,"220")) {
		return false;
	}
	
	pathos_smtp_sendServerMessage($socket,"HELO ".$_SERVER['HTTP_HOST']);
	if (!pathos_smtp_checkResponse($socket,"250")) {
		pathos_smtp_sendExit($socket);
		return false;
	}
	
	if (!function_exists($callback)) $callback = "pathos_smtp_blankMailCallback";
	
	for ($i = 0; $i < count($to_r); $i++) {
		$to = $to_r[$i];
		
		$callback($i,$message,$subject,$headers);
		
		$headers["To"] = $to;
	
		pathos_smtp_sendServerMessage($socket,"MAIL FROM: <$from>");
		if (!pathos_smtp_checkResponse($socket,"250")) {
			pathos_smtp_sendExit($socket);
			return false;
		}
		
		pathos_smtp_sendServerMessage($socket,"RCPT TO: <$to>");
		if (!pathos_smtp_checkResponse($socket,"250")) {
			pathos_smtp_sendExit($socket);
			return false;
		}
		
		pathos_smtp_sendServerMessage($socket,"DATA");
		if (!pathos_smtp_checkResponse($socket,"354")) {
			return false;
		}
		
		pathos_smtp_sendHeadersPart($socket,$headers);
		pathos_smtp_sendMessagePart($socket,"\r\n".wordwrap($message)."\r\n");
		pathos_smtp_sendServerMessage($socket,"\r\n.\r");
		if (!pathos_smtp_checkResponse($socket,"250")) {
			pathos_smtp_sendExit($socket);
			return false;
		}
		
	}
	
	pathos_smtp_sendExit($socket);
	return true;
}

/**
 * Check Server Response
 *
 * Checks the server response to a message sent previously.
 *
 * @param Socket $socket The socket object connected to the mail server
 * @param string $expected_response The response code (3-digit number)
 *	expected from the server
 *
 * @return boolean True if the expected response was the actual response,
 *	and false if there was a discrepency.
 */
function pathos_smtp_checkResponse($socket,$expected_response) {
	$response = fgets($socket,256);
	return (substr($response,0,3) == $expected_response);
}

/**
 * Send a Message to the Mail Server
 *
 * Sends an SMTP message to the server through the given socket.
 *
 * @param Socket $socket The socket object connected to the mail server
 * @param string $message The message to send
 */
function pathos_smtp_sendServerMessage($socket,$message) {
	if (substr($message,-1,1) != "\n") $message .="\n";
	if ($message != null) fputs($socket,$message);
}

function pathos_smtp_sendExit($socket) {
	pathos_smtp_sendServerMessage($socket,"RSET");
	pathos_smtp_sendServerMessage($socket,"QUIT");
}

/**
 * Send Email Headers to Server
 *
 * Sends the header part of an email message to the server.  This takes
 * care of properly escaping newlines as \r\n escape characters
 *
 * @param Socket $socket The socket object connected to the mail server
 * @param Array $headers An associative array of header keys to header values
 */
function pathos_smtp_sendHeadersPart($socket,$headers) {
	$headerstr = "";
	foreach ($headers as $key=>$value) {
		$headerstr .= $key.": ". $value . "\r\n";
	}
	pathos_smtp_sendServerMessage($socket,$headerstr);
}

/**
 * Send Email Body yo Server
 *
 * Sends the message part of an email message to the server.  This takes
 * care of properly (and intelligently) escaping newlines as \r\n escape characters.
 *
 * @param Socket $socket The socket object connected to the server
* @param string $message The body of the email.
 */
function pathos_smtp_sendMessagePart($socket,$message) {
	pathos_smtp_sendServerMessage($socket,str_replace("\n","\r\n",str_replace("\r\n","\n",$message)));
}

/**
 * A Blank Callback Function
 *
 * A blank callback function that shows external argument list
 *
 * @param integer $email_index The numerical index of the email address
 * @param string $msg The text of the message.  This is a modifiable referenced variable
 * @param string $subject The subject of the message.  This is a modifiable referenced variable
 * @param array $headers The headers array.  This is a modifiable referenced variable.
 */
function pathos_smtp_blankMailCallback($email_index,&$msg,&$subject,&$headers) {

}

?>