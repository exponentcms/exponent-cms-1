<?php
# see docs/license.txt for licensing

# user access details. all users may use get.php without logging in, but
#   if the following details are filled in, then login will be required
#   for the main KFM application
# for more details, see http://kfm.verens.com/security
//$kfm_username='';
//$kfm_password='';

# what type of database to use
# values allowed: mysql, pgsql, sqlite, sqlitepdo
$kfm_db_type='mysql';

# the following options should only be filled if you are not using sqlite/sqlitepdo as the database
$kfm_db_prefix='kfm_';
$kfm_db_host='mysql.harrisonhills.org';
$kfm_db_name='harrisonhills_org';
$kfm_db_username='hhbc1';
$kfm_db_password='sb3467';
$kfm_db_port=''; # leave blank if using default port


##########
# where are the files located on the hard-drive, relative to the website's root directory?
# In the default example, the user-files are at http://kfm.verens.com/sandbox/UserFiles/
# Note that this is the actual file-system location of the files.
# This value must begin and end in '/'.
$kfm_userfiles='/files/';

# if you want to hide any panels, add them here as a comma-delimited string
# for example, $kfm_hidden_panels='logs,file_details,file_upload,search,directory_properties';
$kfm_hidden_panels='logs,directory_properties';

# what happens if someone double-clicks a file or presses enter on one? use 'return' for FCKeditor
$kfm_file_handler='return'; # values allowed: download, return

# if 'return' is chosen above, do you want to allow multiple file returns?
$kfm_allow_multiple_file_returns=true;

# maximum length of filenames displayed. use 0 to turn this off, or enter the number of letters.
$kfm_files_name_length_displayed=20;

# 1 = users are allowed to delete directories
# 0 = users are not allowed to delete directories
$kfm_allow_directory_delete=1;

# 1 = users are allowed to edit directories
# 0 = users are not allowed to edit directories
$kfm_allow_directory_edit=1;

# 1 = users are allowed to move directories
# 0 = users are not allowed to move directories
$kfm_allow_directory_move=1;

# 1 = users are allowed to create directories
# 0 = user are not allowed create directories
$kfm_allow_directory_create=1;

# 1 = users are allowed to create files
# 0 = users are not allowed to create files
$kfm_allow_file_create=1;

# 1 = users are allowed to delete files
# 0 = users are not allowed to delete files
$kfm_allow_file_delete=1;

# 1 = users are allowed to edit files
# 0 = users are not allowed to edit files
$kfm_allow_file_edit=1;

# 1 = users are allowed to move files
# 0 = users are not allowed to move files
$kfm_allow_file_move=1;

# 1 = users are allowed to upload files
# 0 = user are not allowed upload files
$kfm_allow_file_upload=1;

# use this array to ban dangerous files from being uploaded.
$kfm_banned_extensions=array('asp','cfm','cgi','php','php3','php4','phtm','pl','sh','shtm','shtml');

# you can use regular expressions in this one.
# for exact matches, use lowercase.
# for regular expressions, use eithe '/' or '@' as the delimiter
$kfm_banned_files=array('thumbs.db','/^\./','/^_/');

# this array tells KFM what extensions indicate files which may be edited online.
$kfm_editable_extensions=array('css','html','htm','js','txt','xhtml','xml');

# this array tells KFM what extensions indicate files which may be viewed online.
# the contents of $kfm_editable_extensions will be added automatically.
$kfm_viewable_extensions=array('sql','php');

# 1 = users can only upload images
# 0 = don't restrict the types of uploadable file
$kfm_only_allow_image_upload=0;

# 0 = only errors will be logged
# 1 = everything will be logged
$kfm_log_level=0;

# use this array to show the order in which language files will be checked for
$kfm_preferred_languages=array('en','de','da','es','fr','nl','ga');

# themes are located in ./themes/
# to use a different theme, replace 'default' with the name of the theme's directory.
$kfm_theme='default';

# use ImageMagick's 'convert' program?
$kfm_use_imagemagick=1;

# where is the 'convert' program kept, if you have it installed?


# show files in groups of 'n', where 'n' is a number (helps speed up files display - use low numbers for slow machines)
$kfm_show_files_in_groups_of=10;

