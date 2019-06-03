<?php
	
	// 防止直接访问
	if (!isset($_COOKIE['userid'])) {
		echo "<script>alert('Error : You have not login!')</script>";
        echo "<script>setTimeout(\"window.location.href='login.html'\");</script>";
	}

	$method = $_GET['method'];

	function skipmsg($result,$str){
		if ( $result ==TRUE) {
			echo "<script>alert('".$str." Successfully!')</script>";
        	echo "<script>setTimeout(\"window.location.href='msg.php'\");</script>";
		}else{
			echo "<script>alert('".$str." Failed.')</script>";
        	echo "<script>setTimeout(\"window.location.href='msg.php'\");</script>";
		}
	}

	function skipsol($result,$str){
		if ( $result ==TRUE) {
			echo "<script>alert('".$str." Successfully!')</script>";
        	echo "<script>setTimeout(\"window.location.href='socialext.php'\");</script>";
		}else{
			echo "<script>alert('".$str." Failed.')</script>";
        	echo "<script>setTimeout(\"window.location.href='socialext.php'\");</script>";
		}
	}

	function skipto($result,$str,$tar){
		if ( $result ==TRUE) {
			echo "<script>alert('".$str." Successfully!')</script>";
        	echo "<script>setTimeout(\"window.location.href='$tar'\");</script>";
		}else{
			echo "<script>alert('".$str." Failed.')</script>";
        	echo "<script>setTimeout(\"window.location.href='$tar'\");</script>";
		}
	}

	// add new friends
	if ($method=='add') {
		require_once("connect.php");
		$uid = $_COOKIE["userid"];
		$fid = $_POST['fid'];
		$groupid = $_POST['groupid']==''? 0:$_POST['groupid'];

		$sqlid = "select uid from users where uid='$fid'";
		$result = $conn->query($sqlid);
		if ($result->fetch_assoc() =="") {
			echo "<script>alert('Error : this email is not registered')</script>";
	        echo "<script>setTimeout(\"window.location.href='welcome.php'\");</script>";
		}else{
			$sqlins ="insert into friends values ('$uid','$fid','$groupid')";
			$result = $conn->query($sqlins);
			if ($result === TRUE) {
				echo "<script>alert('Added Successfully!')</script>";
	        	echo "<script>setTimeout(\"window.location.href='welcome.php'\");</script>";
			}else{
				echo "<script>alert('You have added the friend!')</script>";
	        	echo "<script>setTimeout(\"window.location.href='welcome.php'\");</script>";
			}
		}
		$conn->close();
	}

	// delete a friend
	if ($method=='delete') {
		$fid = $_GET['fid'];
		$userid = $_COOKIE['userid'];
		
		require_once("connect.php");

		$sqldel = "delete from friends where uid='$userid' and fid='$fid' ;";
		$result = $conn->query($sqldel);

		if ( $result ==TRUE) {
			echo "<script>alert('Deleted Successfully!')</script>";
        	echo "<script>setTimeout(\"window.location.href='welcome.php'\");</script>";
		}else{
			echo "<script>alert('Delete Failed.')</script>";
        	echo "<script>setTimeout(\"window.location.href='welcome.php'\");</script>";
		}
		$conn->close();
	}

	// change the group number of friend
	if ($method=='change') {
		$fid = $_GET['fid'];
		$fgroup = $_GET['fgroup'];
		$userid = $_COOKIE['userid'];

		require_once("connect.php");

		$sqldel = "update friends set fgroup=$fgroup where uid='$userid' and fid='$fid' ;";
		$result = $conn->query($sqldel);
		if ( $result ==TRUE) {
			echo $fgroup;
		}
		$conn->close();
	}

	// change personal information.
	if ($method=='perinfo') {
		$userid = $_COOKIE['userid'];
		$uname = $_POST['uname'];
		$usex = $_POST['usex'];
		$ubirthday = $_POST['ubirthday'] ==''? '0000-01-01' : $_POST['ubirthday'] ;
		$uaddress = $_POST['uaddress'];

		require_once('connect.php');

		$sqlupd = "update users set uname='$uname',usex='$usex',ubirthday='$ubirthday',uaddress='$uaddress' where uid='$userid';";
		$result = $conn->query($sqlupd);

		if ( $result ==TRUE) {
			echo "<script>alert('Saved Successfully!')</script>";
        	echo "<script>setTimeout(\"window.location.href='msg.php'\");</script>";
		}else{
			echo "<script>alert('Save Failed.')</script>";
        	echo "<script>setTimeout(\"window.location.href='msg.php'\");</script>";
		}
		$conn->close();
	}

	// delete emails.
	if ($method =='delemail') {
		$userid = $_COOKIE['userid'];
		$email = $_GET['email'];

		if ($userid==$email) {
			echo "<script>alert('The email as user id can not be deleted.')</script>";
			echo "<script>setTimeout(\"window.location.href='msg.php'\");</script>";
			return;
		}

		require_once('connect.php');

		$sqldel = "delete from emails where uid='$userid' and email='$email';";
		$result = $conn->query($sqldel);
		skipmsg($result,'Delete');
		$conn->close();
	}

	// add new email.
	if ($method == 'addemail') {
		$email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
		if ($email==false) {
			echo "<script>alert('Email Format Error!')</script>";
        	echo "<script>setTimeout(\"window.location.href='msg.php'\");</script>";
		}

		require_once('connect.php');

		$userid = $_COOKIE['userid'];
		$sqlins = "insert into emails values ('$userid','$email'); ";
		$result = $conn->query($sqlins);
		skipmsg($result,'Add');
		$conn->close();
	}

	// delete new topic
	if ($method =='delemail') {
		$userid = $_COOKIE['userid'];
		$itopic = $_POST['itopic'];

		require_once('connect.php');

		$sqldel = "delete from interest where uid='$userid' and itopic='$itopic';";
		$result = $conn->query($sqldel);
		skipmsg($result,'Delete');
		$conn->close();
	}

	// add new topic
	if ($method == 'addtopic') {
		$userid = $_COOKIE['userid'];
		$itopic = $_POST['itopic'];

		require_once('connect.php');

		$sqlins = "insert into interest values ('$userid','$itopic'); ";
		$result = $conn->query($sqlins);
		skipmsg($result,'Add');
		$conn->close();
	}

	// delete education record.
	if ($method == 'deledu') {
		$eid = $_POST['eid'];

		require_once('connect.php');

		$sqldel = "delete from education where eid='$eid';";
		
		$result = $conn->query($sqldel);
		skipmsg($result,'Delete');
		$conn->close();
	}

	// change and save education record.
	if ($method == 'chaedu') {
		$eid = $_POST['eid'];
		$elevel = $_POST['elevel'];
		$eschool = $_POST['eschool'];
		$edegree = $_POST['edegree'];
		$estart = $_POST['estart'] ==''? '0000-01-01' : $_POST['estart']; # 修订特殊值
		$eend = $_POST['eend'] == ''? '0000-01-01' : $_POST['eend'] ;	# 修订特殊值

		require_once('connect.php');

		$sqlupd = "update education set elevel='$elevel', eschool='$eschool', edegree='$edegree', estart='$estart', eend='$eend' where eid='$eid';";
		
		$result = $conn->query($sqlupd);
		skipmsg($result,'Save');
		$conn->close();
	}

	// add new education record.
	if ($method == 'addedu') {
		$elevel = $_POST['elevel'];
		$eschool = $_POST['eschool'];
		$edegree = $_POST['edegree'];
		$estart = $_POST['estart'] ==''? '0000-01-01' : $_POST['estart']; # 修订特殊值
		$eend = $_POST['eend'] == ''? '0000-01-01' : $_POST['eend'] ;	# 修订特殊值
		$userid = $_COOKIE['userid'] ;

		require_once('connect.php');

		$sqlins = "insert into education (uid,elevel,eschool,edegree,estart,eend) values ('$userid','$elevel','$eschool','$edegree','$estart','$eend');";
		
		$result = $conn->query($sqlins);
		skipmsg($result,'Add');
		$conn->close();
	}

	// delete career record.
	if ($method == 'delcar') {
		$cid = $_POST['cid'];

		require_once('connect.php');

		$sqldel = "delete from career where cid='$cid';";
		
		$result = $conn->query($sqldel);
		skipmsg($result,'Delete');
		$conn->close();
	}

	// change and save career record.
	if ($method == 'chacar') {
		$cid= $_POST['cid'];
		$corg = $_POST['corg'];
		$cpos = $_POST['cpos'];
		$cstart = $_POST['cstart'] ==''? '0000-01-01' : $_POST['cstart'];
		$cend = $_POST['cend'] == ''? '0000-01-01' : $_POST['cend'];

		require_once('connect.php');

		$sqlupd = "update career set corg='$corg', cpos='$cpos', cstart='$cstart', cend='$cend' where cid='$cid';";

		$result = $conn->query($sqlupd);
		skipmsg($result,'Save');
		$conn->close();
	}

	// add new career record.
	if ($method == 'addcar') {
		$corg = $_POST['corg'];
		$cpos = $_POST['cpos'];
		$cstart = $_POST['cstart'] ==''? '0000-01-01' : $_POST['cstart'];
		$cend = $_POST['cend'] == ''? '0000-01-01' : $_POST['cend'];
		$userid = $_COOKIE['userid'] ;

		require_once('connect.php');

		$sqlins = "insert into career (uid,corg,cpos,cstart,cend) values ('$userid','$corg','$cpos','$cstart','$cend');";
		
		$result = $conn->query($sqlins);
		skipmsg($result,'Add');
		$conn->close();
	}

	// release new log.
	if ($method == 'rellog') {
		$uid = $_COOKIE['userid'];
		$ltitle = $_POST['ltitle'];
		$lcontent = $_POST['lcontent'];
		$ldate = date("Y-m-d h-i-s");

		require_once('connect.php');

		$sqlins = "insert into logs (uid,ltitle,lcontent,ldate) values ('$uid','$ltitle','$lcontent','$ldate');";
		
		$result = $conn->query($sqlins);
		skipsol($result,'Release'); 
		$conn->close();
	}

	// delete my log.
	if ($method == 'delmylog') {
		$lid = $_POST['lid'];
		require_once('connect.php');
		$sqldel = "delete from logs where lid='$lid';";
		$result = $conn->query($sqldel);
		skipsol($result,'Delete'); 
		$conn->close();
	}

	// change and save mylog
	if ($method == 'chamylog') {
		$lid = $_POST['lid'];
		$ltitle = $_POST['ltitle'];
		$lcontent = $_POST['lcontent'];
		$ldate = date("Y-m-d h-i-s");

		require_once('connect.php');
		$sqlupd = "update logs set ltitle='$ltitle', lcontent='$lcontent', ldate='$ldate' where lid='$lid';";
		$result = $conn->query($sqlupd);
		skipsol($result,'Save'); 
		$conn->close();
	}

	// reply log
	if ($method == 'replylog') {
		$lid = $_POST['lid'];
		$rcontent = $_POST['reply'];
		$rdate = date("Y-m-d h-i-s");
		$uid = $_COOKIE['userid'];

		require_once('connect.php');
		$sqlins = "insert into replys values ('$lid','$uid','$rcontent','$rdate');";
		$result = $conn->query($sqlins); 
		skipsol($result,'Reply'); 
		$conn->close();
	}

	// share log
	if ($method == 'sharelog') {
		$lid = $_POST['lid'];
		$scontent = $_POST['comment'];
		$sdate = date("Y-m-d h-i-s");
		$uid = $_COOKIE['userid'];

		require_once('connect.php');
		$sqlins = "insert into shares values ('$lid','$uid','$scontent','$sdate');";
		$result = $conn->query($sqlins);
		skipsol($result,'Share'); 
		$conn->close(); 
	}


?>