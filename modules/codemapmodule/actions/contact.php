<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: contact.php,v 1.4 2005/04/26 02:52:46 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

$stepstone = null;
if (isset($_GET['id'])) $stepstone = $db->selectObject('codemap_stepstone','id='.$_GET['id']);
if ($stepstone) {
	
	if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
	exponent_forms_initialize();
	
	$form = new form();
	$form->meta('module','codemapmodule');
	$form->meta('action','contact_send');
	$form->meta('stepstone_id',$_GET['id']);
	
	$form->register('email','Your Email',new textcontrol());
	$form->register('name','Your Name',new textcontrol());
	
	if ($stepstone->developer != '') {
		$form->meta('type','dev');
		// We have a developer
		$form->register('subject','Subject',new textcontrol());
		$form->register('message','Message',new texteditorcontrol());
		
		$template = new template('codemapmodule','_form_contactDeveloper');
	} else {
		$form->meta('type','vol');
		$form->register(null,'',new htmlcontrol('<i>Please list your reasons for volunteering</i>'));
		$form->register('reasons','',new texteditorcontrol());
		$form->register(null,'',new htmlcontrol('<i>How can you help with this feature?</i>'));
		$form->register('howhelp','',new texteditorcontrol());
		$form->register(null,'',new htmlcontrol('<i>What past experience and qualifications do you have?</i>'));
		$form->register('experience','',new texteditorcontrol());
		
		$template = new template('codemapmodule','_form_contactVolunteer');
	}
	
	$form->register('submit','',new buttongroupcontrol('Send','','Cancel'));
	
	$template->assign('form_html',$form->toHTML());
	$template->output();
	
	exponent_forms_cleanup();
} else echo SITE_404_HTML;

?>