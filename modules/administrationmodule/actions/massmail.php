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

if (!defined("PATHOS")) exit("");

if ($user && $user->is_acting_admin) {
	if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
	pathos_forms_initialize();
	
	$form = new form();
	$form->meta("module","administrationmodule");
	$form->meta("action","massmail_send");
	
	$data = array();
	$previewed = false;
	if (pathos_sessions_isset("massmail_data")) {
		$data = pathos_sessions_get("massmail_data");
		$previewed = true;
		pathos_sessions_unset("massmail_data");
	} else {
		$data = array(
			"subject"=>"",
			"from"=>"Site Admin <admin@".$_SERVER['HTTP_HOST'].">",
			"returnpath"=>"admin@".$_SERVER['HTTP_HOST'],
			"replyto"=>"admin@".$_SERVER['HTTP_HOST'],
			"message"=>""
		);
	}
	
	if ($previewed) $form->register(uniqid(""),"",new htmlcontrol("<hr size='1'/>Preview Sent Successfully to ".$data['to'][1]));
	$form->register("to","To",new massmailcontrol());
	$form->register("subject","Subject",new textcontrol($data['subject']));
	$form->register("returnpath","Return Path",new textcontrol($data['returnpath']));
	$form->register("from","From",new textcontrol($data['from']));
	$form->register("replyto","Reply-To",new textcontrol($data['replyto']));
	$form->register("message","Message",new htmleditorcontrol($data['message']));
	$form->register("submit","",new buttongroupcontrol("Send","","Cancel"));
	
	$template = new template("administrationmodule","_form_massmail",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->output();
	
	pathos_forms_cleanup();
} else {
	echo SITE_403_HTML;
}

?>