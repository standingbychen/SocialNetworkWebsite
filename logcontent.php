<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>  
<html>
<head>
	<title id="pagetitle">Log</title>

</head>
<body>

	<?php
		// 登陆验证
		if(!isset($_COOKIE['userid']))
		{
			echo "<script>alert('Error : You have not login!')</script>";
	    	echo "<script>setTimeout(\"window.location.href='login.html'\");</script>";
		}
		
		$userid = $_COOKIE['userid'];
		$lid = $_POST['lid']==''? $_GET['lid'] : $_POST['lid'];
		require_once("connect.php");

		$sqllog = "select * from logs where lid='$lid';";
		$result = $conn->query($sqllog);
		$row = $result->fetch_assoc();
 		// uid, lid, ltitle, lcontent, ldate
		$uid = $row['uid'];
		$ltitle = $row['ltitle'];
		$lcontent = $row['lcontent'];
		$ldate = $row['ldate'];

		$self = ($userid==$uid) ;

	?>



	<form method="post">
		<input type='text' value='<?php echo ($lid); ?>' name='lid' hidden>
		<input type="text" name="ltitle" size="30" value="<?php echo($ltitle); ?>" style="font-size: 30px" 
		<?php if (!$self) {
			echo "readonly";
		} ?> >
		<br>
		<p>From <?php  echo $uid; ?></p>
		<br>
		<textarea  name="lcontent" cols="40" rows="15"  style="font-size:20px ;resize: none;" 
		<?php if (!$self) {
			echo "readonly";
		}  ?> ><?php echo($lcontent) ?></textarea>
		<?php
			// 自己的log：修改
		 if ($self) {
			echo "<br><input type='submit' value='Save' formaction='edit.php?method=chamylog'>";
		} 
			// 朋友的log：评论，转发
		else{
			echo "<br><input type='text' name='reply'>
			<input type='submit' value='Reply' formaction='edit.php?method=replylog'>";
			echo "<br><input type='text' name='comment'>
			<input type='submit' value='Share' formaction='edit.php?method=sharelog'>";					
		} 

		?>
		</form> 



</body>
</html>