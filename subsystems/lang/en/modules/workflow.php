<?php

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
define('TR_WORKFLOW_IMPLICITAPPROVAL',		'New Content Implicitly Approved');
define('TR_WORKFLOW_POSTED_ADMIN',			'Content posted or edited by an Administrator');
define('TR_WORKFLOW_APPROVED_ADMIN',		'Content approved by an Administrator');

?>