# should disabled links be shown (but grayed out and unclickable), or completely hidden?
# you might use this if you want your users to not know what it is that's been disabled, for example.
$kfm_show_disabled_contextmenu_links=1;

# multiple file uploads are handled through the external SWFUpload flash application.
# this can cause difficulties on some systems, so if you have problems uploading, then disable this.
$kfm_use_multiple_file_upload=0;

# seconds between slides in a slideshow
$kfm_slideshow_delay=4;

# allow users to resize/rotate images
$kfm_allow_image_manipulation=1;

# set root folder name
$kfm_root_folder_name='root';

# if you are using a CMS and want to return the file's DB id instead of the URL, set this
$kfm_return_file_id_to_cms=0;

#Permissions for uploaded files.  This only really needs changing if your
#host has a weird permissions scheme.
$kfm_default_upload_permission = '664';

#Listview or icons
$kfm_listview = 0;

###########

/**
 * This setting specifies if you want to use the KFM security. If set to false, no login form will be displayd
 * Note that the user_root_folder setting will not work when the user is the main user
 *
 * Please change this to 'true' if you want to use usernames and passwords.
 */
$use_kfm_security=false;

/**
 * where on the server should the uploaded files be kept?
 * if the first two characters of this setting are './', then the files are relative to the directory that KFM is in.
 * Here are some examples:
 *    $kfm_userfiles_address = '/home/kae/userfiles'; # absolute address in Linux
 *    $kfm_userfiles_address = 'D:/Files';            # absolute address in Windows
 *    $kfm_userfiles_address = './uploads';           # relative address
 */
//$kfm_userfiles_address = '/home/kae/Desktop/userfiles';
$kfm_userfiles_address = '/home/hhbc/harrisonhills.org/files/';

// where should a browser look to find the files?
// This setting assumes that the files are available throught a public address.
// This is not secure. To securely store files, put them outside the public hierarchy, make sure that the setting
// $kfm_userfiles_address is correct and set kfm_url to secure in the admin panel or put in this place the correct
// values for the secure settings if you are not using the admin panel:
// $kfm->setting('kfm_url', '/kfm/'); // Web address of KFM
// $kfm->setting('file_url', 'secure');
// Examples for public accessable files:
//   $kfm_userfiles_output = 'http://thisdomain.com/files/';
//   $kfm_userfiles_output = '/files/';
//$kfm_userfiles_output = '/userfiles/';
$kfm_userfiles_output='http://www.harrisonhills.org/files/';

// directory in which KFM keeps its database and generated files
// if this starts with '/', then the address is absolute. otherwise, it is relative to $kfm_userfiles_address.
// $kfm_workdirectory = '.files';
// $kfm_workdirectory = '/home/kae/files_cache';
// warning: if you use the '/' method, then you must use the get.php method for $kfm_userfiles_output.
$kfm_workdirectory = '.files';

// where is the 'convert' program kept, if you have it installed?
$kfm_imagemagick_path = '/usr/bin/convert';
//$kfm_imagemagick_path='/home/hhbc/bin/convert';

// use server's version of Pear?
$kfm_use_servers_pear = false;

// we would like to keep track of installations, to see how many there are, and what versions are in use.
// if you do not want us to have this information, then set the following variable to '1'.
$kfm_dont_send_metrics = 0;

// hours to offset server time by.
// for example, if the server is in GMT, and you are in Northern Territory, Australia, then the value to use is 9.5
$kfm_server_hours_offset = 1;

// thumb format. use .png if you need transparencies. .jpg for lower file size
$kfm_thumb_format='.jpg';

// what plugin should handle double-clicks by default
$kfm_default_file_selection_handler='return_url';


//define('ERROR_LOG_LEVEL',1); # 0=none, 1=errors, 2=1+warnings, 3=2+notices, 4=3+unknown
//require_once(dirname(__FILE__).'/initialise.php');

/**
 * Ignore DB Session - leave this as "false", unless you are a developer and accessing KFM through an API.
 * Developers: this is for cases where KFM files are included and you just need to use its functions without going through the whole setup.
 */
if(!isset($kfm_do_not_save_session))$kfm_do_not_save_session=false;

/**
 * This function is called in the admin area. To specify your own admin requirements or security, un-comment and edit this function
 */
//	function kfm_admin_check(){
//		return false; // Disable the admin area
//	}

?>
