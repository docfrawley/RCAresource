<? include_once("rcainitialize.php");

class rca_ereport {
	
	private $rca;
	private $budgetid;
	private $rcabudget;
	private $exp_array;
	
	function __construct($therca, $budgetid) {
		global $database;
		$this->exp_array = array();
		$this->rca = $therca;
		$this->budgetid = $budgetid;
		$sql="SELECT * FROM collegebudgets WHERE rcaid='".$therca."' AND budgetid= '".$budgetid."'";
		$result_set = $database->query($sql);
		if ($database->num_rows($result_set) > 0) {
			$rowstuff = $database->fetch_array($result_set);
			$this->rcabudget=$rowstuff['budget'];
		}
		else {
			$sql="SELECT * FROM collegebudgets WHERE rcaid='defaultbud' AND budgetid= '".$budgetid."'";
			$result_set = $database->query($sql);
			if ($database->num_rows($result_set) > 0) {
				$rowstuff = $database->fetch_array($result_set);
				$this->rcabudget=$rowstuff['budget'];
			} else { $this->rcabudget = 0; }
        }
	}

	function generate_list(){
		global $database;
		$this->exp_array = array();
		$counter = 0;
		$sql="SELECT * FROM rcaexpenses WHERE rcaname='".$this->rca."' AND budgetid='".$this->budgetid."' ORDER BY datereceipt ASC";
		$result_set = $database->query($sql);
		while ($resultrow = $database->fetch_array($result_set)) {
			$counter++;
			$the_exp = new expenseobject($this->rca, $this->budgetid, $resultrow['numindex'], $counter);
			array_push($this->exp_array, $the_exp);
		}
	}

	function get_rca(){
		return $this->rca;
	}
	
	function num_expenses(){
		$this->generate_list();
		return count($this->exp_array);
	}
	
	function get_budget(){
		return $this->rcabudget;
	}
	
	function print_expenses($dsluser=false){
		$this->generate_list();
		if (!$dsluser) { echo "<p class='etable_heading'>Click on the entry number to edit or delete</p>";}
		$counter = 1;
		?><table width="800px">
		<tr><td class='etable_heading'>NUM ENTRY</td> <td class='etable_heading'>DATE RECEIPT</td> <td class='etable_heading'>DATE ENTERED</td> <td class='etable_heading'>VENDOR</td> <td class='etable_heading'>EXPENSE</td><?
		foreach ($this->exp_array as $value) {
			$value->print_expense($dlsuser, $counter);
			$counter++;
		}
		?></table><?
	}
	
	function total_spent(){
		$this->generate_list();
		$total = 0;
		foreach ($this->exp_array as $item) {
			$total += $item->get_amount();
		}
		return $total;
	}
	
	function money_left() {
		$amt = $this->rcabudget - $this->total_spent();
		return $amt;
	}
	
	function last_entry(){
		$this->generate_list();
		$the_last = 0;
		foreach ($this->exp_array as $item) {
			if ($item->get_whenentered() >= $the_last) {$the_last = $item->get_whenentered();}
		}
		return $the_last;	
	}
	
	function expense_form(){
		?> <table><form action = "rcaexpenses.php" method="post">
		<tr><td>Date of Purchase: </td><td>
					<select name="month">
						<? GetMonths(); ?>
						</select>
					<select name="day">
						<? GetDays(); ?>
						</select>
					<select name="year">
						<? GetYears(); ?> 
					</select></td></tr>
					<tr><td>Vendor</td><td>
					<input type="text" name="vendor" />
					</td></tr>
					<tr><td>Amount:</td><td>
					<input type="text" name="expense"/></td></tr>
        <input type="hidden" name="task" value="enter_expense"/> 
		<tr><td><input type="submit" value="submit" /></td></tr>
		</form></table><?
	}
	
	function enter_expense($info){
			global $database;
			$datereceipt= mktime(10,0,0, $info['month'], $info['day'], $info['year']);
			$whenentered=date("U");
			$sql = "INSERT INTO rcaexpenses (";
	  		$sql .= "rcaname, budgetid, datereceipt, expenditure, vendor, whenentered";
	  		$sql .= ") VALUES ('";
			$sql .= $this->rca ."', '";
			$sql .= $this->budgetid ."', '";
			$sql .= $database->escape_value($datereceipt) ."', '";
			$sql .= $database->escape_value((float)$info['expense']) ."', '";
			$sql .= $database->escape_value($info['vendor']) ."', '";
			$sql .= $whenentered ."')";
			$database->query($sql);
		
	}
	
}
?>