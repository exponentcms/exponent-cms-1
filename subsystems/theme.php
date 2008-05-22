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

/* exdoc
 * The definition of this constant lets other parts of the system know
 * that the subsystem has been included for use.
 * @node Subsystems:Theme
 */
define("SYS_THEME",1);

$css_files = array();  // This array keeps track of all the css files that need to be included via the minify script
$jsfiles = array();

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function exponent_theme_loadCommonCSS() {
	global $css_files;

	$commondir = 'themes/common/css';

	if (is_dir(BASE . $commondir) && is_readable(BASE . $commondir) ) {
		$dh = opendir(BASE . $commondir);
		while (($cssfile = readdir($dh)) !== false) {
			$filename = BASE . $commondir.'/'.$cssfile;
			if ( is_file($filename) && substr($filename,-4,4) == ".css") {
				$css_files["common-".substr($cssfile,0,-4)] = URL_FULL.$commondir.'/'.$cssfile;
				if (is_readable('themes/'.DISPLAY_THEME_REAL.'/css/'.$cssfile)) {
					$css_files["usertheme-".substr($cssfile,0,-4)] = URL_FULL.'themes/'.DISPLAY_THEME_REAL.'/css/'.$cssfile;
				} elseif (is_readable('themes/'.DISPLAY_THEME_REAL.'/'.$cssfile)) {
					$css_files["usertheme-".substr($cssfile,0,-4)] = URL_FULL.'themes/'.DISPLAY_THEME_REAL.'/'.$cssfile;
				}
			}
		}
	}
}

function exponent_theme_resetCSS() {
	global $css_files;
	$css_files = array_merge(array("reset-fonts-grids"=>URL_FULL."external/yui/build/reset-fonts-grids/reset-fonts-grids.css"), $css_files);
}

function exponent_theme_loadRequiredCSS() {
	global $css_files;

	$requireddir = 'themes/common/css/required/';
	$requiredthemedir = 'themes/'.DISPLAY_THEME_REAL.'/css/required/';
	if (is_dir(BASE . $requireddir) && is_readable(BASE . $requireddir) ) {
		$dh = opendir( BASE . $requireddir);
		while (($cssfile = readdir($dh)) !== false) {
			$filename = BASE . $requireddir.$cssfile;
			$themefilename = $requiredthemedir.$cssfile;
			if ( is_file($filename) && substr($filename,-4,4) == ".css") {
				$css_files["common-required-".substr($cssfile,0,-4)] = URL_FULL.$requireddir.$cssfile;
			}
			if (is_file($themefilename) && substr($themefilename,-4,4) == ".css") {
				$css_files["theme-required-".substr($cssfile,0,-4)] = URL_FULL.$requiredthemedir.$cssfile;
			}
		}
	}
	//eDebug($css_files);
}

/* exdoc
 * @function include_css()
 * checks for a css document to include on your page.
 * checks first in your theme/css/ folder, then in the 
 * root of your theme, then in themes/common/css/
 * @node Subsystems:Theme
 */
function exponent_theme_includeThemeCSS($files = array()) {
	global $css_files;
	if (empty($files)) {
		global $css_files;
		//exponent_theme_resetCSS();
		//exponent_theme_loadYUICSS(array('menu'));
		//exponent_theme_loadExpDefaults();

		$cssdirs = array('themes/'.DISPLAY_THEME_REAL.'/css/', 'themes/'.DISPLAY_THEME_REAL.'/');
		
		foreach ($cssdirs as $cssdir) {
				if (is_dir($cssdir) && is_readable($cssdir) ) {
						$dh = opendir($cssdir);
						while (($cssfile = readdir($dh)) !== false) {
								$filename = $cssdir.$cssfile;
								if ( is_file($filename) && substr($filename,-4,4) == ".css" && !array_key_exists(substr("usertheme-".$cssfile,0,-4), $css_files)) {
										$css_files["usertheme-".substr($cssfile,0,-4)] = URL_FULL.$cssdir.$cssfile;
								}
						}
				}
		}
	} else {	
		foreach ($files as $file) {
			if (is_readable( BASE . 'themes/'.DISPLAY_THEME_REAL.'/css/'.$file)) {
				$css_files[] = URL_FULL.'themes/'.DISPLAY_THEME_REAL.'/css/'.$file;
			} elseif (is_readable(BASE . 'themes/'.DISPLAY_THEME_REAL.'/'.$file)) {
				$css_files[] = URL_FULL.'themes/'.DISPLAY_THEME_REAL.'/'.$file;
			}
		}
	}
	return $css_files;
	//eDebug($css_files);
}

