<?php 
session_start();
session_unset();
session_destroy();

setcookie('search_box', '', time() - 1, '/');
setcookie('user_type', '', time() - 1, '/');

header('Location:./login.php');
exit();


?>

