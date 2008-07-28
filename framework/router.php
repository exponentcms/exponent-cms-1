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
	public  $url_parts = '';
	public  $current_url = '';
	public  $url_type = '';
	public  $url_style = '';
	public  $params = array();
	public  $sefPath = null;
	
	function __construct() {
		include_once(BASE.'framework/router_maps.php');
		$this->maps = $maps;
	}

	public function makeLink($params, $force_old_school=false) {
		$linkbase = (ENABLE_SSL ? NONSSL_URL : URL_BASE);
		$linkbase .= SCRIPT_RELATIVE;

        	if (isset($params['section']) && $params['section'] == SITE_DEFAULT_SECTION) {
	                return $linkbase;
        	}

		// Check to see if SEF_URLS have been turned on in the site config
		if (SEF_URLS == 1 && ($_SERVER["PHP_SELF"] == PATH_RELATIVE.'index.php') && $force_old_school == false) {
			if (isset($params['section'])) {
	                	if (empty($params['sef_name'])) {
	                        	global $db;
					$params['sef_name'] = $db->selectValue('section', 'sef_name', 'id='.intval($params['section']));
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
							if ($key == 'controller') {
								$link .= $value."/";
							} else {
								$link .= $params[$key]."/";
							}
						}
						break;  // if this hits then we've found a match
					}
				}

				// if we found a mapping for this link then we can return it now.
				//if ($link != '') return router::encode($linkbase.$link);
				if ($link != '') return urlencode($linkbase.$link);
				
		                $link .= $params['controller'].'/';
	        	        $link .= $params['action'].'/';
	                	foreach ($params as $key=>$value) {
	                        	$value = chop($value);
		                        $key = chop($key);
	        	                if ($value != "") {
	                	                if ($key != 'module' && $key != 'action' && $key != 'controller') {
												if ($key != 'src') {
													$link .= urlencode($key)."/".urlencode($value)."/";
												} else {
													$link .= $key."/".$value."/";
												}
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
                        	if ($value != "") {
								if ($key != 'src') {
									$link .= urlencode($key)."=".urlencode($value)."&";
								} else {
									$link .= $key."=".$value."&";
								}
								
							}
                	}

	                $link = substr($link,0,-1);
                	return $link; // phillip: removed htmlspecialchars so that links return without parsing & to &amp; in URL strings
                	//return htmlspecialchars($link,ENT_QUOTES);
		}
	}

	public function routeRequest() {
		// Set the current url string.  This is used for flow and possible other things too
		// eDebug($_SERVER);
		//

		// start splitting the URL into it's different parts
		$this->splitURL();
		if ($this->url_style == 'sef') {
			if ($this->url_type == 'page' || $this->url_type == 'base') {
				$this->routePageRequest();  			// if we hit this the formating of the URL looks like the user is trying to go to a page.
			} elseif ($this->url_type == 'action') {
				if (!$this->isMappedURL()) {  			//if this URL is handled via the map file then this function will route it
					$ret = $this->routeActionRequest();  	// we didn't have a map for this URL.  Try to route it with this function.

					// if this url wasn't a valid section, or action then kill it.  It might not actually be a "bad" url, 
					// but this is a precautionary measure against bad paths on images, css & js file, etc...with the new
					// mod_rewrite rules these bad paths will not route thru here so we need to take them into account and
					// deal with them accordingly.
					if (!$ret) $this->url_type == 'malformed';  
				}
			} elseif ($this->url_type == 'post') {
				// no need to do anything for POST data.  All the module/controller/action info comes thru the POST vars..
				// we can just let them trickle down to exponent.php && index.php
			}
		} elseif ($this->url_style == 'query' && SEF_URLS == 1 && !empty($_REQUEST['section'])) {
			// if we hit this it's an old school url coming in and we're trying to use SEF's. 
			// we will send a permanent redirect so the search engines don't freak out about 2 links pointing
			// to the same page.
			header("Location: ".$this->makeLink(array('section'=>intval($_REQUEST['section']))),TRUE,301);
			
		}

		// if this is a valid URL then we build out the current_url var which is used by flow, and possibly other places too
		if ($this->url_type != 'malformed') {		
			$this->current_url = $this->buildCurrentUrl();
		}
		
		// this will track the browse history - helps keep track of flow and will be the start of the user behavior tracking
		//$this->updateHistory();

	}

	private function updateHistory() {
		// if we don't have everything we need then just return now
		if (empty($this->url_type)) $this->url_type = "unknown/malformed url";
		if (empty($this->current_url)) $this->current_url = "";
		// get this user's browsing history from the session
		$history = exponent_sessions_get('history');
		// make sure it's been initialize...if not do so now.
		if (empty($history[$this->url_type])) $history[$this->url_type] = array();
		// only keep the last 10 of each type of page.
		if (count($history[$this->url_type]) >= 10) array_shift($history[$this->url_type]);
		// update the history with the current URL
		$history[$this->url_type][] = $this->current_url;
		//eDebug($history);
		// put the updated history back into the session.
		exponent_sessions_set('history', $history);
	}

	public function splitURL() {
		$this->url_parts = array();
		$this->buildSEFPath();
		if (!empty($this->sefPath)) {
			$this->url_style = 'sef';

			$this->url_parts = explode('/', $this->sefPath);
			if (empty($this->url_parts[count($this->url_parts)-1])) array_pop($this->url_parts);
			if (empty($this->url_parts[0])) array_shift($this->url_parts);
			
			if (count($this->url_parts) < 1 || (empty($this->url_parts[0]) && count($this->url_parts) == 1) ) {
				$this->url_type = 'base';
			} elseif (count($this->url_parts) == 1) {
				$this->url_type = 'page';
			} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$this->url_type = 'post';
			} else {
				// take a peek and see if a page exists with the same name as the first value...if so we probably have a page with
                                // extra perms...like printerfriendly=1 or ajax=1;
                                global $db;
                                if ( ($db->selectObject('section', "sef_name='".$this->url_parts[0]."'") != null) && (in_array('printerfriendly', $this->url_parts))) {
                                        $this->url_type = 'page';
                                } else {
                                        $this->url_type = 'action';
                                }
			}

			$this->params = $this->convertPartsToParams();
		} elseif (isset($_SERVER['REQUEST_URI'])) {
			// if we hit here, we don't really need to do much.  All the pertinent info will come thru in the POST/GET vars
			// so we don't really need to worry about what the URL looks like.
			$this->url_style = 'query'; 
		} 
	}

	public function routePageRequest() {		
		global $db;

		if ($this->url_type == 'base') {
			// if we made it in here this is a request for http://www.baseurl.com
			$_REQUEST['section'] = SITE_DEFAULT_SECTION;  
		} else {
			// Try to look up the page by sef_name first.  If that doesn't exist, strip out the dashes and 
			// check the regular page names.  If that still doesn't work then we'll redirect them to the 
			// search module using the page name as the seach string.
			$section = $this->getPageByName($this->url_parts[0]);

			$_REQUEST['section'] = $section->id;
		}
		return true;
	}

	public function isMappedURL() {
		// figure out if this action is mapped via the mapping file (router_maps.php)
		$part_count = count($this->url_parts);
		foreach ($this->maps as $map) {
			$matched = true;
			$pairs = array();
			$i = 0;
			if ($part_count == count($map['url_parts'])) {
				//echo "Count match<br>";
				foreach($map['url_parts'] as $key=>$map_part) {
					$res = preg_match("/$map_part/", $this->url_parts[$i]);
					//echo "MATCHES: $res<br>";
					if ($res != 1) {
						$matched = false;
						break;
					} 
					$pairs[$key] = $this->url_parts[$i];
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
				return true;
			}
		}
		return false;
	}

	public function routeActionRequest() {
		$return_params = array('controller'=>'','action'=>'','url_parts'=>array());
	
		// If we have three url parts we assume they are controller->action->id, otherwise split them out into name<=>value pairs
		$return_params['controller'] = $this->url_parts[0];  	// set the controller/module
		$return_params['action'] = $this->url_parts[1];		// set the action

		// Figure out if this is module or controller request - WE ONLY NEED THIS CODE UNTIL WE PULL OUT THE OLD MODULES
		if (is_readable(BASE.'controllers/'.$return_params['controller'].'Controller.php')) {
			$requestType = 'controller';
		} elseif (is_dir(BASE.'modules/'.$return_params['controller'])) {
			$requestType = 'module';
		} else {
			return false;  //this is an invalid url return an let the calling function deal with it.
		}

		// now figure out the name<=>value pairs
		if (count($this->url_parts) == 3) {
			if ( is_numeric($this->url_parts[2])) {
				$return_params['url_parts']['id'] = $this->url_parts[2];
			}
		} else {
			for($i=2; $i < count($this->url_parts); $i++ ) {
				if ($i % 2 == 0) {
					$return_params['url_parts'][$this->url_parts[$i]] = isset($this->url_parts[$i+1]) ? $this->url_parts[$i+1] : '';
				}
			}
		}		 

		// Set the module or controller - this how the actual routing happens
		$_REQUEST[$requestType] = $return_params['controller']; //url_parts[0];
		$_GET[$requestType] = $return_params['controller'];
		$_POST[$requestType] = $return_params['controller'];
	
		// Set the action for this module or controller
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// most of the time we can just grab the action outta the POST array since this is passed as a hidden field, 
                        // but sometimes it is actually set as the action on the form itself...then we get it from the params array instead.
                        $action = !empty($_POST['action']) ? $_POST['action'] : $this->params['action'];
		} else {
			$action = $return_params['action'];
		}
	
		$_REQUEST['action'] = $action;
		$_GET['action'] = $action;
		$_POST['action'] = $action;

		// pass off the name<=>value pairs
		foreach($return_params['url_parts'] as $key=>$value) {
			//$save_value = is_numeric($value) ? $value: router::decode($value);
			$save_value = $value;
			$_REQUEST[$key] = $save_value;
		        $_GET[$key] = $save_value;
		}

		return true;
	}

	public function buildCurrentUrl() {
		$url =  URL_BASE;
		if ($this->url_style == 'sef') {
			$url .= substr(PATH_RELATIVE,0,-1).$this->sefPath;
		} else {
			$url .= (empty($_SERVER['REQUEST_URI'])) ? $_ENV['REQUEST_URI'] : $_SERVER['REQUEST_URI'];
		}
		return $url;
	}

	public static function encode($url) {
		$url = str_replace('&', 'and', $url);
                return preg_replace("/(-)$/", "", preg_replace('/(-){2,}/', '-', strtolower(preg_replace("/([^0-9a-z-_\+])/i", '-', $url))));	
	}
	
	public static function decode($url) {
		$url = str_replace('-', ' ', $url);
		return str_replace('+', '-', $url);
	}

	public function getSefUrlByPageId($id=null) {
		if (!empty($id)) {
			global $db;
			$section = $db->selectObject('section', 'id='.intval($id));
			$url = URL_FULL;
			$url .= !empty($section->sef_name) ? $section->sef_name : $section->name;
		}
	}

	public function buildUrlByPageId($id=null) {
		if (!empty($id)) {
			global $db;
			//$url = URL_FULL;
			$url = '';
			if (SEF_URLS == 1) {
				$section = $db->selectObject('section', 'id='.intval($id));
                        	$url .= !empty($section->sef_name) ? $section->sef_name : $section->name;
			} else {
				$url .= 'index.php?section='.$id;
			}
		}
		
		return $url;
	}

	public function printerFriendlyLink($link_text="Printer Friendly", $class=null, $width=800, $height=600) {
		$url = '';
		if (PRINTER_FRIENDLY != 1) {
			$class = !empty($class) ? $class : 'printer-friendly-link';
			$url =  '<a class="'.$class.'" href="javascript:void(0)" onclick="window.open(\'';
			if ($this->url_style == 'sef') {
				$url .= $this->convertToOldSchoolUrl();
				if ($this->url_type=='base') $url .= 'index.php?section='.SITE_DEFAULT_SECTION;
			} else {
				$url .= $this->current_url;
			}

			$url .= '&printerfriendly=1\' , \'mywindow\',\'menubar=1,resizable=1,width='.$width.',height='.$height.'\');"';
			$url .= '>'.$link_text.'</a>';
		}
		
		return $url; 
	}

	public function convertToOldSchoolUrl() {
		return $this->makeLink($this->params, true);
	}

	public function convertPartsToParams() {
		if ($this->url_type == 'base') {
			$params['section'] = SITE_DEFAULT_SECTION;
		} elseif ($this->url_type == 'page') {
			$section = $this->getPageByName($this->url_parts[0]);
			$params['section'] = $section->id;
		} elseif ($this->url_type == 'action') {
            $params['module'] = $this->url_parts[0];
            $params['action'] = $this->url_parts[1];
            for($i=2; $i < count($this->url_parts); $i++ ) {
                if ($i % 2 == 0) {
                    $params[$this->url_parts[$i]] = isset($this->url_parts[$i+1]) ? $this->url_parts[$i+1] : '';
                }
            }
        }

		return $params;
	}

	public function getPageByName($url_name) {
		global $db;
		if ($this->url_type == 'base') {
                        // if we made it in here this is a request for http://www.baseurl.com
                        $section = $db->selectObject('section', 'id='.SITE_DEFAULT_SECTION);
		} else {
			$section = $db->selectObject('section', "sef_name='".$url_name."'");
			/*if (empty($section)) {
					$url_name = router::decode($url_name);
		        	//$name = str_replace('-', ' ', $url_name);
		        	$name2 = str_replace(' ', '&nbsp;', $url_name);
		        	$section = $db->selectObject('section', 'name="'.$url_name.'" OR name="'.$name2.'"');
			}*/
		}
		// if section is still empty then we should route the user to the search (cool new feature :-) )
		// at some point we need to write a special action/view for the search module that lets the user
		// know they were redirected to search since the page they tried to go to directly didn't exist.
		if (empty($section)) {
			header("Refresh: 0; url=".$this->makeLink(array('module'=>'searchmodule', 'action'=>'search', 'search_string'=>$this->url_parts[0])), false, 404);
			exit();
		} else {
			return $section;
		}
	}
	
	private function buildSEFPath () {
		// Apache
		if (strpos($_SERVER['SERVER_SOFTWARE'],'Apache') === 0) {
			switch(php_sapi_name()) {
				case "cgi":
					$this->sefPath = !empty($_ENV['REQUEST_URI']) ? urldecode($_ENV['REQUEST_URI']): null;
					break;
				case "cgi-fcgi":
					@$this->sefPath = ($_SERVER['REDIRECT_URL'] != PATH_RELATIVE.'index.php') ? urldecode($_SERVER['REDIRECT_URL']) : urldecode($_ENV['REQUEST_URI']);
					break;
				default:
					$this->sefPath = !empty($_SERVER['REDIRECT_URL']) ? urldecode($_SERVER['REDIRECT_URL']) : null;
					break;
			}
		// Lighty
		} elseif (strpos($_SERVER['SERVER_SOFTWARE'],'lighttpd') === 0) {
			if (isset($_SERVER['ORIG_PATH_INFO'])) {
				$this->sefPath = urldecode($_SERVER['ORIG_PATH_INFO']);
			} elseif (isset($_SERVER['REDIRECT_URI'])){
				$this->sefPath = urldecode(substr($_SERVER['REDIRECT_URI'],9));
			}
		}
		@$this->sefPath = substr($this->sefPath,strlen(substr(PATH_RELATIVE,0,-1)));	
		if (strpos($this->sefPath,'/index.php') === 0) {
			$this->sefPath = null;
		}
	}

	public function getSection() {
		// Check if this was a printer friendly link request
	        define('PRINTER_FRIENDLY', isset($_REQUEST['printerfriendly']) ? 1 : 0);

        	if (isset($_REQUEST['action']) && isset($_REQUEST['module'])) {
        		$section = (exponent_sessions_isset('last_section') ? exponent_sessions_get('last_section') : SITE_DEFAULT_SECTION);
	        } else {
        	 	$section = (isset($_REQUEST['section']) ? $_REQUEST['section'] : SITE_DEFAULT_SECTION);
	        }
        	return $section;
    	}

    	public function getSectionObj($section) {
	        global $db;
        	$sectionObj = $db->selectObject('section','id='. intval($section));
	        if (!navigationmodule::canView($sectionObj)) {
        		define('AUTHORIZED_SECTION',0);
	        } else {
        	    define('AUTHORIZED_SECTION',1);
	        }
        	if (!navigationmodule::isPublic($sectionObj)) {
	        	define('PUBLIC_SECTION',0);
        	} else {
         		define('PUBLIC_SECTION',1);
	        }
        
        	if (isset($_REQUEST['section'])) {
                	exponent_sessions_set('last_section', intval($_REQUEST['section']));
	        } else {
        	        //exponent_sessions_unset('last_section');
	        }
        	return $sectionObj;
    	}
}
?>
