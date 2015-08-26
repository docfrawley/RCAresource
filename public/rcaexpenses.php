<? include_once("../includes/rcainitialize.php"); 
include_once("../includes/layouts/header.php");
if (isset($_SESSION["casnetid"])) {
include_once("../includes/layouts/setdsl.php");  
include_once("../includes/layouts/menu.php"); 
?>


<div id="workarea">
<div id="calenderwork">

<?
$self = htmlentities($_SERVER['PHP_SELF']);


		$numindex=isset($_GET['numindex']) ? $_GET['numindex'] : "" ;
			if (!$numindex) $numindex=isset($_POST['numindex']) ? $_POST['numindex'] : "";	


if ($dsluser) {
	$college=getdslcollege($dsluser);}  
	else {
		$the_rca = new rcaobject($rcauser);
		$college = $the_rca->get_college();
	}
$budgetid = GetBudgetId($college);

if ($dsluser) {
	$core_expenses = new core_expenses($college, $budgetid);
	if (!$core_expenses->budget_made()) {
		if (isset($_POST['createbudget'])){$core_expenses->create_budget($_POST);}
		else {
			?><div id="expense_table"><?
				$core_expenses->budget_form();
				?> </div> <?
		}
	}

	if ($core_expenses->budget_made()){
		$admintask=isset($_GET['admintask']) ? $_GET['admintask'] : "" ;
			if (!$admintask) $admintask=isset($_POST['admintask']) ? $_POST['admintask'] : "" ;
		if ($admintask=="editbudgets")	{ 
			?> 
            <div id="edit_button"><a href="?task=listRCAs">BACK TO RCA LIST</a></div>
            <div id="expense_table"><?
			$core_expenses->edit_form(); ?></div><? }
		else {
			if ($admintask=="editdefault") {$core_expenses->edit_default($_POST);}
			elseif ($admintask=="editindividuals") {$core_expenses->edit_individuals($_POST);}
			?> 
            <div id="edit_button"><a href="?admintask=editbudgets">EDIT BUDGETS</a></div>
            <div id="expense_table"><?
			$core_expenses->list_rcas();
			?></div><?
		}
	}
} else {
	$task=isset($_GET['task']) ? $_GET['task'] : "" ;
		if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
	$rca = new rca_ereport($rcauser, $budgetid);
	if ($task == "enter_expense") {$rca->enter_expense($_POST);}
	?><div id="rca_top"><table width="500px">
	<tr><td class="tablehead">BUDGET</td><td class="tablehead">TOTAL SPENT</td><td class="tablehead">REMAINDER</td></tr>
	<tr><td><? echo $rca->get_budget() ?></td><td><? echo $rca->total_spent() ?></td><td><? echo $rca->money_left() ?></td></tr></table></div><?
	?><div id="rca_table"><?
	$rca->print_expenses(false);
	?><div id="enter_expense">
	<p style="color:#39F; font-size:18px">Use the form below to enter a new expense</p><?
	$rca->expense_form();
	?> </div></div> <?
}

?>
	</div> <!-- <div id="calenderwork"> -->
	</div> <!-- <div id="workarea"> -->

<? $todisplay=isset($_GET['tdisplay']) ? $_GET['tdisplay'] : "none" ; ?>
<div id="blanket" style="display: <? echo $todisplay; ?>"></div>
<div class="displaymod2" style="display:<? echo $todisplay; ?>">
<div id="oncalllogstuff2">
    <div class="goingout">
    <? echo ('<a href="rcaexpenses.php">CLOSE</a>'); ?>
    </div> <!-- <div id="goinout"> -->
<div id="oncallposition">

	<? 
	$the_rca=isset($_GET['rcaid']) ? $_GET['rcaid'] : "";
	$rca = new rca_ereport($the_rca, $budgetid);
	$rca->print_expenses(true); ?>

</div> <!-- <div id="oncallposition"> -->
</div> <!-- <div id="oncallstuff"> -->
</div> <!-- <div class="displaymod2" : > --> 

<? $thedisplay=isset($_GET['thedisplay']) ? $_GET['thedisplay'] : "" ;
if (!$thedisplay) $thedisplay=isset($_POST['thedisplay']) ? $_POST['thedisplay'] : "none" ; ?>

<div id="blanket" style="display: <? echo $thedisplay; ?>"></div>
<div class="displaymod2" style="display:<? echo $thedisplay; ?>">
<div id="oncalllogstuff2">
<div class="goingout">
<? echo ('<a href="?task=enterexpense">CLOSE</a>'); ?>
</div> <!-- <div id="goinout"> -->
<div id="oncallposition">

<?

$whattodo=isset($_GET['whattodo']) ? $_GET['whattodo'] : "" ;
	if (!$whattodo) $whattodo=isset($_POST['whattodo']) ? $_POST['whattodo'] : "" ;
$expense = 	new expenseobject($rcauser, $budgetid, $numindex, 0);
	if ($whattodo == "delete_expense") {$expense->delete_expense($_POST);}
	else {
		if ($whattodo == "edit_expense") {$expense->edit_expense($_POST);}
		$expense->edit_form();
	}
 
?>		
</div> <!-- <div id="oncallposition"> -->
</div> <!-- <div id="oncallstuff"> -->
</div> <!-- <div class="displaymod2" : > --> 

       
<? 
}
include("../includes/layouts/footer.php"); ?>

