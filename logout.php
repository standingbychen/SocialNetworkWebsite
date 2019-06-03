<?php

    if(!isset($_COOKIE['userid']))
    {
        echo "<script>alert('Error : You have not login!')</script>";
        echo "<script>setTimeout(\"window.location.href='login.html'\");</script>";
    }
    $username = $_COOKIE['userid'];
    //clear cookie
    setcookie("userid","",time()-1);
    echo "<span style='color:blue'>".$username . "</span> Welcome to log in again<br>";
    echo "Jumping to Home...";
    echo "<script>setTimeout(\"window.location.href='login.html'\", 2000)</script>";

?>