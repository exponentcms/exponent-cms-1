<?php 
##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
# Written and Designed by Dave Leffler
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

define('SCRIPT_EXP_RELATIVE','');
define('SCRIPT_FILENAME','xmlrpc-news.php');

// Initialize the Exponent Framework
require_once('exponent.php');
// These three files are from the PHP-XMLRPC library.
require_once('external/xmlrpc.php');
require_once('external/xmlrpcs.php');
require_once('external/xmlrpc_wrappers.php');

/**
* Used to test usage of object methods in dispatch maps
*/
class xmlrpc_server_methods_container
{
}

// Internal User Login function	
function userLogin($username, $password, $src, $area) {
	global $db;

    // This is where you would check to see if the username and password are valid
    // and whether the user has rights to perform this action ($area) 'post' or 'edit' or 'delete'
    // Return true if so. Or false if the login info is wrong.
	
	if (!defined('SYS_USERS')) require_once('subsystems/users.php');

	// Retrieve the user object from the database.  This may be null, if the username is non-existent.
	$user = $db->selectObject('user', "username='" . $username . "'");	
	$authenticated = exponent_users_authenticate($user, $password);

	if($authenticated) {		
		//Update the last login timestamp for this user.
		$user->last_login = time();
		$db->updateObject($user, 'user');

		// Retrieve the full profile, complete with all Extension data.
		$user = exponent_users_getFullProfile($user);
	
		// Call on the Sessions subsystem to log the user into the site.
		exponent_sessions_login($user);
	}

//	exponent_users_login($username, $password);
	if (exponent_users_isLoggedIn()) {
		return true;
	} else {
		return false;
	}
}

// Get List of User News Modules function	
$getUsersBlogs_sig=array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString));
$getUsersBlogs_doc='Returns a list of news modules to which an author has posting privileges.';
function getUsersBlogs($xmlrpcmsg)
{
	global $db;
	$username=$xmlrpcmsg->getParam(1)->scalarval();
	$password=$xmlrpcmsg->getParam(2)->scalarval();
	
	if (userLogin($username, $password, null, 'add_item') == true) {	
		// setup the list of blogs.
		$structArray = array();

		$allnews = exponent_modules_getModuleInstancesByType('newsmodule');
		foreach ($allnews as $src => $news) {
			$news_name = (empty($news[0]->title) ? 'Untitled' : $news[0]->title).' on page '.$news[0]->section;
			$loc = exponent_core_makeLocation('newsmodule',$src);
			$section = $db->selectObject('sectionref', 'source="'.$src.'"');
			$page = $db->selectObject('section', 'id="'.$section->section.'"');
			if (exponent_permissions_check('add_item',$loc) || (exponent_permissions_check('edit_item',$loc))) {
				$structArray[] = new xmlrpcval(array(
				  'isAdmin'    => new xmlrpcval(true, 'boolean'),
				  'url'        => new xmlrpcval(URL_FULL . $page->sef_name, 'string'), 
				  'blogid'     => new xmlrpcval($src, 'string'),
				  'blogName'   => new xmlrpcval($news_name, 'string'),
				  'xmlrpc'     => new xmlrpcval(URL_FULL . 'xmlrpc-news.php', 'string')
				  ), 'struct');  
			} 
		}
		return new xmlrpcresp(new xmlrpcval($structArray,'array'));
	} else {
		return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
	}
}

