<?php 
class directorylistingmodule { 
	function name() { return 'Directory Listing Module'; } 
	function description() { return 'Recursively lists the content of a specified directory'; } 
	function author() { return 'Fred Dirkse, OIC Group, Inc.'; } 
	function hasContent() { return true; } 
	function hasSources() { return true; } 
	function hasViews() { return true; }
	function supportsWorkflow() { return false; }
	
	function permissions() { 
		return array( 
			'administrate'=>'Administrate', // Standard - for permissions 
			//'manage_categories'=>'Manage Categories', // Custom 
			//'manage_faqs'=>'Manage FAQs' // Custom
			);
		} 
	
	function show($view,$loc = null, $title = "") {
		global $db;
		
		// Used later, for recalculation and other things.
		//if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
		if (!defined("SYS_FILES")) include_once(BASE."subsystems/files.php");		
		//echo $_REQUEST['traverse'];
		//exit ("what?");
		//run default listing action
		$_GET['view'] = $view;
		$_GET['title'] = $title;
		include( BASE . "modules/directorylistingmodule/actions/list.php");
	}
	
	function deleteIn($loc) {
		
	}
	
	function copyContent($oloc,$nloc) {
		
	}
} 

?>