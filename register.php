<?php 

    //Sanitize the customer data
    $email=filter_var($_POST["email"],FILTER_SANITIZE_EMAIL);
    $password=filter_var($_POST["password"],FILTER_SANITIZE_STRING);

    include 'connect.php';

    $sqlstr = "select uid from users where uid='$email';";
    $result = $conn->query($sqlstr);

    if ($result->num_rows > 0) {
        echo "<script>alert('This email has been registered already')</script>";
        echo "<script>setTimeout(\"window.location.href='reg.html'\");</script>";
    }

    // close auto-commit
    $conn->autocommit(false);

    $sqlins1 = "INSERT INTO users (uid, password) values ('$email','$password');";
    $sqlins2 = "INSERT INTO emails (uid, email) values ('$email','$email');";
    // emails rely on users' key
    

	if ( ( $conn->query($sqlins1) && $conn->query($sqlins2) ) === TRUE) {
		// if ok , commit
		$conn->commit();
        setcookie("userid",$email,time()+3*60*60);
        setcookie("username",$email,time()+3*60*60);
        echo "<script>alert('Regist Successfully.')</script><br/>";
		echo "<script>setTimeout(\"window.location.href='welcome.php'\" ,1000);</script>";
	}else {
		$conn->rollback();
		echo "<script>alert('Regist Failed.')</script><br/>";
		echo "<script>setTimeout(\"window.location.href='reg.html'\",1000);</script>";
	}

    $conn->close();

?>

