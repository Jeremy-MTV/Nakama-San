<?php
session_start();
$_SESSION = array();
session_destroy();
if (isset($_COOKIE['rester_connecte'])) {
    unset($_COOKIE['rester_connecte']);
    setcookie('rester_connecte', '', time() - 3600, '/');
}
header('Location: ../index.php');

?>
<!-- PHP de la deconnexion -->
