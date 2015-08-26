<? include_once("../includes/rcainitialize.php"); 
include_once("../includes/layouts/header.php");
if (isset($_SESSION["casnetid"])) {
include_once("../includes/layouts/setdsl.php");  
include_once("../includes/layouts/menu.php"); 
?>


    <div id="workarea"><div id="calenderwork">
<?

if ($dsluser) { $college=getdslcollege($dsluser);}  
else {
	$the_rca = new rcaobject($rcauser);
	$college = $the_rca->get_college();
}

$self = htmlentities($_SERVER['PHP_SELF']);
		
$confirmsignup=isset($_GET['confirmsup']) ? $_GET['confirmsup'] : "" ;
$otheraduser=isset($_GET['otadmin']) ? $_GET['otadmin'] : "" ;
	if (!$otheraduser) $otheraduser=isset($_POST['otadmin']) ? $_POST['otadmin'] : "" ;
$task=isset($_GET['task']) ? $_GET['task'] : "" ;
	if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
$tevent=isset($_POST['title']) ? $_POST['title'] : "";
$whichclass=true;
$taskrca=isset($_GET['taskrca']) ? $_GET['taskrca'] : "";
$todisplay=isset($_GET['tdisplay']) ? $_GET['tdisplay'] : "none" ;
$to2display=isset($_GET['t2display']) ? $_GET['t2display'] : "";
	if (!$to2display) $to2display=isset($_POST['t2display']) ? $_POST['t2display'] : "none" ;
$rcaperson=isset($_GET['rcaperson']) ? $_GET['rcaperson'] : ""; //keep
$theid=isset($_GET['inservid']) ? $_GET['inservid'] : "" ;
	if (!$theid) $theid=isset($_POST['inservid']) ? $_POST['inservid'] : "" ;
$addrcas=isset($_GET['addrcas']) ? $_GET['addrcas'] : "" ;
	if (!$addrcas) $addrcas=isset($_POST['addrcas']) ? $_POST['addrcas'] : "" ;

 switch ($task) { // 
 	case"listRCAs":
		$thecoregroup = new coregroup($college);
		$thecoregroup->list_inservices();        
   	break;

 	case "check":
		$whattodo=isset($_POST['inservstep']) ? $_POST['inservstep'] : "" ;
		$taskcheck=isset($_GET['taskins']) ? $_GET['taskins'] : "" ;
			if (!$taskcheck) $taskcheck=isset($_POST['taskins']) ? $_POST['taskins'] : "" ;
		$sql="SELECT * FROM inservices WHERE id='".$theid."'";
		$result_set = $database->query($sql);
		$rowstuff = $database->fetch_array($result_set);
		switch ($taskcheck){ // 
			case "":
			?><div class="adminmessage4"> <?
				echo "You have selected the following in-service: <br/><br/>";
				echo $rowstuff['title'];
				echo " on ";
				echo (date("l, F j, Y", $rowstuff['time'])."<br/><br/>");
				echo "Please select one of the following options:<br/>";
				echo ('<a href="?task=check&taskins=deleteinservice&inservid='.$theid.'">Delete In-Service</a><br/>');
				echo ('<a href="?task=check&taskins=editinservice&inservid='.$theid.'">Edit In-Service</a><br/>');
				echo ('<a href="?task=check&taskins=runinservice&inservid='.$theid.'">Run/Check In-Service</a><br/>');
				echo ('<a href="?"> Back to List of In-Services</a>');
				?></div> <!--- <div class="adminmessage4"> ----><?
			break;
			case "deleteinservice":
			?><div class="adminmessage4"> <?
				$doublecheck=isset($_GET['doublech']) ? $_GET['doublech'] : "" ;
				
				if (!$doublecheck){
					
						if ($rowstuff['time']>date("U")) { // 
							echo "Just double checking; are you sure you want to delete this inservice?<br/>";
							echo $rowstuff['title'];
							echo " on ";
							echo (date("l, F j, Y", $rowstuff['time'])."<br/><br/>");
							echo ('<a href="?task=check&doublech=yes&taskins=deleteinservice&inservid='.$theid.'">Yes, Delete</a><br/><br/>');
							echo ('<a href="?">No, Return to In-Service List</a>');
						} else { // if ($rowstuff['time']>date("U"))
							echo "You cannot delete an in-service with a date that has already past.<br/><br/>";
							$task="";
							$whichclass=false;
						} // if ($rowstuff['time']>date("U"))
						
				} else { // if (!$doublecheck)
					$sql = "DELETE FROM inservices ";
	  				$sql .= "WHERE title='". $database->escape_value($rowstuff['title'])."' AND ";
					$sql .= "id='". $database->escape_value($rowstuff['id'])."'";
	  				$sql .= " LIMIT 1";
	 				$database->query($sql);
	  				if ($database->affected_rows() == 1) { 
						$sql="SELECT * FROM track WHERE inserviceid='".$rowstuff['id']."'";
						$result_set = $database->query($sql);
							while ($result_row = $database->fetch_array($result_set)){ // 
								$therca=$result_row['rca'];
								$theins=$result_row['inserviceid'];
								$theattended=$result_row['attended'];
								$sql = "DELETE FROM track ";
	  							$sql .= "WHERE rca='". $database->escape_value($therca)."' AND ";
								$sql .= "inserviceid='". $database->escape_value($theins)."' AND";
								$sql .= "attended='". $database->escape_value($theattended)."'";
	  							$sql .= " LIMIT 1";
								$database->query($sql);
							} // while ($result_row = mysql_fetch_array($resulta, MYSQL_ASSOC)
						echo "You have successfully deleted the inservice.";
						$task="";
						$whichclass=false;
					} // if ($result)	
											
				}	// // if (!$doublecheck)
				?></div> <!--- <div class="adminmessage4"> ----><?
			break;
			
			case "editinservice":
				?><div class="adminmessage4"> <?
			
					if (!$whattodo) { // 
						echo "Please make the edits needed and then press SUBMIT.<br/>Be forewarned that edits are final when submitted.<br/><br/>";
						$month=date("F", $rowstuff['time']);
						$monthnum=date("m", $rowstuff['time']);
						$day=date("j", $rowstuff['time']);
						$year=date("Y", $rowstuff['time']);
						$hour= date('g', $rowstuff['time']);
						$minute= date('i', $rowstuff['time']);
						$ampm= date('a', $rowstuff['time']);
						?><table>
						<form action = "<? echo $self; ?>" method="post">
						<tr><td>Title: </td><td><input type="text" name="title" value="<? echo $rowstuff['title']; ?>"/></td></tr>
                        <tr><td>Core Competency: </td><td><select name="area">
							<option selected="selected" value="<? echo $rowstuff['area']; ?>"><? echo $rowstuff['area']; ?></option>
							<option value="Diversity and Inclusion">Diversity and Inclusion</option>
							<option value="Personal Development">Personal Development</option>
                            <option value="Health and Wellness">Health and Wellness</option>
							</select></td></tr>
						<tr><td>Location: </td><td><input type="text" name="location" value="<? echo $rowstuff['location']; ?>"/></td></tr>
						<tr><td>Semester: </td><td><select name="semester">
							<option selected="selected" value="<? echo $rowstuff['semester']; ?>"><? echo $rowstuff['semester']; ?></option>
							<option value="fall">fall</option>
							<option value="spring">spring</option>
							</select></td></tr>
						<tr><td>Date: 
						</td><td><select name="month">
							<option selected="selected" value="<? echo $monthnum; ?>"><? echo $month; ?></option>
							<? GetMonths(); ?>
							</select>
							<select name="day1">
							<option selected="selected" "<? echo $day; ?>"><? echo $day; ?></option>
							<? GetDays(); ?>
							</select>  
							<select name="year">
								<option selected="selected" value="<? echo $year; ?>"><? echo $year; ?></option>
								<? GetYears(); ?>   
							</select></td></tr>
						<tr><td>Time:</td><td>
							<select name="hour">
								<option selected="selected" value="<? echo $hour; ?>"><? echo $hour; ?></option>
								<option value="01">1</option>
								<option value="02">2</option>
								<option value="03">3</option>
								<option value="04">4</option>
								<option value="05">5</option>
								<option value="06">6</option>
								<option value="07">7</option>
								<option value="08">8</option>
								<option value="09">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="00">12</option>
							</select>
							<select name="minute">
								<option selected="selected" value="<? echo $minute; ?>"><? echo $minute; ?></option>
								<option value="00">00</option>
								<option value="15">15</option>
								<option value="30">30</option>
								<option value="45">45</option>
								</select>
							<select name="morning">
								<option selected="selected" value="<? echo $ampm; ?>"><? echo $ampm; ?></option>
								<option value="am">am</option>
								<option value="pm">pm</option>
							</select></td></tr>
						<tr><td>Description</td>
							</td><td><textarea name="descriptionl" rows="7" cols="40"><? echo $rowstuff['description']; ?>
							</textarea></td></tr>
							<input type="hidden" name="inservid" value="<? echo $theid; ?>"/>
							<input type="hidden" name="task" value="check"/>
							<input type="hidden" name="taskins" value="editinservice"/>
							<input type="hidden" name="inservstep" value="goahead"/>
							<tr><td><input type="submit" value="submit" /></td></tr>
							</form></table>
							<?
					} else { // if (!$whattodo)
					
						if ($_POST['morning'] == 'pm') { // 
								$hourstuff = $_POST['hour']+12;
						}else{
								$hourstuff = $_POST['hour'];
						} // if ($_POST['morning'] == 'pm')
						
						$ttime = mktime($hourstuff, $_POST['minute'], 0, $_POST['month'], $_POST['day1'], $_POST['year']);
						$thetitle = $_POST['title'];
						$thearea = $_POST['area'];
						$thesemester = $_POST['semester'];
						$thelocation = $_POST['location'];
						$thedescription = $_POST['descriptionl'];
						
						$sql = "UPDATE inservices SET ";
						$sql .= "title='". $database->escape_value($thetitle) ."', ";
						$sql .= "location='". $database->escape_value($thelocation) ."', ";
						$sql .= "description='". $database->escape_value($thedescription) ."', ";
						$sql .= "time='". $database->escape_value($ttime) ."', ";
						$sql .= "semester='". $database->escape_value($thesemester) ."', ";
						$sql .= "area='". $database->escape_value($thearea) ."' ";
						$sql .= "WHERE id='". $database->escape_value($theid)."'";
	  					$database->query($sql);
					  	if ($database->affected_rows() == 1) {
							echo 'The in-service has been updated. <br/>';
							$task="";
							$whichclass=false;
							$tevent="";
						} // if ($result)
					} // if (!$whattodo)
				?></div> <!--- <div class="adminmessage4"> ----><?	
			break;
			
			case "runinservice":
				?><div class="adminmessage4"> <?
					$sql = "SELECT * FROM track WHERE inserviceid='".$theid."' ORDER BY rca";
					$result_set = $database->query($sql);
					$numrows = $database->num_rows($result_set);
					if ($numrows>0) { //
						if (!$whattodo) { // 
							echo "Listed below are the RCAs currently signed-up for ".$rowstuff['title'].".<br/>";
							$already=($rowstuff['time'] < date("U"));
								if ($already) { // 
									echo "Because this inservice has already occurred, the form is already loaded with the previous attendance record.<br/>";
									echo "The RCAs who were listed as in attendance have a check by their name. You can remove the check if needed.<br/>";
								} // if ($already)
							echo "Please check only those RCAs in attendance.<br/>";
							echo "If you need to include RCAs not listed below, click <a href='?t2display=yes&inservid=".$theid."'>HERE</a><br/><br/>";
							?>
							<form action = "<? echo $self; ?>" method="post">
							<? 
							while ($result_row = $database->fetch_array($result_set)){ // 
								if ($result_row['attended']=="Y") { // 
									$checked='checked="checked"';
								} else { // if ($result_row['attended']=="Y")
									$checked="";
								} // if ($result_row['attended']=="Y")
								?> 
								 <input type="checkbox" name="<? echo $result_row['rca']; ?>" value="YES" <? echo $checked; ?>/><? echo $result_row['rca']."<br/>";
							} // while ($result_row = mysql_fetch_array($resulta, MYSQL_ASSOC))
							?>
							<input type="hidden" name="dslid" value="<? echo $dsluser; ?>"/>
							<input type="hidden" name="inservid" value="<? echo $theid; ?>"/>
							<input type="hidden" name="task" value="check"/>
							<input type="hidden" name="taskins" value="runinservice"/>
							<input type="hidden" name="inservstep" value="goahead"/>
							<br/><input type="submit" value="submit" />
							</form><br/><br/>
							<?
						} else { // if (!$whattodo)
							$affectedRCAs=0;
							while ($result_row = mysql_fetch_array($resulta, MYSQL_ASSOC)){ // 
								$whichrca=$result_row['rca'];
								$didattend=isset($_POST["$whichrca"]) ? $_POST["$whichrca"] : "" ;
								
									if ($didattend=="YES") { // 
										$didthey="Y";
									}else{
										$didthey="N";
									} // if ($didattend=="YES")
									
								$sql = "UPDATE track SET ";
								$sql .= "attended='". $database->escape_value($didthey) ."' ";
								$sql .= "WHERE rca='". $database->escape_value($whichrca)."' AND ";
								$sql .= "inserviceid ='". $database->escape_value($theid)."'";
	  							$database->query($sql);
					  			if ($database->affected_rows() == 1) {$affectedRCAs +=1;}	
							} // while ($result_row = mysql_fetch_array($resulta, MYSQL_ASSOC))
							if ($affectedRCAs >0){echo $affectedRCAs." RCAs have been recorded as attending this inservice.";}								
							$task="";
							$whichclass=false;
						} // if (!$whattodo)
						
				} else { // if ($numrows>0
					echo "Currently there are no RCAs signed up for this In-Service";
					$task="";
					$whichclass=false;
				}//  if ($numrows>0
				
				echo ('<a href="?">Return to In-Service List</a>');
				?></div> <!--- <div class="adminmessage4"> ----><?
			break;
			
			default:
				echo "yikes big time";
			break;
		}// switch ($taskcheck)
		
	break;
	
 	case"listind":
		$showrest=true;
		
		if ($taskrca=="edrcainserv" ) {
			?><div class="adminmessage4"> <?
			$caninser=isset($_GET['caninserv']) ? $_GET['caninserv'] : "" ;
			$inservicenumber=$_GET['inservicenum'];
			
			if ($caninser=="") {
				$sql = "SELECT * FROM inservices WHERE id='".$inservicenumber."'";
				$result_set = $database->query($sql);
				$rowstuff = $database->fetch_array($result_set);
				echo ($rcauser);
				echo ", you are currently signed up for the following in-service: <br/><br/>";
				echo ($rowstuff['title']);
				echo ", scheduled for ";
				echo (date("l, F j, Y, g:i a", $rowstuff['time']).".");
				echo "<br/><br/>Would you like to cancel your participation in this in-service?<br/>";
				echo ("<a href='?task=listind&taskrca=edrcainserv&caninserv=yes&inservicenum=".$rowstuff['id']."'>Yes, Cancel sign-up</a><br/>");
				echo ("<a href='?task=listind'>Back to In-Service Record</a>");
				$showrest=false;
			} else { // if ($caninser=="") 
				$sql = "SELECT * FROM track WHERE rca='".$rcauser."' AND inserviceid='".$inservicenumber."'";
				$result_set = $database->query($sql);
				$rowstuff = $database->fetch_array($result_set);
				
				$sql = "DELETE FROM track ";
	  			$sql .= "WHERE rca='". $database->escape_value($therca)."' AND ";
				$sql .= "inserviceid='". $database->escape_value($inservicenumber)."'";
	  			$sql .= " LIMIT 1";
				$database->query($sql);
				if ($database->affected_rows() == 1) { // 
					echo "You have successfully canceled your spot in this in-service.";
				} // if ($result)
				
			} // if ($caninser=="") 
			?></div> <!--- <div class="adminmessage4"> ----><?
		} // if ($taskrca="edrcainserv" )
		
		if ($taskrca == "signup") { //
			?><div class="adminmessage4"> <?
			$inservicenumber = $_GET['inservid'];
			if ($confirmsignup=="") { //
				$sql = "SELECT * FROM track WHERE rca='".$rcauser."' AND inserviceid='".$inservicenumber."'";
				$result_set = $database->query($sql);
				if ($database->num_rows($result_set)<1){ // 
					echo "Please confirm that you would like to sign up for the following inservice.<br/><br/>";
					$sql = "SELECT * FROM inservices WHERE id='".$inservicenumber."'";
					$result_set = $database->query($sql);
					$rowstuff = $database->fetch_array($result_set);
					$ttitle=$rowstuff['title'];
					echo ($ttitle);
					echo ", ";
					echo (date('l, F j, Y, g:i a', $rowstuff['time']));
					echo "<br/><br/>";
					echo ('<a href="?confirmsup=yes&task=listind&taskrca=signup&inservid='.$inservicenumber.'">Sign me up</a><br/>');
					echo ('<a href="?task=listind">No thanks</a>');
					$showrest=false;
				} else { // if (mysql_num_rows($result)<1)
					echo "You are already signed up for that inservice.";
				} // if (mysql_num_rows($result)<1)
				
			} else { //if ($confirmsignup=="") {
				
				$sql = "INSERT INTO track (";
	  			$sql .= "rca, inserviceid";
	  			$sql .= ") VALUES ('";
				$sql .= $database->escape_value($rcauser) ."', '";
				$sql .= $database->escape_value($inservicenumber) ."')";
				if($database->query($sql)) { //
					$sql = "SELECT * FROM inservices WHERE id ='".$inservicenumber."'";
					$result_set = $database->query($sql);
					$rowstuff = $database->fetch_array($result_set);
					echo ('You are now signed up for '.$rowstuff['title'].'');
					echo (date("l, F j, Y, g:i a", $rowstuff['time'])."<br/>");
					$task="";
					$whichclass=false;
				} // if ($result)
				
			} // if ($confirmsignup=="")
			?></div> <!--- <div class="adminmessage4"> ----><? 
		} // if ($taskrca == "signup")
		
		if ($showrest) {
			?> <div class="adminmessage4"> <? 
			echo "The following in-service record is for ".$rcauser.": <br/><br/>";
				if ($dsluser=="") { // 
					echo "Click on the inservices you have yet to attend to cancel your participation.<br/><br>";
				} // if ($dsluser=="")
			$sql="SELECT * FROM track WHERE rca='".$rcauser."'";
			$result_set = $database->query($sql);
			?><table width="700" cellpadding="5" >
            <tr><td align="left">INSERVICE</td>
            <td>CORE COMPETENCY</td>
            <td>INSERVICE DATE</td>
            <td>ATTENDED</td>
            <td>SKIPPED</td>
            </tr>
    		<?
			
			while ($result_row = $database->fetch_array($result_set)){ // 
				$sql="SELECT * FROM inservices WHERE id='".$result_row['inserviceid']."'";
				$result_set = $database->query($sql);
				$rowstuff= $database->fetch_array($result_set);
				?><tr><td><?
					if (($dsluser=="") AND ($rowstuff['time']>=date("U"))){
						$ident=$rowstuff['id'];
						$thetitle=$rowstuff['title'];
						echo ('<a href="?task=listind&taskrca=edrcainserv&inservicenum='.$ident.'">'.$thetitle.'</a>');
					} else {
						echo ($rowstuff['title']);
					} // if (($dsluser=="") AND ($rowstuff['time']>=date("U")))
				?></td><td><?
				echo $rowstuff['area'];
				?></td><td><?
				echo (date("F j, Y", $rowstuff['time']));
				?></td><td><?
				if ($result_row['attended']=="Y") { // 
					echo "X";
				} // if ($result_row['attended']=="Y")
				?></td><td><?
				if (($result_row['attended']=="N") AND ($rowstuff['time'] < date("U"))) {
					echo "X";
				} // if (($result_row['attended']=="N") AND ($rowstuff['time'] < date("U")))
				?></td>
				</tr><?
			} // while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
			
			?></table>      
			</div> <!--- <div class="adminmessage4"> ----><? 
			$task="";
		} // if ($showrest)
			
	break;
	case "":
	break;	
	
	default:
		echo "yikes";
	} // switch ($task)
	
	
if ($task=="") { //

	if ($tevent != "") { //
	
		if ($_POST['morning'] == 'pm') {
			$hourstuff = $_POST['hour']+12;
		}else{
			$hourstuff = $_POST['hour'];
		} // if ($_POST['morning'] == 'pm')
			
		$ttime = mktime($hourstuff, $_POST['minute'], 0, $_POST['month'], $_POST['day1'], $_POST['year']);
		$thetitle = $_POST['title'];
		$area = $_POST['area'];
		$thesemester = $_POST['semester'];
		$thelocation = $_POST['location'];
		$thedescription = $_POST['description'];
		
		$sql = "INSERT INTO inservices (";
	  	$sql .= "title, location, description, time, semester, owner, area";
	  	$sql .= ") VALUES ('";
		$sql .= $database->escape_value($thetitle) ."', '";
		$sql .= $database->escape_value($thelocation) ."', '";
		$sql .= $database->escape_value($thedescription) ."', '";
		$sql .= $database->escape_value($ttime) ."', '";
		$sql .= $database->escape_value($thesemester) ."', '";
		$sql .= $database->escape_value($dsluser) ."', '";
		$sql .= $database->escape_value($area) ."')";
		if($database->query($sql)) { // 
			?><div id="upperbox"> <?
 			echo 'Your new In-Service has been entered and listed below with the other available In-Services. <br/>';
			?></div><?
		}  // if ($result)	
	}// if ($tevent != "")
	
	$sql = "SELECT * FROM inservices ORDER BY time";
	$result_set = $database->query($sql);
	
	if ($whichclass) { ?> <div class="adminmessage"> <? } else { ?> <div class="adminmessage2"> <? }
	
		if ($rcauser=="") { // 
			echo "Listed below are the In-Services Currently Available.<br/>Click on the Title of any In-Service to edit/delete or<br/>to check on the RCAs signed up for that event.<br/><br/>";	
		} else { // if ($rcauser=="")
		
			if ($confirmsignup=="no"){ //
				echo "Here again is the list of the available In-services. ";
			} else {
				echo "Listed below are the In-services currently available. ";
			} // if ($confirmsignup=="no")
			
			echo "Click on the title to sign-up for that in-service.<br/><br/>";		
		} // if ($rcauser=="")
	
	?> </div>
    
    <div id="admintable2"><table>
    <tr><td align="left">TITLE</td>
    <td>CORE COMPETENCY</td>
    <td>DATE & TIME</td>
    <td>LOCATION</td>
    <td>DSL IN CHARGE</td>
    </tr>
    <?
	$rowcolor=false;
			
	while ($result_row = $database->fetch_array($result_set)){ // 
		$ttitle = $result_row['title'];
		
		if ($rowcolor) {
			$rowcolor=false;
			?><tr id="evenrow"> <?
		} else {
			$rowcolor=true;
			?><tr id="oddrow"><?
		}		/* if ($rowcolor) */
		
		if ($dsluser) { //
			?><td><?
			echo '  <a href="?task=check&inservid='.$result_row['id'].'">'.$ttitle.'</a>';
			?></td><td><?
			echo ($result_row['area']);
			?></td><td><?
			echo (date("l,", $result_row['time']));
			echo "<br/>";
			echo (date("F j", $result_row['time']));
			echo "<br/>";
			echo (date("g:i a", $result_row['time']));
			?></td><td><?
			echo ($result_row['location']);
			?></td><td><?
            $dslincharge=$result_row['owner'];
			
			getdslincharge($dslincharge);
				
			
		 	?></td></tr><?
		} else { //if ($dsluser)
		
			if ($result_row['time'] >= date("U")) { // 
				?><td><?
				echo '  <a href="?task=listind&taskrca=signup&inservid='.$result_row['id'].'">'.$ttitle.'</a>';
				?></td><td><?
				echo ($result_row['area']);
				?></td><td><?
				echo (date("l,", $result_row['time']));
				echo "<br/>";
				echo (date("F j", $result_row['time']));
				echo "<br/>";
				echo (date("g:i a", $result_row['time']));
				?></td><td><?
				echo ($result_row['location']);
				?></td><td><?
                $dslincharge=$result_row['owner'];
				getdslincharge($dslincharge);
				?></td><?
			} // if ($result_row['time'] >= date("U"))
			
		} // if ($dsluser)
		
		?></tr><?
	} // while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
	
	?></table> </div><?
	
	if (!$dsluser=="") { // 
	?><div id="adminmessage3">  <?
	echo "If you would like to enter a new In-service event, please complete the form below.<br><br/>";
	
?><table><tr><td>
<form action = "<? echo $self; ?>" method="post">
Title: </td><td><input type="text" name="title" cols="50"/></td></tr>
<tr><td>Core Competency: </td><td><select name="area">
	<option selected="selected" value="">Core Competency</option>
	<option value="Diversity and Inclusion">Diversity and Inclusion</option>
	<option value="Personal Development">Personal Development</option>
	<option value="Health and Wellness">Health and Wellness</option>
	</select></td></tr>
<tr><td>Location: </td><td><input type="text" name="location"/></td></tr>
<tr><td>Semester: </td><td><select name="semester">
	<option selected="selected" value="">Semester</option>
    <option value="fall">fall</option>
    <option value="spring">spring</option>
    </select></td></tr>
<tr><td>Date: </td><td>
<select name="month">
	<option selected="selected" value="">Month</option>
    <? GetMonths(); ?>
    </select>
<select name="day1">
	<option selected="selected" value="">Day</option>
    <? GetDays(); ?>
    </select>  
<select name="year">
	<option selected="selected" value="">Year</option>
    <? GetYears(); ?> 
</select></td></tr>
<tr><td>Time:</td><td>
<select name="hour">
	<option selected="selected" value=""> </option>
    <option value="01">1</option>
    <option value="02">2</option>
    <option value="03">3</option>
    <option value="04">4</option>
    <option value="05">5</option>
    <option value="06">6</option>
    <option value="07">7</option>
    <option value="08">8</option>
    <option value="09">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="00">12</option>
    </select>
<select name="minute">
	<option selected="selected" value=""> </option>
    <option value="00">00</option>
    <option value="15">15</option>
    <option value="30">30</option>
    <option value="45">45</option>
    </select>
<select name="morning">
	<option selected="selected" value=""> </option>
    <option value="am">am</option>
    <option value="pm">pm</option>
</select></td></tr>
<tr><td>Description</td><td>
<textarea name="description" rows="3" cols="50">
</textarea></td></tr>
<input type="hidden" name="dslid" value="<? echo $dsluser; ?>"/>
<tr><td><input type="submit" value="submit" /></td></tr>
</form> </table></div>

<? } // if (!$dsluser=="") 
} // if ($task=="")
	
   ?>
                </div> <!-- <div id="calenderwork"> -->
                </div> <!-- <div id="workarea"> -->
                
 <div id="blanket" style="display: <? echo $to2display; ?>"></div>
<div class="displaymod23" style="display:<? echo $to2display; ?>">
<div id="oncalllogstuff2">
<div class="goingout">
<? echo ('<a href="?task=check&taskins=runinservice&inservid='.$theid.'">CLOSE</a>'); ?>
</div> <!-- <div id="goinout"> -->
<div id="oncallposition3">
<? 
	if ($addrcas) {
		foreach($addrcas as $value) {
		$ifattended = "Y";
		$sql = "INSERT INTO track (";
	  	$sql .= "rca, inserviceid, attended";
	  	$sql .= ") VALUES ('";
		$sql .= $database->escape_value($value) ."', '";
		$sql .= $database->escape_value($theid) ."', '";
		$sql .= $database->escape_value($ifattended) ."')";
		} // foreach($addrcas as $value)
		echo "The RCAs you checked have been added to the list and marked as attended. You can now either add<br/>
		more RCAs to the list with the form below or click 'CLOSE' above to return to the 
		list of RCAs signed<br/>up for this inservice.";
	} else { // if ($addrcas)
		echo "Listed below are all the RCAs not currently signed up for this inservice.<br/>
		Click on the RCAs who want to include and then hit 'submit' at the bottom.<br/>";
	} // if ($addrcas)
	$sql = "SELECT * FROM track WHERE inserviceid='".$theid."' ORDER BY rca";
	$result_set = $database->query($sql);
	$counter = 0;
	while ($result_row = $database->fetch_array($result_set)) {
		$RCAlist[$counter] = $result_row['rca'];
		$counter++;
	}
	
		?><form action = "<? echo $self; ?>" method="post">
        	<table cellpadding="15px">
            <tr>
            <td valign="top"><? CollegeList(Butler, $RCAlist) ?></td>
            <td valign="top"><? CollegeList(Forbes, $RCAlist) ?></td>
            <td valign="top"><? CollegeList(Mathey, $RCAlist) ?></td>
            <td valign="top"><? CollegeList(Rocky, $RCAlist) ?></td>
            <td valign="top"><? CollegeList(Whitman, $RCAlist) ?></td>
            <td valign="top"><? CollegeList(Wilson, $RCAlist) ?></td>
            </tr>
            <tr><td>
            <input type="hidden" name="inservid" value="<? echo $theid; ?>"/>
			<input type="hidden" name="t2display" value="yes"/>
			<input type="submit" value="submit" />
            </td></tr>
            </table></form>      
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
		echo "The following in-service record is for ".$rcaperson.": <br/><br/>";
		if ($dsluser=="") { // 
			echo "Click on the inservices you have yet to attend to cancel your participation.<br/><br>";
		} // if ($dsluser=="")
		$sql="SELECT * FROM track WHERE rca='".$rcaperson."' ORDER BY inserviceid";
		$result_set = $database->query($sql);
		?><table width="900" cellpadding="5" >
            <tr><td align="left">INSERVICE</td>
            <td>CORE COMPETENCY</td>
            <td>INSERVICE DATE</td>
            <td>ATTENDED</td>
            <td>SKIPPED</td>
            </tr>
    		<?
			while ($result_row = $database->fetch_array($result_set)){ // 
				$query="SELECT * FROM inservices WHERE id='".$result_row['inserviceid']."'";
				$result_set = $database->query($sql);
				$rowstuff=$database->fetch_array($result_set);
				?><tr><td><?
				echo ($rowstuff['title']);
				?></td><td><?
				echo ($rowstuff['area']);
				?></td><td><?
				echo (date("n/j/Y", $rowstuff['time']));
				?></td><td><?
				if ($result_row['attended']=="Y") { // 
					echo "X";
				} // if ($result_row['attended']=="Y")
				?></td><td><?
				if (($result_row['attended']=="N") AND ($rowstuff['time'] < date("U"))) {
					echo "X";
				} // if (($result_row['attended']=="N") AND ($rowstuff['time'] < date("U")))
				?></td>
				</tr><?
			} // while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
			?></table>      
			</div> <? 
?>
</div> <!-- <div id="oncallposition"> -->
</div> <!-- <div id="oncallstuff"> -->
</div> <!-- <div class="displaymod2" : > -->
<?   
if ($dsluser) {
	?><div id="oncallpdf"><? echo ('<a href="pdfs/signup.pdf">RCA SIGN-IN SHEET</a>'); ?></div><?
}
 ?>
<? 
 }
include("../includes/layouts/footer.php"); ?>

