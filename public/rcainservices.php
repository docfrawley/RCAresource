<? include_once("../includes/rcainitialize.php"); 
include_once("../includes/layouts/header.php");
if (isset($_SESSION["casnetid"])) {
include_once("../includes/layouts/setdsl.php");  
include_once("../includes/layouts/menu.php"); 

?><div id="workarea"><div id="calenderwork"><?

if ($dsluser) { $college=getdslcollege($dsluser);}  
else {
	$the_rca = new rcaobject($rcauser);
	$college = $the_rca->get_college();
}

$self = htmlentities($_SERVER['PHP_SELF']);

//if have inservice id, go ahead and create that object//
$theid=isset($_GET['inservid']) ? $_GET['inservid'] : "" ;
	if (!$theid) $theid=isset($_POST['inservid']) ? $_POST['inservid'] : "" ;
if ($theid) { $the_inservice = new inserviceevent($theid); }			
		
if ($rcauser) { $rca_record = new rcainservices($rcauser); }	

$task=isset($_GET['task']) ? $_GET['task'] : "" ;
	if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;


$inservices_list = new allinservices();	

switch ($task) { // 
 	case"listRCAs":
		$thecoregroup = new coregroup($college);
		$thecoregroup->list_inservices();        
   	break;

 	case "check":
		$whattodo=isset($_POST['inservstep']) ? $_POST['inservstep'] : "" ;
		$taskcheck=isset($_GET['taskins']) ? $_GET['taskins'] : "" ;
			if (!$taskcheck) $taskcheck=isset($_POST['taskins']) ? $_POST['taskins'] : "" ;
		
		switch ($taskcheck){ // 
			case "":
				?><div class="adminmessage4"> <?
				echo "You have selected the following in-service: <br/><br/>";
				echo $the_inservice->get_title();
				echo " on ";
				echo (date("l, F j, Y", $the_inservice->get_when())."<br/><br/>");
				echo "Please select one of the following options:<br/>";
				echo ('<a href="?task=check&taskins=deleteinservice&inservid='.$theid.'">Delete In-Service</a><br/>');
				echo ('<a href="?task=check&taskins=editinservice&inservid='.$theid.'">Edit In-Service</a><br/>');
				echo ('<a href="?task=check&taskins=runinservice&inservid='.$theid.'">Run/Check In-Service</a><br/>');
				echo ('<a href="?"> Back to List of In-Services</a>');
				?></div> <!--- <div class="adminmessage4"> --><?
			break;
			case "deleteinservice":
				?><div class="adminmessage4"> <?
					$doublecheck=isset($_GET['doublech']) ? $_GET['doublech'] : "" ;
					$the_inservice->delete_inservice($doublecheck);	
					echo ('<br/><a href="?">Return to In-Service List</a>');
				?></div> <!--- <div class="adminmessage4"> --><?
			break;
			
			case "editinservice":	
				?><div class="adminmessage4"> <?
					if (!$whattodo) { $the_inservice->edit_form(); }
					else { $the_inservice->edit_inservice($_POST); }
					echo ('<br/><a href="?">Return to In-Service List</a>');
				?></div> <!--- <div class="adminmessage4"> --><?	
			break;
			
			case "runinservice":
				?><div class="adminmessage4"> <?
					$the_inservice = new inserviceevent($theid);
					if (!$whattodo) { $the_inservice->list_rcas(); }
					else { 
						$the_inservice->rcas_attended($_POST); 
						$the_inservice->list_rcas();
					}				
				echo ('<br/><a href="?">Return to In-Service List</a>');
				?></div> <!--- <div class="adminmessage4"> --><?
			break;
			
			default:
				echo "yikes big time";
			break;
		}// switch ($taskcheck)
	break;
	
 	case"listind":
		$taskrca=isset($_GET['taskrca']) ? $_GET['taskrca'] : "";
		$caninser=isset($_GET['caninserv']) ? $_GET['caninserv'] : "" ;
		$confirmsignup=isset($_GET['confirmsup']) ? $_GET['confirmsup'] : "" ;
		if ($taskrca=="edrcainserv" ) {
			?><div class="adminmessage4"> <?
				if ($caninser=="") {
					echo $rcauser.", you are currently signed up for the following in-service: <br/><br/>";
					echo $the_inservice->get_title();
					echo ", scheduled for ";
					echo (date("l, F j, Y, g:i a", $the_inservice->get_when()).".");
					echo "<br/><br/>Would you like to cancel your participation in this in-service?<br/>";
					echo ("<a href='?task=listind&taskrca=edrcainserv&caninserv=yes&inservid=".$the_inservice->get_id()."'>Yes, Cancel sign-up</a><br/>");
					echo ("<a href='rcainservices.php'>No, back to the In-Service List</a>");
				} else { 
					$rca_record->delete_inservice($the_inservice->get_id()); 
					$task = "";
				}
			?></div> <!--- <div class="adminmessage4"> --><?
		} // if ($taskrca="edrcainserv" )
		
		if ($taskrca == "signup") { //
			?><div class="adminmessage4"> <?
				$rca_record->add_inservice($the_inservice->get_id(), $confirmsignup);
				if ($confirmsignup) { $task = ""; }
			?></div> <!--- <div class="adminmessage4"> --><? 
		} // if ($taskrca == "signup")
			
	break;
	case "":
	break;	
	
	default:
		echo "yikes";
} // switch ($task)
	
	
if ($task=="") {
	$addinservice=isset($_GET['addinservice']) ? $_GET['addinservice'] : "" ;
		if (!$addinservice) $addinservice=isset($_POST['addinservice']) ? $_POST['addinservice'] : "" ;
	if ($addinservice) {$inservices_list->add_inservice($_POST, $dsluser); }
	if ($whichclass) { ?> <div class="adminmessage"> <? } else { ?> <div class="adminmessage2"> <? }
	
		if ($dsluser) { // 
			echo "Listed below are the In-Services Currently Available.<br/>Click on the Title of any In-Service to edit/delete or<br/>to check on the RCAs signed up for that event.<br/><br/>";	
		} else { 
			echo "Listed below are the In-services currently available.<br/>"; 
			echo "Click on the title to sign-up for that in-service.<br/><br/>";		
		} // if ($rcauser=="")
	
		?> </div><div id="admintable2"> <?
			$inservices_list->list_inservices($dsluser);
		?> </div><?
	
	if ($dsluser) { ?><div id="adminmessage3"><? $inservices_list->add_form(); ?></div><? }
	else 
		{ 
			?><div id="adminmessage3"><? 
			$rca_record->show_record(false); 
			?></div><? }
} // if ($task=="")
	
   ?>
                </div> <!-- <div id="calenderwork"> -->
                </div> <!-- <div id="workarea"> -->
 
<? 
$todisplay=isset($_GET['tdisplay']) ? $_GET['tdisplay'] : "none" ;
$to2display=isset($_GET['t2display']) ? $_GET['t2display'] : "";
	if (!$to2display) $to2display=isset($_POST['t2display']) ? $_POST['t2display'] : "none" ; 
?>

<div id="blanket" style="display: <? echo $to2display; ?>"></div>
<div class="displaymod23" style="display:<? echo $to2display; ?>">
<div id="oncalllogstuff2">
<div class="goingout">
<? echo ('<a href="?task=check&taskins=runinservice&inservid='.$theid.'">CLOSE</a>'); ?>
</div> <!-- <div id="goinout"> -->
<div id="oncallposition3">
<? 
$addrcas=isset($_GET['addrcas']) ? $_GET['addrcas'] : "" ;
if ($to2display != 'none')
{
	if (!$addrcas) $addrcas=isset($_POST['addrcas']) ? $_POST['addrcas'] : "" ;
	if ($addrcas) { $the_inservice->add_rcas($addrcas); }
	else { 
		echo "Listed below are all the RCAs not currently signed up for this inservice.<br/>
		Click on the RCAs who want to include and then hit 'submit' at the bottom.<br/>";
	} 
	$the_inservice->add_rcas_form();
}
	
?>     
</div> <!-- <div id="oncallposition"> -->
</div> <!-- <div id="oncallstuff2"> -->
</div> <!-- <div class="displaymod2" -->

<div id="blanket" style="display: <? echo $todisplay; ?>"></div>
<div class="displaymod2" style="display:<? echo $todisplay; ?>">
<div id="oncalllogstuff2">
<div class="goingout">
<? echo ('<a href="?task=listRCAs">CLOSE</a>'); ?>
</div> <!-- <div id="goinout"> -->
<div id="oncallposition">

<? 
	$rcaperson=isset($_GET['rcaperson']) ? $_GET['rcaperson'] : "";
	if ($rcaperson) {
		$rca_record = new rcainservices($rcaperson);
		$rca_record->show_record($dsluser);
	}
?>
</div> <!-- <div id="oncallposition"> -->
</div> <!-- <div id="oncallstuff"> -->
</div> <!-- <div class="displaymod2" : > -->
<?   
	if ($dsluser) {
		?><div id="oncallpdf"><? echo ('<a href="pdfs/signup.pdf">RCA SIGN-IN SHEET</a>'); ?></div><?
	}
} // if (isset($_SESSION["casnetid"])) 
include("../includes/layouts/footer.php"); ?>

