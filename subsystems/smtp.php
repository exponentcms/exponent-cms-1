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

/* exdoc
 * The definition of this constant lets other parts of the system know 
 * that the subsystem has been included for use.
 */
define("SYS_SMTP",1);

/* exdoc
 * Sends mail through a raw SMTP socket connection, to one or
 * more recipients.  This function is optimized for multiple recipients.
 * Returns true if all mail messages were sent.  Returns false if anything failed.
 *
 * @param array $to_r An array of recipient email address, or a single email address string.
 * @param string $from The from header string, usually an email address.
 * @param string $subject The subject of the message to send.  The same subject is used for all messages.
 * 	The subject cannot have newlines or carriage returns in it. (currently not checked)
 * @param string $message The message to send.  The same message is used for all messages.  Newlines
 * 	will be converted as needed.
 * @param array $headers An associative array of header fields for the email.
 * @param string $callback The name of a callback function for processing each message
 * @node Subsystems:SMTP
 */
function pathos_smtp_mail($to_r,$from,$subject,$message,$headers=array(),$callback="",$udata=null) {

	$from = SMTP_FROMADDRESS; // For shared hosters

	if (!is_array($to_r)) $to_r = array($to_r);
	if (!is_array($headers)) {
		$headers = explode("\r\n",trim($headers));
	}
	$headers["Subject"] = $subject;
	if (!isset($headers["From"])) $headers["From"] = $from;
	if (!isset($headers["Date"])) $headers["Date"] = strftime("%a, %b %d %Y %R:%S %Z",time());
	if (!isset($headers["Reply-to"])) $headers["Reply-to"] = $from;
	
	$m = str_replace("\n","\r\n",str_replace("\r\n","\n",$message));
	
	$errno = 0;
	$error = "";
	$socket = @fsockopen(SMTP_SERVER, SMTP_PORT, $errno, $error, 1);
	if ($socket === false) {
		return false;
	}
	
	if (!pathos_smtp_checkResponse($socket,"220")) {
		return false;
	}

	// Try EHLO (Extendend HELO) first
	pathos_smtp_sendServerMessage($socket,"EHLO ".$_SERVER['HTTP_HOST']);
	
	if (!pathos_smtp_checkResponse($socket,"250")) {
		// If EHLO failed, try to fallback to HELO, according to RFC2821
		pathos_smtp_sendServerMessage($socket,"HELO ".$_SERVER['HTTP_HOST']);
		if (!pathos_smtp_checkResponse($socket,"250")) {
			pathos_smtp_sendExit($socket);
			return false;
		}
	} else {
		// EHLO succeeded - try to figure out what we need for auth
		//GREP:NOTIMPLEMENTED
	}
	
	if (SMTP_AUTHTYPE != "NONE" && !pathos_smtp_authenticate($socket,SMTP_AUTHTYPE,SMTP_USERNAME,SMTP_PASSWORD)) {
		pathos_smtp_sendExit($socket);
		return false;
	}
	
	if (!function_exists($callback)) $callback = "pathos_smtp_blankMailCallback";
	
	for ($i = 0; $i < count($to_r); $i++) {
		$to = $to_r[$i];
		$message = $m.'';
		
		$callback($i,$message,$subject,$headers,$udata);
		
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
		// Xavier Basty - 2005/02/07 - Fix for Lotus Notes SMTP
//		pathos_smtp_sendServerMessage($socket,"\r\n.\r");
		pathos_smtp_sendServerMessage($socket,"\r\n.\r\n");
		if (!pathos_smtp_checkResponse($socket,"250")) {
			pathos_smtp_sendExit($socket);
			return false;
		}
		
	}
	
	pathos_smtp_sendExit($socket);
	return true;
}

/* exdoc
 * Checks the server response to a message sent previously.  Returns
 * rue if the expected response was the actual response, and false if there was a discrepency.
 *
 * @param Socket $socket The socket object connected to the mail server
 * @param string $expected_response The response code (3-digit number)
 *	expected from the server
 * @node Subsystems:SMTP
 */
function pathos_smtp_checkResponse($socket,$expected_response) {
	$response = fgets($socket,256);
	$line = $response;
	$count = 20;
	while ($count && substr($line,3,1) == "-") {
		$line = fgets($socket,256); // Clear the buffer, EHLO
		$count--;
	}
	return (substr($response,0,3) == $expected_response);
}

/* exdoc
 * Sends an SMTP message to the server through the given socket.
 *
 * @param Socket $socket The socket object connected to the mail server
 * @param string $message The message to send
 * @node Subsystems:SMTP
 */
function pathos_smtp_sendServerMessage($socket,$message) {
	// Xavier Basty - 2005/02/07 - Fix for Lotus Notes SMTP
//	if (substr($message,-1,1) != "\n") $message .="\n";
	if (substr($message,-1,1) != "\n") $message .="\r\n";
	if ($message != null) fwrite($socket,$message);
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_smtp_sendExit($socket) {
	pathos_smtp_sendServerMessage($socket,"RSET");
	pathos_smtp_sendServerMessage($socket,"QUIT");
}

/* exdoc
 * Sends the header part of an email message to the server.  This takes
 * care of properly escaping newlines as \r\n escape characters
 *
 * @param Socket $socket The socket object connected to the mail server
 * @param Array $headers An associative array of header keys to header values
 * @node Subsystems:SMTP
 */
function pathos_smtp_sendHeadersPart($socket,$headers) {
	$headerstr = "";
	foreach ($headers as $key=>$value) {
		$headerstr .= $key.": ". $value . "\r\n";
	}
	pathos_smtp_sendServerMessage($socket,$headerstr);
}

/* exdoc
 * Sends the message part of an email message to the server.  This takes
 * care of properly (and intelligently) escaping newlines as \r\n escape characters.
 *
 * @param Socket $socket The socket object connected to the server
 * @param string $message The body of the email.
 * @node Subsystems:SMTP
 */
function pathos_smtp_sendMessagePart($socket,$message) {
	$message = preg_replace("/([^\r]{1})\n/","\\1\r\n",$message);
	$message = preg_replace("/\n\n/","\n\r\n",$message);
	$message = preg_replace("/\n\./","\n..",$message);
	
	pathos_smtp_sendServerMessage($socket,$message);
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_smtp_parseEHLO($socket) {
	
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_smtp_authenticate($socket,$type,$username,$password) {
	pathos_smtp_sendServerMessage($socket,"AUTH $type");
	if (pathos_smtp_checkResponse($socket,"334")) {
		switch ($type) {
			case "LOGIN":
				// Code shamelessly ripped from PEAR
				pathos_smtp_sendServerMessage($socket,base64_encode($username));
				if (!pathos_smtp_checkResponse($socket,"334")) {
					return false;
				}
				pathos_smtp_sendServerMessage($socket,base64_encode($password));
				break;
			case "PLAIN":
				pathos_smtp_sendServerMessage($socket,base64_encode(chr(0).$username.chr(0).$password));
				break;
		}
		
		return pathos_smtp_checkResponse($socket,"235");
	} else {
		return false;
	}
}

/* exdoc
 * A blank callback function that shows external argument list
 *
 * @param integer $email_index The numerical index of the email address
 * @param string $msg The text of the message.  This is a modifiable referenced variable
 * @param string $subject The subject of the message.  This is a modifiable referenced variable
 * @param array $headers The headers array.  This is a modifiable referenced variable.
 * @node Subsystems:SMTP
 */
function pathos_smtp_blankMailCallback($email_index,&$msg,&$subject,&$headers) {

}

?>