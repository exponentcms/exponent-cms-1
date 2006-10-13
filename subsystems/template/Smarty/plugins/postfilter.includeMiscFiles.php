<?php
##################################################
#
# Copyright (c) 2005-2006 Maxim Mueller
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
# 
##################################################
/* exdoc
 * 
 * This function creates html loaders for - currently - JS and CSS Files
 * Please note it wil only work for newtype __names (SomeModule, SomeForm, SomeTheme, SomeControl...)
 */
function smarty_postfilter_includeMiscFiles($compiledsource,&$smarty) {		
	ob_start();

		//CSS	
		$myCSS = exponent_core_resolveFilePaths("guess", $smarty->_tpl_vars['__name'], "css", $smarty->_tpl_vars['__view'] . "*");
		
		if($myCSS != false) {
			foreach($myCSS as $myCSSFile){
				echo "<link rel='stylesheet' type='text/css' href='" . exponent_core_abs2rel($myCSSFile) . "'></link>";
			}
		}
		
		//JavaScript
		$myJS = exponent_core_resolveFilePaths("guess", $smarty->_tpl_vars['__name'], "js", $smarty->_tpl_vars['__view'] . "*");
		
		if($myJS != false) {
			foreach($myJS as $myJSFile){
				echo "<script type='text/javascript' src='" . exponent_core_abs2rel($myJSFile) . "'></script>";
			}
		}
	
	$html = ob_get_contents();
	ob_end_clean();
	return $html . $compiledsource;

}
?>