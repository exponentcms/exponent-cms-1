<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
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

if (!defined('EXPONENT')) exit('');

$i18n = exponent_lang_loadFile('modules/weblogmodule/actions/reply_send.php');

$template = new template('weblogmodule',$_POST['msg'],$loc);
$template->assign('post',$_POST);
$msg = $template->render();

$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($loc)."'");
if (!empty($config->use_captcha)) $validate['captcha'] = 'captcha_string';
$validate['valid_email'] = 'email';
if (count($validate) > 0) validator::validate($validate, $_POST);
if ($config == null) {
	$config->subject = $i18n['default_subject'];
	$config->replyto_address = '';
	$config->from_address = 'info@'.HOSTNAME;
	$config->from_name = $i18n['default_from'];
	
} else {
	if ($config->subject == '') {
		$config->subject = $i18n['default_subject'];
	}
}

$headers = array();
if (isset($_POST['email']) && $_POST['email'] != '') {
        $headers['From'] = isset($_POST['name']) ? $_POST['name'] . ' <'.$_POST['email'].'>' : $_POST['email'];
        $headers['Reply-to'] = $_POST['email'];
} else {
        $headers['From'] = $config->from_name . ' <'.$config->from_address.'>';
        $headers['Reply-to'] = $config->replyto_address;
        
}

require_once(BASE.'subsystems/mail.php');
$mail = new exponentMail();
//$mail->addTo($emails);
$mail->addTo($_POST['emailto']);
//$mail->subject($config->subject);
$mail->subject($_POST['subject']);
$mail->addText($msg);
if (isset($_POST['email']) && $_POST['email'] != '') {
	$mail->from = $_POST['email'];
}

// Send the message
if ($mail->send() > 0) {
	$template = new template('weblogmodule','_final_message');
	$template->assign('message',$config->final_message);
	$template->output();
} else {
	echo $i18n['smtp_error'];
}

?>