function css_file_needs_rebuilt() {
	if (DEVELOPMENT > 0 || !is_readable(BASE.'tmp/css/exp-styles-min.css')) {
		return true;
	} else {
		return false;
	}
}


function rebuild_css(){
	if (css_file_needs_rebuilt()) {
		global $css_files;
		//eDebug($css_files);
		//exit;
		// Load the Minify library if needed.                 
		include_once(BASE.'external/minify/minify.php');                 
		// Create new Minify objects.                 
		$minifyCSS = new Minify(Minify::TYPE_CSS);                         

		// Specify the files to be minified. Full URLs are allowed as long as they point                 
		// to the same server running Minify. 
	       	$minifyCSS->addFile($css_files);

		// Establish the file where we will build the compiled CSS file
	       	$compiled_file = fopen(BASE.'tmp/css/exp-styles-min.css', 'w');
		//	eDebug($minifyCSS->combine());

		fwrite($compiled_file, $minifyCSS->combine());
		fclose($compiled_file);
	}
}

function exponent_theme_remove_css() {
	if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
	return exponent_files_remove_files_in_directory(BASE.'tmp/css');
}

function exponent_theme_remove_smarty_cache() {
	if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
	return exponent_files_remove_files_in_directory(BASE.'tmp/views_c');	
}

function exponent_theme_loadYUIJS($files=array()) {
		global $jsfiles;
		exponent_theme_buildYUIPaths();
		//eDebug($jsfiles);
		$files_to_load = array();
		$files_to_string = "";
		//manually add yui-dom-event, since it doen't have a -min version'
	//		$files_to_string .= "\t".'<script type="text/javascript" src="'.$jsfiles['yahoo-dom-event'].'"></script>'."\r\n";
		foreach($files as $filename) {
			if ($filename!="yahoo-dom-event") $filename = $filename."-min"; 
			if (array_key_exists($filename, $jsfiles)) {	
				$files_to_string .= "\t".'<script type="text/javascript" src="'.$jsfiles[$filename].'"></script>'."\r\n";
			}
		}	
		return $files_to_string;
}

function exponent_theme_buildCSSFile($cssfile) {
	if (DEVELOPMENT > 0 || !is_readable(BASE.$cssfile)) {
		global $css_files;
		define('MINIFY_CACHE_DIR',BASE.'tmp/minify'); 			// Define the cache dir first.
		if (!is_dir(BASE.'tmp/minify')) mkdir(BASE.'tmp/minify');	//if the dir doesnt exist- create it  
		include_once(BASE.'external/minify/minify.php');		// Load the Minify library if needed.                 
		
		// Create new Minify objects.                 
		$minifyCSS = new Minify(Minify::TYPE_CSS);                         

		// Specify the files to be minified. Full URLs are allowed as long as they point to the same server running Minify. 
	       	$minifyCSS->addFile($css_files);

		// Establish the file where we will build the compiled CSS file
	       	$compiled_file = fopen(BASE . $cssfile, 'w');
		//	eDebug($minifyCSS->combine());

		fwrite($compiled_file, $minifyCSS->combine());
		fclose($compiled_file);
	}
}

