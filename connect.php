<?php

	$servername = "144.34.176.245:3306";
    $username = "root";
    $dbpassword = "zhi";
    $dbname = "socialnetwork";

    // Create connection
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    if ($conn->connect_error)
    {
     	die("Connection failed to database: " . $conn->connect_error);
    }

    $conn->query("set names 'utf8'");
    
?>