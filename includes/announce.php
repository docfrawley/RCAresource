<? include_once("rcainitialize.php");

class announceobject {
	
	public $an_array = array();
	
	function __construct($college) {
		global $database;
		$sql="SELECT * FROM rcaannounce WHERE whenend>='".date('U')."' ORDER BY posted DESC";
		$result_set = $database->query($sql);
		while($value = $database->fetch_array($result_set)){ //
			if (($value['college']=="All") OR ($value['college']==$college)) {
				array_push($this->an_array,$value['numentry']);
			}
		} 
	}
	
	function num_announce() {
		return count($this->an_array);
	}
	
	function get_key($value) {
		return array_search($value, $this->an_array);
	}
	
	function get_numkey($title){
		global $database;
		$sql = "SELECT * FROM rcaannounce WHERE thetitle='".$title."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		return $value['numentry'];
	}
	
	function delete_announce($numentry){
		global $database;
		$sql = "DELETE FROM rcaannounce ";
	  	$sql .= "WHERE numentry=". $database->escape_value($numentry);
	  	$sql .= " LIMIT 1";
	 	$database->query($sql);
	  	if ($database->affected_rows() == 1) {echo "<br/>Announcement has been deleted.";}
		else {echo "<br/> Announcement was not deleted.";}
		array_splice($this->an_array, $this->get_key($numentry), 1);
	}
	
	function convert_time($info) {
		#if ($info['month']=="" && $info['day']=="" && $info['year']=="") {
		#	return mktime(0, 0, 0, date('m', date('U')), date('d', date('U')), date('Y', date('U')));
		#}
		return mktime(0, 0, 0, $info['month'], $info['day'], $info['year']);
	}
	
	function edit_announce($info){
		global $database;
		$sql = "UPDATE rcaannounce SET ";
		$sql .= "thetitle='". $database->escape_value($info['thetitle']) ."', ";
		$sql .= "tbody='". $database->escape_value($info['tbody']) ."', ";
		$sql .= "college='". $database->escape_value($info['thecollege']) ."', ";
		$time_mk = $this->convert_time($info);
		$sql .= "whenend='". $time_mk ."', ";
		$sql .= "posted='". $database->escape_value($info['posted']) ."' ";
		$sql .= "WHERE numentry='". $database->escape_value($info['numentry'])."'";
	  	$database->query($sql);
	}
	
	function add_announce($info){
		global $database;
		$sql = "INSERT INTO rcaannounce (";
	  	$sql .= "thetitle, tbody, college, whenend, posted";
	  	$sql .= ") VALUES ('";
		$sql .= $database->escape_value($info['thetitle']) ."', '";
		$sql .= $database->escape_value($info['tbody']) ."', '";
		$sql .= $database->escape_value($info['thecollege']) ."', '";
		$sql .= $this->convert_time($info) ."', '";
		$sql .= date('U') ."')";
		if($database->query($sql)) {echo "<br/>Announcement has been posted.";} 
		else {echo "<br/>Announcement did not post.";}
		array_push($this->an_array, $this->get_numkey($database->escape_value($info['thetitle'])));
	}
	
	function get_content($numentry) {
		global $database;
		$sql = "SELECT * FROM rcaannounce WHERE numentry='".$numentry."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		return $value;
	}
	
	function print_announcements($dsluser) {
		foreach ($this->an_array as $ind_announce) {
			$value = $this->get_content($ind_announce);
			?> 
            <div class="<? echo $value['college']."background"; ?>">  
                <div class="insidetitle"><? 
                if ($dsluser) {echo "<a href='../public/rcahome.php?numentry=".$ind_announce."&whichform=announce'>".$value['college']." RCAs: ".$value['thetitle']."</a>";} 
                else {echo $value['college']." RCAs: ".$value['thetitle']; } ?> 
               	<div id="littleguy"><?
							echo "posted: ".date("m",$value['posted'])."/".date("j",$value['posted'])."/".date("Y",$value['posted']);
				?></div> <!---- <div class="littleguy"> --->
                </div> <!---- <div class="insidetitle"> --->
                <div class="insideannounce">  <?
                    echo $value['tbody'];	?>
                </div> <!---- <div class="insideannounce"> --->
			</div> <!--- <div class "background"> ----> <?
		}
	}
	
