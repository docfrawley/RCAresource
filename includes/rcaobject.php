<? include_once("rcainitialize.php");

class rcaobject {
	
	public $netid;
	public $college;
	public $year;
	
	function __construct($netid) {
		global $database;
		$sqli = "SELECT * FROM rca WHERE netid = '".$netid."'";
		$result_seti = $database->query($sqli);
		if ($database->num_rows($result_seti)>0){
			$value = $database->fetch_array($result_seti);
			$this->netid = $netid;
			$this->college = $value['college'];
			$this->year = $value['fyear'];
		} else {$this->netid = $netid;}
	}
	
	function get_college() {
		return $this->college;
	}
	
	function get_netid() {
		return $this->netid;
	}
	
	function get_year() {
		return $this->year;
	}
	
	function check_db(){
		global $database;
		$sql="SELECT * FROM rca WHERE netid='".$this->netid."'";
		$result_set = $database->query($sql);
		return ($database->num_rows($result_set)<1);
	}
	
	function delete_rca() {
		global $database;
		$sql = "DELETE FROM rca ";
	  	$sql .= "WHERE netid='".$this->netid."' ";
	  	$sql .= "LIMIT 1";
		$database->query($sql);
		if ($database->affected_rows() == 1) {
			?> <div class="adminmessage"> <?
				echo $this->netid." has been deleted as an RCA from {$this->college} College.";	
			?> </div> <?
		}
	}

	function insert_rca($year, $college) {
		global $database;
		$sql = "INSERT INTO rca (";
	  	$sql .= "netid, fyear, college";
 		$sql .= ") VALUES ('";
		$sql .= $database->escape_value($this->netid) ."', '";
		$sql .= $database->escape_value($year) ."', '";
		$sql .= $database->escape_value($college) ."')";
		$database->query($sql);
		$this->year = $database->escape_value($year);
		$this->college = $database->escape_value($college);
	}

	function update_year() {
		global $database;
		if ($this->year == 'first') {$this->year='second';}
		else {$this->year = 'first';}
		$sql = "UPDATE rca SET ";
		$sql .= "fyear='". $database->escape_value($this->year) ."' ";
		$sql .= "WHERE netid='". $this->netid."'";
	  	$database->query($sql);
		?> <div class="adminmessage"> <?
				echo $this->netid." has been updated to a {$this->year} year RCA.";	
		?> </div> <?
	}		
	
	function change_rca_form() {
		?> <table><form action="../public/rcaadmin.php" method="post">
		<tr><td>
		<?
			echo "Would like to change {$this->netid} from a {$this->year} year RCA to a ";
			if ($this->year =="first") {echo "second ";}
			else {echo "first ";}
			echo "year RCA?"
			?>
				</td><td><input type="checkbox" name="fyear" value="change"/> Yes</td></tr>
                <tr><td>		
			<? echo "Or would you like to delete this RCA?"; ?>
				</td><td><input type="checkbox" name="dRCA" value="change"/> Yes</td></tr>
                <input type="hidden" name="rcaid" value="<? echo $this->netid; ?>"/>  
				<input type="hidden" name="task" value="modify"/>        
				<tr><td><input type="submit" value="submit" /></td></tr>
				</form> 	</table>
		<?
	}
	
	
	

}
?>