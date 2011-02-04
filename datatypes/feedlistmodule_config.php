<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
# $Id: faqmodule_config.php,v 1.3 2005/02/19 16:53:34 filetreefrog Exp $
##################################################

class feedlistmodule_config {
    function form($object) {        
        $i18n = exponent_lang_loadFile('datatypes/feedlistmodule_config.php');
        if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
        exponent_forms_initialize();
        
        $form = new form();
        if (!isset($object->id)) {
            $object->enable_rss = false;
            $object->feed_title = "";
            $object->feed_desc = "";           
			$object->rss_cachetime = 24;
		} else {
            $form->meta("id",$object->id);
        }
        
        $form->register('enable_rss',$i18n['enable_rss'], new checkboxcontrol($object->enable_rss));
        $form->register('feed_title',$i18n['feed_title'],new textcontrol($object->feed_title,35,false,75));
        $form->register('feed_desc',$i18n['feed_desc'],new texteditorcontrol($object->feed_desc));
		$form->register('rss_cachetime', $i18n['rss_cachetime'], new textcontrol($object->rss_cachetime));
      
        $form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
        
        exponent_forms_cleanup();
        return $form;
    }
    
    function update($values,$object) {
        $object->enable_rss = (isset($values['enable_rss']) ? 1 : 0);
        $object->feed_title = $values['feed_title'];
        $object->feed_desc = $values['feed_desc'];
		$object->rss_cachetime = $values['rss_cachetime'];
        return $object;
    }
}

?>