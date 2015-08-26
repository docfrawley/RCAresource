<? include_once("rcainitialize.php");

class inserviceevent {
	
	private $id;
	private $value;
	
	function __construct($id=0) {
		global $database;
		$sqli = "SELECT * FROM inservices WHERE id = '".$id."'";
		$result_seti = $database->query($sqli);
		if ($database->num_rows($result_seti)>0) {
			$this->id = $id;
		} else {
			$this->id = 0;
		}
		$this->value = array();
	}
	
	function set_array(){
		global $database;
		$this->value = array();
		$sqli = "SELECT * FROM inservices WHERE id = '".$this->id."'";
		$result_seti = $database->query($sqli);
		$this->value = $database->fetch_array($result_seti);
	}

	function get_title(){
		$this->set_array();
		return $this->value['title'];
	}
	
	function get_owner(){
		$this->set_array();
		return $this->value['owner'];
	}
	
	function get_location(){
		$this->set_array();
		return $this->value['location'];
	}
	
	function get_when(){
		$this->set_array();
		return $this->value['time'];
	}
	
	function get_description(){
		$this->set_array();
		return $this->value['description'];
	}
	
	function get_area(){
		$this->set_array();
		return $this->value['area'];
	}

	function get_id() {
		return $this->id;
	}

	function edit_form(){
		$this->set_array();
		echo "Please make the edits needed and then press SUBMIT.<br/>Be forewarned that edits are final when submitted.<br/><br/>";
		$month=date("F", $this->value['time']);
		$monthnum=date("m", $this->value['time']);
		$day=date("j", $this->value['time']);
		$year=date("Y", $this->value['time']);
		$hour= date('g', $this->value['time']);
		$minute= date('i', $this->value['time']);
		$ampm= date('a', $this->value['time']);
		?><table>
		<form action = "rcainservices.php" method="post">
		<tr><td>Title: </td><td><input type="text" name="title" value="<? echo $this->value['title']; ?>"/></td></tr>
        <tr><td>Core Competency: </td><td>
        <select name="area">
			<option selected="selected" value="<? echo $this->value['area']; ?>"><? echo $this->value['area']; ?></option>
			<option value="Diversity and Inclusion">Diversity and Inclusion</option>
			<option value="Personal Development">Personal Development</option>
        	<option value="Health and Wellness">Health and Wellness</option>
		</select></td></tr>
		<tr><td>Location: </td><td><input type="text" name="location" value="<? echo $this->value['location']; ?>"/></td></tr>
		<tr><td>Date: 
		</td><td><select name="month">
		<option selected="selected" value="<? echo $monthnum; ?>"><? echo $month; ?></option>
			<? GetMonths(); ?>
		</select>
		<select name="day1">
			<option selected="selected" "<? echo $day; ?>"><? echo $day; ?></option>
			<? GetDays(); ?>
		</select>  
		<select name="year">
			<option selected="selected" value="<? echo $year; ?>"><? echo $year; ?></option>
			<? GetYears(); ?>   
		</select></td></tr>
		<tr><td>Time:</td><td>
		<select name="hour">
			<option selected="selected" value="<? echo $hour; ?>"><? echo $hour; ?></option>
			<option value="01">1</option>
			<option value="02">2</option>
			<option value="03">3</option>
			<option value="04">4</option>
			<option value="05">5</option>
			<option value="06">6</option>
			<option value="07">7</option>
			<option value="08">8</option>
			<option value="09">9</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="00">12</option>
		</select>
		<select name="minute">
			<option selected="selected" value="<? echo $minute; ?>"><? echo $minute; ?></option>
			<option value="00">00</option>
			<option value="15">15</option>
			<option value="30">30</option>
			<option value="45">45</option>
		</select>
		<select name="morning">
			<option selected="selected" value="<? echo $ampm; ?>"><? echo $ampm; ?></option>
			<option value="am">am</option>
			<option value="pm">pm</option>
		</select></td></tr>
		<tr><td>Description</td>
		</td><td><textarea name="description" rows="7" cols="40"><? echo $this->value['description']; ?>
		</textarea></td></tr>
		<input type="hidden" name="inservid" value="<? echo $this->id; ?>"/>
		<input type="hidden" name="task" value="check"/>
		<input type="hidden" name="taskins" value="editinservice"/>
		<input type="hidden" name="inservstep" value="goahead"/>
		<tr><td><input type="submit" value="submit" /></td></tr>
							</form></table><?
	}