// Create a New News Item function	
$newPost_sig=array(array($xmlrpcBoolean, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct, $xmlrpcBoolean));
$newPost_doc='Post a new item to the news module.';
function newPost($xmlrpcmsg)
{
    global $db, $user;
    $src = $xmlrpcmsg->getParam(0)->scalarval();
    $username = $xmlrpcmsg->getParam(1)->scalarval();
    $password = $xmlrpcmsg->getParam(2)->scalarval();
    
    if(userLogin($username, $password, $src, 'add_item') == true) {
		$loc = exponent_core_makeLocation('newsmodule',$src);	
		if (exponent_permissions_check('add_item',$loc)) {
			$content = $xmlrpcmsg->getParam(3);
			$title = $content->structMem('title')->scalarval();
			$description = $content->structMem('description')->scalarval();
			//$dateCreated = $content->structMem('dateCreated')->serialize();   // Not all clients send dateCreated info. So add if statement here if you want to use it.
			//$timestamp = iso8601_decode($dateCreated);  // To convert to unix timestamp
//			if($content->structMem('categories')->arraySize() > 0) {
//				$categories = $content->structMem('categories')->arrayMem(0)->scalarval();
//			}
			$categories = array();
			for ($i = 0; $i < $content->structMem('categories')->arraySize(); $i++) {
				$categories[$i] = $content->structMem('categories')->arrayMem($i)->scalarval();
			}
			$published = $xmlrpcmsg->getParam(4)->scalarval();
			
			// Put your DB queries in here to store the new post.       
			$post = null;
			$iloc = null;

			$post->title = $title;
//			$post->internal_name = preg_replace('/--+/','-',preg_replace('/[^A-Za-z0-9_]/','-',$title));
			$post->body = htmlspecialchars_decode(htmlentities($description,ENT_NOQUOTES));
//			$post->is_private = 0;
			$post->is_featured = (($published) ? 0 : 1);			
			
			$post->location_data = serialize($loc);
			//Get and add the tags selected by the user
			$tags = array();
			foreach ($categories as $cat) {
				$tags[] = $db->selectValue('tags','id',"name = '".$cat."'");
			}
			$post->tags = serialize($tags);
			
			$post->poster = $user->id;
			$post->posted = time();
			$post->publish = time();
			$post->id = $db->insertObject($post,'newsitem');

			$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);

			// New, so assign full perms.
			exponent_permissions_grant($user,'edit_item',$iloc);
			exponent_permissions_grant($user,'delete_item',$iloc);
//			exponent_permissions_triggerSingleRefresh($user);

			return new xmlrpcresp(new xmlrpcval($post->id,'string')); // Return the id of the post just inserted into the DB. See mysql_insert_id() in the PHP manual.
		} else {
			return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
		}
	} else {
			return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
	}
}
    
// Edit a News Item function	
$editPost_sig=array(array($xmlrpcBoolean, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct, $xmlrpcBoolean));
$editPost_doc='Edit a news item in the module.';
function editPost($xmlrpcmsg)
{
    global $db, $user;
    $postid = $xmlrpcmsg->getParam(0)->scalarval();
    $username = $xmlrpcmsg->getParam(1)->scalarval();
    $password = $xmlrpcmsg->getParam(2)->scalarval();

	$post = $db->selectObject('newsitem','id='.intval($postid));
	$loc = unserialize($post->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id); 
    if(userLogin($username, $password, $loc->src, 'edit_item') == true) {
		if (exponent_permissions_check('edit_item',$loc)) {	
			$content = $xmlrpcmsg->getParam(3);
			$title = $content->structMem('title')->scalarval();
			$description = $content->structMem('description')->scalarval();
			//$dateCreated = $content->structMem('dateCreated')->serialize();   // Not all clients send dateCreated info. So add if statement here if you want to use it.
			//$timestamp = iso8601_decode($dateCreated);  // To convert to unix timestamp
			// if($content->structMem('categories')->arraySize() > 0) {
				// $categories = $content->structMem('categories')->arrayMem(0)->scalarval();
			// }
			// $published = $xmlrpcmsg->getParam(4)->scalarval();
			// if($content->structMem('categories')->arraySize() > 0) {
			$categories = array();
			for ($i = 0; $i < $content->structMem('categories')->arraySize(); $i++) {
				$categories[$i] = $content->structMem('categories')->arrayMem($i)->scalarval();
			}
			$published = $xmlrpcmsg->getParam(4)->scalarval();
			// Put your DB queries in here to update the post corresponding to the $postid.  

				$post->title = $title;
	//			$post->internal_name = preg_replace('/--+/','-',preg_replace('/[^A-Za-z0-9_]/','-',$title));
				$post->body = htmlspecialchars_decode(htmlentities($description,ENT_NOQUOTES));
//				$post->is_private = 0;
				$post->is_featured = (($published) ? 0 : 1);			
				
				$post->location_data = serialize($loc);
				//Get and add the tags selected by the user
				$tags = array();
				foreach ($categories as $cat) {
					$tags[] = $db->selectValue('tags','id',"name = '".$cat."'");
				}
				$post->tags = serialize($tags);
				
	//			$post->poster = $user->id;
	//			$post->posted = time();
				
				$post->editor = $user->id;
				$post->edited = time();
				$db->updateObject($post,'newsitem');

			return new xmlrpcresp(new xmlrpcval(true,'boolean'));
		} else {
			return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
		}		
    } else {
		return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
    }
}
    
