<? require_once("../includes/functions.php"); 
include("../includes/layouts/header.php"); 
if (isset($_SESSION["casnetid"])) {
include("../includes/layouts/setdsl.php");  
include("../includes/layouts/menu.php"); 

?>
    
    <div id="workarea">
    <div id="calenderwork">
    
    <?php
	
$self = htmlentities($_SERVER['PHP_SELF']);
	@mysql_connect("localhost", "matheyhp", "*4MatheyC011ege")
		or die("Could not connect");
	@mysql_select_db("matheyhp_rcasite") 
		or die ("could not connect to database.");


$task=isset($_GET['task']) ? $_GET['task'] : "";
	if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "";
$tdisplay=isset($_GET['tdisplay']) ? $_GET['tdisplay'] : "";
	if (!$tdisplay) $tdisplay=isset($_POST['tdisplay']) ? $_POST['tdisplay'] : "";	
$whichorderin=isset($_GET['whichorderin']) ? $_GET['whichorderin'] : "";
	if (!$whichorderin) $whichorderin=isset($_POST['whichorderin']) ? $_POST['whichorderin'] : "";	
		$signupfor=isset($_POST['signupfor']) ? $_POST['signupfor'] : "";
		$month=isset($_POST['month']) ? $_POST['month'] : "";
		$day=isset($_POST['day']) ? $_POST['day'] : "";
		$overallrate=isset($_POST['overallrate']) ? $_POST['overallrate'] : "";
		$numstudents=isset($_POST['numstudents']) ? $_POST['numstudents'] : "";
		$comments=isset($_POST['comments']) ? $_POST['comments'] : "";
		$otheroptions=isset($_POST['otheroptions']) ? $_POST['otheroptions'] : "";

//-------------------determine the college-------------------------//
if ($dsluser) {
	$whichcollege=getdslcollege($dsluser);
} else {
	$query = "SELECT * FROM rca WHERE netid='".$rcauser."'";
	$result = mysql_query($query);
	$result_row =  mysql_fetch_array ($result, MYSQL_ASSOC);
	$whichcollege = $result_row['college'];
} // if ($dsluser) 
//-------------------determine the college-------------------------//

//----------enter RCA eval before loaded menu so ratings updated----//
		if ($task=='rcaeval') {
			$query = "UPDATE orderintrack SET  numstudents='".$numstudents."', overallrate='".$overallrate."', comments='".$comments."', otheroptions='".$otheroptions."' WHERE rcaid='".$rcauser."'";
			$result=mysql_query($query);
			if ($result) { 
				$queryc = "SELECT * FROM rca WHERE netid='".$rcauser."'";
				$resultc=mysql_query($queryc);
				$rowstuff=mysql_fetch_array($resultc, MYSQL_ASSOC);
				$whichcollege=$rowstuff['college'];
				switch ($whichcollege) {
						case "Forbes":
						$dslmail = "mellisat";
						break;
						case "Mathey":
						$dslmail = "mfrawley";
						break;
						case "Rocky":
						$dslmail = "ksisti";
						break;
						case "Butler":
						$dslmail = "aandres";
						break;
						case "Whitman":
						$dslmail = "dwessman";
						break;
						case "Wilson":
						$dslmail = "aaking";
						break;				
						default:
						echo "yikes";
					} // switch ($whichcollege)	
					$queryc = "SELECT * FROM orderintrack WHERE rcaid='".$rcauser."'";
					$resultc=mysql_query($queryc);
					$rowstuffc=mysql_fetch_array($resultc, MYSQL_ASSOC);
					$querya = "SELECT * FROM orderins WHERE numorderin='".$rowstuffc['orderinnum']."'";
					$resulta=mysql_query($querya);
					$rowstuffa=mysql_fetch_array($resulta, MYSQL_ASSOC);
					$to = $dslmail."@princeton.edu";
					$subject = "RCA Order-In Evalution for: ".$rcauser;
					$message = 'TYPE: '.$rowstuffa['type']."\r\n"."\r\n";
					$message .= 'TITLE: '.$rowstuffa['title']."\r\n"."\r\n";
					$message .= 'Date of Order-In: '.date("F j, Y", $rowstuffc['datedone'])."\r\n"."\r\n";
					$message .= 'NUMBER OF STUDENTS ATTENDED: '.$rowstuffc['numstudents']."\r\n"."\r\n";
					$message .= 'OVERALL RATING (0 to 5): '.$rowstuffc['overallrate']."\r\n"."\r\n";
					$message .= 'COMMENTS:  '.$rowstuffc['comments']."\r\n"."\r\n";
					$message .= 'SUGGESTIONS FOR OTHER ORDER-INS: '.$rowstuffc['otheroptions']."\r\n"."\r\n";
					$headers = "From: ".$rcauser."@princeton.edu"."\r\n"."Reply-To: ".$rcauser."@princeton.edu"."\r\n"."X-mailer:PHP/".phpversion();
					mail ($to, $subject, $message, $headers);
			}
		} //if ($rcaeval)
//-------------------------------------------------------//
if ($dsluser) {
	?><div id="ordermenu"><ul><?
		if ($task=="listrcas") {
			?><li>LIST RCAS</li>
			<li><a href="?task=listorderins&dslid=<? echo $dsluser; ?>">LIST ORDER-INS</a></li>
            <?
		} else {  // if ($task=="listrcas")
			?><li><a href="?task=listrcas&dslid=<? echo $dsluser; ?>">LIST RCAs</a></li>
			<li>LIST ORDER-INS</li>
            <?
		} // if ($task=="listrcas")
	
	?></ul></div><?
	
}

if ($task=="listrcas") {
	$query="SELECT * FROM rca WHERE college='".$whichcollege."' GROUP BY netid ORDER BY netid";
	$result = mysql_query($query);
	?><div id="counttable0"><table width="720" cellpadding="5" >
            <tr><td>NAME</td><td>  </td>
            <td>TYPE</td>
            <td>TITLE</td>
            <td>DATE SELECTED</td>
            <td>DATE OF ORDER-IN</td>
            <td>RATING</td>
            </tr>
            <tr><td></td></tr>
    		<?
			while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC)){
				$query1="SELECT * FROM orderintrack WHERE rcaid='".$result_row['netid']."'";
				$result1 = mysql_query($query1);
				$result_row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
				$query2="SELECT * FROM orderins WHERE numorderin='".$result_row1['orderinnum']."'";
				$result2 = mysql_query($query2);
				$result_row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
				?><tr><td><?
				if (($result_row1['datedone']=="") AND ($result_row1['overallrate']=="")) {
					echo $result_row['netid'];
				}else{  //if (($result_row1['datedone']=="") AND ($result_row1['overallrate']==""))
					if ($result_row1['datedone'] < date('U')) {
						echo $result_row['netid'];
					}else{ //if ($result_row1['datedone'] < date('U'))
					echo ('<a href="?tdisplay=1&dslid='.$dsluser.'&whichrca="'.$result_row['netid'].'">'.$result_row['netid'].'</a>');
					} //if ($result_row1['datedone'] < date('U'))
				}  //if (($result_row1['datedone']=="") AND ($result_row1['overallrate']==""))
				?></td><td>  </td>
                <? if ($result_row2['title']=="") {
					?> </tr> <?
					
				} else { // if ($result_row2['title']=="")				
				?><td><?
					echo $result_row2['type'];
				?></td><td><?
					echo $result_row2['title'];
				?></td><td><?
					echo date ("F d, Y", $result_row1['datesignup']);	
				?></td><td><?
					echo date ("F d, Y", $result_row1['datedone']);	
				?></td><td><?
					if ($result_row1['overallrate'] !="") {
					echoacorns($result_row1['overallrate']);
					}
				?></td></tr><?
				} // if ($result_row2['title']=="")
			} //while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
			?></table></div>  <!---- <div id="counttable0">  ---->
			<?
} else {  //if ($task=="listrcas")
		if ($dsluser) {
			$type=isset($_POST['type']) ? $_POST['type'] : "";
			$updatetask=isset($_GET['updatetask']) ? $_GET['updatetask'] : "";
				if (!$updatetask) $updatetask=isset($_POST['updatetask']) ? $_POST['updatetask'] : "";
			
			$othertype=isset($_POST['othertype']) ? $_POST['othertype'] : "";
			$title=isset($_POST['title']) ? $_POST['title'] : "";
			$description=isset($_POST['description']) ? $_POST['description'] : "";
			$contact=isset($_POST['contact']) ? $_POST['contact'] : "";
			$tryagain=isset($_POST['tryagain']) ? $_POST['tryagain'] : "";
			$update='';
			if ($othertype){  //need to have type set to options or new type, if not then just go to form again.
				$type=$othertype;
			} 

				if ($updatetask) {
					if ($updatetask=='delete') {
							$query = "DELETE FROM orderins WHERE numorderin = '".$whichorderin."'";
							$result=mysql_query($query);
							if ($result) {$updated="deleted";}
					} else { //if ($updatetask=='delete')
							$query = "UPDATE orderins SET  type='".$type."', title='".$title."', description='".$description."', contact='".$contact."' WHERE numorderin = '".$whichorderin."'";
							$result=mysql_query($query);
							if ($result) {$updated="updated";}
					} // if ($updatetask=='delete')
				} else { //if ($updatetask)
					if (($title) AND ($description)) {
							$query="INSERT INTO orderins SET type='".$type."', title='".$title."', description='".$description."', contact='".$contact."'";
							$result=mysql_query($query);
							if ($result) {
								$tryagain="no";
								$task="";
								$type="";
								$othertype="";
								
								$description='';
								$contact="";
							}
					}//if (($title) AND ($description))
				} //if ($updatetask)

		} //if ($dsluser)

		$query="SELECT * FROM orderins ORDER BY type";
		$result = mysql_query($query);
		if ($result) {
			?><div class="adminmessage"><table width="800px">
            <tr><td>TYPE</td>
            <td>TITLE</td>
            <td>RATING</td>
            <td>#RCAs</td>
            </tr>
            <?
				$thetype="";
				while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC)){
						?><tr><td><?
						if ($thetype!=$result_row['type']){
							?></td></tr><tr></tr><tr><td width="175px"><?
							echo $result_row['type'];	
							$thetype=$result_row['type'];
						}// if ($thetype!=$result_row['type'])
					?></td><td width="350px"><?
						echo ('<a href="?task=indorderin&rcaid='.$rcauser.'&dslid='.$dsluser.'&whichorderin='.$result_row['numorderin'].'">'.$result_row['title'].'</a>');//if dsl, clicking allows for editing, for rca they can sign up.
					?></td><td><?
						$numberacorns=getrating($result_row['numorderin']);
						echoacorns($numberacorns);
					?></td><td><?	
						$query1="SELECT COUNT(rcaid) AS numrca FROM orderintrack WHERE orderinnum='".$result_row['numorderin']."'";
						$result1= mysql_query($query1);
						$result_row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
						echo $result_row1['numrca'];
					?></td></tr><?
					
				} //while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
			?></table><?
		} // if ($result)  only echo table if have orderins listed in database
	if ($dsluser) {
		
		if ($task=='indorderin') {
			if ($updated !="") {
				if ($updated=="updated") {
						echo "<br/><br/>The inservice has been updated. The updated version is shown below in the form.";
					} else { //if ($updated=="updated")
						echo "<br/><br/>As you can see from the menu above, the inservice has been deleted.";
						$task="";
					} //if ($updated=="updated")
				} else {
				$query1="SELECT * FROM orderins WHERE numorderin='".$whichorderin."'";
				$result1 = mysql_query($query1);
				$result_row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
				$type=$result_row1['type'];
				$title=$result_row1['title'];
				$description=$result_row1['description'];
				$contact=$result_row1['contact'];
				if ($tryagain != 'yes') {
					echo "<br/><br/>You can make edits to the Order-In in the form below. If you would like to delete this order-in, click the delete button below the form.<br/>";
				} else { //if ($tryagain != 'yes')
					echo "<br/><br/>Your edits were incomplete, please make sure that every line of the form is complete.";
				}//if ($tryagain != 'yes')
			} //if ($updated !="")
		}//if ($task=='indorderin')
		if (($tryagain=='yes') AND (!$task)){
			echo "<br/><br/>Before we can enter the new order-in into the database, please make sure that you have completed the entire form.";
		} else { //(($tryagain=='yes') AND (!$task))
			if ($tryagain=='no') {
				echo "<br/><br/>The new order-in, entitled, <strong>".$title."</strong>, has been entered into the database as you can see from the list of order-ins above.</br>";
				$title="";
				}
			if ($task !="indorderin") {
			echo "<br/>If you would like to enter a new order-in, please complete the form below.<br/><br/>";
			}
		} //if (($tryagain=='yes') AND (!$task))
		if (!$type) {$type="";}
		if (!$othertype) {$othertype="";}
		$query="SELECT DISTINCT type FROM orderins ORDER BY type";
		$result = mysql_query($query);
		?><table width="700" cellpadding="4" >
        <form id="mainform"  action = "<? echo $self; ?>" method="post">
            <tr><td>TYPE</td>
            <td> <select name="type" onchange="tdisplay(this)">
					<option selected="selected" value="<? echo $type; ?>"><? echo $type; ?></option>
                    <? 
						while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC)){
							if ($result_row['type'] != $type){
						?><option value="<? echo $result_row['type']; ?>"><? echo $result_row['type']; ?></option><?
							}//if ($result_row['type'] != $type)
						} //while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))?>
                       
                        <option value="other">OTHER</option>
                        
					</select>
            </td></tr>
            
            <tr id="othertyped" style="display:none"><td>OTHER TYPE</td>
            <td><input type="text" name="othertype" value="<? echo $othertype; ?>"/></td>
            </tr>
           
            <tr><td>TITLE</td>
            <td><input type="text" name="title" value="<? echo $title; ?>"/></td>
            </tr>
            <tr> <td>DESCRIPTION</td>
            <td> <textarea name="description" rows="5" cols="40"> <? echo $description; ?> </textarea>
            </td></tr>
            <tr><td>CONTACT</td>
            <td><input type="text" name="contact" value="<? echo $contact; ?>"/></td>
            </tr>
			<input type="hidden" name="dslid" value="<? echo $dsluser; ?>"/>
			<input type="hidden" name="task" value="<? echo $task; ?>"/>
            <? if ($task=="indorderin") {
				?>
            <input type="hidden" name="updatetask" value="edit"/>
			<? } ?>
			
            <input type="hidden" name="whichorderin" value="<? echo $whichorderin; ?>"/>
            <input type="hidden" name="tryagain" value="yes"/>
            <tr><td><input type="submit" value="submit" /></td></tr></form></table><?
			if ($task=="indorderin") {
				echo ('<br/><a href="?task=indorderin&updatetask=delete&dslid='.$dsluser.'&whichorderin='.$whichorderin.'">DELETE THIS ORDER-IN</a>');
				echo ('<br/><a href="?task=notlist&dslid='.$dsluser.'">CLEAR FORM</a>');
			}
	}else{ //if ($dsluser)  else is RCA section

		if ($signupfor) {
			$currenttime = date("U");
			$theyear = date("Y");
			$dateoford = mktime (1,0,0,$month,$day,$theyear);
			$queryr1="SELECT * FROM orderintrack WHERE rcaid='".$rcauser."' ";
			$resultr1=mysql_query($queryr1);
			$rowstuffrca1=mysql_fetch_array($resultr1, MYSQL_ASSOC);
			if ($rowstuffrca1['rcaid'] !="") {
				$query = "UPDATE orderintrack SET  orderinnum='".$whichorderin."', datesignup='".$currenttime."', datedone='".$dateoford."' WHERE rcaid='".$rcauser."'";
			} else {
				$query="INSERT INTO orderintrack SET rcaid='".$rcauser."', orderinnum='".$whichorderin."', datesignup='".$currenttime."', datedone='".$dateoford."'";
			}
			$result=mysql_query($query);
			
		} // if ($signupfor) 

		
		$querye="SELECT * FROM orderintrack WHERE rcaid = '".$rcauser."'";
		$resulte=mysql_query($querye);
		$rowstuffrca=mysql_fetch_array($resulte, MYSQL_ASSOC);
		if ($rowstuffrca['rcaid'] !='') {
			echo "<br/><br/>Below is the order-in for which you are currently signed-up. ";
			if ($rowstuffrca['overallrate'] == "") {
				echo "<br/>If you would like to change order-ins, simply click on the desired order-in title above.<br/><br/>";	
			} // ($rowstuffrca['overallrate'] == "")
			$query="SELECT * FROM orderins WHERE numorderin = '".$rowstuffrca['orderinnum']."'";
			$result=mysql_query($query);
			$rowstuff=mysql_fetch_array($result, MYSQL_ASSOC);
			?>
			<table width="600" cellspacing="5">
			<tr>
			<td>TYPE</td>
			<td>TITLE</td>
			<td>DATE SIGNED-UP</td>
			<td>DATE OF ORDER-IN</td>
			</tr><tr>
			<td><? echo $rowstuff['type'];  ?></td>
			<td><? echo $rowstuff['title'];  ?></td>
			<td><? echo date ("F j, Y", $rowstuffrca['datesignup']);  ?></td>
			<td><? echo date ("F j, Y", $rowstuffrca['datedone']);  ?></td>
			</tr>
			</table>
			<?
			if ($rowstuffrca['overallrate'] =='') {
				echo "<br/><br/>Below is an evaluation of the order-in we ask that you complete after the event. Thanks!<br/><br/>";
			}else{ // if ($rowstuffrca['overallrate'] =='')
				echo "<br/><br/>Thanks for completing the order-in evaluation. If you would like to change anything, please make whatever edits you want below and hit SUBMIT.<br/><br/>";
			} // if ($rowstuffrca['overallrate'] =='')
			
			?><table>
            <form action = "<? echo $self; ?>" method="post">
                       <tr><td>Overall Rating(0 to 5): </td><td>
                       <select name="overallrate">
                       <option selected="selected" value="<? echo $rowstuffrca['overallrate']; ?>"><? echo $rowstuffrca['overallrate']; ?></option>
                       <?
					   for($i=0; $i<=5;$i++){
						   ?><option value="<? echo $i; ?>"><? echo $i; ?></option><?
					   } ?>
					   </select>
					   </td></tr><tr><td>Number of Students Attended: </td><td>
                       <select name="numstudents">
                       <option selected="selected" value="<? echo $rowstuffrca['numstudents']; ?>"><? echo $rowstuffrca['numstudents']; ?></option>
                       <?
					   for($i=1; $i<=30;$i++){
						   ?><option value="<? echo $i; ?>"><? echo $i; ?></option><?
					   } ?>
					   </select></td></tr>
                       <tr> <td valign="top">Comments on Order-In: </td>
            			<td> <textarea name="comments" rows="5" cols="40"> <? echo $rowstuffrca['comments']; ?> </textarea>
                        </td></tr>
                       <tr> <td valign="top">Suggestions for other order-ins: </td>
            			<td> <textarea name="otheroptions" rows="5" cols="40"> <? echo $rowstuffrca['otheroptions']; ?> </textarea>
                        </td></tr>
                       <input type="hidden" name="rcaid" value="<? echo $rcauser; ?>"/>
                       <input type="hidden" name="task" value="rcaeval"/>
              			<input type="hidden" name="whichorderin" value="<? echo $whichorderin; ?>"/>
                        <tr><td><input type="submit" value="submit" /></td></tr>
                       </form>
</table>
            <?
		} // if ($rowstuffrca['rcaid'] !='') 
		
	}//if ($dsluser)
	?></div><?
} //if ($task=="listrcas")
   ?>
                </div> <!-- <div id="calenderwork"> -->
                </div> <!-- <div id="workarea"> -->



