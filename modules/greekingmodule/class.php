<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: class.php,v 1.2 2005/03/18 03:01:20 filetreefrog Exp $
##################################################

class greekingmodule {
	function name() { return 'Greeking Text'; }
	function description() { return 'Outputs random latin paragraphs and sentences for designers.'; }
	function author() { return 'OIC Group, Inc.'; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		return array(
			'administrate'=>'Administrate',
			'configure'=>'Configure',
		);
	}
	
	function show($view,$loc = null, $title = "") {
		global $db;
		
		$config = $db->selectObject('greekingmodule',"location_data='".serialize($loc)."'");
		if ($config == null) {
			$config->num_para = 3;
			$config->num_sent = 3;
			$config->num_word = 2;
		}
		
		$limits = array(
			'p'=>array(
				$config->num_para,
				$config->num_para + 2
			),
			's'=>array(
				$config->num_sent,
				$config->num_sent + 3
			),
			'w'=>array(
				$config->num_word,
				$config->num_word + 6
			),
		);
		
		$str = '';
		
		$latin = include(BASE.'modules/greekingmodule/latin.wordlist.php');
		$num_latin = count($latin)-1;
	
		$paragraphs = rand($limits['p'][0],$limits['p'][1]);
		$sentences = array();
		$text = '';
		for ($i = 0; $i < $paragraphs; $i++) {
			$sentences = rand($limits['s'][0],$limits['s'][1]);
			$paragraph = '';
			for ($j = 0; $j < $sentences; $j++) {
				$sentence = '';
				$words = rand($limits['w'][0],$limits['w'][1]);
				for ($k = 0; $k < $words; $k++) {
					$sentence .= $latin[rand(0,$num_latin)] . ' ';
				}
				$paragraph .= ucfirst(substr($sentence,0,-1) . '.  ');
			}
			$text .= substr($paragraph, 0,-2).'<br /><br />';
			shuffle($latin);
		}
		
		$template = new template('greekingmodule',$view,$loc);
		$template->register_permissions(
			array('administrate','configure'),$loc);
		$template->assign('text',$text);
		$template->assign('moduletitle',$title);
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$db->delete('greekingmodule_config',"location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		$config = $db->selectObject('greekingmodule_config',"location_data='".serialize($oloc)."'");
		$config->location_data = serialize($nloc);
		$db->insertObject($config,'greekingmodule_config');
	}
	
	function spiderContent($item = null) {
		// Who would want to search gibberish text?
		return false;
	}
}

?>