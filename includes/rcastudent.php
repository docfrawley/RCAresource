<?php include_once("initialize.php");

class rca_student {
	
	public $name;
	public $which_year;
	public $college;
	
  function __construct($rcaname) {
	  global $database;
	$this->name = $rcaname;
	$sql="SELECT * FROM rca WHERE netid='".$rcaname."'";
	$result_set = $database->query($sql);
	$rowstuffo = $database->fetch_array($result_set);
	$this->college = $rowstuffo['college'];
	$this->which_year = $rowstuffo['fyear'];
  }

	function change_form(){
		?> <form action="../public/rcaadmin.php" method="post">
			<table><tr><td>
		<?
			echo "Would like to change {$this->name} from a {$this->which_year} year RCA to a ";
			if ($this->which_year =="first") { //
				echo "second ";
			}else{
				echo "first ";
			} //  if ($RCAyear=="first")
			echo "year RCA?"
			?>
				<input type="checkbox" name="RAyear" value="change"/> Yes </td></tr>
                <tr><td>	
			<?
			echo "OR would you like to delete this RCA?";
			?>
				<input type="checkbox" name="dRCA" value="change"/> Yes </td></tr>
                <input type="hidden" name="rcaid" value=<? echo $rcaid; ?>>  
                <input type="hidden" name="whichyear" value=<? echo $RCAyear; ?>>                          
				<input type="hidden" name="task" value="modify"/>        
				<tr><td><input type="submit" value="submit" /></td></tr></table>
				</form> 	<?
	}
	
	function add_form(){
		echo ("If you would like to enter a new RCA, please complete the form below.<br/><br/>");
		?> <form action="../public/rcaadmin.php" method="post">
        <table>
		<tr><td valign="top">UserID: </td><td><input type="text" name="rcaid" /></td></tr>
		<tr><td></td><td><input type="radio" name="year" value="first"/> First Year RCA <br/>
		<input type="radio" name="year" value="second"/> Second Year RCA </td></tr>
		<input type="hidden" name="SelCollege" value=<? echo $college; ?>> 
		<input type="hidden" name="task" value="enter"/>        
		<tr><td><input type="submit" value="submit" /></td></tr></table>
		</form> 		<?
	}
	
	function delete_rca() {
		global $database;
		$sql = "DELETE FROM rca ";
	  	$sql .= "WHERE number='".$this->name."' ";
	  	$sql .= "LIMIT 1";
	 	$database->query($sql);
	}
	
	function insert_rca() {
		global $database;
		$sql = "INSERT INTO rca (";
	  	$sql .= "netid, fyear, college";
 		$sql .= ") VALUES ('";
		$sql .= $database->escape_value($this->name) ."', '";
		$sql .= $database->escape_value($this->which_year) ."', '";
		$sql .= $database->escape_value($this->college) ."')";
		$database->query($sql);
	}
	
	function update_rca($year) {
		global $database;
		$sql = "UPDATE rca SET ";
		$sql .= "fyear='". $database->escape_value($year) ."' ";
		$sql .= "WHERE number='". $this->name."'";
	  	$database->query($sql);
	}
	
	function print_oncalls(){
	}
}


?>