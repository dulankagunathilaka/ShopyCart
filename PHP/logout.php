<?php
session_start();              
session_unset();              // Unset all session variables
session_destroy();            
header("Location:../HTML/index.php");
exit;
?>
