<?php

		if (!defined("SYS_FILES")) require_once(BASE."subsystems/files.php");
		// Don't forget to change CHANGEME
		$directory = "files/CHANGEME/" . $loc->src;
		if (!file_exists(BASE.$directory)) {
			switch(pathos_files_makeDirectory($directory)) {
				case SYS_FILES_FOUNDFILE:
					echo "Found a file in the directory path.";
					return;
				case SYS_FILES_NOTWRITABLE:
					echo "Unable to create directory to store files in.";
					return;
			}
		}
		
?>