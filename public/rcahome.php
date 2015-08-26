<?php include_once("../includes/rcainitialize.php");
include_once("../includes/rcalogin.php"); 
include_once("../includes/layouts/header.php");

if (isset($_SESSION["casnetid"])) {

include("../includes/layouts/menu.php");   
	?>
<div id="workarea">
<div id="workarearelative">
               <? 
	
$self = htmlentities($_SERVER['PHP_SELF']);
$task=isset($_GET['task']) ? $_GET['task'] : "" ;
$taskadmin=isset($_GET['tasksecond']) ? $_GET['tasksecond'] : "" ;
if (!$taskadmin) $taskadmin=isset($_POST['tasksecond']) ? $_POST['tasksecond'] : "" ;
$college=isset($_GET['college']) ? $_GET['college'] : "" ;
if (!$college) $college=isset($_POST['college']) ? $_POST['college'] : "" ;
$taskedit=isset($_POST['taskthird']) ? $_POST['taskthird'] : "" ;
if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
switch ($task) {
	case "" :
		if ($dsluser) {
			
					$college=getdslcollege($dsluser);
			   		$numentry=isset($_GET['numentry']) ? $_GET['numentry'] : "" ;
						if (!$numentry) $numentry=isset($_POST['numentry']) ? $_POST['numentry'] : "" ;
					$whichform=isset($_GET['whichform']) ? $_GET['whichform'] : "" ;
						if (!$whichform) $whichform=isset($_POST['whichform']) ? $_POST['whichform'] : "" ;	
					$taskannounce=isset($_GET['taskannounce']) ? $_GET['taskannounce'] : "" ;
						if (!$taskannounce) $taskannounce=isset($_POST['taskannounce']) ? $_POST['taskannounce'] : "" ;
					$thecollege=isset($_POST['thecollege']) ? $_POST['thecollege'] : "" ;	
			   ?><div class="adminmessage6"><?	
			   		$announcements = new announceobject($college);
					echo "ANNOUNCEMENT FORM<br/>";
					if (($whichform=='announce') AND ($taskannounce)) {
						if ($numentry) { //if numentry then row already there so must be update or delete not insert
							if ($taskannounce=="deleteannounce") { $announcements->delete_announce($numentry); }
							else { $announcements->edit_announce($_POST); }
						} else { $announcements->add_announce($_POST); }
						if ($taskannounce!='deleteannounce') { $announcements->mail_announcement($college);}
						$numentry=0;
					} // if (($whichform=='announce') AND ($taskannounce))
					$announcements->announcment_form($numentry, $college, $whichform);
				?>
                </div> <!--- <div class="adminmessage6"> --->
                <div class="adminmessage66">
                <?	$calendars = new calendarobject($college);
					echo "CALENDAR FORM<br/>";
					if (($whichform=='calendar') AND ($taskannounce)) {
						if ($numentry) { //if numentry then row already there so must be update or delete not insert
							if ($taskannounce=="deleteevent") {$calendars->delete_cal($numentry); }
							else { $calendars->edit_cal($_POST);}
						} else { $calendars->add_cal($_POST); } // if ($numentry)
						$numentry=0;
					} // if (($whichform=='announce') AND ($taskannounce))
					$calendars->calendar_form($numentry, $college, $whichform);
				?>
                </div>  <!--- <div class="adminmessage66"> --->
                <? 
				
			} else { //    if ($dsluser) what RCAs see instead of DSLs
					?><div class="adminmessage67"> <div id='ten'><?
					echo "<strong>ON CALL SYNOPSIS</strong><br/>";
					?></div><?
					$rca_oncall = new rcaoncall($rcauser);					
					?><table><tr><td> <?
						echo "# ON-CALL: ";
					?></td><td> <?
						echo $rca_oncall->num_oncall();
					?></td></tr><tr><td> <?
						echo "# ROUNDS: ";
					?></td><td> <?
						echo $rca_oncall->num_rounds()
					?></td></tr><tr><td> <?
						echo "NEXT ON-CALL: ";
					?></td><td id="four"> <?
						echo $rca_oncall->next_oncall();
					?></td></tr><tr><td> <?
						echo "NEXT ROUNDS: ";
					?></td><td id='one'> <?
						echo $rca_oncall->next_rounds();
					?></td></tr></table><br/><br/>
                    
                    <div id='ten'><strong>INSERVICES</strong><br/></div>
                    <? $rca_inservices = new rcainservices($rcauser)  ?>
                    <table><tr><td> <?
						echo "# REQUIRED: ";
					?></td><td> <?
						echo $rca_inservices->required_inservices();
					?></td></tr><tr><td> <?
						echo "# SIGNED-UP: ";
					?></td><td> <?
						echo $rca_inservices->signedup_inservices();
					?></td></tr><tr id="four"><td> <?
					echo "# COMPLETED: ";
					?></td><td> <?
						echo $rca_inservices->completed_inservices();
					?></td></tr><tr><td> <?
					echo "# SKIPPED: ";
					?></td><td> <?
						echo $rca_inservices->skipped_inservices();
					?></td></tr></table><br/><br/>
                    
                    <div id='ten'><strong>BUDGET SNAPSHOT </strong><br/></div>
                    <?
					$the_rca = new rcaobject($rcauser);
					$budgetid = GetBudgetId($the_rca->get_college());
					$rca_expenses = new rca_ereport($rcauser, $budgetid);
					if ($rca_expenses->get_budget() == 0) {echo "Sadly, no budgets for your college";}
                    else {	
						?><table><tr>
						<td>Budget:</td>
						<td>  <? echo $rca_expenses->get_budget(); ?> </td></tr>
						<tr><td>Spent:</td>
						<td> <? echo $rca_expenses->total_spent(); ?></td></tr>
						<tr id='one'><td>Remaining: </td>
						<td> <? echo $rca_expenses->money_left(); ?></td></tr>
						<tr><td># of Entries:</td>
						<td> <? echo $rca_expenses->num_expenses(); ?></td></tr>
						<tr><td>Last Entry: </td>
						<td>
						<?
						if ($rca_expenses->last_entry() ==0) { // 
							echo "no entries yet";
						}else{ // if ($lastentry=="-")
							echo date("n/j/Y", $rca_expenses->last_entry());
						} // if ($lastentry=="-")
						?></td></tr></table><?
                    } // if ($rcabudget != 0) 
                    ?>
                    </div> <!--- <div class="adminmessage67"> ---><?
				} // ($dsluser)
				?>  <div id="titlestuff"><?
                	echo "ANNOUNCEMENTS"; 
				?></div>
				<div class="adminmessage5">
					<? 
					$announcements = new announceobject($college);
					$announcements->print_announcements($dsluser); ?>
                </div> <!--- <div class="adminmessage5"> --->
                <div id="titlestuff22"><?
                	echo "CALENDAR"; 
				?></div>
                <div class="adminmessage55"> <? 
					$calendars = new calendarobject($college);
                	$calendars->print_calendar($dsluser);?>
                </div> <!--- <div class="adminmessage"> --->
</div> <!--- <div id="workarearelative"> --->
</div> <!--- <div id="workarea"> --->
                <?
		break;
		//case "outofhere":
		//	session_destroy();
		//break;
	default:
		echo "yikes first admin";
	}  // switch ($task)

} //   isset $_SESSION['casenetid']
include("../includes/layouts/footer.php"); ?>

