<?php

class CHANGEME {
	function name() { return ""; }
	function description() { return ""; }
	function author() { return ""; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		if ($internal == "") {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
			);
		} else {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
			);
		}
	}
	
	function show($view,$loc = null, $title = "") {
	
	}
	
	function deleteIn($loc) {
	
	}
	
	function copyContent($oloc,$nloc) {
	
	}
}

?>