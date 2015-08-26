<? include_once("rcainitialize.php");

class rcainservices {
	
	private $netid;
	private $college;
	private $year;
	private $list;
	
	function __construct($netid) {
		global $database;
		$sqli = "SELECT * FROM rca WHERE netid = '".$netid."'";
		$result_seti = $database->query($sqli);
		$value = $database->fetch_array($result_seti);
			$this->netid = $netid;
			$this->college = $value['college'];
			$this->year = $value['fyear'];
		$this->list = array();
	}

	function prepare_set(){
		global $database;
		$sql="SELECT * FROM track WHERE rca='".$this->netid."'";
		$result_set = $database->query($sql);
		return $result_set;
	}

	function set_array(){
		global $database;
		$result_set = $this->prepare_set();
		$this->list = array();
		$this->list = $database->fetch_array($result_set);	
	}
	
	function required_inservices() {
		if ($this->year =="first") {return 4; }
		else { return 2; }
	}
	
	function signedup_inservices() {
		global $database;
		$result_set = $this->prepare_set();
		$counter = 0;
		while ($value = $database->fetch_array($result_set))
			{ $counter++; }
		return $counter;
	}
	
	function completed_inservices() {
		global $database;
		$result_set = $this->prepare_set();
		$counter = 0;
		while ($value = $database->fetch_array($result_set)) {
			if ($value['attended'] == 'Y') { $counter++; }
		}
		return $counter;
	}
	
	function skipped_inservices() {
		global $database;
		$result_set = $this->prepare_set();
		$counter = 0;
		while ($value = $database->fetch_array($result_set)) {
			$the_inservice = new inserviceevent($value['inserviceid']);
			if ($value['attended'] != 'Y' && $the_inservice->get_when() < date('U')) { $counter++; }
		}
		return $counter;
	}
	
	function delete_inservice($id) {
		global $database;
		$sql = "SELECT * FROM track WHERE rca='".$this->netid."' AND inserviceid='".$database->escape_value($id)."'";
		$result_set = $database->query($sql);
		$rowstuff = $database->fetch_array($result_set);
		$sql = "DELETE FROM track ";
	  	$sql .= "WHERE rca='". $this->netid."' AND ";
		$sql .= "inserviceid='". $database->escape_value($id)."'";
	  	$sql .= " LIMIT 1";
		$database->query($sql);
		if ($database->affected_rows() == 1) { echo "You have successfully canceled your spot in this in-service."; }
	}
	
	function add_inservice($id, $confirmsignup) {
		global $database;
		$the_inserv = new inserviceevent($id);
		if (!$confirmsignup) {
			if (!$the_inserv->rca_present($this->netid)){ // 
				echo "Please confirm that you would like to sign up for the following inservice:<br/><br/>";
				echo $the_inserv->get_title(). ", ". date('l, F j, Y, g:i a', $the_inserv->get_when());
				echo "<br/>Description: {$the_inserv->get_description()}<br/><br/>";
				echo ('<a href="?confirmsup=yes&task=listind&taskrca=signup&inservid='.$id.'">Sign me up</a><br/>');
				echo ('<a href="rcainservices.php">No thanks</a>');
			} else { 
				echo "You are already signed up for that inservice.<br/><br/>"; 
				echo ('<a href="rcainservices.php">Return to In-Service List</a>');
			} 
		} else {
			$sql = "INSERT INTO track (";
	  		$sql .= "rca, inserviceid";
	  		$sql .= ") VALUES ('";
			$sql .= $this->netid ."', '";
			$sql .= $database->escape_value($id) ."')";
			$database->query($sql);
			echo "You are now signed up for {$the_inserv->get_title()} on ".date('l, F j, Y, g:i a', $the_inserv->get_when()).".<br/>";
		} 
	}

	function show_record($dsluser=false){
		global $database;
		$result_set = $this->prepare_set();
		echo "The following in-service record is for {$this->netid}: <br/><br/>";
		if (!$dsluser) { echo "Click on the inservices you have yet to attend to cancel your participation.<br/><br>";} 
			
			?><table width="800" cellpadding="5" >
            <tr><td align="left">INSERVICE</td>
            <td>CORE COMPETENCY</td>
            <td>INSERVICE DATE</td>
            <td>ATTENDED</td>
            <td>SKIPPED</td>
            </tr>
    		<?
			while ($value = $database->fetch_array($result_set)){ // 
				$the_inservice = new inserviceevent($value['inserviceid']);
				?><tr><td><?
					if (!$dsluser && $the_inservice->get_when() >= date("U")){
						echo '<a href="?task=listind&taskrca=edrcainserv&inservid='.$the_inservice->get_id().'">'.$the_inservice->get_title().'</a>';
					} else { echo $the_inservice->get_title(); }
				?></td><td><?
					echo $the_inservice->get_area();
				?></td><td><?
					echo (date("F j, Y", $the_inservice->get_when()));
				?></td><td><?
					if ($value['attended']=="Y") { echo "X"; } 
				?></td><td><?
					if (($value['attended']=="N") AND ($the_inservice->get_when() < date("U"))) { echo "X"; } 
				?></td></tr><?
			}
			?></table><?
	}
}

?>