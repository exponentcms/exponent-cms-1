<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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
# $Id$
##################################################

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SYS_SEARCH",1);

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SEARCH_TYPE_ANY",1);
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SEARCH_TYPE_ALL",2);
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define("SEARCH_TYPE_PHRASE",3);

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_search_whereClause($fields,$terms,$type = SEARCH_TYPE_ANY) {
	$where = "";
	foreach ($fields as $field) {
		switch ($type) {
			case SEARCH_TYPE_ALL:
				$where .= "(" . $field . " LIKE '%" . implode("% ' AND $field LIKE ' %",$terms) . " %') ";
				break;
			case SEARCH_TYPE_PHRASE:
				$where .= $field . " LIKE '% " . implode(" ",$terms) . " %' ";
				break;
			default:
				$where .= $field . " LIKE '%" . implode("%' OR $field LIKE '%",$terms) . "%' ";
				break;
		}
		$where .= "OR ";
	}
	return substr($where,0,-4);
}
	
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_search_saveSearchKey($search) {
	$search->title = " " . $search->title . " ";
	$search->body = " " . $search->body . " ";
	
	global $db;
	if (isset($search->id)) {
		$db->updateObject($search,"search");
	} else {
		$db->insertObject($search,"search");
	}
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_search_removeHTML($str) {
	$str = str_replace(array("\r\n","\n")," ",$str);
	return strip_tags(str_replace(array("<br/>","<br>","<br />","</div>"),"\n",$str));
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_search_cleanSearchQuery($query) {
	$exclude = array_map("trim",file(BASE."subsystems/search/exclude.en.list"));
	$newquery = array('valid'=>array(),'excluded'=>array());
	foreach ($query as $q_tok) {
		if (!in_array($q_tok,$exclude)) {
			$newquery['valid'][] = $q_tok;
		} else {
			$newquery['excluded'][] = $q_tok;
		}
	}
	return $newquery;
}

?>