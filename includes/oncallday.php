<? include_once("rcainitialize.php");

class oncallday {
	
	private $who_oncall;
	private $who_rounds;
	private $the_day;
	private $college;
	
	function __construct($whichday, $college) {
		global $database;
		$sql = "SELECT * FROM oncalltrack WHERE college = '".$college."' AND oncalldate = '".$whichday."'";
		$result_set = $database->query($sql);
		if ($database->num_rows($result_set) >0) {
			$result_row = $database->fetch_array($result_set);
			if ($result_row['whooncall'] !="") {$this->who_oncall = $result_row['whooncall'];}
			else {$this->who_oncall = "Select RCA";}
			
			if ($result_row['rounds'] !="") {$this->who_rounds = $result_row['rounds'];}
			else {$this->who_rounds = "Select RCA";}
		} else {
			$this->who_oncall = "Select RCA";
			$this->who_rounds = "Select RCA";
		}  
		$this->the_day = $whichday;
		$this->college = $college;
	}
	
	function print_form($dsluser='', $m) {
		echo "On-Call Rotation for ".date ('l', $this->the_day).", ".date('F d, Y', $this->the_day).":<br/><br/>";
		if ($dsluser) { //
		$carray = new coregroup($this->college);
			?> <form action="../public/rcaoncall.php" method="post"> <?
			echo "RCA On-Call:";
			?>
			<select name="oncallperson">
				<option selected="selected" value="<? echo $this->who_oncall; ?>"><? echo $this->who_oncall; ?></option>
				<?
				for ($x=0; $x<$carray->number_rcas(); $x++){
					if ($carray->get_rca($x) != $this->who_oncall) {?><option value="<? echo $carray->get_rca($x); ?>"><? echo $carray->get_rca($x); ?></option> <? }
				}
				?><option value="Delete Current">Delete Current</option>
			</select> <?
			echo "<br/><br/>RCA Rounds:";
			?> 
			<select name="roundsperson">
				<option selected="selected" value="<? echo $this->who_rounds; ?>"><? echo $this->who_rounds; ?></option>
				<?
				for ($x=0; $x<$carray->number_rcas(); $x++){
					if ($carray->get_rca($x) != $this->who_rounds) {?><option value="<? echo $carray->get_rca($x); ?>"><? echo $carray->get_rca($x); ?></option> <? }
				} 
				?><option value="Delete Current">Delete Current</option>
			</select> 
				<input type="hidden" name="thedate" value="<? echo $this->the_day; ?>"/>
				<input type="hidden" name="mcheck" value="<? echo $m; ?>"/>
				<br/><input type="submit" value="submit"/>
			<?
		} else {  // this is what the RCAs see
			echo "RCA On-Call: ";
			if ($this->who_oncall =="Select RCA") {echo "Open<br/><br/>";}
			else {echo $this->who_oncall."<br/><br/>";}
			echo "RCA Rounds: ";
			if ($this->who_rounds =="Select RCA") {echo "Open<br/><br/>";}
			else {echo $this->who_rounds."<br/><br/>";}		
		} // if ($dsluser) 
	}
	
	function oncall_update($rca){
		global $database;
		$sql = "SELECT * FROM oncalltrack WHERE college = '".$this->college."' AND oncalldate = '".$this->the_day."'";
		$result_set = $database->query($sql);	
		if ($database->num_rows($result_set) >0) {
				$result_row = $database->fetch_array($result_set);
				if ($rca != $result_row['whooncall']) {
					if ($rca=="Delete Current") {$rca = "";}
					$sql = "UPDATE oncalltrack SET ";
					$sql .= "whooncall='". $database->escape_value($rca) ."' ";
					$sql .= "WHERE oncalldate='". $this->the_day ."'";
					$sql .= " AND college='". $this->college ."'";
					$database->query($sql);
				}
		}  else {
				$sql = "INSERT INTO oncalltrack (";
	  			$sql .= "oncalldate, college, whooncall";
	  			$sql .= ") VALUES ('";
				$sql .= $this->the_day ."', '";
				$sql .= $database->escape_value($this->college) ."', '";
				$sql .= $database->escape_value($rca) ."')";
				$database->query($sql);
			}
	}
	
	function rounds_update($rca){
		global $database;
		$sql = "SELECT * FROM oncalltrack WHERE college = '".$this->college."' AND oncalldate = '".$this->the_day."'";
		$result_set = $database->query($sql);
				if ($database->num_rows($result_set) >0) {
					$result_row = $database->fetch_array($result_set);	
					if ($rca != $result_row['rounds']) {
						if ($rca=="Delete Current") {$rca = "";}
						$sql = "UPDATE oncalltrack SET ";
						$sql .= "rounds='". $database->escape_value($rca) ."' ";
						$sql .= "WHERE oncalldate='". $this->the_day ."'";
						$sql .= " AND college='". $this->college ."'";
						$database->query($sql);
					}
				}  else {
					$sql = "INSERT INTO oncalltrack (";
	  				$sql .= "oncalldate, college, rounds";
	  				$sql .= ") VALUES ('";
					$sql .= $this->the_day ."', '";
					$sql .= $database->escape_value($this->college) ."', '";
					$sql .= $database->escape_value($rca) ."')";
					$database->query($sql);
				}
	}
	
}