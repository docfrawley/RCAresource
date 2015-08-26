<? include_once("rcainitialize.php");

class coregroup {
	
	private $core_array = array();
	private $the_college;
	
	function __construct($college) {
		global $database;
		$sql = "SELECT * FROM rca WHERE college = '".$college."' ORDER BY netid";
		$result_set = $database->query($sql);
		while($value = $database->fetch_array($result_set)){ 
			$rca = new rcaobject($value['netid']);
			array_push($this->core_array,$rca);
		}
		$this->the_college = $college;
	}
	
	
	function add_rca_form() {
		echo "If you would like to enter a new RCA, please complete the form below.<br/><br/>";
		?> <table><form action="../public/rcaadmin.php" method="post">
		<tr><td style="width:20px;">NetID: </td><td><input type="text" name="rcaid" /></td></tr>
		<tr><td></td><td><input type="radio" name="year" value="first"/> First Year RCA </td></tr>
		<tr><td></td><td><input type="radio" name="year" value="second"/> Second Year RCA </td></tr>
		<input type="hidden" name="task" value="enter"/>        
		<tr><td><input type="submit" value="submit" /></td></tr>
		</form></table>
        <?
	}
	
	function get_rca($index){
		return $this->core_array[$index]->get_netid();
	}
	
	function list_admin() {
		?><table><?
		foreach ($this->core_array as $ind_rca) {
			?><tr><td style="width:100px;"><? echo "<a href='../public/rcaadmin.php?task=modify&rcaid={$ind_rca->get_netid()}'>{$ind_rca->get_netid()}</a>";
			?></td><td><? 
			if ($ind_rca->get_year()=="first"){ echo "First Year";} 
			else {echo "Second Year";}
			?></td></tr><?
		}
		?></table><?
	}
	
	function number_rcas() {
		return count($this->core_array);
	}
	
	function list_oncall() {
		?><table width="700"  >

            <tr><td align="left" class="core_oncall">NAME</td><td>  </td>
            <td class="core_oncall"># ON CALL</td>
            <td class="core_oncall"> # ROUNDS</td>
            <td class="core_oncall">NEXT ON CALL</td>
            <td class="core_oncall">NEXT ROUNDS</td>
            </tr>
            <tr><td></td></tr><?
		foreach ($this->core_array as $ind_rca) {
			$rcainfo = new rcaoncall($ind_rca->get_netid());
			?><tr><td><? echo "<a href='../public/rcaoncall.php?tdisplay=yes&whichrca={$ind_rca->get_netid()}'>{$ind_rca->get_netid()}</a>";
			?></td><td>  </td><td><? 
			echo $rcainfo->num_oncall();
			?></td><td><? 
			echo $rcainfo->num_rounds();
			?></td><td><? 
			echo $rcainfo->next_oncall(true);
			?></td><td><? 
			echo $rcainfo->next_rounds(true);
			?></td></tr><?
		}
		?></table><?
	}
	
	function list_inservices() {
		?> <div class="adminmessage"></div><div id="admintable"><table width="800" cellpadding="4" >
            <tr><td align="left">NAME</td>
            <td>#REQUIRED</td>
            <td>#SIGNED UP</td>
            <td>#COMPLETED</td>
            <td>#SKIPPED</td>
            </tr><?
		foreach ($this->core_array as $ind_rca) {
			$rcainfo = new rcainservices($ind_rca->get_netid());
			?><tr><td><? echo ("<a href='?tdisplay=yes&task=listRCAs&rcaperson={$ind_rca->get_netid()}'>".$ind_rca->get_netid()."</a>");
			?></td><td><? 
			echo $rcainfo->required_inservices();
			?></td><td><? 
			echo $rcainfo->signedup_inservices();
			?></td><td><? 
			echo $rcainfo->completed_inservices();
			?></td><td><? 
			echo $rcainfo->skipped_inservices();
			?></td></tr><?
		}
		?></table></div><?
	}

	function RCAs_attending($id){
		global $database;
		$tarray = array();
		$fill_array = array();
		$the_inservice = new inserviceevent($id);
		$result_set = $the_inservice->get_rcas();
		while ($result_row = $database->fetch_array($result_set)) { array_push($fill_array,$result_row['rca']); }
		foreach ($this->core_array as $ind_rca) {
			if (in_array($ind_rca->get_netid(), $fill_array)) { array_push($tarray,$ind_rca->get_netid()); }
		}
		return $tarray;
	}

	function RCAs_notAttending($id) {
		$in_array = $this->RCAs_attending($id);
		$not_array = array();
		foreach ($this->core_array as $ind_rca) {
				if (!in_array($ind_rca->get_netid(), $in_array)) { array_push($not_array, $ind_rca->get_netid()); }
		}
		return $not_array;
	}

	function inservice_form($id){
		$temp_array = $this->RCAs_notAttending($id);
		?><table border="1px" cellpadding="5px"><tr><td><strong><? echo strtoupper($this->the_college); ?></strong>
        </td></tr><? 
        foreach($temp_array as $value) {
			?><tr><td>
            <input type="checkbox" name="addrcas[]" id="checkbox_id" value="<? echo $value; ?>"><? echo $value; ?>
            </td></tr><?
	 	} 
		?></table><?
	}

}
?>