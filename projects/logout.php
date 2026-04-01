<?php
session_destroy();
session_start();
session_destroy();
header("Location: /projects/login.php");
exit();
?>