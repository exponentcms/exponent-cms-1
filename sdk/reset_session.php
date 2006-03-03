<?php

include_once('../exponent.php');
unset($_SESSION[SYS_SESSION_KEY]);

header('Location: dump_session.php');

?>