function exponent_theme_includeCSS($cssfile) {
	$str = "";
	if (DEVELOPMENT == 0) {
		$str = "\t".'<link rel="stylesheet" type="text/css" href="'.URL_FULL.$cssfile.'" '.XHTML_CLOSING.'>'."\r\n";
	} else {
		global $css_files;
		foreach ($css_files as $file) {
			$str .= "\t".'<link rel="stylesheet" type="text/css" href="'.$file.'" '.XHTML_CLOSING.'>'."\r\n";
		}
	}

	return $str;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function exponent_theme_headerInfo($config) {
	echo headerInfo($config);
}

function headerInfo($config) {
	global $sectionObj, $user;
	
	$langinfo = include(BASE.'subsystems/lang/'.LANG.'.php');

	if(!isset($config['include-common-css'])) $config['include-common-css']=false;
	if(!isset($config['include-theme-css'])) $config['include-theme-css']=true;
	if(empty($config['css-file'])) $cssfile = 'tmp/css/exp-styles-min.css';
	if(empty($config['xhtml'])||($config['xhtml']==false)){ define("XHTML",0);define("XHTML_CLOSING","");} else {define("XHTML",1); define("XHTML_CLOSING","/");}

	// load all the required CSS files for the user.
	exponent_theme_loadRequiredCSS();
	//load all configs from user's theme
	if(!empty($config['reset-fonts-grids'])) exponent_theme_resetCSS();
	if($config['include-common-css']!=false) exponent_theme_loadCommonCSS();
	if($config['include-theme-css']!=false) exponent_theme_includeThemeCSS();
	
	// Build the CSS file
	exponent_theme_buildCSSFile($cssfile);

	$str = '';
	if ($sectionObj != null) {
		$str = '<title>'.($sectionObj->page_title == "" ? SITE_TITLE : $sectionObj->page_title)."</title>\r\n";
		$str .= "\t".'<meta http-equiv="Content-Type" content="text/html; charset='.$langinfo['charset'].'" '.XHTML_CLOSING.'>'."\n";
		$str .= "\t".'<meta name="Generator" content="Exponent Content Management System - '.EXPONENT_VERSION_MAJOR.'.'.EXPONENT_VERSION_MINOR.'.'.EXPONENT_VERSION_REVISION.'.'.EXPONENT_VERSION_TYPE.'" '.XHTML_CLOSING.'>' . "\n";
		$str .= "\t".'<meta name="Keywords" content="'.($sectionObj->keywords == "" ? SITE_KEYWORDS : $sectionObj->keywords) . '" />'."\n";
		$str .= "\t".'<meta name="Description" content="'.($sectionObj->description == "" ? SITE_DESCRIPTION : $sectionObj->description) . '" '.XHTML_CLOSING.'>'."\n";
		
		//the last little bit of IE 6 support
		$str .= "\t".'<!--[if IE 6]><style type="text/css"> img { behavior: url(external/png-opacity.htc); } body { behavior: url(external/csshover.htc); }</style><![endif]-->'."\n";
		
		$str .= exponent_theme_includeCSS($cssfile);	
		$str .= "\t".'<script type="text/javascript" src="'.URL_FULL.'external/yui/build/yuiloader-dom-event/yuiloader-dom-event.js"></script>'."\r\n";
		$str .= "\t".'<script type="text/javascript" src="'.URL_FULL.'exponent.js.php"></script>'."\r\n";
		if($user->id!=0){
			$secloc = exponent_core_makeLocation("navigationmodule", '', $sectionObj->id);
			if (exponent_permissions_checkOnModule("administrate","containermodule")||
				exponent_permissions_checkOnModule("add_module","containermodule")||
				exponent_permissions_checkOnModule("edit_module","containermodule")||
				exponent_permissions_checkOnModule("delete_module","containermodule")||
				exponent_permissions_checkOnModule("order_modules","containermodule") || 
				exponent_permissions_check('manage', $secloc)
				){
				//$str .= "\t".'<script type="text/javascript" src="'.URL_FULL.'/themes/common/javascript/containermodule/containermenus.js"></script>'."\r\n";
				$str .= "\t".'<script type="text/javascript">YAHOO.namespace ("expadminmenus");</script>'."\r\n";
			}
		}
		if(file_exists(BASE.'themes/'.DISPLAY_THEME_REAL.'/favicon.ico')) {
		    $str .= "\t".'<link rel="shortcut icon" href="'.URL_FULL.'themes/'.DISPLAY_THEME_REAL.'/favicon.ico" type="image/x-icon" />'."\r\n";
		}
	}
	return $str;
}

function exponent_theme_footerInfo() {
	footerInfo();
}

function footerInfo() {
	//exponent_theme_showModule("administrationmodule","_panel");
	exponent_javascript_outputJStoDOMfoot();
	rebuild_css();	
	exponent_sessions_unset("last_POST");  //ADK - putting this here so one form doesn't unset it before another form needs it.
}



/* exdoc
 * Prints the HTML for the Source Selector header table.  This is required
 * of all themes, so that the source selector allows users to browse to Archived
 * content.
 * @node Subsystems:Theme
 */
function exponent_theme_sourceSelectorInfo() {
	if (defined("SOURCE_SELECTOR") || defined("CONTENT_SELECTOR")) {
		$i18n = exponent_lang_loadFile('subsystems/theme.php');
		?>
		<script type="text/javascript">
		window.resizeTo(800,600);
		</script>
		<table cellspacing="0" cellpadding="5" width="100%" border="0">
			<tr>
				<td width="70%">
					<b><?php echo $i18n['selector']; ?></b>
				</td>
				<td width="30%" align="right">
					[ <a class="mngmntlink" href="orphan_source_selector.php"><?php echo $i18n['archived_content']; ?></a> ]
				</td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="5" width="100%" border="0">
			<tr>
				<td colspan="2" style="background-color: #999; color: #fff; border-bottom: 1px solid #000; padding-bottom: .5em;">
					<i><?php echo $i18n['selector_desc']; ?></i>
				</td>
			</tr>
		</table>
		<?php
	}
}

/* exdoc
 * Calls the necessary methods to show a specific module, in a section-sensitive way.
 *
 * @param string $module The classname of the module to display
 * @param string $view The name of the view to display the module with
 * @param string $title The title of the module (support is view-dependent)
 * @param string $prefix The prefix of the module's source.	 The current section id will be appended to this
 * @param bool $pickable Whether or not the module is pickable in the Source Picer.
 * @node Subsystems:Theme
 */
function exponent_theme_showSectionalModule($module,$view,$title,$prefix = null, $pickable = false, $hide_menu=false) {
	global $db;

	if ($prefix == null) $prefix = "@section";

	$src = $prefix;

	if (exponent_sessions_isset("themeopt_override")) {
		$config = exponent_sessions_get("themeopt_override");
		if (in_array($module,$config['ignore_mods'])) return;
		$src = $config['src_prefix'].$prefix;
		$section = null;
	} else {
		global $sectionObj;
		//$last_section = exponent_sessions_get("last_section");
		//$section = $db->selectObject("section","id=".$last_section);
		$src .= $sectionObj->id;
	}


	exponent_theme_showModule($module,$view,$title,$src,$pickable,$sectionObj->id, $hide_menu);
}

/* exdoc
 * Calls the necessary methods to show a specific module in such a way that the current
 * section displays the same content as its top-level parent and all of the top-level parent's
 * children, grand-children, grand-grand-children, etc.
 *
 * @param string $module The classname of the module to display
 * @param string $view The name of the view to display the module with
 * @param string $title The title of the module (support is view-dependent)
 * @param string $prefix The prefix of the module's source.	 The current section id will be appended to this
 * @param bool $pickable Whether or not the module is pickable in the Source Picer.
 * @node Subsystems:Theme
 */
function exponent_theme_showTopSectionalModule($module,$view,$title,$prefix = null, $pickable = false) {
	global $db;

	if ($prefix == null) $prefix = "@section";
	$last_section = exponent_sessions_get("last_section");

	$section = $db->selectObject("section","id=".$last_section);
	// Loop until we find the top level parent.
	while ($section->parent != 0) $section = $db->selectObject("section","id=".$section->parent);

	exponent_theme_showModule($module,$view,$title,$prefix.$section->id,$pickable,$section);
}

/* exdoc
 * Calls the necessary methods to show a specific module
 *
 * @param string $module The classname of the module to display
 * @param string $view The name of the view to display the module with
 * @param string $title The title of the module (support is view-dependent)
 * @param string $source The source of the module.
 * @param bool $pickable Whether or not the module is pickable in the Source Picer.
 * @node Subsystems:Theme
 */
function exponent_theme_showModule($module,$view = "Default",$title = "",$source = null,$pickable = false,$section = null, $hide_menu=false) {
	if (!AUTHORIZED_SECTION && $module != 'navigationmodule' && $module != 'loginmodule') {
		return;
	}
	global $db, $sectionObj;
	// Ensure that we have a section
	//FJD - changed to $sectionObj
	if ($sectionObj == null) {
		$section_id = exponent_sessions_get('last_section');
		if ($section_id == null) {
			$section_id = SITE_DEFAULT_SECTION;
		}
		$sectionObj = $db->selectObject('section','id='.$section_id);
		//$section->id = $section_id;
	}
	if ($module == "loginmodule" && defined("PREVIEW_READONLY") && PREVIEW_READONLY == 1) return;

	if (exponent_sessions_isset("themeopt_override")) {
		$config = exponent_sessions_get("themeopt_override");
		if (in_array($module,$config['ignore_mods'])) return;
	}
	$loc = exponent_core_makeLocation($module,$source."");

	if ($db->selectObject("locationref","module='$module' AND source='".$loc->src."'") == null) {
		$locref = null;
		$locref->module = $module;
		$locref->source = $loc->src;
		$locref->internal = "";
		$locref->refcount = 1000;
		$db->insertObject($locref,"locationref");
		if ($sectionObj != null) {
			$locref->section = $sectionObj->id;
			$locref->is_original = 1;
			$db->insertObject($locref,'sectionref');
		}
	}

	if (defined("SELECTOR") && call_user_func(array($module,"hasSources"))) {
		containermodule::wrapOutput($module,$view,$loc,$title);
	} else {
		if (is_callable(array($module,"show"))) {
			if (!$hide_menu && $loc->mod != "containermodule" && (call_user_func(array($module,"hasSources")) || $db->tableExists($loc->mod."_config"))) {
				$container->permissions = array(
                                	'administrate'=>(exponent_permissions_check('administrate',$loc) ? 1 : 0),
                                	'configure'=>(exponent_permissions_check('configure',$loc) ? 1 : 0)
                        	);

				if ($container->permissions['administrate'] || $container->permissions['configure']) {
					$container->randomizer = ceil(microtime(1));
					$container->view = $view;
					$container->info['class'] = $loc->mod;
					$container->info['module'] = call_user_func(array($module,"name"));
					$container->info['source'] = $loc->src;
					$container->info['hasConfig'] = $db->tableExists($loc->mod."_config");
					$template = new template('containermodule','_hardcoded_module_menu',$loc);
					$template->assign('container', $container);
					$template->output();
				}
			}
			call_user_func(array($module,"show"),$view,$loc,$title);
		} else {
			$i18n = exponent_lang_loadFile('subsystems/theme.php');
			echo sprintf($i18n['mod_not_found'],$module);
		}
	}
}

/* exdoc
 * Checks to see if the page is currently in an action.	 Useful only if the theme does not use the exponent_theme_main() function
 * Returns whether or not an action should be run.
 * @node Subsystems:Theme
 */
function exponent_theme_inAction() {
	return (isset($_REQUEST['action']) && (isset($_REQUEST['module']) || isset($_REQUEST['controller'])));
	//return (isset($_REQUEST['action']) && isset($_REQUEST['module']) );
}

/* exdoc
 * Checks to see if the user is authorized to view the current section.
 * Retursn whether or not the user is authorized.
 * @node Subsystems:Theme
 */
function exponent_theme_canViewPage() {
	return AUTHORIZED_SECTION;
	/*
	global $db;
	$last_section = exponent_sessions_get("last_section");
	$section = $db->selectObject("section","id=".$last_section);
	if ($section && navigationModule::canView($section)) {
		$sloc = exponent_core_makeLocation("navigationmodule","",$section->id);
		return exponent_permissions_check("view",$sloc);
	} else return true;
	*/
}

/*	exdoc
 * Looks at the attributes of the current section and properly calls exponent_flow_set
 * @node Subsystems:Theme
 */
function exponent_theme_setFlow() {
	if ((!defined("SOURCE_SELECTOR") || SOURCE_SELECTOR == 1) && (!defined("CONTENT_SELECTOR") || CONTENT_SELECTOR == 1)) {
		global $db;
		$last_section = exponent_sessions_get("last_section");
		$section = $db->selectObject("section","id=".$last_section);

		if ($section && $section->public == 0) {
			exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_SECTIONAL);
		} else if ($section && $section->public == 1) {
			exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_SECTIONAL);
		}
	}
}