// Get a News Item function	
$getPost_sig=array(array($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString));
$getPost_doc='Get an item in the news module.';
function getPost($xmlrpcmsg)
{
    global $db;
    $postid = $xmlrpcmsg->getParam(0)->scalarval();
    $username = $xmlrpcmsg->getParam(1)->scalarval();
    $password = $xmlrpcmsg->getParam(2)->scalarval();
    
//convert $postid to $src  
	$post = $db->selectObject('newsitem','id='.intval($postid));
	$loc = unserialize($post->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);   
    if(userLogin($username, $password, $loc->src, 'edit_item') == true) {
		if (exponent_permissions_check('edit_item',$loc)) {	 
				$cat = array();
				$tags = unserialize($post->tags);
				foreach ($tags as $tag){
					$cat[] = $db->selectValue('tags','name','id = '.$tag);				
				}				
				return new xmlrpcresp(new xmlrpcval(array(
					'postid'       => new xmlrpcval($post->id, 'string'),
					'dateCreated'  => new xmlrpcval($post->posted, 'dateTime.iso8601'),
					'title'        => new xmlrpcval($post->title, 'string'),
					'description'  => new xmlrpcval($post->body, 'string'),
            		'categories'   => php_xmlrpc_encode($cat),
					'publish'      => new xmlrpcval((($post->is_featured) ? 0 : 1), 'boolean')
					), 'struct'));

		} else {
			return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
		}
	} else {
		return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
	}
}
    
// Delete a News Item function	
$deletePost_sig=array(array($xmlrpcBoolean, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcBoolean));
$deletePost_doc='Deletes a news item.';
function deletePost($xmlrpcmsg)
{
    $postid=$xmlrpcmsg->getParam(1)->scalarval();
    $username=$xmlrpcmsg->getParam(2)->scalarval();
    $password=$xmlrpcmsg->getParam(3)->scalarval();
    
//convert $postid to $src  
	$post = $db->selectObject('newsitem','id='.intval($postid));
	$loc = unserialize($post->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);    
    if(userLogin($username, $password, $loc->src, 'delete_item') == true) {
      
      $query = "DELETE FROM sometable WHERE id=$postid LIMIT 1";
      mysql_query($query) or die (mail("your@email.com","Error with MetaWebLog deleting new post",$query . ' | ' . mysql_error()));
      
      return new xmlrpcresp(new xmlrpcval(true,'boolean'));
	} else {
      return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
	}
}
    
