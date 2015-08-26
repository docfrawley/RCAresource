<? include_once("rcainitialize.php");

class dsl_budget {
	
	private $budgetid;
	private $defaultb;
	private $bud_array = array();
	private $dsl;
	
	function __construct($budgetid, $dsluser) {
		global $database;
		$this->budgetid = $budgetid;
		$this->dsl = $dsluser;
		$sql="SELECT * FROM collegebudgets WHERE budgetid='".$budgetid."'";
		$result_set = $database->query($sql);
		$this->bud_array = $bud_array;
		$this->defaultb = 0;
		while ($resultrow = $database->fetch_array($result_set)) {
			if ($resultrow['rcaid'] == 'default') {$this->defaultb = $resultrow['budget']; }
			else { 
				$temp_array = array($resultrow['rcaid'] => $resultrow['budget']);
				$this->bud_array = array_merge($this->bud_array, $temp_array);
			}
		}
	}
	
	function create_budget(){
		
	}
	
	function edit_budget(){
	}
	
	function delete_budget(){
	}
	
	function budget_form($doing_edit){
		if (!$doing_edit) {
			echo "You are creating a budget for the {GetSem()} semester of {date('Y')}<br/>";
			if ($this->defaultb != 0) {echo "For your convenience, your previous budgets for the RCAs are listed<br/><br/>";	}
		} else {
			echo "";
		}
		?><table><form action = "../public/rcaexpenses.php" method="post">
		<tr><td>Enter default budget for the RCAs:</td><td>
		<input type="text" name="defaultb" value "<? echo $this->defaultb; ?>"/></td></tr></table><br/>
        Now please enter budgets for the RCAs that differ from the default budget.<br/>
		Please do not enter anything for the RCAs that will have the default budget. <br/>
		They will be assigned the default budget automatically.<br/>
        <table><?
		$sql="SELECT netid FROM rca WHERE college='".$college."' ORDER BY netid";
		$result_set = $database->query($sql);
		while($value = $database->fetch_array($result_set)){ //
			$rcabudget = 0;
			foreach ($this->bud_array as $key=>$value){
				if ($resultrow['rcaid'] == $value) {$rcabudget = $this->bud_array[$value];}
			}
			?><tr><td> <?
			echo $value['netid'].": ";
			?></td><td>
			<input type="text" name="<? echo $value['netid']; ?>" value="<? if ($rcabudget == 0) {echo "";} else {echo $rcabudget;} ?>"/></td></tr><?
		}?>
        <input type="hidden" name="task" value="<? echo $doing_edit; ?>"/>
		<input type="hidden" name="task" value="expadmin"/>
		<input type="hidden" name="admintask" value="newbudgets"/><br/>
		<tr><td><input type="submit" value="submit" /></td></tr></table>
		</form><?
	}
	
	function print_rcas($college){
		$coregroup = new coregroup($college);
		
	}
	
	function check_expenses($rca){
	}
}