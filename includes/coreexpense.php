<? include_once("rcainitialize.php");

class core_expenses {
	
	private $expense_array;
	private $college;
	private $budgetid;
	
	function __construct($college, $budgetid) {
		$this->college = $college;
		$this->budgetid = $budgetid;
		$this->expense_array= array();
	}

	function generate_list(){
		$this->expense_array= array();
		$coregroup = new coregroup($this->college);
		for ($index = 0; $index < $coregroup->number_rcas(); $index++) { 
			$rca = new rca_ereport($coregroup->get_rca($index), $this->budgetid);
			array_push($this->expense_array,$rca);
		}
	}

	function list_rcas(){
		$this->generate_list();?>
		<table width="800px"><tr"><td class="tablehead">RCA</td><td class="tablehead">BUDGET</td><td class="tablehead">NUM EXPENSES</td><td class="tablehead">TOTAL SPENT</td><td class="tablehead">REMAINDER</td><td class="tablehead">LAST ENTRY</td></tr><?
			foreach ($this->expense_array as $value) {
				?><tr><td><? echo '<a href="?tdisplay=yes&rcaid='.$value->get_rca().'">'.$value->get_rca().'</a>'; ?>
				  </td><td class="expensecontent"><? echo $value->get_budget(); 
				?></td><td class="expensecontent"><? echo $value->num_expenses(); 
				?></td><td class="expensecontent"><? echo "$".$value->total_spent(); 
				?></td><td class="expensecontent"><? echo $value->money_left(); 
				?></td><td class="expensecontent"><? 
				if ($value->last_entry() > 0) {echo date("n/j/Y", $value->last_entry());}
				else {echo "no entries.";}
				 ?></td></tr><?
			}
		?></table><?
	}

	function budget_made(){
		global $database;
		$sql = "SELECT * FROM collegebudgets WHERE budgetid='".$this->budgetid."'";
		$result_set = $database->query($sql);
		return $database->num_rows($result_set) > 0;
	}

	function create_budget($info){
		global $database;
		$sql = "INSERT INTO collegebudgets (";
	  	$sql .= "college, rcaid, budget, budgetid";
	  	$sql .= ") VALUES ('";
		$sql .= $this->college ."', '";
		$sql .= "defaultbud', '";
		$sql .= $database->escape_value($info['defaultb']) ."', '";
		$sql .= $this->budgetid ."')";
		$database->query($sql);
		$this->generate_list();
		foreach ($this->expense_array as $value) {
			$name = $value->get_rca();
			$diffbud = isset($info[$name]) ? $info[$name] : "" ;
			if ($diffbud){ //
				$sql = "INSERT INTO collegebudgets (";
				$sql .= "college, rcaid, budget, budgetid";
				$sql .= ") VALUES ('";
				$sql .= $this->college ."', '";
				$sql .= $name ."', '";
				$sql .= $database->escape_value($diffbud) ."', '";
				$sql .= $this->budgetid  ."')"; 
				$database->query($sql);
			} // if ($diffbud)
		} //foreach 
	}

	function budget_form(){
		echo "<br/><br/>You are creating a budget for the ".GetSem()." semester of ".date('Y').".<br/><br/>";	
		?>
		<table> 
			<form action = "rcaexpenses.php" method="post">
			<tr><td>Enter default budget for the RCAs:</td><td>
			<input type="text" name="defaultb"/></td></tr></table><br/>
			Now please enter budgets for the RCAs that <strong>differ</strong> from the default budget.<br/>
			Please do not enter anything for the RCAs that will have the default budget. <br/>
			They will be assigned the default budget automatically.<br/>
        <table>
		<?
		$this->generate_list();
		foreach ($this->expense_array as $value){
			?><tr><td> <?
			echo $value->get_rca().": ";
			?></td><td>
			<input type="text" name="<? echo $value->get_rca(); ?>"/></td></tr>
			<?
		} // foreach ($resultd as $value){
		?>
		<input type="hidden" name="createbudget" value="yes"/><br/>
		<tr><td><input type="submit" value="submit" /></td></tr></table>
		</form>
		<?
	}
	
