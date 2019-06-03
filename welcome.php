<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
	<title>Welcome</title>
	<script type="text/javascript">
		// check the fid input.
		function check(){
			var fid = document.getElementById('fid').value;
			if (fid.length<1) {
				alert("Please input the new friend id.");
				return false;
			}
			return true;
		}

		// get the new group number and update.
		// str: url to edit.php .
		// group : mark the group number in table to update.
		function getGroupNumber(str,groupid)
		{
		    var xmlhttp;
		    if (window.XMLHttpRequest)
		    {
		        // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
		        xmlhttp=new XMLHttpRequest();
		    }
		    else
		    {
		        // IE6, IE5 浏览器执行代码
		        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		    }

		    var num = prompt("Input new group number","0");
		    // concel:
		    if (num===null) {return;}

		    xmlhttp.onreadystatechange=function()
		    {
		        if (xmlhttp.readyState==4 && xmlhttp.status==200)
		        {
		            document.getElementById(groupid).innerHTML=xmlhttp.responseText;
		        }
		    }
		    xmlhttp.open("GET",str+"&fgroup="+num,true);
		    xmlhttp.send();
		}


	</script>
</head>
<body>
	<h1>Welcome to social network.</h1>

	
	<?php
		if(isset($_COOKIE['userid']))
		{

			echo "<h3>Congratulations! <span style='color:blue'>".$_COOKIE["username"]."</span> You have login successfully!</h3>";
		}
		else{
			echo "<script>alert('Error : You have not login!')</script>";
        	echo "<script>setTimeout(\"window.location.href='login.html'\");</script>";
		}

	?>

	<p><a href="msg.php">User Personal Information.</a></p>
	<p><a href="socialext.php">Circle of Friends.</a></p>
	<p><a href="logout.php">Logout.</a></p>

	<?php

		// 按表格形式输出，设置input
		function friendsInfo($row){
			$fgroup = $row['fgroup'];
			$fid = $row['fid'];
			$act = $row['act'];
			$tdid = "tdid".$fgroup;
			$url= "edit.php?method=change&fid=$fid";
			echo "<tr><form method='post' >";
			echo "<td id='$tdid'>".$fid."</td><td>".$fgroup."</td><td>".$act."</td>";
			echo "<td>
			<input type='submit' value='Delete' formaction='edit.php?method=delete&fid=$fid'>
			<input type='submit' value='Group'  onclick=\"getGroupNumber('$url','$tdid')\">
					</td>";
			echo "</form></tr>";
		}

		require_once('connect.php');

		$uid = $_COOKIE['userid'];
		$sqlsch = "select fid,fgroup,count(lid) as act
					from activeness
					where uid='$uid' 
					group by fid,fgroup
					order by fgroup,act
					;";
		$result = $conn->query($sqlsch);

		// 制表
		echo "<table cellspacing='10'><tr>
			<th>Friend ID</th>
			<th>Group Number</th>
			<th>Activeness</th>
			</tr>";

		while($row = $result->fetch_assoc()){
			friendsInfo($row);
			echo "<br/>";
		}
		echo "<table>";
	
	?>

	<hr>
	<form method="post" action="edit.php?method=add" >
		<table>
			<th>Add a new friend here !</th>
			<tr>
				<td>Add a new friends by id:</td>
				<td><input type="email" name="fid" id="fid"><br/></td>
			</tr>

			<tr>
				<td>Set a group number   :</td>
				<td><input type="number" name="groupid" value="0" min="0" max="10"><br/></td>
			</tr>
		</table>
		<input type="submit" value="Add new friend" onclick="return check()">
	</form>
	
		
</body>
</html>