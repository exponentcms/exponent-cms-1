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

if (!defined('SITE_TITLE')) define('SITE_TITLE','My New Exponent Site');
if (!defined('SITE_ALLOW_REGISTRATION')) define('SITE_ALLOW_REGISTRATION',1);

if (!defined('SITE_404_HTML')) define('SITE_404_HTML',html_entity_decode('<h3>Resource Not Found</h3>The resource you were looking for wasn&apos;t found.  It may have been deleted, or moved.'));
// SITE_403_HTML will be set by sessions, to be either the timeout html, or forbidden
if (!defined('SITE_403_REAL_HTML')) define('SITE_403_REAL_HTML',html_entity_decode('<h3>Authorization Failed</h3>You are not allowed to perform this operation.'));

if (!defined('SITE_KEYWORDS')) define('SITE_KEYWORDS','');
if (!defined('SITE_DESCRIPTION')) define('SITE_DESCRIPTION','');
if (!defined('SITE_DEFAULT_SECTION')) define('SITE_DEFAULT_SECTION',1);

if (!defined('SESSION_TIMEOUT')) define('SESSION_TIMEOUT',3600);
if (!defined('SESSION_TIMEOUT_HTML')) define('SESSION_TIMEOUT_HTML',html_entity_decode('<h3>Expired Login Session</h3>Your session has expired, because you were idle too long.  You will have to log back into the system to continue what you were doing.'));

if (!defined('ENABLE_SSL')) define('ENABLE_SSL',0);
if (!defined('SSL_URL')) define('SSL_URL','https://my.domain/');
if (!defined('NONSSL_URL')) define('NONSSL_URL','http://my.domain/');

if (!defined('FILE_DEFAULT_MODE_STR')) define('FILE_DEFAULT_MODE_STR','0700');
if (!defined('FILE_DEFAULT_MODE')) define('FILE_DEFAULT_MODE',octdec(FILE_DEFAULT_MODE_STR+0));
if (!defined('DIR_DEFAULT_MODE_STR')) define('DIR_DEFAULT_MODE_STR','0700');
if (!defined('DIR_DEFAULT_MODE')) define('DIR_DEFAULT_MODE',octdec(DIR_DEFAULT_MODE_STR+0));

if (!defined('USE_LANG')) define('USE_LANG','en');

?>