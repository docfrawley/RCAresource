<? include_once("rcainitialize.php");

class logobject {
	
	private $thedate;
	private $netid;
	private $college;
	
	function __construct($netid, $thedate, $college) {
		$this->netid = $netid;
		$this->logdate = $thedate;
		$this->college = $college;
	}
	
	function completed() {
		global $database;
		$sql = "SELECT * FROM oncalllog WHERE oncalldate='".$this->logdate."' AND oncallp = '".$this->netid."'";
		$result_set = $database->query($sql);
		return $database->num_rows($result_set) >0;
		
	}
	
	
	function add_log($info) {
		global $database;
		$sql = "INSERT INTO oncalllog (";
	  		$sql .= "oncalldate, oncallp, college, numlowrisk, highrisknop, highriskp, reasonfor, numtrans, noiselevel1, noiselevel2, noiselevel3, whereactive, interesting, facility, oncallphone, interactwith, comstandards, nomcomplaints, hostevent, desevent";
	  		$sql .= ") VALUES ('";
			$sql .= $this->logdate ."', '";
			$sql .= $this->netid ."', '";
			$sql .= $database->escape_value($this->college) ."', '";
			$sql .= $database->escape_value($info['numlowrisk']) ."', '";
			$sql .= $database->escape_value($info['highrisknop']) ."', '";
			$sql .= $database->escape_value($info['highriskp']) ."', '";
			$sql .= $database->escape_value($info['reasonfor']) ."', '";
			$sql .= $database->escape_value($info['numtrans']) ."', '";
			$sql .= $database->escape_value($info['noiselevel1']) ."', '";
			$sql .= $database->escape_value($info['noiselevel2']) ."', '";
			$sql .= $database->escape_value($info['noiselevel3']) ."', '";
			$sql .= $database->escape_value($info['whereactive']) ."', '";
			$sql .= $database->escape_value($info['interesting']) ."', '";
			$sql .= $database->escape_value($info['facility']) ."', '";
			$sql .= $database->escape_value($info['oncallphone']) ."', '";
			$sql .= $database->escape_value($info['interactwith']) ."', '";
			$sql .= $database->escape_value($info['comstandards']) ."', '";
			$sql .= $database->escape_value($info['nomcomplaints']) ."', '";
			$sql .= $database->escape_value($info['hostevent']) ."', '";
			$sql .= $database->escape_value($info['desevent']) ."')";
			if ($database->query($sql)){	
					echo $this->netid.", thank you for completing the on-call log.<br/><br/>A copy of your log report has been sent to your DSL.";
			}
	}
	
	function mail_log(){
		global $database;
		$sql = "SELECT * FROM oncalllog WHERE oncalldate='".$this->logdate."' AND oncallp = '".$this->netid."'";
		$result_set = $database->query($sql);
		$result_row = $database->fetch_array($result_set);
		$dslmail = getemailaddress($this->college);
		$to = $dslmail."@princeton.edu";
		$subject = "RCA Log Report for".date('l, F d, Y', $this->logdate);
		$message = '(1) Overall noise level of the college from 11:00pm to 11:30pm: '.$result_row['noiselevel1']."\r\n"."\r\n";
		$message .= 'from 11:30pm to 12:00am: '.$result_row['noiselevel2']."\r\n"."\r\n";
		$message .= 'from 12:00am to 12:30am: '.$result_row['noiselevel3']."\r\n"."\r\n";
		$message .= '(2) What would you say was the most active area/community/hall in the college while on rounds? '.$result_row['whereactive']."\r\n"."\r\n";
		$message .= '(3) What was the most interesting thing that happened while on rounds? '.$result_row['interesting']."\r\n"."\r\n";
		$message .= '(4) Did you see any service or building needs? Please state where and describe need? '."\r\n".$result_row['facility']."\r\n"."\r\n";
		$message .= '(5) Number of calls received on on-call phone:  '.$result_row['oncallphone']."\r\n"."\r\n";
		$message .= '(6) With about how many students did you interact? '.$result_row['interactwith']."\r\n"."\r\n";
		$message .= '(7) Did you encounter any community standard issues? Please describe. '.$result_row['comstandards']."\r\n"."\r\n";
		$message .= '(8) Number of noise complaints or issues encountered: '.$result_row['nomcomplaints']."\r\n"."\r\n";
		if ($result_row['hostevent'] =='') {$message .='(9)I did not event event while on call.'."\r\n"."\r\n"; }
		else {
			$message .='(9) I hosted event while on call and here is a description of the event:'."\r\n";
			$message .=$result_row['desevent']."\r\n"."\r\n";
		}
		$message .= '(10) Number of low-risk alcohol situations addressed while on call: '.$result_row['numlowrisk']."\r\n"."\r\n";
		$message .='(11) Number of high-risk alcohol situations addressed without need to call Public Safety: '.$result_row['highrisknop']."\r\n"."\r\n";
		$message .='(12) Number of high-risk alcohol situations addressed and needed to call Public Safety: '.$result_row['highriskp']."\r\n"."\r\n";
		$message .='The primary reason I called public safety for the previous question was ';
		if ($result_row['reasonfor'] == "reduce") {$message .='to reduce the high-risk violation'."\r\n"."\r\n";}
		else {$message .='for an alcohol transport'."\r\n"."\r\n"; }
		$message .='(13) Number of times called Public Safety for an alcohol transport (not related to an alcohol violation): '.$result_row['numtrans']."\r\n";
		$headers = "From: ".$this->netid."@princeton.edu"."\r\n"."Reply-To: ".$this->netid."@princeton.edu"."\r\n"."X-mailer:PHP/".phpversion();
		mail ($to, $subject, $message, $headers);
	}
	
