<?php

if (!defined("EXPONENT")) exit("");
if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");

// PERM CHECK
$post = null;
$bb = null;
$ploc = null;
$bbloc = null;
$quoteReply = 0;
$parent = null;

// previewing a post that is being edited...
if (isset($_POST['id'])) {
	$post = $db->selectObject("bb_post","id=".$_POST['id']);
	if ($post) {
		$bb = $db->selectObject("bb_board","id=".$post->board_id);
		$loc = unserialize($bb->location_data);
		$ploc = exponent_core_makeLocation($loc->mod,$loc->src,"p".$post->id);
	}
} 
// previewing a new reply
else if (isset($_POST['parent'])) {
	$parent = $db->selectObject("bb_post","id=".$_POST['parent']);
	if ($parent) {
		$bb = $db->selectObject("bb_board","id=".$parent->board_id);
		$loc = unserialize($bb->location_data);
		$post->parent = $parent->id;
	}
	if (isset($_POST['quote'])) {
		$quoteReply = $_POST['quote'];
	}
}
// previewing a new post
else if (isset($_POST['bb'])) {
	$bb = $db->selectObject("bb_board","id=".$_POST['bb']);
} 

if ($bb && $user) {
	$loc = exponent_core_makeLocation($loc->mod,$loc->src,"b".$bb->id);
	
	if (($post == null && exponent_permissions_check("create_thread",$loc)) ||
		(!isset($post->id) && exponent_permissions_check("reply",$loc)) ||
		(isset($post->id) && (exponent_permissions_check("edit_post",$loc) || $post->poster == $user->id))
	) 
	{
		$post->subject = isset($_POST['subject']) ? $_POST['subject'] : "";
		$post->body = isset($_POST['body']) ? $_POST['body'] : "";
		$post->board_id = $bb->id;
		$post->poster = $user;
		$post->poster->forumPic = $db->selectObject("xtbprofileimage", "uid=".$post->poster->id." AND imagetype=2");
		if ($post->poster->forumPic != null && $post->poster->forumPic->approved == false)
			$post->poster->forumPic = null;
		if ($post->poster->forumPic != null)
			$post->poster->forumPic->file = $db->selectObject("file","id=".$post->poster->forumPic->file_id);
		if (isset($post->parent) && $parent != null)
		{
			$post->subject = "RE: ".$parent->subject;
			if ($quoteReply)
				$originalPoster = exponent_users_getUserById($parent->poster);
		}
		
		$form = bb_post::form($post, true);
		$form->location($loc);
		$form->meta("quote", $quoteReply);
		
		$template = new template("bbmodule","_form_previewPost",$loc);
		$template->assign("is_edit",(isset($post->id) ? 1 : 0));
		$template->assign("is_reply",(isset($post->parent) ? 1 : 0));
		$template->assign("thread",$post);
		$template->assign("form_html",$form->toHTML());
		$template->output();
	}
	
} else echo SITE_404_HTML;
// END PERM CHECK

?>