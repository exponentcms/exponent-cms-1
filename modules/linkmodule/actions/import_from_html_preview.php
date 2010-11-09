<?php

#############################################################
# LINK MODULE
#############################################################
# Written by Eric Lestrade 
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

if (!defined('EXPONENT')) exit('');
if(exponent_permissions_check('import',$loc)) {
	global $db;
	$mode=$_POST['mode'];
	$url=$_POST['url'];
	$cat1=$_POST['categories'];
	$authorized_modes=array('webpage');
	if(in_array($mode,$authorized_modes)) {
		$i18n = exponent_lang_loadFile('modules/linkmodule/actions/import_from_html_preview.php');
		$location = serialize($loc);

		// Read url
		$file=file_get_contents($url);

		// Ends of line cause problems with regular expressions => get rid of it
		$file=strtr($file,"\n\p","  ");		 

		// Initialization of the form
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		$form = new form();
		$form->meta('action','save_import_from_html');
		$form->location($loc);

		// Try to grab all [external] links in the webpage
		preg_match_all ("|<a [^>]*href=\"?(http://[^ >]*)[^>]*>(.*?)</a>|", $file,  $links,PREG_SET_ORDER);
		// Local test :
		//		preg_match_all ("|<a [^>]*href=\"?([^ >]*)[^>]*>(.*?)</a>|", "texte<a t=g href=url>Liens<img dssdf><u>jkko</u></a>texte<a href=url2>Lien2</a><a href=u><img alt=\"truc fgfg\" dsfdf></a><a href=\"http://www.sun.com/software/star/openoffice/index.html\">" .
		//				"<img title=\"Sponsored by Sun Microsystems, Inc.\" alt=\"Sun Microsystems Logo\" src=\"/branding/images/sun_logo.gif\" /></a>" .
		//				"</a>",  $links,PREG_SET_ORDER);

	    $allcats = $db->selectObjects('category', "location_data='".serialize($loc)."'");
	    if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
	    usort($allcats, "exponent_sorting_byRankAscending");
	    $catarray = array();
	    $catarray[0]="&lt;".exponent_lang_loadKey('modules/linkmodule/actions/edit_link.php','top_level')."&gt;";
	    foreach ($allcats as $cat) {
	       $catarray[$cat->id] = $cat->name;
	    }			

		$form->register('import_link',null,new htmlcontrol('<b>'.$i18n['import_link'].'</b>',true));
		$i=0;
		foreach($links as $link) {
			$link[1]=trim($link[1],'"');
			
			$alt='';
			// 	Try to grab the "title" or the "alt" attribute of the image in case of the link is a graphical link (and not a plain text link)
			// Note : can not grap alt or title delimited with simple quote (needs to duplicate some lines of code - I am not a reg exp pro...)
			if (preg_match("|<img .*?title=([^ >]*)[^>]*>|",$link[2],$match_array))
				$alt=trim(trim($match_array[1],'"'));;
			if($alt) {
				if(preg_match("|<img .*?title=\"([^\"]*)\"[^>]*>|",$link[2],$match_array)){
					$alt2=trim(trim($match_array[1],'"'));
					if(strlen($alt2)>strlen($alt)) $alt=$alt2; 
				}
			} else {
				if(preg_match("|<img .*?alt=([^ >]*)[^>]*>|",$link[2],$match_array)) {
					$alt=trim(trim($match_array[1],'"'));
					if($alt) {
						if(preg_match("|<img .*?alt=\"([^\"]*)\"[^>]*>|",$link[2],$match_array)) {
							$alt2=trim(trim($match_array[1],'"'));
							if(strlen($alt2)>strlen($alt)) $alt=$alt2;
						} 
					}
				}
			}
			
			// delete all HTML tags in link name
			$link[2]=trim(preg_replace ("|<.+?>|","", $link[2] ));
			
			// If there were only HTML tags (like image) and non significant characters (space, end of line, etc.) => use the alt var (can be empty)
			if(!$link[2])	$link[2]=$alt;
			
			// the form manager in Exponent seems to reject array of form elements (name="url[]"), so we use different names : url1,url2,url3,... 
			$form->register('hr'.$i,null,new htmlcontrol('<hr>',true));
			$form->meta('url'.$i,$link[1]);
			$form->register('get'.$i,$link[1],new checkboxcontrol(true,true));
			$form->register('category_id'.$i,$i18n['category'],new dropdowncontrol($cat1, $catarray));
			$form->register('name'.$i,$i18n['title'],new textcontrol($link[2],80,false,200));
			$i++;
			if($i>100) break;
		}
		$form->register('import','',new buttongroupcontrol($i18n['import'],'',$i18n['cancel']));
		
		$template = new template('linkmodule','_form_import_from_html_preview');
		$template->assign('form_html',$form->toHTML());
		$template->assign('mode',$mode);
		$template->output();
	}
	else {	echo SITE_404_HTML;}
}
else {	echo SITE_403_HTML;}
?>
