<?php
##################################################
#
# Copyright (c) 2007 Ron Miller, OIC Group, Inc.
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

global $db;

$sql = "UPDATE exponent_section set sef_name = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(REPLACE(name, ' - ', '-')), ' ', '-'), '?', ''), '\"', ''), '\'', ''), '(', ''), ')', ''), '&', 'and') where sef_name = ''";
$updateThese = $db->sql($sql);

?>
