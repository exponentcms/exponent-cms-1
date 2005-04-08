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

if (!defined('PATHOS')) exit('');

/**
 * HTML Editor Control
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 * @version 0.95
 *
 * @package Subsystems
 * @subpackage Forms
 */

/**
 * Manually include the class file for formcontrol, for PHP4
 * (This does not adversely affect PHP5)
 */
include_once(BASE."subsystems/forms/controls/formcontrol.php");

/**
 * HTML Editor Control
 *
 * @package Subsystems
 * @subpackage Forms
 */class htmleditorcontrol extends formcontrol {
	var $module = "";
	
	function name() { return "WYSIWYG Editor"; }
	
	function htmleditorcontrol($default="",$module = "",$rows = 20,$cols = 60) {
		$this->default = $default;
		$this->module = $module; // For looking up templates.
	}
	
	function controlToHTML($name) {
		ob_start();
		if (!defined("CTL_HTMLAREAINIT")) {
			// We are the first htmleditor control.  Set up basic initializations
			
			global $db;
			// Handle associations some day.
			$templates = $db->selectObjects("htmltemplate");
			
			?>
			<!-- Basic setup for HTMLArea -->
			<script type="text/javascript">
			_editor_url = "<?php echo PATH_RELATIVE."external/htmlarea/"; ?>";
			_editor_lang = "en";
			</script>
			<!-- Pull in HTMLAREA source -->
			<script type="text/javascript" src="<?php echo PATH_RELATIVE."external/htmlarea/"; ?>htmlarea.js"></script>
			<!-- Setup initialization functions for htmleditorcontrol -->
			<script type="text/javascript">
			// Setup the basic HTMLArea environment
			HTMLArea.loadPlugin("ContextMenu");
			HTMLArea.loadPlugin("Template");
			
			var htmleditorcontrols = new Array(); // an array of textareanames.
			var editors = new Array();
			
			// Register an initialization function with the Pathos JS Support System.
			// This will be called onLoad (assuming the theme is playing nice)
			var once = false;
			pathosJSregister(function () {
				for (i = 0; i < htmleditorcontrols.length; i++) {
					editors[i] = new HTMLArea(htmleditorcontrols[i]);
					editors[i].config = htmleditorconfig;
					if (!once) {
						if (typeof TableOperations != "undefined") {
							editors[i].registerPlugin(TableOperations);
						}
						if (typeof ContextMenu != "undefined") editors[i].registerPlugin(ContextMenu);
						<?php if (count($templates)) { ?>
						if (typeof Template != "undefined") editors[i].registerPlugin(Template,{
							combos: [
								{	label: "Template",
									options: {"None":""<?php
									foreach ($templates as $template) echo ',"'.$template->title . '":"' . str_replace(array("\n","\r"),"",str_replace('"','\"',$template->body)) . '"';
									?>}
								}
							]
						});
						<?php } ?>
						
						once = true;
					}
					setTimeout("editors["+i+"].generate();",i*500+100);
				}
			});

			var htmleditorconfig = new HTMLArea.Config();
			<?php
			global $db;
			$config = $db->selectObject("htmlareatoolbar","active=1");
			if ($config) {
				$plugins = array();
				echo "htmleditorconfig.toolbar = [ ";
				$data = unserialize($config->data);
				$rowcount = 0;
				foreach ($data as $row) {
					if ($rowcount != 0) echo ",\n";
					echo "[  ";
					
					for ($j = 0; $j < count($row); $j++) {
						echo '"'.$row[$j].'"';
						if ($j != count($row)-1) echo ",";
						if ($row[$j] == "inserttable") $plugins[] = "TableOperations";
					}
					
					echo " ]";
					$rowcount++;
				}
				echo "];\n";
				
				foreach ($plugins as $plug) {
					echo 'HTMLArea.loadPlugin("'.$plug.'");'."\n";
				}
			}
			if (is_readable(THEME_BASE."icons/htmleditorcontrol")) {
				echo "htmleditorconfig.imgURL = '".ICON_RELATIVE."htmleditorcontrol/';\n";
			} else {
				echo "htmleditorconfig.imgURL = '".PATH_RELATIVE."external/htmlarea/toolbaricons/';\n";
			}
			echo "htmleditorconfig.generateButtons();\n";
			?>
			// Configured toolbar.
			htmleditorconfig.pageStyle = "@import url(<?php echo THEME_RELATIVE; ?>editor.css);";
			</script>
			<?php
			define("CTL_HTMLAREAINIT",1);
		}
		// all setup has been done.
		?>
		<script type="text/javascript">
		htmleditorcontrols[htmleditorcontrols.length] = "<?php echo $name; ?>";
		</script>
		<textarea id="<?php echo $name; ?>" name="<?php echo $name; ?>" style="width:100%" rows="24" cols="80"><?php
			echo htmlentities($this->default,ENT_COMPAT,LANG_CHARSET); 
		?></textarea>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function parseData($name, $values, $for_db = false) {
		$html = $values[$name];
		if (trim($html) == "<br />") $html = "";
		return $html;
	}
}

?>