<?  
if (($rcauser) AND ($task=='indorderin')) {
	$todisplay = "block";
} else {
	$todisplay="none";
}

?>
<div id="blanket" style="display: <? echo $todisplay; ?>"></div>
<div class="displaymod2" style="display:<? echo $todisplay; ?>">
<div id="oncalllogstuff2">
<div class="goingout">
<? echo ('<a href="?rcaid='.$rcauser.'">CLOSE</a>'); ?>
</div> <!-- <div id="goinout"> -->
<div id="oncallposition2">

<? 
			$querye="SELECT * FROM orderins WHERE numorderin = '".$whichorderin."'";
			$resulte=mysql_query($querye);
			$rowstuffrca=mysql_fetch_array($resulte, MYSQL_ASSOC);
					?> 
					<table width="700" cellspacing="5">
					<tr><td>TYPE:</td><td>
						<?
						echo $rowstuffrca['type'];
						?>
						</td></tr>
                        <tr><td>
						TITLE:</td><td>
						<?
						echo $rowstuffrca['title'];
						?>
						</td></tr>
                        <tr><td valign="top">
						DESCRIPTION:
						</td><td>
                        <?
						echo $rowstuffrca['description'];
						?>
                        </td></tr>
                        <tr><td>
						CONTACT:
						</td><td>
                        <?
						echo $rowstuffrca['contact'];
						?>
                        </td></tr>
						<form action = "<? echo $self; ?>" method="post">
                       <td></td><td> <input type="checkbox" name="signupfor" value="youbet"> Yes, sign me up for this order-in.</td></tr>
                       <td>Anticipated Date of Order-in:</td><td>
                       	<select name="month">
                       <?
					   $whenentered=date("U");
					   $thismonth= (int)date('m', $whenentered);
					   if ($thismonth>06) {$thismonth=01;}
					   $theyear = date("Y");
					   for($i=$thismonth; $i<=6;$i++){
						  $thetime=mktime(1,0,0,$i, 1, $theyear);
						  $thetime2=date('F', $thetime);
						   ?><option value="<? echo $i; ?>"><? echo $thetime2; ?></option><?
					   } ?>
					   </select>
					   	<select name="day">
                       <? GetDays() ?>
					   </select></td></tr>
                       <input type="hidden" name="rcaid" value="<? echo $rcauser; ?>"/>
                       <input type="hidden" name="task" value="rcaorderin"/>
              			<input type="hidden" name="whichorderin" value="<? echo $whichorderin; ?>"/>
                        <tr><td><input type="submit" value="submit" /></td></tr>
                       </form>
</table>
</div> <!-- <div id="oncallposition2"> -->
</div> <!-- <div id="oncallstuff"> -->
</div> <!-- <div class="displaymod2" : > -->     

<? 
}
include("../includes/layouts/footer.php"); ?>
