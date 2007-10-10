<?php

##################################################
#
# Copyright (c) 2007-2008 OIC Group, Inc.
# Written and Designed by Adam Kessler
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

function smarty_function_stars($params,&$smarty) {


//if($params['zerostar']==1){
//	echo '<a onclick="rate(0,\''.$params['label'].'\',\''.$params['objectid'].'\');" href="javascript:void(0);" class="nostar" >&nbsp;</a>';
//};			
	//echo $params['starcount'];		
$rating = new rating($params['item_id'], $params['item_type']);
$star_percent = $params['starcount'] / 100;
$number_of_stars = $rating->totalRatingAverage() * $star_percent;
$whole_stars = intval($number_of_stars);
$half_stars = ($number_of_stars - $whole_stars) >= .5 ? 1 : 0;

echo $number_of_stars;
echo "Whole stars: ".$whole_stars."<br>";
echo "Half stars: ".$half_stars."<br>";

for($i=1; $i<=$params['starcount']; $i++) {
	$percentage = 100 / $params['starcount'] * ($i+1);              
	if ($i <= $whole_stars) {
		$class="star active-star";
	} elseif ($i == $whole_stars + $half_stars) {
		$class = "star half-star";
	} else {
		$class = "star";
	}
        echo '<a onclick="rate('.$percentage.',\''.$params['label'].'\',\''.$params['item_id'].'\');" href="javascript:void(0);" class="'.$class.'" >&nbsp;</a>';
}

}

?>

