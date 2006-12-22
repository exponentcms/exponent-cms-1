//##################################################
//#
//# Copyright (c) 2006 Maxim Mueller
//#
//# This file is part of Exponent
//#
//# Exponent is free software; you can redistribute
//# it and/or modify it under the terms of the GNU
//# General Public License as published by the Free
//# Software Foundation; either version 2 of the
//# License, or (at your option) any later version.
//#
//# GPL: http://www.gnu.org/licenses/gpl.txt
//#
//##################################################

//this file provides an Array associating availiable Actions, their Icons, and, if required for this action, their plugins, with their internal ids
//TODO: determine whether the Editor provides a queryable API for that
//TODO: adjust for themes

//TODO: possibly create a subobject WYSIWYG ?
// first = action name
// second = icon location
// third = required plugin

console.log("Starting toolbox");

//FIXME: 
//FJD - plugins are going to take a bit more work, so the few here are commented out for now.
//there are many available - not nearly all are listed here.  Might have to load them differently.
//Also todo, add configuration options for defining panel style, skin, etc.
eXp.WYSIWYG_toolboxbuttons =	{
 		"about" : ["About Xinha", "/external/editors/Xinha/images/ed_about.gif", ""],
        "justifycenter" : ["Align Center", "/external/editors/Xinha/images/ed_align_center.gif", ""],
        "justifyfull" : ["Align Justify", "/external/editors/Xinha/images/ed_align_justify.gif", ""],
        "justifyleft" : ["Align Left", "/external/editors/Xinha/images/ed_align_left.gif", ""],
        "justifyright" : ["Align Right", "/external/editors/Xinha/images/ed_align_right.gif", ""],
        "separator" : ["Blank Separator", "/external/editors/Xinha/images/ed_blank.gif", ""],
        /*"insertcharacter" : ["Character Map", "/external/editors/Xinha/images/ed_charmap.gif", "CharacterMap"],*/
        "clearfonts" : ["Clear Fonts", "/external/editors/Xinha/images/ed_clearfonts.gif", ""],
        "hilitecolor" : ["Background Color", "/external/editors/Xinha/images/ed_color_bg.gif", ""],
        "forecolor" : ["Foreground Color", "/external/editors/Xinha/images/ed_color_fg.gif", ""],
        "copy" : ["Copy", "/external/editors/Xinha/images/ed_copy.gif", ""],
        /*"custom" : ["Custom", "/external/editors/Xinha/images/ed_custom.gif", ""],*/
        "cut" : ["Cut", "/external/editors/Xinha/images/ed_cut.gif", ""],
        /*"delete" : ["Delete", "/external/editors/Xinha/images/ed_delete.gif", ""],*/
        "bold" : ["Bold", "/external/editors/Xinha/images/ed_format_bold.gif", ""],
        "italic" : ["Italics", "/external/editors/Xinha/images/ed_format_italic.gif", ""],
        "strikethrough" : ["Strike Thru", "/external/editors/Xinha/images/ed_format_strike.gif", ""],
        "subscript" : ["Subscript", "/external/editors/Xinha/images/ed_format_sub.gif", ""],
        "superscript" : ["Sup", "/external/editors/Xinha/images/ed_format_sup.gif", ""],
        "underline" : ["Underline", "/external/editors/Xinha/images/ed_format_underline.gif", ""],
        "showhelp" : ["Help", "/external/editors/Xinha/images/ed_help.gif", ""],
        "inserthorizontalrule" : ["Horizontal Rule", "/external/editors/Xinha/images/ed_hr.gif", ""],
        "htmlmode" : ["View Source", "/external/editors/Xinha/images/ed_html.gif", ""],
        "insertimage" : ["Insert Image", "/external/editors/Xinha/images/ed_image.gif", ""],
        "outdent" : ["Indent Less", "/external/editors/Xinha/images/ed_indent_less.gif", ""],
        "indent" : ["Indent More", "/external/editors/Xinha/images/ed_indent_more.gif", ""],
        "killword" : ["Kill Word", "/external/editors/Xinha/images/ed_killword.gif", ""],
        "lefttoright" : ["Left to Right", "/external/editors/Xinha/images/ed_left_to_right.gif", ""],
        "createlink" : ["Insert Web Link", "/external/editors/Xinha/images/ed_link.gif", ""],
        "unorderedlist" : ["Bulleted List", "/external/editors/Xinha/images/ed_list_bullet.gif", ""],
        "orderedlist" : ["Numeric List", "/external/editors/Xinha/images/ed_list_num.gif", ""],
        "overwrite" : ["Overwrite", "/external/editors/Xinha/images/ed_overwrite.gif", ""],
        "paste" : ["Paste", "/external/editors/Xinha/images/ed_paste.gif", ""],
        "print" : ["Print", "/external/editors/Xinha/images/ed_print.gif", ""],
        "redo" : ["Redo", "/external/editors/Xinha/images/ed_redo.gif", ""],
        "righttoleft" : ["Right to Left", "/external/editors/Xinha/images/ed_right_to_left.gif", ""],
        "removeformat" : ["Remove Format", "/external/editors/Xinha/images/ed_rmformat.gif", ""],        
        "saveas" : ["Save As", "/external/editors/Xinha/images/ed_saveas.gif", ""],
        "selectall" : ["Select All", "/external/editors/Xinha/images/ed_selectall.gif", ""],
        /*"show_border" : ["Show Border", "/external/editors/Xinha/images/ed_show_border.gif", ""],*/
        "splitblock" : ["Split Block", "/external/editors/Xinha/images/ed_splitblock.gif", ""],
        /*"splitcell" : ["Split Cell", "/external/editors/Xinha/images/ed_splitcel.gif", ""],*/
        "undo" : ["Undo", "/external/editors/Xinha/images/ed_undo.gif", ""],
        "wordclean" : ["Remove MS Word Formatting", "/external/editors/Xinha/images/ed_word_cleaner.gif", ""],
        "popupeditor" : ["Maximize Fullscreen", "/external/editors/Xinha/images/fullscreen_maximize.gif", ""],
        /*"fullscreen_minimize" : ["Minimize", "/external/editors/Xinha/images/fullscreen_minimize.gif", ""],*/
        "inserttable" : ["Insert Table", "/external/editors/Xinha/images/insert_table.gif", ""],
        /*"insertfilelink" : ["Insert File Link", "/external/editors/Xinha/images/insertfilelink.gif", ""],*/
        /*"insertmacro" : ["Insert Macro", "/external/editors/Xinha/images/insertmacro.png", ""],*/
        /*"tidy.gif" : ["Tidy", "/external/editors/Xinha/images/tidy.gif", ""],*/
        "toggleborders" : ["Toggle Borders", "/external/editors/Xinha/images/toggle_borders.gif", ""],
};
