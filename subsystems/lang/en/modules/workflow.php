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

// I18n constants for Workflow helper module

define('TR_WORKFLOW_POLICYNAME',			'Policy Name');
define('TR_WORKFLOW_POLICYDESC',			'Description');
define('TR_WORKFLOW_MAXAPPROVERS',			'Maximum Number of Approvers');
define('TR_WORKFLOW_REQUIREDAPPROVALS',		'Number of Required Approvals');

define('TR_WORKFLOW_REVOKENONE',			'Revoke None');
define('TR_WORKFLOW_REVOKEALL',				'Revoke All Approvals');
define('TR_WORKFLOW_REVOKEPOSTER',			'Revoke Poster\'s Approval');
define('TR_WORKFLOW_REVOKEAPPROVERS',		'Revoke Approvers\' Approvals');
define('TR_WORKFLOW_REVOKEOTHERS',			'Revoke Poster and Approvers\' Approvals');

define('TR_WORKFLOW_ONAPPROVE',				'When approved 100%');
define('TR_WORKFLOW_ONEDIT',				'When edited and approved');
define('TR_WORKFLOW_ONDENY',				'When not approved');
define('TR_WORKFLOW_DELETEONDENY',			'Delete content if not approved?');

define('TR_WORKFLOW_ACTION',				'Action');
define('TR_WORKFLOW_PARAMETER',				'Parameter');

define('TR_WORKFLOW_NOPOLICY',				'No Policy');
define('TR_WORKFLOW_POLICY',				'Policy');
define('TR_WORKFLOW_DEFAULTPOLICY',			'Default: %s');

define('TR_WORKFLOW_DENYCOMMENT',			'Comment');

define('TR_WORKFLOW_DEFAULTTHANKYOU',		'Thank you for your participation in this approval process.  The content you approved has met all of the required criteria, and has been published live.');

define('TR_WORKFLOW_POSTED',				'New Content Posted');
define('TR_WORKFLOW_EDITED',				'Existing Content Edited');
define('TR_WORKFLOW_APPROVED_APPROVED',		'Content Approved as-is');
define('TR_WORKFLOW_APPROVED_EDITED',		'Content Edited, but approved');
define('TR_WORKFLOW_APPROVED_DENIED',		'Content Denied Approval');
define('TR_WORKFLOW_APPROVED_FINAL',		'Content Published');
define('TR_WORKFLOW_DELETED',				'Approval Path Deleted');
define('TR_WORKFLOW_RESTARTED',				'Approval Path Restarted');
define('TR_WORKFLOW_IMPLICIT_APPROVAL',		'New Content Implicitly Approved');
define('TR_WORKFLOW_POSTED_ADMIN',			'Content posted or edited by an Administrator');
define('TR_WORKFLOW_APPROVED_ADMIN',		'Content approved by an Administrator');

?>