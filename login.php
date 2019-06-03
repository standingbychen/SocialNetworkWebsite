<?php

    $email=filter_var($_POST["email"],FILTER_SANITIZE_EMAIL);
    $password=filter_var($_POST["password"],FILTER_SANITIZE_STRING);

   	include 'connect.php';

    $sql = "select uid, password, uname from users where uid=" . "'" . $email . "'" . ";";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    //email not exist
    if ($row == "") {
        echo "<script>alert('Error : this email is not registered, please register first')</script>";
        echo "<script>setTimeout(\"window.location.href='login.html'\");</script>";
    }
    elseif ($row["password"] == $password) {
        // login successfully
        setcookie("userid",$email,time()+3*60*60);
        $uname = $row['uname'] == null ? $email : $row['uname'];
        setcookie("username",$uname,time()+3*60*60);

        echo "<script>setTimeout(\"window.location.href='welcome.php'\" ,1000);</script>";
    }
    else{
        echo "<script>alert('Error : wrong password')</script>";
        echo "<script>setTimeout(\"window.location.href='login.html'\");</script>";
    }
    
    $conn-> close();


?>