// Get a List of Recent News Items function	
$getRecentPosts_sig=array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcInt));
$getRecentPosts_doc='Get the recent news items on the module.';
function getRecentPosts($xmlrpcmsg)
{
    global $db;
    $src = $xmlrpcmsg->getParam(0)->scalarval();
    $username = $xmlrpcmsg->getParam(1)->scalarval();
    $password = $xmlrpcmsg->getParam(2)->scalarval();
    
    if(userLogin($username, $password, $src, 'edit_item') == true) {
		$loc = exponent_core_makeLocation('newsmodule',$src);	
		if (exponent_permissions_check('edit_item',$loc)) {		
			$numposts = $xmlrpcmsg->getParam(3)->scalarval();

			$structArray = array();
	 
			// If this module has been configured to aggregate then setup the where clause to pull
			// posts from the proper blogs.
//			$config = $db->selectObject('newsmodule_config',"location_data='".serialize($loc)."'");
			$locsql = "(location_data='".serialize($loc)."'";
			// if (!empty($config->aggregate)) {
					// $locations = unserialize($config->aggregate);
					// foreach ($locations as $source) {
							// $tmploc = null;
							// $tmploc->mod = 'newsmodule';
							// $tmploc->src = $source;
							// $tmploc->int = '';
							// $locsql .= " OR location_data='".serialize($tmploc)."'";
					// }
			// }
			$locsql .= ')';

//			$where = '(is_draft = 0 OR poster = '.$user_id.") AND ".$locsql;
			$where = $locsql;
//echo $where;			
//			if (!exponent_permissions_check('view_private',$loc)) $where .= ' AND is_private = 0';
		
			$posts = $db->selectObjects('newsitem',$where . ' ORDER BY posted DESC '.$db->limit($numposts,0));
//echo print_r($posts);
			if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
			for ($i = 0; $i < count($posts); $i++) {
//				$ploc = exponent_core_makeLocation($loc->mod,$loc->src,$posts[$i]->id);

//				$posts[$i]->permissions = array(
//					'administrate'=>exponent_permissions_check('administrate',$ploc),
//					'edit'=>exponent_permissions_check('edit',$ploc),
//					'delete'=>exponent_permissions_check('delete',$ploc),
//					'comment'=>exponent_permissions_check('comment',$ploc),
//					'edit_comments'=>exponent_permissions_check('edit_comments',$ploc),
//					'delete_comments'=>exponent_permissions_check('delete_comments',$ploc),
//					'view_private'=>exponent_permissions_check('view_private',$ploc),
//				);
//				$comments = $db->selectObjects('weblog_comment','parent_id='.$posts[$i]->id);
//				usort($comments,'exponent_sorting_byPostedDescending');
//				$posts[$i]->comments = $comments;
//				$posts[$i]->total_comments = count($comments);
	
				//Get the tags for this weblogitem
//				$selected_tags = array();
//				$tag_ids = unserialize($posts[$i]->tags);
//				if(is_array($tag_ids) && count($tag_ids)>0) {$selected_tags = $db->selectObjectsInArray('tags', $tag_ids, 'name');}
//				$posts[$i]->tags = $selected_tags;
//				$posts[$i]->selected_tags = $selected_tags;
				
				$structArray[] = new xmlrpcval(array(
				  'postid'        => new xmlrpcval($posts[$i]->id, 'string'),
				  'dateCreated'        => new xmlrpcval($posts[$i]->posted, 'dateTime.iso8601'),
				  'title'        => new xmlrpcval($posts[$i]->title, 'string'),
//				  'description'        => new xmlrpcval($posts[$i]->body, 'string'),
//				  'categories'        => new xmlrpcval(array(new xmlrpcval($posts[$i]->selected_tags, 'string')), 'array'),
				  'publish'        => new xmlrpcval((($posts[$i]->is_featured) ? 0 : 1), 'boolean')
				  ), 'struct'); 	
			}
			return new xmlrpcresp(new xmlrpcval($structArray , 'array')); // Return type is struct[] (array of struct)
		} else {
			return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
		}		
	} else {
		return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
	}
}
    
// Get a List of Categories function	
$getCategories_sig=array(array($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString));
$getCategories_doc='Get the categories in the news module.';
function getCategories($xmlrpcmsg) {
	global $db;
    
    $src=$xmlrpcmsg->getParam(0)->scalarval();
    $username=$xmlrpcmsg->getParam(1)->scalarval();
    $password=$xmlrpcmsg->getParam(2)->scalarval();
    
    if(userLogin($username, $password, $src, 'add_item') == true) {
		$loc = exponent_core_makeLocation('newsmodule',$src);	
		$newsmodule_config = $db->selectObject('newsmodule_config', "location_data='".serialize($loc)."'");
		$structArray = array();
		if (isset($newsmodule_config->enable_tags) && $newsmodule_config->enable_tags = true) {
			$cols = array();
			$tags = array();
			$cols = unserialize($newsmodule_config->collections);
			if (count($cols) > 0) {
				foreach ($cols as $col) {
					$available_tags = array();
					$available_tags = $db->selectObjects('tags', 'collection_id='.$col);
					$tags = array_merge($tags, $available_tags);
				}
					
				if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
				usort($tags, "exponent_sorting_byNameAscending");
				foreach ($tags as $tag) {
					$structArray[]    = new xmlrpcval(array(
						'title'       => new xmlrpcval($tag->name),
						'description' => new xmlrpcval($tag->name)
						), 'struct');  					
				}
			}
		}		
		return new xmlrpcresp(new xmlrpcval($structArray , 'array')); // Return type is struct[] (array of struct)
    } else {
		return new xmlrpcresp(0, $xmlrpcerruser+1, 'Login Failed');
	}
}
    
