<? include_once("rcainitialize.php");

class allinservices {
	
	private $inservices;
	
	function __construct() {
		$this->inservices = array();
	}
	
	function set_array(){
		global $database;
		$this->inservices = array();
		$sqli = "SELECT * FROM inservices ORDER BY time";
		$result_seti = $database->query($sqli);
		$this->value = $database->fetch_array($result_seti);	
		while ($result_row = $database->fetch_array($result_seti)){ 
			$inservice = new inserviceevent($result_row['id']);
			array_push($this->inservices, $inservice);
		}
	}
	
	function list_inservices($dsluser = false){
		$this->set_array();
		?><table width="850px">
    	<tr><td align="left">TITLE</td>
    	<td>CORE COMPETENCY</td>
    	<td>DATE & TIME</td>
   		<td>LOCATION</td>
    	<td>DSL IN CHARGE</td>
    	</tr><?
		$rowcolor=false;
		foreach ($this->inservices as $inservice) {
			if ($dsluser || $inservice->get_when() >= date("U")) {
				if ($rowcolor) { $rowcolor=false; ?><tr id="evenrow"> <? }
				else {$rowcolor=true; ?><tr id="oddrow"><? }
				?><td><?
				if ($dsluser) { echo '<a href="?task=check&inservid='.$inservice->get_id().'">'.$inservice->get_title().'</a>'; }
				else { echo '<a href="?task=listind&taskrca=signup&inservid='.$inservice->get_id().'">'.$inservice->get_title().'</a>'; }
				?></td><td><?
				echo $inservice->get_area();
				?></td><td><?
				echo (date("l,", $inservice->get_when()));
				echo "<br/>";
				echo (date("F j", $inservice->get_when()));
				echo "<br/>";
				echo (date("g:i a", $inservice->get_when()));
				?></td><td><?
				echo $inservice->get_location();
				?></td><td><?				
				getdslincharge($inservice->get_owner());
				?></td></tr><?
			} // if ($dsluer || $inservice->get_when() >= date("U"))
		} // foreach ($this->inservices as $inservice)
		?> </table><?
	}
	
	function add_form(){
		echo "If you would like to enter a new In-service event, please complete the form below.<br><br/>";
		?><table>
		<form action = "rcainservices.php" method="post">
		<tr><td>Title: </td><td><input type="text" name="title" cols="50"/></td></tr>
		<tr><td>Core Competency: </td><td><select name="area">
		<option selected="selected" value="">Core Competency</option>
		<option value="Diversity and Inclusion">Diversity and Inclusion</option>
		<option value="Personal Development">Personal Development</option>
		<option value="Health and Wellness">Health and Wellness</option>
		</select></td></tr>
		<tr><td>Location: </td><td><input type="text" name="location"/></td></tr>
		<tr><td>Date: </td><td>
		<select name="month">
		<option selected="selected" value="">Month</option>
    		<? GetMonths(); ?>
    	</select>
		<select name="day1">
		<option selected="selected" value="">Day</option>
    		<? GetDays(); ?>
    	</select>  
		<select name="year">
		<option selected="selected" value="">Year</option>
    		<? GetYears(); ?> 
        </select></td></tr>
		<tr><td>Time:</td><td>
		<select name="hour">
		<option selected="selected" value=""> </option>
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
        <option selected="selected" value=""> </option>
        <option value="00">00</option>
        <option value="15">15</option>
        <option value="30">30</option>
        <option value="45">45</option>
        </select>
    	<select name="morning">
        <option selected="selected" value=""> </option>
        <option value="am">am</option>
        <option value="pm">pm</option>
    	</select></td></tr>
    	<tr><td>Description</td><td>
    	<textarea name="description" rows="3" cols="50">
    	</textarea></td></tr>
    	<input type="hidden" name="addinservice" value="yes"/>
    	<tr><td><input type="submit" value="submit" /></td></tr>
    	</form> </table> <?
	}
	
	function add_inservice($info, $dsluser){
		global $database;
		$sentered = false;
		if ($info['morning'] == 'pm') { $hourstuff = $info['hour']+12; }
		else{ $hourstuff = $info['hour']; }
		$ttime = mktime($hourstuff, $info['minute'], 0, $info['month'], $info['day1'], $info['year']);		
		$sql = "INSERT INTO inservices (";
	  	$sql .= "title, location, description, time, owner, area, sentered";
	  	$sql .= ") VALUES ('";
		$sql .= $database->escape_value($info['title']) ."', '";
		$sql .= $database->escape_value($info['location']) ."', '";
		$sql .= $database->escape_value($info['description']) ."', '";
		$sql .= $database->escape_value($ttime) ."', '";
		$sql .= $database->escape_value($dsluser) ."', '";
		$sql .= $database->escape_value($info['area']) ."', '";
		$sql .= $sentered ."')";
		if($database->query($sql)) { // 
			?><div id="upperbox"> <?
 			echo 'Your new In-Service has been entered and listed below. <br/>';
			?></div><?
		} 
	}
}