	function log_form() {
		echo "Hello {$this->netid}. Please complete the on-call form below for the date of ".date('l, F d, Y', $this->logdate).".<br/><br/>";
		?> <div id="formreduce"><table><form action="../public/rcaoncall.php" method="post"> 
		<tr><td valign="top">(1) Overall noise level of the college<br/>
		From 11:00pm to 11:30pm: </td><td valign="top">
   	 	<input type="radio" name="noiselevel1" value="dead" /> dead <br/> 
     	<input type="radio" name="noiselevel1" value="quietly active" /> quietly active <br/> 
     	<input type="radio" name="noiselevel1" value="lively" /> lively <br/> 
     	<input type="radio" name="noiselevel1" value="insane" /> insane <br/>
     	</td></tr> 
        
    	<tr><td valign="top">From 11:30pm to 12:00am:</td><td valign="top">
   	 	<input type="radio" name="noiselevel2" value="dead" /> dead <br/> 
     	<input type="radio" name="noiselevel2" value="quietly active" /> quietly active <br/> 
     	<input type="radio" name="noiselevel2" value="lively" /> lively <br/> 
     	<input type="radio" name="noiselevel2" value="insane" /> insane <br/>
	 	</td></tr>    
        
    <tr><td valign="top">From 12:00am to 12:30am:</td><td valign="top">
		<input type="radio" name="noiselevel3" value="dead" /> dead <br/> 
     	<input type="radio" name="noiselevel3" value="quietly active" /> quietly active <br/> 
     	<input type="radio" name="noiselevel3" value="lively" /> lively <br/> 
     	<input type="radio" name="noiselevel3" value="insane" /> insane <br/>   	 
	 	</td></tr>
        
    <tr><td valign="top">(2) What would you say was the most active <br/>area/community/hall in the college while on rounds?</td><td valign="top"><input type="text" name="whereactive"/></td></tr>
    
    <tr><td valign="top">(3) What was the most interesting thing that<br/>happened while on rounds?</td><td valign="top"><textarea name="interesting" rows="5" cols="40">
	</textarea></td></tr>
    
   	<tr><td valign="top">(4) Did you see any service or building needs?<br/>Please state where and describe need?</td><td valign="top"><textarea name="facility" rows="5" cols="40">
    </textarea></td></tr>
    
    <tr><td valign="top">
    (5) Number of calls received on on-call phone:</td><td valign="top"><input type="text" name="oncallphone" /></td></tr>
    
    <tr><td valign="top">
    (6) With about how many students did you interact?</td><td valign="top"><input type="text" name="interactwith"/></td></tr>
    
    <tr><td valign="top">
    (7) Did you encounter any community standard issues?<br/>Please describe.</td><td valign="top"><textarea name="comstandards" rows="5" cols="40"></textarea></td></tr>
    
    <tr><td valign="top">
    (8) Number of noise complaints or issues encountered:</td><td valign="top"><input type="text" name="nomcomplaints"/></td></tr>

	<tr><td valign="top">
    (9) Did you host an event while on-call?</td><td valign="top"><input type="checkbox" name="hostevent" value="yes"/> Yes </td></tr>
    
    <tr><td valign="top">  
    If so, please briefly describe the event:</td><td valign="top"><textarea name="desevent" rows="5" cols="40"></textarea></td></tr>
    
    <tr><td valign="top">
	(10) Number of low-risk alcohol violations<br/>addressed while on call:</td><td valign="top"><input type="text" name="numlowrisk"/></td></tr>

	<tr><td valign="top">
	(11) Number of high-risk alcohol violation<br/>addressed without need to call Public Safety:</td><td valign="top"><input type="text" name="highrisknop"/></td></tr>

	<tr><td valign="top">
   	(12)  Number of high-risk alcohol violations<br/>addressed and needed to call Public Safety:</td><td valign="top"><input type="text" name="highriskp"/></td></tr>

	<tr><td valign="top">
   	The primary reason I called public safety for<br/>the previous question was:</td><td valign="top">
   	<input type="radio" name="reasonfor" value="reduce" /> to reduce the high-risk violation, or <br/>
	<input type="radio" name="reasonfor" value="transport" /> for an alcohol transport.</td></tr>
    
    <tr><td valign="top">
   	(13) Number of times called Public Safety<br/>for an alcohol transport<br/>(unrelated to an alcohol violation):</td><td valign="top"><input type="text" name="numtrans"/></td></tr>
    <input type="hidden" name="thedate" value="<? echo $this->logdate; ?>"/>
    <input type="hidden" name="timethrough" value="three"/>
    <input type="hidden" name="tdisplaytwo" value="yes"/>
	<tr><td><input type="submit" value="submit"/></td></tr>
     </form></table></div>
	<?
	}
	
}