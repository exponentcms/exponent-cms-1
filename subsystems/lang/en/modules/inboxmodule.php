<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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

// I18N strings for the Inbox Module

define('TR_INBOXMODULE_USER',				'User');
define('TR_INBOXMODULE_BLOCKUSER',			'Block User');
define('TR_INBOXMODULE_GROUPNAME',			'Group Name');
define('TR_INBOXMODULE_DESCRIPTION',		'Description');
define('TR_INBOXMODULE_MEMBERS',			'Members');

define('TR_INBOXMODULE_SUBJECT',			'Subject');
define('TR_INBOXMODULE_MESSAGE',			'Message');

define('TR_INBOXMODULE_PERSONALLIST',		'(personal list)');
define('TR_INBOXMODULE_SYSUSER',			'(system user)');
define('TR_INBOXMODULE_SYSGROUP',			'(system group)');

define('TR_INBOXMODULE_SEND',				'Send');

define('TR_INBOXMODULE_REPLYTO',			'Reply to %s');
define('TR_INBOXMODULE_COPYTO',				'Send a copy to');
define('TR_INBOXMODULE_GROUPCOPYTO',		'Send a copy to groups(s)');
define('TR_INBOXMODULE_RECIPIENT',			'Recipient(s)');
define('TR_INBOXMODULE_GROUPRECIPIENT',		'Group Recipient(s)');

define('TR_INBOXMODULE_NOCONTACTSWARNING',	'You have no contacts in your personal contact list.');

define('TR_INBOXMODULE_FAILED_TITLE',		'Failed Delivery');
define('TR_INBOXMODULE_FAILED_FROM',		'System Message');
define('TR_INBOXMODULE_FAILED_MSG',			'The following message was not delivered because the recipient was not found in the system.<hr size="1" /><hr size="1" />%s');
define('TR_INBOXMODULE_FAILED_404MSG',		'The following message was not delivered.<hr size="1" /><hr size="1" />%s');

define('TR_INBOXMODULE_ERR_SMTP',			'Something didn\'t work with the email config');

define('TR_INBOXMODULE_NOMEMBERS',			'<i>You will not be able to create a contact list, because there are no users you can contact.</i>');

// Permissions
define('TR_INBOXMODULE_PERM_ADMIN',			'Administrate');
define('TR_INBOXMODULE_PERM_CONTACTALL',	'Contact All Users');

?>