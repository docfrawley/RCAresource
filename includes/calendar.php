<? include_once("rcainitialize.php");

class calendarobject {
	
	public $cal_array = array();
	
	function __construct($college) {
		global $database;
		$sql="SELECT * FROM rcacallendar WHERE thedate>='".date('U')."' ORDER BY thedate ASC";
		$result_set = $database->query($sql);
		while($value = $database->fetch_array($result_set)){ //
			if (($value['college']=="All") OR ($value['college']==$college)) {
				array_push($this->cal_array,$value['numentry']);
			}
		} 
	}
	
	function num_calendar() {
		return count($this->cal_array);
	}
	
	function get_key($value) {
		return array_search($value, $this->cal_array);
	}
	
	function get_numkey($title){
		global $database;
		$sql = "SELECT * FROM rcacallendar WHERE thetitle='".$title."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		return $value['numentry'];
	}
	
	function delete_cal($numentry){
		global $database;
		$sql = "DELETE FROM rcacallendar ";
	  	$sql .= "WHERE numentry=". $database->escape_value($numentry);
	  	$sql .= " LIMIT 1";
	 	$database->query($sql);
		array_splice($this->cal_array, $this->get_key($numentry), 1);
	}
	
	function convert_time($info) {
		return mktime(0, 0, 0, $info['month'], $info['day'], $info['year']);
	}
	
	function edit_cal($info){
		global $database;
		$sql = "UPDATE rcacallendar SET ";
		$sql .= "thetitle='". $database->escape_value($info['thetitle']) ."', ";
		$sql .= "tbody='". $database->escape_value($info['tbody']) ."', ";
		$sql .= "college='". $database->escape_value($info['thecollege']) ."', ";
		$time_mk = $this->convert_time($info);
		$sql .= "thedate='". $time_mk ."' ";
		$sql .= "WHERE numentry='". $database->escape_value($info['numentry'])."'";
	  	$database->query($sql);
	}
	
	function add_cal($info){
		global $database;
		$sql = "INSERT INTO rcacallendar (";
	  	$sql .= "thetitle, college, thedate, tbody";
	  	$sql .= ") VALUES ('";
		$sql .= $database->escape_value($info['thetitle']) ."', '";
		$sql .= $database->escape_value($info['thecollege']) ."', '";
		$sql .= $this->convert_time($info) ."', '";
		$sql .= $database->escape_value($info['tbody'])."')";
		if($database->query($sql)) {echo "<br/>Calendar item has been posted.";} 
		else {echo "<br/>Calendar item did not post.";}
		array_push($this->cal_array, $this->get_numkey($database->escape_value($info['thetitle'])));
	}
	
	function get_content($numentry) {
		global $database;
		$sql = "SELECT * FROM rcacallendar WHERE numentry='".$numentry."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		return $value;
	}
	
	function print_calendar($dsluser) {
		foreach ($this->cal_array as $ind_cal) {
			$value = $this->get_content($ind_cal);?> 
            <div class="<? echo $value['college']."background"; ?>">  
                <div class="insidetitle"><? 
                if ($dsluser) {echo "<a href='../public/rcahome.php?numentry=".$ind_cal."&whichform=calendar'>".date('l, F j',$value['thedate'])."</a>";} 
                else {echo date('l, F j',$value['thedate']); } ?> 
                </div> <!---- <div class="insidetitle"> --->
                <div class="insideannounce">  <?
                    echo $value['college']." RCAs: <strong>".$value['thetitle']."</strong>, ";	
                    echo $value['tbody'];	?>
                </div> <!---- <div class="insideannounce"> --->
			</div> <!--- <div class "background"> ----> <?
		}
	}
	
	function mail_calendar($college){
		global $database;
		if ($thecollege==$college) {$sql  = "SELECT * FROM rca WHERE college='".$thecollege."'";} 
		else {$sql = "SELECT * FROM rca";}
		$result_set = $database->query($sql);
		$to="";
		while($value = $database->fetch_array($result_set))
			{$to .= $value."@princeton.edu, ";} 
		$subject = $thetitle;
		$message1 = $tbody."\r\n";
		$thesender = $casnetid."@princeton.edu";
		$headers = "From: ".$thesender."\r\n"."Reply-To: ".$thesender."\r\n"."X-mailer:PHP/".phpversion();
		mail ($to, $subject, $message1, $headers);	
	}
	
	function calendar_form($numentry, $college, $whichform){
		global $database;
		if ($numentry)  {
			$sql = "SELECT * FROM rcacallendar WHERE numentry='".$numentry."'";
			$result_set = $database->query($sql);
			$rowstuff = $database->fetch_array($result_set);
			$thetitle = $rowstuff['thetitle'];
			$tbody= $rowstuff['tbody'];
			$year= date('Y', $rowstuff['thedate']);
			$month= date('F', $rowstuff['thedate']);
			$nummonth=date('m', $rowstuff['thedate']);
			$day= date('d', $rowstuff['thedate']);
			$thecollege=$rowstuff['college'];
		} else { // if (($numentry) AND ($whichform=='calendar'))
			$thetitle = "";
			$tbody="";
			$year= "";
			$month= "";
			$day= "";
			$thecollege="";
		} // if (($numentry) AND ($whichform=='calendar'))
		?><br/><form action = "<? echo $self; ?>" method="post">
        <table><tr><td>Event:</td><td><input type="text" name="thetitle" value="<? echo $thetitle; ?>"/></td></tr></table>
		<table><tr><td>Info about Event:</td><tr>
        <tr><td> <textarea name="tbody" rows="7" cols="40"><? echo $tbody; ?>
		</textarea></td></tr></table>
        <table><tr><td>Date of Event</td></tr></table>
        <table><tr><td>Month</td><td>Day</td><td>Year</td></tr>
        <tr><td>
		<select name="month">
		<option selected="selected" value="<? echo $nummonth; ?>"><? echo $month; ?></option>
			<? GetMonths(); ?>
		</select></td><td>
		<select name="day">
		<option selected="selected" "<? echo $day; ?>"><? echo $day; ?></option>
			<? GetDays(); ?>
		</select>  </td><td>
		<select name="year">
		<option selected="selected" value="<? echo $year; ?>"><? echo $year; ?></option>
			<? GetYears(); ?> 
		</select></td></tr></table>
		<table><tr><td> This Event is for:</td><td><? 	
		if ($thecollege =="All") { 
			?> <input type="radio" name="thecollege" value="All" checked/> All RCAs <br/><?
		}else { // if ($thecollege =="All")
			?> <input type="radio" name="thecollege" value="All"/> All RCAs <br/> <?
		} // if ($thecollege =="All")
		if ($thecollege ==$college) { 
			?> <input type="radio" name="thecollege" value="<? echo $college; ?>" checked/> <? echo $college; ?> RCAs<?
		} else { // if ($thecollege ==$college)
			?> <input type="radio" name="thecollege" value="<? echo $college; ?>"/> <? echo $college; ?> RCAs <?
		} // if ($thecollege ==$college)
		?></td></tr>
		<input type="hidden" name="numentry" value="<? echo $numentry; ?>"/>
		<input type="hidden" name="taskannounce" value="addedit"/>
		<input type="hidden" name="whichform" value="calendar"/>
		<tr><td><input type="submit" value="submit" /></td></tr></table>
		</form><?
		if (($numentry) AND ($whichform=='calendar')) {
			echo ("<a href='../public/rcahome.php?numentry=".$numentry."&taskannounce=deleteevent&whichform=calendar'>".'DELETE THIS EVENT'."</a>"); 
		} //
	}

	
	
	

}
?>