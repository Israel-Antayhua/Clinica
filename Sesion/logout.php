<?php
session_start();

$timeout = isset($_GET['timeout']);

session_unset();
session_destroy();

if ($timeout) {

    session_start();
    $_SESSION['mensaje_timeout'] = true;

    header("Location: login.php");

} else {

    header("Location: login.php");
}

exit;
