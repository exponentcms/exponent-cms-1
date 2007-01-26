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

if (!defined('EXPONENT')) exit('');
global $db;

$page = null;
if (isset($_POST['id'])) {
	$page = $db->selectObject('wizard_pages','id='.intval($_POST['id']));
} else {
	$page->rank = $db->max('wizard_pages', 'rank', null, "wizard_id=".$_POST['wizard_id']);
        if ($page->rank == null) {
        	$page->rank = 0;
        } else {
                $page->rank += 1;
        }
} 

//eDebug($page);
$page->wizard_id = $_POST['wizard_id'];
$page = wizard_page::update($_POST,$page);

//eDebug($page);exit();

$f = null;
$f = $db->selectObject('wizard_form','wizard_page_id='.$page->id);

if (isset($page->id)) {
	$db->updateObject($page, 'wizard_pages');
} else {
	$id = $db->insertObject($page, 'wizard_pages');
	$page->id = $id;
}

$f->name = $page->name;
$f->wizard_page_id = $page->id;
$f->wizard_id = $_POST['wizard_id'];
$f->table_name = wizard_form::updateTable($f);   //This will actually create the table and then return it's name.

if (!$f->id) {
	//Create a form if it's missing...
        $frmid = $db->insertObject($f,"wizard_form");
        $f->id = $frmid;

        //Create Default Report;
        $rpt->name = $i18n['default_report'];
        $rpt->description = "";
        $rpt->location_data = $f->location_data;
        $rpt->text = "";
        $rpt->column_names = "";
        $rpt->wizard_id = $page->wizard_id;
        $db->insertObject($rpt,"wizard_report");
} else {
	$db->updateObject($f,"wizard_form", "wizard_page_id=".$page->id);
}

exponent_flow_redirect();

?>
