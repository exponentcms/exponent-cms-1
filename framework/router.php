<?php
##################################################
#
# Copyright (c) 2007-2008 OIC Group, Inc.
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

class router {
	private $maps = array();
	
	function __construct() {
		include_once('router_maps.php');
		$this->maps = $maps;
	}

	public function makeLink($params) {
		$linkbase = (ENABLE_SSL ? NONSSL_URL : URL_BASE);
		$linkbase .= SCRIPT_RELATIVE;

        	if (isset($params['section']) && $params['section'] == SITE_DEFAULT_SECTION) {
	                return $linkbase;
        	}

		// Check to see if SEF_URLS have been turned on in the site config
		if (SEF_URLS == 1) {
			if (isset($params['section'])) {
	                	if (empty($params['sef_name'])) {
	                        	global $db;
		                        $spaces = array('&nbsp;', ' ');
	        	                $params['sef_name'] = strtolower(str_replace($spaces, '-', $db->selectValue('section', 'name', 'id='.intval($params['section']))));
	                	}
	        	        return $linkbase.$params['sef_name'];
	        	} else {
				// initialize the link
				$link = '';

				// we need to add the change the module parameter to controller if it exists
				// we can remove this snippit once the old modules are gone.
				if (!empty($params['module']) || empty($params['controller'])) $params['controller'] = $params['module'];
			
				// check to see if we have a router mapping for this controller/action
				for($i=0; $i < count($this->maps); $i++) {
					$missing_params = array("dump");
					if(in_array($params['controller'], $this->maps[$i]) && in_array($params['action'], $this->maps[$i])) {
						$missing_params = array_diff_key($this->maps[$i]['url_parts'], $params);
					}

					if (count($missing_params) == 0) {
						foreach($this->maps[$i]['url_parts'] as $key=>$value) {
							$link .= urlencode($params[$key])."/";
						}
						break;  // if this hits then we've found a match
					}
				}

				// if we found a mapping for this link then we can return it now.
				if ($link != '') return $linkbase.$link;

		                $link .= $params['controller'].'/';
	        	        $link .= $params['action'].'/';
	                	foreach ($params as $key=>$value) {
	                        	$value = chop($value);
		                        $key = chop($key);
	        	                if ($value != "") {
	                	                if ($key != 'module' && $key != 'action' && $key != 'controller') {
	                        	                $link .= urlencode($key)."/".urlencode($value)."/";
	                                	}
	                        	}
	                	}
	                	return $linkbase.$link;
	        	}
		} else {
			// if the users don't have SEF URL's turned on then we make the link the old school way.
			if (!empty($params['sef_name'])) unset($params['sef_name']);
			$link = $linkbase . SCRIPT_FILENAME . "?";
	                foreach ($params as $key=>$value) {
        	                $value = chop($value);
                	        $key = chop($key);
                        	if ($value != "") $link .= urlencode($key)."=".urlencode($value)."&";
                	}

	                $link = substr($link,0,-1);
                	return htmlspecialchars($link,ENT_QUOTES);
		}
	}

	public function routeRequest() {
		$url_parts = array();
		if (isset($_SERVER['REDIRECT_URL'])) {
			$url = substr_replace($_SERVER['REDIRECT_URL'],'',0,strlen(PATH_RELATIVE));
			$url_parts = explode('/', $url);
			if (empty($url_parts[count($url_parts)-1])) array_pop($url_parts);
			//eDebug("URL: ".$url);
			//eDebug($url_parts);
		}

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// DO NOTHING FOR A POST REQUEST?
		} elseif (count($url_parts) < 1 || $url_parts[0] == '' || $url_parts[0] == null) {
			$_REQUEST['section'] = SITE_DEFAULT_SECTION;
		} elseif (count($url_parts) == 1) {
			global $db;

			// Try to look up the page by sef_name first.  If that doesn't exist, strip out the dashes and 
			// check the regular page names.  If that still doesn't work then we'll redirect them to the 
			// search module using the page name as the seach string.
		        $section = $db->selectObject('section', 'sef_name="'.$url_parts[0].'"');
		        if (empty($section)) {
		                $name = str_replace('-', ' ', $url_parts[0]);
		                $name2 = str_replace('-', '&nbsp;', $url_parts[0]);
		                $section = $db->selectObject('section', 'name="'.$name.'" OR name="'.$name2.'"');
		        }

			// if section is still empty then we should route the user to the search (cool new feature :-) )
			// at some point we need to write a special action/view for the search module that lets the user
			// know they were redirected to search since the page they tried to go to directly didn't exist.
			if (empty($section)) {
				redirect_to(array('controller'=>'searchmodule', 'action'=>'search', 'search_string'=>$url_parts[0]));
			}

			$_REQUEST['section'] = $section->id;
		} else {
			$return_params = array('controller'=>'','action'=>'','url_parts'=>array());
			//eDebug($return_params);
			//eDebug($url_parts);
			//eDebug($maps);

			$part_count = count($url_parts);
			foreach ($this->maps as $map) {
				$matched = true;
				$pairs = array();
				$i = 0;
				if ($part_count == count($map['url_parts'])) {
					//echo "Count match<br>";
					foreach($map['url_parts'] as $key=>$map_part) {
						$res = preg_match("/$map_part/", $url_parts[$i]);
						//echo "MATCHES: $res<br>";
						if ($res != 1) {
							$matched = false;
							break;
						} 
						$pairs[$key] = $url_parts[$i];
						$i++;
					}
				} else {
					$matched = false;
					//echo "Match ruled out due to count difference<br>";
				}

				if ($matched) {
					$return_params['controller'] = $map['controller'];
					$return_params['action'] = $map['action'];
					$return_params['url_parts'] = $pairs;
					//echo "A complete match was found...breaking loop now <br>";
					break;
				}
			}

			// If we have three url parts we assume they are controller->action->id, otherwise split them out into name<=>value pairs
			if ($return_params['controller'] == '') {
				$return_params['controller'] = $url_parts[0];  	// set the controller/module
				$return_params['action'] = $url_parts[1];	// set the action

				// now figure out the name<=>value pairs
				if (count($url_parts) == 3) {
					if ( is_numeric($url_parts[2])) {
						$return_params['url_parts']['id'] = $url_parts[2];
					}
				} else {
					for($i=2; $i < count($url_parts); $i++ ) {
						if ($i % 2 == 0) {
							$return_params['url_parts'][$url_parts[$i]] = isset($url_parts[$i+1]) ? $url_parts[$i+1] : '';
						}
					}
				}
			}	

			//eDebug($return_params);

			// Figure out if this is module or controller request - WE ONLY NEED THIS CODE UNTIL WE PULL OUT THE OLD MODULES
			if (is_readable(BASE.'controllers/'.$return_params['controller'].'Controller.php')) {
				$requestType = 'controller';
			} elseif (is_dir(BASE.'modules/'.$return_params['controller'])) {
				$requestType = 'module';
			} 

			// Set the module or controller
			$_REQUEST[$requestType] = $return_params['controller']; //url_parts[0];
			$_GET[$requestType] = $return_params['controller'];
			$_POST[$requestType] = $return_params['controller'];
			
			// Set the action for this module or controller
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$action = $_POST['action'];
			} else {
				$action = $return_params['action'];
			}
			
			$_REQUEST['action'] = $action;
			$_GET['action'] = $action;
			$_POST['action'] = $action;

			// pass off the name<=>value pairs
			foreach($return_params['url_parts'] as $key=>$value) {
				$_REQUEST[$key] = $value;
			        $_GET[$key] = $value;
			}
		}
	}
}
?>

