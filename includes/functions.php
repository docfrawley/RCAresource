<? include_once("database.php");
function redirect_to() {
	  header("Location: http://mathey.princeton.edu/demo/public/rcahome.php");
	  exit;
}

function getrating($orderinnum) {
	global $database;
		$sql = "SELECT AVG(overallrate) AS averrating FROM orderintrack WHERE orderinnum='".$orderinnum."' AND overallrate !=''";
		$result_set = $database->query($sql);
		$result_row = $database->fetch_array($result_set);
		return number_format($result_row['averrating']);
	}
	
function echoacorns($avgnum) {
		?><div id="acornarea"><?
		if ($avgnum !=0){
			for($i=1;$i<=$avgnum;$i++){
				?><img id="indacorn" src="../public/images/acornrate.png" /><?
			}//for($i=1;$i<=$avgnum;$i++)
		} else{ //if ($avgnum !=0)
			echo 'no acorns';
		} //if ($avgnum !=0)
		?></div> 
		<?
	}
	
function getdslcollege($dsluser) {
					switch ($dsluser){ // 
						case "mfrawley":
						$college = "Mathey";
						break;
						case "mellisat":
						$college = "Forbes";
						break;
						case "amyham":
						$college = "Rocky";
						break;
						case "ak19":
						$college = "Wilson";
						break;
						case "aandres":
						$college = "Butler";
						break;
						case "momo":
						$college = "Whitman";
						break;
						default:
						echo "yikes college";
					} // switch ($dsluser)
		return $college;			
}

function getdslincharge($dsluser) {
			switch ($dsluser){ 	
				case "mfrawley":
					echo "Matt Frawley";
				break;
				case "mellisat":
					echo "Mellisa Thompson";
				break;
				case "amyham":
					echo "Amy Ham Johnson";
				break;
				case "ak19":
					echo "Aaron King";
				break;
				case "aandres":
					echo "Alexis Andres";
				break;
				case "momo":
					echo "Momo Wolapaye";
				break;
				default:
					echo "yikes college";
			} 
}

function GetBudgetId($college) {
	$sem = GetSem($college);
	$y = date('Y');
	if (date('n') == 1 && $college != 'Rocky') {$y -=1;}
	return $sem.$y.$college;
}

function WhichBudDo() {
	global $database;
	$sem = GetSem();
		$y = date('Y');
		$y = date('Y');
		if (date('n') == 1) {$y -=1;}
		$sql="SELECT * FROM collegebudgets WHERE semester='".$sem."' AND year='".$y."'";
				$result_set = $database->query($sql);
				$result_rowc = $database->fetch_array($result_set);
		if ($result_rowc['budgetid'] !='') {
			echo '<a href="?task=expadmin&admintask=editbudgets">EDIT BUDGET</a><br/>';	
		} else {
			echo '<a href="?task=expadmin&admintask=newbudgets">CREATE BUDGET</a><br/>';
		}
}

function GetMonths() {
	$i = 1;
$month = strtotime('2013-01-01');
	while($i <= 12)
	{
		$month_name = date('F', $month);
		echo '<option value="'. $i. '">'.$month_name.'</option>';
		$month = strtotime('+1 month', $month);
		$i++;
	}
}

function GetDays() {
	for($i=1; $i<=31;$i++){
		?><option value="<? echo $i; ?>"><? echo $i; ?></option><?
	} 
}

function GetYears() {
	$next = date("Y",strtotime("+1 years"));
	if (date('n') == 1) {
		?><option value="<? echo date('Y',strtotime("-1 years")); ?>"><? echo date('Y',strtotime("-1 years")); ?></option> <?
	}
	?><option value="<? echo date('Y'); ?>"><? echo date('Y'); ?></option>  
    <option value="<? echo date('Y',strtotime("+1 years")); ?>"><? echo date('Y',strtotime("+1 years")); ?></option> <?
}

function GetSem($college ="") {
	if ($college == 'Rocky') {
		if ((date('n') >= 1) && (date('n') < 7)) { return "spring";}
		return "fall";
	} else {
		if ((date('n') > 1) && (date('n') < 7)) { return "spring";}
		return "fall";
	}
}

 function spitout($whichnum, $dsluser) {
	 global $database;
	 			global $database;
				$sql="SELECT thewhat FROM rcamanual WHERE numentry='".$whichnum."'";
				$result_set = $database->query($sql);
				$rowstuff = $database->fetch_array($result_set);
				echo $rowstuff['thewhat'];
				if ($dsluser) {
					?> <div class="editing"><?
					echo ("<a href='?tdisplay=yes&numentry=".$whichnum."'>EDIT</a>");
					?> </div> <?
				}
}

function getemailaddress($college) {
			switch ($college) {
				case "Forbes":
					return "mellisat";
				break;
				case "Mathey":
					return "mfrawley";
				break;
				case "Rocky":
					return "amyham";
				break;
				case "Butler":
					return "aandres";
				break;
				case "Whitman":
					return "momo";
				break;
				case "Wilson":
					return "aaking";
				break;				
				default:
				echo "yikes";
			} // switch ($whichcollege)	
}
?>