/* exdoc
 * Takes care of all the specifics of either showing a sectional container or running an action.
 * @node Subsystems:Theme
 */
function exponent_theme_main() {
	global $db, $user;

	echo show_msg_queue();
	if ((!defined("SOURCE_SELECTOR") || SOURCE_SELECTOR == 1) && (!defined("CONTENT_SELECTOR") || CONTENT_SELECTOR == 1)) {
		$last_section = exponent_sessions_get("last_section");
		$section = $db->selectObject("section","id=".$last_section);
		// View authorization will be taken care of by the runAction and mainContainer functions
		if (exponent_theme_inAction()) {
			exponent_theme_runAction();
		} else if ($section == null) {
			exponent_theme_goDefaultSection();
		} else {
			exponent_theme_mainContainer();
		}
	} else {
		if (isset($_REQUEST['module'])) {
			include_once(BASE."modules/containermodule/actions/orphans_content.php");
		} else {
			$i18n = exponent_lang_loadFile('subsystems/theme.php');
			echo $i18n['select_module'];
		}
	}
}

/* exdoc
 * Runs the approriate action, by looking at the $_REQUEST variable.
 * @node Subsystems:Theme
 */
function exponent_theme_runAction() {

	if (exponent_theme_inAction()) {
		if (!AUTHORIZED_SECTION) {
			echo SITE_403_HTML;
		//	return;
		}
		if (exponent_sessions_isset("themeopt_override")) {
			$config = exponent_sessions_get("themeopt_override");
			echo "<a class='mngmntlink sitetemplate_mngmntlink' href='".$config['mainpage']."'>".$config['backlinktext']."</a><br /><br />";
		}

		if (isset($_REQUEST['controller'])) {
                      echo renderAction($_REQUEST); 
                } else {
			if ($_REQUEST['action'] == 'index') {
				$view = empty($_REQUEST['view']) ? 'Default' : $_REQUEST['view'];			
				$title = empty($_REQUEST['title']) ? '' : $_REQUEST['title'];	
				$src = empty($_REQUEST['src']) ? null : $_REQUEST['src'];		
				exponent_theme_showModule($_REQUEST['module'], $view, $title, $src);
				return true;
			}

			global $db, $user;

			$loc = null;
			$loc->mod = $_REQUEST['module'];
			$loc->src = (isset($_REQUEST['src']) ? $_REQUEST['src'] : "");
			$loc->int = (isset($_REQUEST['int']) ? $_REQUEST['int'] : "");

			$actfile = "/" . $_REQUEST['module'] . "/actions/" . $_REQUEST['action'] . ".php";
			if (isset($_REQUEST['_common'])) $actfile = "/common/actions/" . $_REQUEST['action'] . ".php";

			if (is_readable(BASE."themes/".DISPLAY_THEME_REAL."/modules".$actfile)) {
				include_once(BASE."themes/".DISPLAY_THEME_REAL."/modules".$actfile);
			} elseif (is_readable(BASE.'modules/'.$actfile)) {
				include_once(BASE.'modules/'.$actfile);
			} else {
				$i18n = exponent_lang_loadFile('subsystems/theme.php');
				echo SITE_404_HTML . '<br /><br /><hr size="1" />';
				echo sprintf($i18n['no_action'],strip_tags($_REQUEST['module']),strip_tags($_REQUEST['action']));
				echo '<br />';
			}
		}
	}
}

