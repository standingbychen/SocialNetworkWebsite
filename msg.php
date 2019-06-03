<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>  
<html>
<head>
	<title>Personal Information</title>
	<script type="text/javascript">
		function checksex() {
			var sex = document.getElementById("usex").value;
			if (sex=='m'||sex=='f' ||sex=='') {return true;}
			alert("Sex should be 'm' or 'f' .");
			return false;
		}
		function checkemail(){
			var email = document.getElementById("email").value;
			if (email.length<1) {
				alert("Please input the new email.");
				return false;
			}
			return true;
		}
		function checktopic() {
			var topic = document.getElementById("itopic").value;
			if (itopic.length<0 || itopic.length>10) {
				alert("Please input a topic that does not exceed 10 characters.");
				return false;
			}
			return true;
		}
	</script>
</head>
<body>

	<?php

		if(!isset($_COOKIE['userid']))
		{
			echo "<script>alert('Error : You have not login!')</script>";
	    	echo "<script>setTimeout(\"window.location.href='login.html'\");</script>";
		}

		$userid = $_COOKIE['userid'];
		$uname = $_COOKIE['username'];
		echo "<h1>Personal Information of <span style='color:blue'>".$uname."</span> </h1>";
		
		echo "<a href='welcome.php'>return</a>";

		// 个人基本信息
		function perinfo($row){
			 
			$uname = $row['uname'];
			$usex = $row['usex'];
			$ubirthday = $row['ubirthday'];
			$uaddress = $row['uaddress'];

			echo "<hr><h3>Basic Information</h3>";
			echo "<form action='edit.php?method=perinfo' method='post'><table cellspacing='10'>";
			echo "<tr><td>name</td>
			<td><input type='text' value='$uname' name='uname'></td></tr>";
			echo "<tr><td>sex(m/f)</td>
			<td><input type='text' value='$usex' name='usex' id='usex'></td></tr>";
			echo "<tr><td>birthday</td>
			<td><input type='date' value='$ubirthday' name='ubirthday'></td></tr>";
			echo "<tr><td>address</td>
			<td><input type='text' value='$uaddress' name='uaddress'></td></tr>";
			echo "</table>";
			echo "<input type='submit' value='Save' onclick='return checksex()'>";
			echo "</form>";
		}

		// 个人邮箱
		function emails($row){
			$email = $row['email'];
			global $userid;
			echo "<tr><form method='post'>";
			echo "<td><input type='text' value='$email' readonly ></td>";
			if ($email!=$userid) {
				echo "<td><input type='submit' value='Delete' formaction='edit.php?method=delemail&email=$email'></td>";	
			}
			echo "</form></tr>";
		}

		// 个人兴趣话题
		function interests($row){
			$itopic = $row['itopic'];
			echo "<tr><form method='post'>";
			echo "<td><input type='text' value='$itopic' readonly >";
			echo "<input type='text' value='$itopic' name='itopic' hidden ></td>";
			echo "<td><input type='submit' value='Delete' formaction='edit.php?method=deltopic'></td>";	
			echo "</form></tr>";
		}

		// 个人教育经历
		function education($row){
			$eid= $row['eid'];
			$elevel = $row['elevel'];
			$eschool = $row['eschool'];
			$edegree = $row['edegree'];
			$estart = $row['estart'];
			$eend = $row['eend'];

			echo "<tr><form method='post'>";
			echo "<td><input type='text' name='elevel' value='$elevel'></td>";
			echo "<td><input type='text' name='eschool' value='$eschool'></td>";
			echo "<td><input type='text' name='edegree' value='$edegree'></td>";
			echo "<td><input type='date' name='estart' value='$estart'></td>";
			echo "<td><input type='date' name='eend' value='$eend'></td>";
			echo "<td><input type='submit' value='Delete' formaction='edit.php?method=deledu'></td>";
			echo "<input type='number' name='eid' value='$eid' hidden>";
			echo "<td><input type='submit' value='Save' formaction='edit.php?method=chaedu'></td>";
			echo "</form></tr>";
		}

		// 个人工作经历 
		function work($row){
			$cid= $row['cid'];
			$corg = $row['corg'];
			$cpos = $row['cpos'];
			$cstart = $row['cstart'];
			$cend = $row['cend'];

			echo "<tr><form method='post'>";
			echo "<td><input type='text' name='corg' value='$corg'></td>";
			echo "<td><input type='text' name='cpos' value='$cpos'></td>";
			echo "<td><input type='date' name='cstart' value='$cstart'></td>";
			echo "<td><input type='date' name='cend' value='$cend'></td>";
			echo "<td><input type='submit' value='Delete' formaction='edit.php?method=delcar'></td>";
			echo "<input type='number' name='cid' value='$cid' hidden>";
			echo "<td><input type='submit' value='Save' formaction='edit.php?method=chacar'></td>";
			echo "</form></tr>";
		}

		require_once('connect.php');
		

		// 输出个人信息
		$sqlpi = "select * from users where uid='$userid';";
		$result = $conn->query($sqlpi);
		perinfo($result->fetch_assoc());


		// 邮箱信息
		$sqleml = "select email from emails where uid='$userid';";
		$result = $conn->query($sqleml);

		echo "<hr><h3>Emails</h3><table>";
		while ($row = $result->fetch_assoc()) {
			emails($row);
		}
		echo "<tr><form method='post' action='edit.php?method=addemail'>";
		echo "<td><input type='email' name='email' id='email'></td>";
		echo "<td><input type='submit' value='Add' onclick='return checkemail()' ></td>"; 
		echo "</form></tr></table>";
		
		// 兴趣话题
		$sqleml = "select itopic from interest where uid='$userid';";
		$result = $conn->query($sqleml);

		echo "<hr><h3>Topic of interest</h3><table>";
		while ($row = $result->fetch_assoc()) {
			interests($row);
		}
		echo "<tr><form method='post' action='edit.php?method=addtopic'>";
		echo "<td><input type='text' name='itopic' id='itopic'></td>";
		echo "<td><input type='submit' value='Add' onclick='return checktopic()' ></td>"; 
		echo "</form></tr></table>";


		
		// 教育经历
		$sqledu = "select * from education where uid='$userid';";
		$result = $conn->query($sqledu);
		echo "<hr><h3>Education</h3><table>
			<th>Level</th><th>School</th><th>Degree</th><th>Start</th><th>End</th>";
		while ($row = $result->fetch_assoc()) {
			education($row);
		}
		echo "<tr><form method='post'>";
		echo "<td><input type='text' name='elevel' ></td>";
		echo "<td><input type='text' name='eschool' ></td>";
		echo "<td><input type='text' name='edegree' ></td>";
		echo "<td><input type='date' name='estart'></td>";
		echo "<td><input type='date' name='eend' ></td>";
		echo "<td><input type='submit' value='Add' formaction='edit.php?method=addedu'></td>";
		echo "</form></tr></table>";


		// 工作经历
		$sqlcar = "select * from career where uid='$userid';";
		$result = $conn->query($sqlcar);
		echo "<hr><h3>Career</h3><table>
			<th>Organization</th><th>Position</th><th>Start</th><th>End</th>";
		while ($row = $result->fetch_assoc()) {
			work($row);
		}
		echo "<tr><form method='post'>";
		echo "<td><input type='text' name='corg' ></td>";
		echo "<td><input type='text' name='cpos' ></td>";
		echo "<td><input type='date' name='cstart'></td>";
		echo "<td><input type='date' name='cend' ></td>";
		echo "<td><input type='submit' value='Add' formaction='edit.php?method=addcar'></td>";
		echo "</form></tr></table>";



	?>

</body>
</html>