	function mail_announcement($college){
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
	
	function announcment_form($numentry, $college, $whichform){
		global $database;
		if ($numentry) {
			$sql = "SELECT * FROM rcaannounce WHERE numentry='".$numentry."'";
						$result_set = $database->query($sql);
						$found_user = $database->fetch_array($result_set);
						$athetitle = $found_user['thetitle'];
						$atbody= $found_user['tbody'];
						$ayear= date('Y', $found_user['whenend']);
						$amonth= date('F', $found_user['whenend']);
						$anummonth=date('m', $found_user['whenend']);
						$aday= date('d', $found_user['whenend']);
						$athecollege=$found_user['college'];
						$posted=$found_user['posted'];
		} else { //if (($numentry) AND ($whichform=='announce'))	
						$athetitle = "";
						$atbody="";
						$ayear= "";
						$amonth= "";
						$aday= "";
						$athecollege="";
						$posted=date('U');
		} //if (($numentry) AND ($whichform=='announce'))
				
					?><br/><form action = "<? echo $self; ?>" method="post">
                    <table><tr><td>Title:</td><td><input type="text" name="thetitle" value="<? echo $athetitle; ?>"/></td></tr></table>
					<table><tr><td>Announcement:</td><tr>
                    <tr><td> <textarea name="tbody" rows="7" cols="40"><? echo $atbody; ?>
							</textarea></td></tr></table>
                            <table><tr><td>Date When Take Down Post</td></tr></table>
                            <table><tr><td>Month</td><td>Day</td><td>Year</td></tr>
                            <tr><td>
						<select name="month">
							<option selected="selected" value="<? echo $anummonth; ?>"><? echo $amonth; ?></option>
							<? GetMonths(); ?>
							</select></td><td>
						<select name="day">
							<option selected="selected" "<? echo $aday; ?>"><? echo $aday; ?></option>
							<? GetDays(); ?>
							</select>  </td><td>
							<select name="year">
								<option selected="selected" value="<? echo $ayear; ?>"><? echo $ayear; ?></option>
								<? GetYears(); ?>  
							</select></td></tr></table>
							<table><tr><td> This Announcement is for:</td><td>
                            <? if ($athecollege =="All") { 
									?> <input type="radio" name="thecollege" value="All" checked/> All RCAs <br/><?
								} else { // ($athecollege =="All")
									?> <input type="radio" name="thecollege" value="All"/> All RCAs <br/> <?
								} // ($athecollege =="All")
								
								if ($athecollege ==$college) { 
									?> <input type="radio" name="thecollege" value="<? echo $college; ?>" checked/> <? echo $college; ?> RCAs<?
								}else { //if ($athecollege ==$college)
									?> <input type="radio" name="thecollege" value="<? echo $college; ?>"/> <? echo $college; ?> RCAs <?
								} // if ($athecollege ==$college)
							?></td></tr>
							<input type="hidden" name="numentry" value="<? echo $numentry; ?>"/>
							<input type="hidden" name="posted" value="<? echo date('U'); ?>"/>
							<input type="hidden" name="taskannounce" value="addedit"/>
							<input type="hidden" name="whichform" value="announce"/>
							<tr><td><input type="submit" value="submit" /></td></tr></table>
							</form><?
							if (($numentry) AND ($whichform=='announce')) {
								echo ("<a href='../public/rcahome.php?numentry=".$numentry."&taskannounce=deleteannounce&whichform=announce'>".'DELETE THIS EVENT'."</a>"); 
							} // if (($numentry) AND ($whichform=='announce'))

	}

	
	
	

}
?>