<?php

define('TR_CONFIG_SITE_TITLE','General Site Configuration');

define('TR_CONFIG_SITE_SITE_TITLE','Site Title');
define('TR_CONFIG_SITE_SITE_TITLE_DESC','The title of the website.');

define('TR_CONFIG_SITE_USE_LANG','Interface Language');
define('TR_CONFIG_SITE_USE_LANG_DESC','What language should be used for the Exponent interface?');

define('TR_CONFIG_SITE_ALLOW_REGISTRATION','Allow Registration?');
define('TR_CONFIG_SITE_ALLOW_REGISTRATION_DESC','Whether or not new users should be allowed to create accounts for themselves.');

define('TR_CONFIG_SITE_USE_CAPTCHA','Use CAPTCHA Test?');
define('TR_CONFIG_SITE_USE_CAPTCHA_DESC','A CAPTCHA (Computer Automated Public Turing Test to Tell Computers and Humans Apart) is a means to prevent massive account registration.  When registering a new user account, the visitor will be required to enter a series of letters and numbers appearing in an image.  This prevents scripted bots from registering a large quantity of accounts.');

define('TR_CONFIG_SITE_KEYWORDS','Keywords');
define('TR_CONFIG_SITE_KEYWORDS_DESC','Search engine keywords for the site.');

define('TR_CONFIG_SITE_DESCRIPTION','Description');
define('TR_CONFIG_SITE_DESCRIPTION_DESC','A description of what the site is about.');

define('TR_CONFIG_SITE_404','"Not Found" Error Text');
define('TR_CONFIG_SITE_404_DESC','HTML to show to a user when they try to request something that is not found (like a deleted post, section etc.)');

define('TR_CONFIG_SITE_403','"Access Denied" Error Text');
define('TR_CONFIG_SITE_403_DESC','HTML to show to a user when they try to perform some action that their user account is not allowed to perform.');

define('TR_CONFIG_SITE_DEFAULT_SECTION','Default Section');
define('TR_CONFIG_SITE_DEFAULT_SECTION_DESC','The default section.');

define('TR_CONFIG_SITE_SESSION_TIMEOUT','Session Timeout');
define('TR_CONFIG_SITE_SESSION_TIMEOUT_DESC','How long a user can be idle (in seconds) before they are automatically logged out.');

define('TR_CONFIG_SITE_TIMEOUT_ERROR','"Session Expired" Error Text');
define('TR_CONFIG_SITE_TIMEOUT_ERROR_DESC','HTML to show to a user when their session expires and they are trying to perform some action that requires them to have certain permissions.');

define('TR_CONFIG_SITE_FILEPERMS','Default File Permissions');
define('TR_CONFIG_SITE_FILEPERMS_DESC','The readability / writability of uploaded files, for users other than the web server user.');

define('TR_CONFIG_SITE_DIRPERMS','Default Directory Permissions');
define('TR_CONFIG_SITE_DIRPERMS_DESC','The readability / writability of created directories, for users other than the web server user.');

define('TR_CONFIG_SITE_ENABLE_SSL','Enable SSL Support');
define('TR_CONFIG_SITE_ENABLE_SSL_DESC','Whether or not to turn on Secure Linking through SSL');

define('TR_CONFIG_SITE_NONSSL_URL','Non-SSL URL Base');
define('TR_CONFIG_SITE_NONSSL_URL_DESC','Full URL of the website without SSL support (usually starting with "http://")');

define('TR_CONFIG_SITE_SSL_URL','SSL URL Base');
define('TR_CONFIG_SITE_SSL_URL_DESC','Full URL of the website with SSL support (usually starting with "https://")');

?>