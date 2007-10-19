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

// Find news by the title of the news post.  URL would look like news/my-post-title
$maps[] = array('controller'=>'news',
		'action'=>'findByTitle',
		'url_parts'=>array(
				'controller'=>'news',
				'title'=>'(.*)'),
);

// Find news by the date of the news post.  URL would look like news/2007/10/18 to find all the posts on Oct 18, 2007
$maps[] = array('controller'=>'news',
		'action'=>'findByDate',
		'url_parts'=>array(
				'controller'=>'news',
				'year'=>'(19|20)\d\d',
				'month'=>'[01]?\d',
				'day'=>'[0-3]?\d'),
);

$maps[] = array('controller'=>'news',
                'action'=>'findByDate',
                'url_parts'=>array(
                                'controller'=>'news',
                                'year'=>'(19|20)\d\d'),
);

$maps[] = array('controller'=>'news',
                'action'=>'findByDate',
                'url_parts'=>array(
                                'controller'=>'news',
                                'year'=>'(19|20)\d\d',
				'month'=>'[01]?\d',),
);
?>