	function edit_form(){
		global $database;
		$this->generate_list();
		?>
        <p class="tablehead">To change the default budget, enter a new amount and hit submit.</p>
        <form action = "rcaexpenses.php" method="post">
		<table><tr><td>DEFAULT BUDGET: </td><td><input type="" name="defaultbud" value="<? echo $this->get_prevdef('defaultbud'); ?>"/></td></tr>
		<input type="hidden" name="admintask" value="editdefault"/>
		<tr><td><input type="submit" value="submit" /></td></tr>
		</table></form>
		<br/>
        <p class="tablehead">To change budgets for any and all RCA enter all new amounts and hit submit.</p>
		<form action = "rcaexpenses.php" method="post">
		<table> <?
		foreach ($this->expense_array as $value){
			?><tr><td> <?
			echo $value->get_rca().": ";
			?></td><td>
			<input type="text" name="<? echo $value->get_rca(); ?>" value="<? echo $value->get_budget(); ?>"/></td></tr>
			<?
		} ?>
		<input type="hidden" name="admintask" value="editindividuals"/>
		<tr><td><input type="submit" value="submit" /></td></tr>
		</form></table>
		<? 
	}
	
	function get_prevdef($what){
		global $database;
		$sql="SELECT * FROM collegebudgets WHERE budgetid='".$this->budgetid."' AND rcaid='".$what."'";
		$result_set = $database->query($sql);
		if ($database->num_rows($result_set) > 0) {
				$barry = $database->fetch_array($result_set);
				return (float)$barry['budget'];
			} else {return 0;}
	}
	
	function edit_default($info){
		global $database;
		$diffbud=(float) $info['defaultbud'];
		$prevdefault= $this->get_prevdef('defaultbud');
		if ($prevdefault == 0) {
			$name = 'defaultbud';
			$sql = "INSERT INTO collegebudgets (";
			$sql .= "college, rcaid, budget, budgetid";
			$sql .= ") VALUES ('";
			$sql .= $this->college ."', '";
			$sql .= $name ."', '";
			$sql .= $database->escape_value($diffbud) ."', '";
			$sql .= $this->budgetid  ."')"; 
			$database->query($sql);
		} else {
			if ($diffbud !=$prevdefault) { // 
				$sql = "UPDATE collegebudgets SET ";
				$sql .= "budget='". $database->escape_value($diffbud) ."' ";
				$sql .= "WHERE budgetid='". $this->budgetid ."' AND rcaid='defaultbud'";
				$database->query($sql);
			} 
		}
		$this->generate_list(); //get rid of rca budgets that now equal new default budget.
		foreach ($this->expense_array as $value){
			$name = $value->get_rca();
			$had_different = (float)$this->get_prevdef($name);
			if ($had_different > 0) {
				if ($had_different == $diffbud) {
					$sql = "DELETE FROM collegebudgets WHERE budgetid='".$this->budgetid."' AND rcaid='".$name."'";
					$database->query($sql);
				}
			}
		}
	}
	
	function edit_individuals($info){
		global $database;
		$this->generate_list();
		$thedefault= $this->get_prevdef('defaultbud');
		foreach ($this->expense_array as $value){
				$name = $value->get_rca();
				$diffbud=(float)$_POST[$name];
				$had_different = (float)$this->get_prevdef($name);
				if ($had_different > 0) {
					if ($diffbud==$thedefault) { //
						$sql = "DELETE FROM collegebudgets WHERE budgetid='".$this->budgetid."' AND rcaid='".$name."'";
						$database->query($sql);
					} else { // if ($diffbud==$thedefault)
						if ($diffbud != $had_different) {
							$sql = "UPDATE collegebudgets SET ";
							$sql .= "budget='". $database->escape_value($diffbud) ."' ";
							$sql .= "WHERE budgetid='". $this->budgetid ."' AND rcaid='". $database->escape_value($name) ."'";
							$database->query($sql);
						}
					}
				} else {
					if ($diffbud!=$thedefault) { 
						$sql = "INSERT INTO collegebudgets (";
						$sql .= "college, rcaid, budget, budgetid";
						$sql .= ") VALUES ('";
						$sql .= $this->college ."', '";
						$sql .= $name ."', '";
						$sql .= $database->escape_value($diffbud) ."', '";
						$sql .= $this->budgetid  ."')"; 
						$database->query($sql);
					}
				}
		}
	}
}