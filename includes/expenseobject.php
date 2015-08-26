<? include_once("rcainitialize.php");

class expenseobject {
	
	private $datereceipt;
	private $expenditure;
	private $vendor;
	private $whenentered;
	private $rca;
	private $budgetid;
	private $numindex;
	private $order_num;
	
	function __construct($therca, $budgetid, $numindex, $order_num) {
		$this->datereceipt = 0;
		$this->vendor = "";
		$this->whenentered = 0;
		$this->expenditure = 0;
		$this->rca = $therca;
		$this->budgetid = $budgetid;
		$this->numindex = $numindex;
		$this->order_num = $order_num;
	}
	
	function generate_list(){
		global $database;
		$sql="SELECT * FROM rcaexpenses WHERE rcaname='".$this->rca."' AND numindex = '".$this->numindex."' AND budgetid='".$this->budgetid ."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->datereceipt = $value['datereceipt'];
		$this->expenditure = $value['expenditure'];
		$this->vendor = $value['vendor'];
		$this->whenentered = $value['whenentered'];
	}


	function print_expense($dlsuser=false, $counter) {
		$this->generate_list();
		?><tr><td><?
		if ($dsluser) {echo $counter;}
		else {echo '<a href="../public/rcaexpenses.php?thedisplay=block&budgetid='.$this->budgetid.'&numindex='.$this->numindex.'">'.$counter.'</a>';} 
		?></td><td><?
			echo date("n/j/Y", $this->datereceipt);
		?></td><td><?
			echo date("n/j/Y", $this->whenentered);
		?></td><td><?
			echo $this->vendor;
		?></td><td><?
			echo $this->expenditure;
		?></td></tr><?
	}
	
	function edit_expense($info) {
		global $database;
		$datereceipt= mktime(10,0,0, $info['month'], $info['day'], $info['year']);
		$sql = "UPDATE rcaexpenses SET ";
		$sql .= "datereceipt='". $database->escape_value($datereceipt) ."', ";
		$sql .= "expenditure='". $database->escape_value($info['expense']) ."', ";
		$sql .= "vendor='". $database->escape_value($info['vendor']) ."', ";
		$sql .= "whenentered='". date('U') ."'";
		$sql .= " WHERE rcaname='". $this->rca ."'";
		$sql .= " AND budgetid='". $this->budgetid ."'";
		$sql .= " AND numindex='". $this->numindex ."'";
	  	$database->query($sql);
	  	echo "The form below shows the updates you made<br/>to your entry. 
			If you are happy with the edits you made, simply close this box to return to your expenses page.<br/><br/>";
	}
	
	function delete_expense() {
		$this->generate_list();
		global $database;
		$sql = "DELETE FROM rcaexpenses ";
	  	$sql .= "WHERE rcaname='".$this->rca ."'";
		$sql .= " AND budgetid='". $this->budgetid ."'";
		$sql .= " AND numindex ='".$this->numindex ."'";
	  	$sql .= " LIMIT 1";
	 	$database->query($sql);
	 	echo "That entry for {$this->expenditure} at {$this->vendor} has been deleted.";
	}

	function edit_form(){
		$this->generate_list();
		echo "If you want to edit this entry, please make the corrections in the form below and then hit submit.<br/>If you want to delete this entry, simply hit 'DELETE' below.<br/><br/>";
		?> <table><form action = "<? echo $self; ?>" method="post">
		<tr><td>Date of Purchase: </td><td>
					<select name="month">
						<option selected="selected" value="<? echo date('n', $this->datereceipt); ?>"><? echo date('F', $this->datereceipt);; ?></option>
						<? GetMonths(); ?>
						</select>
					<select name="day">
						<option selected="selected" value="<? echo date('d', $this->datereceipt); ?>"><? echo date('d', $this->datereceipt); ?></option>
						<? GetDays(); ?>
						</select>
					<select name="year">
						<option selected="selected" value="<? echo date('Y', $this->datereceipt); ?>"><? echo date('Y', $this->datereceipt); ?></option>
						<? GetYears(); ?> 
					</select></td></tr>
					<tr><td>Vendor</td><td>
					<input type="text" name="vendor" value="<? echo $this->vendor; ?>" />
					</td></tr>
					<tr><td>Amount:</td><td>
					<input type="text" name="expense" value="<? echo $this->expenditure; ?>"/></td></tr>
        <input type="hidden" name="numindex" value="<? echo $this->numindex; ?>"/>
        <input type="hidden" name="thedisplay" value="block"/>
        <input type="hidden" name="whattodo" value="edit_expense"/> 
					<tr><td><input type="submit" value="submit" /></td></tr>
					</form></table><?
		echo '<br/><a href="?whattodo=delete_expense&thedisplay=block&numindex='.$this->numindex.'">DELETE THIS ENTRY</a>';
	}
	
	function get_num() {
		return $this->order_num;
	}
	
	function get_numindex() {
		return $this->numindex;
	}
	
	function reduce_num(){
		$this->order_num -=1;
	}
	
	function get_amount(){
		$this->generate_list();
		return $this->expenditure;
	}
	
	function get_whenentered() {
		$this->generate_list();
		return $this->whenentered;
	}
	
}
?>