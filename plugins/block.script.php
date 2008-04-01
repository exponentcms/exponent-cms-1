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

function smarty_block_script($params,$content,&$smarty, &$repeat) {
	if ($content) {
		if (!defined('SYS_JAVBASCRIPT')) require_once(BASE.'subsystems/javascript.php');
		global $userjsfiles;
		
		if (!empty($params['yuimodules'])) {
			$loader = '
				var loader'.$params['unique'].' = new YAHOO.util.YUILoader();
				loader'.$params['unique'].'.base = eXp.URL_FULL+\'external/yui/build/\';
				loader'.$params['unique'].'.require('.$params["yuimodules"].');
				loader'.$params['unique'].'.loadOptional = false;
				loader'.$params['unique'].'.onSuccess = init'.$params['unique'].';
				loader'.$params['unique'].'.insert();
			';
			
			if(!empty($params['src'])){

				$newcontent = '
				function init'.$params['unique'].'(){
					var scriptloader'.$params['unique'].' = new YAHOO.util.YUILoader();
					
					scriptloader'.$params['unique'].'.addModule({
					  name:\'js'.$params['unique'].'\',
					  type:\'js\',
					  fullpath:\''.$params['src'].'\',
					  varName: "js'.$params['unique'].'"
					});
				
					scriptloader'.$params['unique'].'.require("js'.$params['unique'].'"); 

					scriptloader'.$params['unique'].'.onSuccess = function() {
						'.$content.'
					};
					
					scriptloader'.$params['unique'].'.onFailure = function() {
						alert("Loading '.$params['src'].' failed");
					};

					scriptloader'.$params['unique'].'.insert();
					}
				';
				
			} else {
				
			$newcontent = '
				function init'.$params['unique'].'(){
					'.$content.'
				}
			';
			}
			
			$userjsfiles[$smarty->_tpl_vars[__name]][$params['unique']] = $loader.$newcontent;
			
		} else if(!empty($params['src']) && empty($params['yuimodules'])){
				
			$loader = '
				var scriptloader'.$params['unique'].' = new YAHOO.util.YUILoader();
				scriptloader'.$params['unique'].'.addModule({
				  name:\'js'.$params['unique'].'\',
				  type:\'js\',
				  fullpath:'.$params['src'].',
				});
				
				scriptloader'.$params['unique'].'.onSuccess = function() {
					//build the custom tree
				};
				
				scriptloader'.$params['unique'].'.insert();';
				
				$newcontent = '
					function init'.$params['unique'].'(){
						'.$content.'
					}
				';
				$userjsfiles[$smarty->_tpl_vars[__name]][$params['unique']] = $loader.$newcontent;

		} else {
			$userjsfiles[$smarty->_tpl_vars[__name]][$params['unique']] = $content;
		}
	}
	
}


?>