function exponent_theme_showAction($module, $action, $src="", $params="") {
	
	global $db, $user;

		$loc = null;
		$loc->mod = $module;
		$loc->src = (isset($src) ? $src : "");
		$loc->int = (isset($int) ? $int : "");

		$actfile = "/" . $module . "/actions/" . $action . ".php";
		//if (isset($['_common'])) $actfile = "/common/actions/" . $_REQUEST['action'] . ".php";

		if (is_readable(BASE."themes/".DISPLAY_THEME_REAL."/modules".$actfile)) {
			include(BASE."themes/".DISPLAY_THEME_REAL."/modules".$actfile);
		} elseif (is_readable(BASE.'modules/'.$actfile)) {
				include(BASE.'modules/'.$actfile);
		} else {
				$i18n = exponent_lang_loadFile('subsystems/theme.php');
				echo SITE_404_HTML . '<br /><br /><hr size="1" />';
				echo sprintf($i18n['no_action'],strip_tags($_REQUEST['module']),strip_tags($_REQUEST['action']));
				echo '<br />';
		}
}

/* exdoc
 * Redirect User to Default Section
 * @node Subsystems:Theme
 */
function exponent_theme_goDefaultSection() {
	$last_section = exponent_sessions_get("last_section");
	if (defined("SITE_DEFAULT_SECTION") && SITE_DEFAULT_SECTION != $last_section) {
		header("Location: ".URL_FULL."index.php?section=".SITE_DEFAULT_SECTION);
		exit();
	} else {
		global $db;
		$section = $db->selectObject("section","public = 1 AND active = 1"); // grab first section, go there
		if ($section) {
			header("Location: ".URL_FULL."index.php?section=".$section->id);
			exit();
		} else {
			echo SITE_404_HTML;
		}
	}
}

