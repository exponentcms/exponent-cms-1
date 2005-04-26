<?php

include_once('../pathos.php');
unset($_SESSION[SYS_SESSION_KEY]);

header('Location: dump_session.php');

?>