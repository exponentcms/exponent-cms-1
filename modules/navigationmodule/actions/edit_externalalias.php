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

// Bail in case someone has visited us directly, or the Pathos framework is
// otherwise not initialized.
if (!defined('PATHOS')) exit('');

// FIXME: Allow non-administrative users to manage certain
// FIXME: parts of the section hierarchy.
if ($user && $user->is_admin == 1) {
	$section = null;
	if (isset($_GET['id'])) {
		// Check to see if an id was passed in get.  If so, retrieve that section from
		// the database, and perform an edit on it.
		$section = $db->selectObject('section','id='.$_GET['id']);
	} else if (isset($_GET['parent'])) {
		// The isset check is merely a precaution.  This action should
		// ALWAYS be invoked with a parent or id value in the GET.
		$section->parent = $_GET['parent'];
	}
	$form = section::externalAliasForm($section);
	$form->meta('module','navigationmodule');
	$form->meta('action','save_externalalias');
	// Create a template for the form's output, to allow the themer to optionally
	// change the form title and caption.  This will help with translation.
	$template = new template('navigationmodule','_form_editExternalAlias');
	// Assign the customary 'is_edit' flag with the template, so that the view can show different
	// text to the user if they are creating a new alias or editing an existing one.
	$template->assign('is_edit',isset($section->id));
	// Assign the form's rendered HTML output to the tempalte using the
	// conventional name of 'form_html'.
	$template->assign('form_html',$form->toHTML());
	$template->output();
}

?>