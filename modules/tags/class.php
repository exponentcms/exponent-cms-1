<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
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

class tags {

	public static function getCollectionIdsForTags($tags=null) {
		global $db;
		
		if (!is_array($tags)) return array();

		$sql = '';
		foreach($tags as $tag) {
			$sql .= $sql == '' ? $tag : ','.$tag; 
		}

		$where = 'id IN(SELECT collection_id from '.DB_TABLE_PREFIX.'_tags WHERE id IN('.$sql.'))';
		return $db->selectColumn('tag_collections', 'id',  $where);
		
	}
}

?>
