<?php

// Set up sessions to use cookies, NO MATTER WHAT
ini_set('session.use_cookies',1);

if (DEVELOPMENT) {
	// In development mode, we need to turn on full throttle error reporting.
	// Display all errors (some production servers have this set to off)
	ini_set('display_errors',1);
	// Up the ante on the error reporting so we can see notices as well.
	ini_set('error_reporting',E_ALL);
	// This is rarely set to true, but the first time it is, we'll be ready.
	ini_set('ignore_repeated_errors',0);
} else {
	// We can't be showing errors in a production environment...
	ini_set('display_errors',0);
	ini_set('ignore_repeated_errors',1);
}

?>