/* exdoc
 * Useful only if theme does not use exponent_theme_main
 *
 * @param bool $public Whether or not the page is public.
 * @node Subsystems:Theme
 */
function exponent_theme_mainContainer() {
	if (!AUTHORIZED_SECTION) {
		// Set this so that a login on an Auth Denied page takes them back to the previously Auth-Denied page
		exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_SECTIONAL);
		echo SITE_403_HTML;
		return;
	}

	if (PUBLIC_SECTION) exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_SECTIONAL);
	else exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_SECTIONAL);

#	if (exponent_sessions_isset("themeopt_override")) {
#		$config = exponent_sessions_get("themeopt_override");
		exponent_theme_showSectionalModule("containermodule","Default","","@section",false,true);
#	} else {
#		exponent_theme_showSectionalModule("containermodule","Default","","@section");
#	}
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function exponent_theme_getSubthemes($include_default = true,$theme = DISPLAY_THEME) {
	$base = BASE."themes/$theme/subthemes";
	// The array of subthemes.	If the theme has no subthemes directory,
	// or the directory is not readable by the web server, this empty array
	// will be returned (Unless the caller wanted us to include the default layout)
	$subs = array();
	if ($include_default == true) {
		// Caller wants us to include the default layout.
		$subs[''] = DEFAULT_VIEW; // Not really its intended use, but it works.
	}

	if (is_readable($base)) {
		// subthemes directory exists and is readable by the web server.  Continue on.
		$dh = opendir($base);
		// Read out all entries in the THEMEDIR/subthemes directory
		while (($s = readdir($dh)) !== false) {
			if (substr($s,-4,4) == '.php' && substr($s,0,1) != '_' && is_file($base."/$s") && is_readable($base."/$s")) {
				// Only readable .php files are allowed to be subtheme files.
				$subs[substr($s,0,-4)] = substr($s,0,-4);
			}
		}
		// Sort the subthemes by their keys (which are the same as the values)
		// using a natural string comparison funciton (PHP built-in)
		uksort($subs,'strnatcmp');
	}
	return $subs;
}