	function edit_inservice($info){
		global $database;
		if ($info['morning'] == 'pm') { $hourstuff = $info['hour']+12; }
		else{ $hourstuff = $info['hour']; } 
		$ttime = mktime($hourstuff, $info['minute'], 0, $info['month'], $info['day1'], $info['year']);
		$sql = "UPDATE inservices SET ";
		$sql .= "title='". $database->escape_value($info['title']) ."', ";
		$sql .= "location='". $database->escape_value($info['location']) ."', ";
		$sql .= "description='". $database->escape_value($info['description']) ."', ";
		$sql .= "time='". $database->escape_value($ttime) ."', ";
		$sql .= "area='". $database->escape_value($info['area']) ."' ";
		$sql .= "WHERE id='". $this->id ."'";
		$database->query($sql);
		if ($database->affected_rows() == 1) {echo 'The in-service has been updated. <br/>'; }
	}

	function delete_inservice($doublecheck){
		global $database;
		if (!$doublecheck){
			if ($this->get_when() > date("U")) { // 
				echo "Just double checking; are you sure you want to delete this inservice?<br/>";
				echo $this->get_title(). " on ".date("l, F j, Y", $this->get_when())."<br/><br/>";
				echo ('<a href="?task=check&doublech=yes&taskins=deleteinservice&inservid='.$this->id.'">Yes, Delete</a><br/><br/>');
				echo ('<a href="?">No, Return to In-Service List</a>');
			} else { echo "You cannot delete an in-service with a date that has already past.<br/><br/>"; }						
		} else { // if (!$doublecheck)
			$sql = "DELETE FROM inservices ";
	  		$sql .= "WHERE id='". $this->id."'";
	  		$sql .= " LIMIT 1";
			$database->query($sql);
  			if ($database->affected_rows() == 1) { 
				$sql="SELECT * FROM track WHERE inserviceid='".$this->id."'";
				$result_set = $database->query($sql);
				while ($result_row = $database->fetch_array($result_set)){ // 
					$sql = "DELETE FROM track ";
	  				$sql .= "WHERE rca='". $result_row['rca']."' AND ";
					$sql .= "inserviceid='". $this->id."'";
	  				$sql .= " LIMIT 1";
					$database->query($sql);
				} 
				echo "You have successfully deleted the inservice.";
			} 	
											
		}	// // if (!$doublecheck)
	}


	function get_rcas(){
		global $database;
		$sql = "SELECT * FROM track WHERE inserviceid='".$this->id."' ORDER BY rca";
		$result_set = $database->query($sql);
		return $result_set;
	}

	function rca_present($rca){
		global $database;
		$result_set= $this->get_rcas();
		while ($value = $database->fetch_array($result_set)) {
			if ($value['rca'] == $rca) { return true; }
		}
		return false;
	}
	
