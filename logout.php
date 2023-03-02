<?php
    include("libs/dbfunctions.php");
    $dbobject = new dbobject();
    session_destroy();
    header('location:index.php');
?>

