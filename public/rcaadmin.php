<? include_once("../includes/rcainitialize.php");
include("../includes/layouts/header.php");
if (isset($_SESSION["casnetid"])) {
	//include("../includes/layouts/setdsl.php");  
include("../includes/layouts/menu.php");


$self = htmlentities($_SERVER['PHP_SELF']);
$task=isset($_GET['task']) ? $_GET['task'] : "" ;
	if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
$rcaid=isset($_GET['rcaid']) ? $_GET['rcaid'] : "" ;
	if (!$rcaid) $rcaid=isset($_POST['rcaid']) ? $_POST['rcaid'] : "" ;	
$college = getdslcollege($dsluser);
	$tryagain=false;
	?><div id="workarea">  <?
	if ($task=='enter') { //
		$the_rca = new rcaobject($rcaid);
		if ($the_rca->check_db()){
			$year = isset($_POST['year']) ? $_POST['year'] : "first";
			$the_rca->insert_rca($year, $college)
				?> <div class="adminmessage"> <?
				echo $rcaid. ' has been entered as a '.$the_rca->get_year().' year RCA in '.$the_rca->get_college().' College.';
				?> </div><?
		} else {
			?> <div class="adminmessage"> <?
				echo 'That netid already appears as an RCA. Please try again';
				?> </div> <?
		} // if (mysql_num_rows($result)<1)
	} // if ($task=='enter')
	
	if ($task=="modify"){ // 
		$changeyear=isset($_POST['fyear']) ? $_POST['fyear'] : "";
		$RCAdelete=isset($_POST['dRCA']) ? $_POST['dRCA'] : "";
		$messup = (($RCAdelete=="change") AND ($changeyear=="change"));
		$the_rca = new rcaobject($rcaid);
		if ((!$changeyear && !$RCAdelete) || $messup){ //
			?> <div class="adminmessage"> <?
			if ($messup) { //
				echo "You have selected both options. Perhaps we should try that one more time...<br/><br/>";
			} // if (($RCAdelete=="change") AND ($changeyear=="change"))
			$tryagain = true;
			$the_rca->change_rca_form();
				?> </div> <?
		} else { 
			if ($RCAdelete=="change") {$the_rca->delete_rca(); }
			if ($changeyear=="change") { $the_rca->update_year(); } 
		} 
	} // if ($task=="modify")
	
	if (!$tryagain) { //
		$core_group = new coregroup($college);
		if ($task =="") { ?> <div class="adminmessage"> <? } else { ?> <div class="adminmessage2"> <? }
		if ($core_group->number_rcas()<1) { echo ("There are no RCAs currently listed in {$college} College.<br/><br/>"); }
		else{
				echo "Below are the RCAs currently listed in {$college} College.<br/>";
				echo "Click on an RCA to edit or delete.<br/><br/>";
				
				?> </div><?
				
                ?><div id="admintable"><?
				$core_group->list_admin();
		} // if (mysql_num_rows($result) < 1)
		?> <div class="adminmessage2"> <?
		$core_group->add_rca_form()
		?></div> <?
	} // if (!$tryagain)
			
 		
 ?>
 </div> <!-- <div id="workarea"> -->
 

<? 
}
include("../includes/layouts/footer.php"); ?>