// Upload a Media File function	
$newMediaObject_sig=array(array($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcStruct));
$newMediaObject_doc='Upload media files onto the server.';
function newMediaObject($xmlrpcmsg)
{
    $username=$xmlrpcmsg->getParam(1)->scalarval();
    $password=$xmlrpcmsg->getParam(2)->scalarval();
    
    if(userLogin($username, $password, null, 'add_item') == true) {    
		$file=$xmlrpcmsg->getParam(3);
		$filename = $file->structMem('name')->scalarval();
		$filename = substr($filename, (strrpos($filename,"/")+1));
		//$type = $file->structMem('type')->scalarval(); // The type of the file
		$bits = $file->structMem('bits')->serialize();
		$bits = str_replace("<value><base64>","",$bits);
		$bits = str_replace("</base64></value>","",$bits);
		$dest = 'files/newsmodule/';
		$uploaddir = BASE.$dest; 
		//Check to see if the directory exists.  If not, create the directory structure.
		if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
		if (!file_exists(BASE.$dest)) {
			exponent_files_makeDirectory($dest);
		}	
		if(fwrite(fopen($uploaddir . $filename, "wb"), base64_decode($bits)) == false) {
			return new xmlrpcresp(0, $xmlrpcerruser+1, "File Failed to Write");
		} else {
			return new xmlrpcresp(new xmlrpcval(array('url' => new xmlrpcval(URL_FULL.$dest.urlencode($filename), 'string')),'struct'));
		}
	} else {
		return new xmlrpcresp(0, $xmlrpcerruser+1, "Login Failed");
	}
}

// Create XML-RPC Server function	
    $o=new xmlrpc_server_methods_container;
    $a=array(
		'blogger.getUsersBlogs' => array(
		  'function' => 'getUsersBlogs',
		  'docstring' => $getUsersBlogs_doc,
		  'signature' => $getUsersBlogs_sig
		),
        "metaWeblog.newPost" => array(
            "function" => "newPost",
            "signature" => $newPost_sig,
            "docstring" => $newPost_doc
        ), 
        "metaWeblog.editPost" => array(
            "function" => "editPost",
            "signature" => $editPost_sig,
            "docstring" => $editPost_doc
        ),
        "metaWeblog.getPost" => array(
            "function" => "getPost",
            "signature" => $getPost_sig,
            "docstring" => $getPost_doc
        ),
        "metaWeblog.getRecentPosts" => array(
            "function" => "getRecentPosts",
            "signature" => $getRecentPosts_sig,
            "docstring" => $getRecentPosts_doc
        ),
        "metaWeblog.getCategories" => array(
            "function" => "getCategories",
            "signature" => $getCategories_sig,
            "docstring" => $getCategories_doc
        ),
        "metaWeblog.newMediaObject" => array(
            "function" => "newMediaObject",
            "signature" => $newMediaObject_sig,
            "docstring" => $newMediaObject_doc
        ),/*
		'blogger.getUserInfo' => array(
			'function' => 'getUserInfo',
			'docstring' => 'Returns information about an author in the system.',
			'signature' => array(array($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString))
		),*/
		'blogger.deletePost' => array(
			"function" => "deletePost",
			"signature" => $deletePost_sig, 
			"docstring" => $deletePost_doc
		)
    );

    $s=new xmlrpc_server($a, false);
    $s->setdebug(2);

    $s->service();
    // that should do all we need!
?>