function exponent_theme_getPrinterFriendlyTheme() {
	$common = 'themes/common/printer-friendly/index.php';
	$theme = 'themes/'.DISPLAY_THEME.'/printer-friendly/index.php';

	if (is_readable($theme)) {
		return $theme;
		} elseif (is_readable($common)) {
		return $common;
	} else {
		return null;
	}

}

function exponent_theme_getTheme() {
	global $sectionObj;
	$action_maps = exponent_theme_loadActionMaps();

	if (exponent_theme_inAction() && !empty($action_maps[$_REQUEST['module']]) && array_key_exists($_REQUEST['action'], $action_maps[$_REQUEST['module']])) {
		if ($action_maps[$_REQUEST['module']][$_REQUEST['action']]=="default"){
			$theme = BASE.'themes/'.DISPLAY_THEME.'/index.php';
		} else {
			$theme = BASE.'themes/'.DISPLAY_THEME.'/subthemes/'.$action_maps[$_REQUEST['module']][$_REQUEST['action']].'.php';
		}
		return $theme;
	} elseif ($sectionObj->subtheme != '' && is_readable(BASE.'themes/'.DISPLAY_THEME.'/subthemes/'.$sectionObj->subtheme.'.php')) {
                return BASE.'themes/'.DISPLAY_THEME.'/subthemes/'.$sectionObj->subtheme.'.php';
	} else {
                return BASE.'themes/'.DISPLAY_THEME.'/index.php';
	}
}

function exponent_theme_loadActionMaps() {
	if (is_readable(BASE.'themes/'.DISPLAY_THEME.'/action_maps.php')) {
		return include(BASE.'themes/'.DISPLAY_THEME.'/action_maps.php');
	} else {
		return array();
	}
}

?>