	function list_rcas(){
		global $database;
		$result_set= $this->get_rcas();
		if ($database->num_rows($result_set) >0) {
			echo "Listed below are the RCAs currently signed-up for {$this->get_title()}.<br/>";
			echo "Please check only those RCAs in attendance.<br/>";
			echo "If you need to include RCAs not listed below, click <a href='?t2display=yes&inservid={$this->id}'>HERE</a><br/><br/>";
			?>
			<form action = "rcainservices.php" method="post"><? 
			while ($result_row = $database->fetch_array($result_set)) {
				if ($result_row['attended']=="Y") { $checked='checked="checked"'; }
				else { $checked="";} ?> 
				<input type="checkbox" name="<? echo $result_row['rca']; ?>" value="YES" <? echo $checked; ?>/><? echo $result_row['rca']."<br/>";
			} ?>
			<input type="hidden" name="inservid" value="<? echo $this->id; ?>"/>
			<input type="hidden" name="task" value="check"/>
			<input type="hidden" name="taskins" value="runinservice"/>
			<input type="hidden" name="inservstep" value="goahead"/>
			<br/><input type="submit" value="submit" />
			</form><br/><br/> <?
		} else { 
			echo "Currently there are no RCAs signed up for this In-Service.<br/><br/>"; 
			echo "If you need to include RCAs, click <a href='?t2display=yes&inservid={$this->id}'>HERE</a><br/><br/>";
		}	
	}

	function add_rcas($addrcas){
		global $database;
		foreach($addrcas as $value) {
			$sql = "INSERT INTO track (";
	  		$sql .= "rca, inserviceid, attended";
	  		$sql .= ") VALUES ('";
			$sql .= $database->escape_value($value) ."', '";
			$sql .= $this->id ."', '";
			$sql .= "Y')";
			$database->query($sql);
		} 
		echo "The RCAs you checked have been added to the list and marked as attended. You can now either add<br/>
		more RCAs to the list with the form below or click 'CLOSE' above to return to the 
		list of RCAs signed<br/>up for this inservice.";
	}
	
	function rcas_attended($info){
		global $database;
		$sql = "SELECT * FROM track WHERE inserviceid='".$this->id."' ORDER BY rca";
		$result_set = $database->query($sql);
		$numrows = $database->num_rows($result_set);
		$affectedRCAs=0;
		while ($result_row = $database->fetch_array($result_set)){ // 
			$whichrca=$result_row['rca'];
			$didattend=isset($info["$whichrca"]) ? $info["$whichrca"] : "" ;
			if ($didattend=="YES") { $didthey="Y"; }
			else{$didthey="N";} 
			$sql = "UPDATE track SET ";
			$sql .= "attended='". $didthey ."' ";
			$sql .= "WHERE rca='". $database->escape_value($whichrca)."' AND ";
			$sql .= "inserviceid ='". $this->id ."'";
	  		$database->query($sql);
			if ($database->affected_rows() == 1) {$affectedRCAs +=1;}	
		} 
		if ($affectedRCAs >0){echo $affectedRCAs." RCAs recorded as attending this inservice.<br/><br/>";}	
	}

	function add_rcas_form(){
		$ButlerCore = new coregroup("Butler");
		$ForbesCore = new coregroup("Forbes");
		$MatheyCore = new coregroup("Mathey");
		$RockyCore = new coregroup("Rocky");
		$WhitmanCore = new coregroup("Whitman");
		$WilsonCore = new coregroup("Wilson");
		?>  <form action = "<? echo $self; ?>" method="post">
        	<table cellpadding="15px">
            <tr>
            <td valign="top"><? $ButlerCore->inservice_form($this->id);	?></td>
            <td valign="top"><? $ForbesCore->inservice_form($this->id); ?></td>
            <td valign="top"><? $MatheyCore->inservice_form($this->id); ?></td>
            <td valign="top"><? $RockyCore->inservice_form($this->id); 	?></td>
            <td valign="top"><? $WhitmanCore->inservice_form($this->id);?></td>
            <td valign="top"><? $WilsonCore->inservice_form($this->id); ?></td>
            </tr>
            <tr><td>
            <input type="hidden" name="inservid" value="<? echo ($this->id); ?>"/>
			<input type="hidden" name="t2display" value="yes"/>
			<input type="submit" value="submit" />
            </td></tr>
            </table></form> <?
	}
}
?>