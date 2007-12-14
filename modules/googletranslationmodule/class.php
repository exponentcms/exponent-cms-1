<?php

##################################################
#
# Written by Imran Somji
#
##################################################

class googletranslationmodule {
	function name() { return 'Google Translation Module'; }
	function description() { return 'Allows real-time translation of your website courtesy of google language tools.'; }
	function author() { return 'Imran Somji'; }
	
	function hasSources() { return false; }
	function hasContent() { return false; }
	function hasViews() { return true; }

	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		return array();
	}
	
	function deleteIn($loc) {
		// Do nothing, no content
	}
	
	function copyContent($from_loc,$to_loc) {
		// Do nothing, no content
	}

	function show($view,$loc=null,$title='') {
		$template = new template('googletranslationmodule',$view,$loc);
		$template->assign('title',$title);
		
		$current_path = URL_FULL . 'index.php?' . $_SERVER['QUERY_STRING'];
		if(!isset($_SERVER["HTTP_REFERER"]) || substr($_SERVER["HTTP_REFERER"], 0, 27) == 'http://translate.google.com') { # assume google translate is referring us
			echo '<script language="javascript" type="text/javascript">';
			echo '	if(self != top) { top.location.href = top.c.location.href; }'; // Remove Googles frame
			echo '</script>';
		}
		$template->assign('current_path',$current_path);
		
		$template->output($view);
	}
	
	function spiderContent($item = null) {
		// No content
		return false;
	}
}

?>
