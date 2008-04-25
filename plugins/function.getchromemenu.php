<?php

##################################################
#
# Copyright (c) 2004-2008 OIC Group, Inc.
# Written and Designed by Adam Kessler
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

function smarty_function_getchromemenu($params,&$smarty) {
	global $router;
	$cloc = $smarty->_tpl_vars['__loc'];
	$module = $params['module'];
	$menu = array();

	if (!empty($params['rank']) && exponent_permissions_check('order_modules', $cloc)) {
		$a = $params['rank'] - 2;
		$b = $params['rank'] - 1;
		$uplink = $router->makeLink(array('module'=>'containermodule', 'action'=>'order', 'a'=>$a, 'b'=>$b));
		$downlink = $router->makeLink(array('module'=>'containermodule', 'action'=>'order', 'a'=>$a, 'b'=>$params['rank']));
		$menu[] = array('text'=>$smarty->_tpl_vars['_TR']['menu_moveup'],"classname"=>"rankup","url"=>$uplink);
		$menu[] = array('text'=>$smarty->_tpl_vars['_TR']['menu_movedown'],"classname"=>"rankdown","url"=>$downlink);
	}

	if ($module->permissions['administrate'] == 1) {
		$userlink = $router->makeLink(array('module'=>$module->info['class'], 'src'=>$module->info['source'], 'action'=>'userperms', '_common'=>1));
		$grouplink = $router->makeLink(array('module'=>$module->info['class'], 'src'=>$module->info['source'], 'action'=>'groupperms', '_common'=>1));
		$menu[] = array("text"=>$smarty->_tpl_vars['_TR']['menu_userperm'], "classname"=>"userperms", "url"=>$userlink);
		$menu[] = array("text"=>$smarty->_tpl_vars['_TR']['menu_groupperm'], "classname"=>"groupperms", "url"=>$grouplink);
	}

	if (!empty($module->id) && exponent_permissions_check('edit_module', $cloc) && $module->permissions['administrate'] == 1) {
		$editlink = $router->makeLink(array('module'=>'containermodule', 'id'=>$module->id, 'action'=>'edit', 'src'=>$module->info['source']));
		$menu[] = array("text"=>$smarty->_tpl_vars['_TR']['menu_confview'], "classname"=>"configview", "url"=>$editlink);
	}

	if ($module->permissions['configure'] == 1 && $module->info['hasConfig']) {
		$configlink = $router->makeLink(array('module'=>$module->info['class'], 'src'=>$module->info['source'], 'action'=>'configure', '_common'=>1));
		$menu[] = array("text"=>$smarty->_tpl_vars['_TR']['menu_confsettings'], "classname"=>"configsettings", "url"=>$configlink);
	}

	if (!empty($module->id) && exponent_permissions_check('delete_module', $cloc)) {
		$deletelink = $router->makeLink(array('module'=>'containermodule', 'id'=>$module->id, 'action'=>'delete', 'rerank'=>1));
		$menu[] = array("text"=>$smarty->_tpl_vars['_TR']['menu_deletemod'], "classname"=>"deletemod", "url"=>$deletelink);
	}

	echo json_encode($menu);
}

?>
