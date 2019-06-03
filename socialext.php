<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>  
<html>
<head>
	<title>SocialExt</title>
	<script type="text/javascript">
		function checklog(){
			var title = document.getElementById('logtitle').value;
			if (title.length<1) {
				alert('Please input title.');
				return false;
			}
			var content = document.getElementById('logcontent').value;
			if (content.length<1) {
				alert('Please input content.');
				return false;
			}
			if (content.length>400) {
				alert('The content should be less than 400.');
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
		echo "<h1>SocialExt of <span style='color:blue'>".$uname."</span> </h1>";
	?>

	<a href="welcome.php">return</a> 
	<!-- 发表日志 -->
	<form method='post' action="edit.php?method=rellog" >
		<hr>
		<h3>Share a Log with others.</h3>
		Title:<br> <input type="text" name="ltitle" id="logtitle"><br>
		log content:<br> <textarea rows="4" cols="30" style="resize: none;" name="lcontent" id="logcontent"></textarea><br>
		<input type="submit" value="Release" onclick="return checklog()">
		<br>
	</form>

	<?php

		// 查看朋友发布/转发日志
		require_once("Log.php");
		
		echo "<hr>";
		echo "<h3>Friends' Logs</h3>";
		echo "<table cellspacing='10'>";
		foreach ($logsarr as $log) {
			$log->print();
		}
		echo "</table>";

		// 查看自己日志
		$sqlmlog = "select lid,ltitle,ldate from logs where uid = '$uid';";
		$result = $conn->query($sqlmlog);

		echo "<hr>";
		echo "<h3>My Logs</h3>";
		echo "<table cellspacing='10'>";
		while ($row = $result->fetch_assoc()) {
			$lid = $row['lid'];
			// 日志标题，时间，删除，编辑
			echo "<tr><form method='post'>";
			echo "<td><span style='color:blue'>".$row['ltitle']."</span></td>";
			echo "<td><span style='font-size:10px'>".$row['ldate']."</span></td>";
			echo "<td><input type='text' value='$lid' name='lid' hidden><input type='submit' value='delete' formaction='edit.php?method=delmylog'> ";
			echo "<input type='submit' value='edit' formaction='logcontent.php'></td>";
			echo "</form></tr>";
			// 回复

			$sqlrep = "select ruid,uname,rcontent,rdate from replys join users where ruid=uid and lid='$lid';";
			$resrep = $conn->query($sqlrep);
			while ($rowrep = $resrep->fetch_assoc()) {
				$repname = $rowrep['uname'] ==''? $rowrep['ruid'] : $rowrep['uname'];
				echo "<tr><td> </td>";
				echo "<td colspan='10' style='font-size:15px'>";
				echo $repname."<span style='font-size:10px'> reply: </span>".$rowrep['rcontent']."<span style='font-size:10px'> at ".$rowrep['rdate']."</span>";
				echo "</td></tr>";
			}

		}
		echo "</table>";



	?>

</body>
</html>