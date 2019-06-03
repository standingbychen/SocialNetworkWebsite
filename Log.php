<?php
	
	/**
	 * define class Log
	 * record logs released by friends
	 * record logs shared by friends
	 */
	class Log
	{
		var $fid;
		var $fname;

		var $lid;
		var $ltitle;
		// time of being released or shared
		var $date;

		var $scontent;
		var $shared;

		public function print(){
			$name = $this->fname == ''? $this->fid : $this->fname;
			$act = $this->shared? 'shared' : 'released' ;

			echo "<tr>";
			echo "<td><b>".$name."</b></td>";
			echo "<td>".$act."</td>";
			echo "<td><a href='logcontent.php?lid=$this->lid'>".$this->ltitle."</a></td>";
			echo "<td><span style='font-size:10px'>".$this->date."</span></td>";
			if ($this->shared) {
				echo "<td>comment: ".$this->scontent."</td>";
			}
			echo "</tr>";
		}


		// record logs released by friends
		// lid, ltitle, ldate, uname, fid
		public static function getFriLog($row){
			$log = new Log;
			$log->fid = $row['fid'];
			$log->fname = $row['uname'];
			$log->lid = $row['lid'];
			$log->ltitle = $row['ltitle'];
			$log->date = $row['ldate'];
			$log->shared = false;
			return $log;
		}

		// record logs shared by friends
		// fid, uname, lid, ltitle, scontent, sdate
		public static function getShaLog($row){
			$log = new Log;
			$log->fid = $row['fid'];
			$log->fname = $row['uname'];
			$log->lid = $row['lid'];
			$log->ltitle = $row['ltitle'];
			$log->date = $row['sdate'];
			$log->scontent = $row['scontent'];
			$log->shared = true;
			return $log;
		}

	}
	
	function sortByDate($a,$b){
		return strcmp($b->date, $a->date);
	}

	require('connect.php');
	$logsarr = array();
 
	$uid = $_COOKIE['userid'];
	$lastweek = date("Y-m-d h-i-s",strtotime("-1 week"));

	// logs released;
	$sqlflog = "select * from fri_logs where uid='$uid' and ldate>'$lastweek';";
	$result = $conn->query($sqlflog);

	while ( $row = $result->fetch_assoc()) {
		$logobj =  Log::getFriLog($row);
		array_push($logsarr, $logobj);
	}

	// logs shared
	$sqlslog = "select * from sha_logs where uid='$uid' and sdate>'$lastweek';";
	$result = $conn->query($sqlslog); 

	while ( $row = $result->fetch_assoc()) {
		$logobj =  Log::getShaLog($row);
		array_push($logsarr, $logobj);
	}

	// sort
	usort($logsarr, 'sortByDate');

?>