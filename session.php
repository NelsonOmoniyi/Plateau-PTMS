<?php
@session_start();

if((time() - $_SESSION['timestamp']) > (60*5)||!isset($_SESSION['pts_username_sess'])||$_SESSION['pts_username_sess']==''||$_SESSION['pts_username_sess']==null)////inactive for 1 minutes
{ 
    session_destroy();
    include('index.php');
    //exit;
} 
else
{
    $_SESSION['timestamp'] = time(); //set new timestamp
}
?>