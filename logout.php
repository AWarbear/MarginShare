<?php 
    require("res/includes/config.php"); 
    unset($_SESSION['user']);
    header("Location: index.php?logout=true"); 
    die("Redirecting to: index.php");
?>