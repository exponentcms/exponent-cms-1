GUESTBOOKMODULE README

	Guestbookmodule v.02.0:	Dec 2006 - 2006-12-04
	for exponent 0.69.5
	By Dirk Olten, http://www.extrabyte.de | (mail AT extrabyte DOT de)

This Guestbookmodule (a modification of the weblogmodule) is now completely rewritten.
If you find any bugs, please write me (mail AT extrabyte DOT de)

To do:

	1.) In the files /datatypes/guestbookmodule_config.php and
	/datatypes/definitions/guestbookmodule_config.php there are 
	option to control if the post-editor uses the HTML-EDITOR or 
	a plain texfield.
	This setting can already be configured and saved in the database, but
	in file /datatypes/guestbook_post.php I couldn't manage to read the
	$loc - variable, which is necessary to read the settings from db.
	The specific lines 24-25 and 47 -50 are commented.
	Please read the comment at line 52 to configure this manually.

	If you find a solution, please send me the code!

Dirk Olten