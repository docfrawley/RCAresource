<? include_once("rcainitialize.php");

class rcaoncall {
	
	private $netid;
	private $call_array;
	private $rounds_array;
	
	function __construct($netid) {
		global $database;
		$this->netid = $netid;
		$this->call_array = array();
		$sql = "SELECT oncalldate from oncalltrack WHERE whooncall='".$netid."' ORDER BY oncalldate";
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			array_push($this->call_array, $value['oncalldate']);
		}
		$this->rounds_array = array();
		$sql = "SELECT oncalldate from oncalltrack WHERE rounds='".$netid."' ORDER BY oncalldate";
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			array_push($this->rounds_array, $value['oncalldate']);
		}
	}
	
	function num_oncall() {
		if (count($this->call_array) >0) {return count($this->call_array);}
		else {return 0; }
	}
	
	function num_rounds() {
		return count($this->rounds_array);
	}
	
	function next_oncall($dsl = false) {
		if ($this->num_oncall() == 0 || end($this->call_array) < mktime(0,0,0,date('n'),date('j'),date('Y'))) { 
			if (!$dsl) { echo "Nada. Zip. Nothing."; }
			else {echo "None";}
		} 
		else {
			$foundit = false;
			$counter = 0;
			while (!$foundit) {
				$foundit = $this->call_array[$counter] >=  mktime(0,0,0,date('n'),date('j'),date('Y'));
				$counter++;
			}
			echo date('l, n/j/Y', $this->call_array[$counter-1]);}
	}
	
	function next_rounds($dsl = false) {
		if ($this->num_rounds() == 0|| end($this->rounds_array) < mktime(0,0,0,date('n'),date('j'),date('Y'))) { 
			if (!$dsl) { echo "Too busy to do rounds, eh?"; }
			else {echo "None";}
		} 
		else {
			$foundit = false;
			$counter = 0;
			while (!$foundit) {
				$foundit = $this->rounds_array[$counter] >=  mktime(0,0,0,date('n'),date('j'),date('Y'));
				$counter++;
			}
			echo date('l, n/j/Y', $this->rounds_array[$counter-1]);}
	}
	
	function list_oncalls($rcauser, $college) {
		?><p class="rca_oncall"><? echo $this->num_oncall()." On-Call Assignments:<br/>"; ?></p> <?
		if ($this->num_oncall() == 0) {echo "no on-call assignments yet<br/>";}
		else {
			foreach ($this->call_array as $oncall) {
				$rcalog = new logobject($rcauser, $oncall, $college);
				if ($rcauser && date('U') >= $oncall) {
					if ($rcalog->completed()) { echo date('l, F d, Y', $oncall)."<br/>"; }
					else {echo '<a href="../public/rcaoncall.php?tdisplaytwo=yes&thedate='.$oncall.'">'.date("l, F d, Y", $oncall).'</a><br/>';}
				}
				else { echo date('l, F d, Y', $oncall)."<br/>"; }
			}
		}
	}
	
	function list_rounds(){
		?><p class="rca_oncall"><? echo $this->num_rounds()." Rounds Assignments:<br/>"; ?></p> <?
		if ($this->num_rounds() == 0) {echo "<br/>no rounds assignments yet";}
		else{
			foreach ($this->rounds_array as $rounds) {
				echo date('l, F d, Y', $rounds)."<br/>";
			}
		}
	}
}
?>