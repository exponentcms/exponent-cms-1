<?php

##################################################
#
# Copyright (c) 2004-2008 OIC Group, Inc.
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

/*
This class will handle reading a RSS feed from a
given URL ($url) and passing it back to a common
function (refreshRSS()) in a datatype ($datetype)
*/

if (!defined('MAGPIE_DIR')) {
	define('MAGPIE_DIR', BASE.'/external/magpierss/');
}
require_once(MAGPIE_DIR.'rss_fetch.inc');
require_once(MAGPIE_DIR.'rss_utils.inc');
define('MAGPIE_CACHE_DIR', BASE.'tmp/rsscache');
if (!defined('MAGPIE_CACHE_AGE')) define('MAGPIE_CACHE_AGE', 3600);

class rssfeed {
	private $url = null;
	private $datatype = null;

	function __construct($url = null, $datatype = null) {
		$this->url = $url;
		$this->datatype = $datatype;
	}
	
	public function fetch() {
		$feed = fetch_rss($this->url);
		if ( !empty($datatype) ) {
			$modDataType = new $datatype();
			$datatype->refreshRSS($feed);
			return 0;
		} else {
			return $feed;
		}
	}
	
	public function setURL($url) {
		$this->url = $url;
	}

	public function setDatatype($datatype) {
		$this->datatype = $datatype;
	}
}
