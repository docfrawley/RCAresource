<? require_once("../includes/functions.php"); ?>
<? include_once("../includes/rcalogin.php"); ?>
<? include("../includes/layouts/header.php"); ?>


<?php
$self = htmlentities($_SERVER['PHP_SELF']);
$task=isset($_GET['task']) ? $_GET['task'] : "" ;
$taskadmin=isset($_GET['tasksecond']) ? $_GET['tasksecond'] : "" ;
if (!$taskadmin) $taskadmin=isset($_POST['tasksecond']) ? $_POST['tasksecond'] : "" ;
$dsluser=isset($_GET['dslid']) ? $_GET['dslid'] : "" ;
if (!$dsluser) $dsluser=isset($_POST['dslid']) ? $_POST['dslid'] : "" ;
$college=isset($_GET['college']) ? $_GET['college'] : "" ;
if (!$college) $college=isset($_POST['college']) ? $_POST['college'] : "" ;
$taskedit=isset($_POST['taskthird']) ? $_POST['taskthird'] : "" ;
if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
$submitteda = true;
					@mysql_connect("localhost", "matheyhp", "*4MatheyC011ege")
					or die("Could not connect");
					@mysql_select_db("matheyhp_rcasite") 
					or die ("could not connect to database.");
switch ($task) {
	case "" :
		$backformore=isset($_GET['backagain']) ? $_GET['backagain'] : "" ;
		$submitted=isset($_GET['userid']) ? $_GET['userid'] : "" ;
		if (!$submitted) $submitted=isset($_POST['userid']) ? $_POST['userid'] : "" ;
		if ($submitted) {
			$dslsrock = array ("mfrawley", "mellisat", "amyham", "aaking", "momo", "aandres", "molin");
			$dsl = (in_array($submitted, $dslsrock));
				if (!$dsl) {
					$query = sprintf("SELECT * FROM rca WHERE netid='%s'",
						mysql_real_escape_string($submitted));
					$result = mysql_query($query);
					$rowstuff = mysql_fetch_array ($result, MYSQL_ASSOC);
								$college = $rowstuff['college'];
					$nonrca = (mysql_num_rows($result)<1);
						if ($nonrca) { //currently not set to get $college for nonrcas
							$query = sprintf("SELECT * FROM otheradmin WHERE netid='%s'",
							mysql_real_escape_string($submitted));
							$result = mysql_query($query);
							$nonadmin = (mysql_num_rows($result)<1);
								if ($nonadmin){
								$submitted = false;
								$submitteda = false;
								} else {
								$rowstuff = mysql_fetch_array ($result, MYSQL_ASSOC);
								} // if ($nonadmin)
						} //if ($nonrca)
				} else {// if (!$dsl)
					switch ($submitted){ // 
						case "mfrawley":
						$college = "Mathey";
						break;
						case "mellisat":
						$college = "Forbes";
						break;
						case "amyham":
						$college = "Rocky";
						break;
						case "aaking":
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
					} // switch ($submitted)
				}
			if ($submitted) {
				?>
                <div id="workarea">
                <div id="workarearelative">
               <? if ($dsl) {  
			   		$numentry=isset($_GET['numentry']) ? $_GET['numentry'] : "" ;
						if (!$numentry) $numentry=isset($_POST['numentry']) ? $_POST['numentry'] : "" ;
					$whichform=isset($_GET['whichform']) ? $_GET['whichform'] : "" ;
						if (!$whichform) $whichform=isset($_POST['whichform']) ? $_POST['whichform'] : "" ;	
					$thetitle=isset($_POST['thetitle']) ? $_POST['thetitle'] : "" ;	
					$tbody=isset($_POST['tbody']) ? $_POST['tbody'] : "" ;	
					$year=isset($_POST['year']) ? $_POST['year'] : "" ;	
					$month=isset($_POST['month']) ? $_POST['month'] : "" ;	
					$posted=isset($_POST['posted']) ? $_POST['posted'] : "" ;
					$day=isset($_POST['day']) ? $_POST['day'] : "" ;	
					$taskannounce=isset($_GET['taskannounce']) ? $_GET['taskannounce'] : "" ;
						if (!$taskannounce) $taskannounce=isset($_POST['taskannounce']) ? $_POST['taskannounce'] : "" ;
					$thecollege=isset($_POST['thecollege']) ? $_POST['thecollege'] : "" ;	
					if (($year) AND ($month) AND ($day)) { 
						$whenend = mktime(0, 0, 0, $month, $day, $year);
						echo $whenend;
					} //if (($year) AND ($month) AND ($day))
			   ?>
                <div class="adminmessage6">
                <?
					echo "ANNOUNCEMENT FORM<br/>";
					if (($whichform=='announce') AND ($taskannounce)) {
						if ($numentry) { //if numentry then row already there so must be update or delete not insert
						  if ($taskannounce=="deleteannounce") {
							  $queryz = "DELETE FROM rcaannounce WHERE numentry = '".$numentry."'";
							  $resultz = mysql_query ($queryz);
							  echo "<br/>Announcement has been deleted.";
						  } else {
							$queryz = "UPDATE rcaannounce SET thetitle='".$thetitle."', tbody ='".$tbody."', college ='".$thecollege."', whenend='".$whenend."', posted='".$posted."' WHERE numentry='".$numentry."'";
							$resultz = mysql_query ($queryz);
							echo "<br/>Announcement has been updated.";
						  }
						} else {
							$queryz = "INSERT INTO rcaannounce SET thetitle='".$thetitle."', tbody ='".$tbody."', college ='".$thecollege."', whenend='".$whenend."', posted='".date('U')."'";
							$resultz = mysql_query ($queryz);
							echo "<br/>Announcement has been posted.";
						}
						if ($taskannounce!='deleteannounce') {
							if ($thecollege==$college) {
								$querym = "SELECT * FROM rca WHERE college='".$thecollege."'";
							} else {//if ($thecollege=='ALL')
								$querym = "SELECT * FROM rca";
							}//if ($thecollege=='ALL')
							$resultm = mysql_query($querym);
							$to="";
							while ($result_row = mysql_fetch_array($resultm, MYSQL_ASSOC)){ //
							$to .= $result_row['netid']."@princeton.edu, ";
							} //while ($result_row = mysql_fetch_array($resultm, MYSQL_ASSOC))
							
						$subject = $thetitle;
						$message1 = $tbody."\r\n";
						$thesender = $submitted."@princeton.edu";
						$headers = "From: ".$thesender."\r\n"."Reply-To: ".$thesender."\r\n"."X-mailer:PHP/".phpversion();
						mail ($to, $subject, $message1, $headers);	
						}
						$numentry="";
					} // if (($whichform=='announce') AND ($taskannounce))
					if (($numentry) AND ($whichform=='announce')) {
						$query = "SELECT * FROM rcaannounce WHERE numentry='".$numentry."'";
						$result = mysql_query($query);
						$rowstuff = mysql_fetch_array($result, MYSQL_ASSOC);
						$athetitle = $rowstuff['thetitle'];
						$atbody= $rowstuff['tbody'];
						$ayear= date('Y', $rowstuff['whenend']);
						$amonth= date('F', $rowstuff['whenend']);
						$anummonth=date('m', $rowstuff['whenend']);
						$aday= date('d', $rowstuff['whenend']);
						$athecollege=$rowstuff['college'];
						$posted=$rowstuff['posted'];
					} else {
						
						$athetitle = "";
						$atbody="";
						$ayear="";
						$amonth="";
						$aday="";
						$athecollege="";
						$posted=date('U');
						
					}
				
					?><br/><form action = "<? echo $self; ?>" method="post">
                    <table><tr><td>Title:</td><td><input type="text" name="thetitle" value="<? echo $athetitle; ?>"/></td></tr></table>
					<table><tr><td>Announcement:</td><tr>
                    <tr><td> <textarea name="tbody" rows="7" cols="40"><? echo $atbody; ?>
							</textarea></td></tr></table>
                            <table><tr><td>Date When Take Down Post</td></tr></table>
                            <table><tr><td>Month</td><td>Day</td><td>Year</td></tr>
                            <tr><td>
						<select name="month">
							<option selected="selected" value="<? echo $anummonth; ?>"><? echo $amonth; ?></option>
							<option value="01">January</option>
							<option value="02">February</option>
							<option value="03">March</option>
							<option value="04">April</option>
							<option value="05">May</option>
							<option value="06">June</option>
							<option value="07">July</option>
							<option value="08">August</option>
							<option value="09">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
							</select></td><td>
						<select name="day">
							<option selected="selected" "<? echo $aday; ?>"><? echo $aday; ?></option>
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
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option> 
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
							</select>  </td><td>
							<select name="year">
								<option selected="selected" value="<? echo $ayear; ?>"><? echo $ayear; ?></option>
								<option value="2012">2012</option>  
								<option value="2013">2013</option>  
							</select></td></tr></table>
							<table><tr><td> This Announcement is for:</td><td>
                            <? if ($athecollege =="All") { 
								?> <input type="radio" name="thecollege" value="All" checked/> All RCAs <br/><?
							}else {
								?> <input type="radio" name="thecollege" value="All"/> All RCAs <br/> <?
							}
							if ($athecollege ==$college) { 
								?> <input type="radio" name="thecollege" value="<? echo $college; ?>" checked/> <? echo $college; ?> RCAs<?
							}else {
								?> <input type="radio" name="thecollege" value="<? echo $college; ?>"/> <? echo $college; ?> RCAs <?
							}
							?></td></tr>
							<input type="hidden" name="numentry" value="<? echo $numentry; ?>"/>
							<input type="hidden" name="userid" value="<? echo $submitted; ?>"/>
							<input type="hidden" name="posted" value="<? echo $posted; ?>"/>
							<input type="hidden" name="taskannounce" value="addedit"/>
							<input type="hidden" name="whichform" value="announce"/>
							<tr><td><input type="submit" value="submit" /></td></tr></table>
							</form><?
							if (($numentry) AND ($whichform=='announce')) {
								echo ("<a href='?numentry=".$numentry."&taskannounce=deleteannounce&userid=".$submitted."&whichform=announce'>".'DELETE THIS EVENT'."</a>"); 
							} //
				?>
                </div> <!--- <div class="adminmessage6"> --->
                <div class="adminmessage66">
                <?
					echo "CALENDAR FORM<br/>";
					if (($whichform=='calendar') AND ($taskannounce)) {
						if ($numentry) { //if numentry then row already there so must be update or delete not insert
						  if ($taskannounce=="deleteevent") {
							  $queryz = "DELETE FROM rcacallendar WHERE numentry = '".$numentry."'";
							  $resultz = mysql_query ($queryz);
							  echo "<br/>Event has been deleted.";
						  } else {
							$queryz = "UPDATE rcacallendar SET thetitle='".$thetitle."', tbody ='".$tbody."', college ='".$thecollege."', thedate='".$whenend."' WHERE numentry='".$numentry."'";
							$resultz = mysql_query ($queryz);
							echo "<br/>Event has been updated.";
						  }
						} else {
							$queryz = "INSERT INTO rcacallendar SET thetitle='".$thetitle."', tbody ='".$tbody."', college ='".$thecollege."', thedate='".$whenend."'";
							$resultz = mysql_query ($queryz);
							echo "<br/>Event has been posted.";
						}
						$numentry="";
					} // if (($whichform=='announce') AND ($taskannounce))
					if (($numentry) AND ($whichform=='calendar')) {
						$query = "SELECT * FROM rcacallendar WHERE numentry='".$numentry."'";
						$result = mysql_query($query);
						$rowstuff = mysql_fetch_array($result, MYSQL_ASSOC);
						$thetitle = $rowstuff['thetitle'];
						$tbody= $rowstuff['tbody'];
						$year= date('Y', $rowstuff['thedate']);
						$month= date('F', $rowstuff['thedate']);
						$nummonth=date('m', $rowstuff['thedate']);
						$day= date('d', $rowstuff['thedate']);
						$thecollege=$rowstuff['college'];
					} else {
						$thetitle = "";
						$tbody="";
						$year="";
						$month="";
						$day="";
						$thecollege="";
					}
				
					?><br/><form action = "<? echo $self; ?>" method="post">
                    <table><tr><td>Event:</td><td><input type="text" name="thetitle" value="<? echo $thetitle; ?>"/></td></tr></table>
					<table><tr><td>Info about Event:</td><tr>
                    <tr><td> <textarea name="tbody" rows="7" cols="40"><? echo $tbody; ?>
							</textarea></td></tr></table>
                            <table><tr><td>Date of Event</td></tr></table>
                            <table><tr><td>Month</td><td>Day</td><td>Year</td></tr>
                            <tr><td>
						<select name="month">
							<option selected="selected" value="<? echo $nummonth; ?>"><? echo $month; ?></option>
							<option value="01">January</option>
							<option value="02">February</option>
							<option value="03">March</option>
							<option value="04">April</option>
							<option value="05">May</option>
							<option value="06">June</option>
							<option value="07">July</option>
							<option value="08">August</option>
							<option value="09">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
							</select></td><td>
						<select name="day">
							<option selected="selected" "<? echo $day; ?>"><? echo $day; ?></option>
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
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option> 
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
							</select>  </td><td>
							<select name="year">
								<option selected="selected" value="<? echo $year; ?>"><? echo $year; ?></option>
								<option value="2012">2012</option>  
								<option value="2013">2013</option>  
							</select></td></tr></table>
							<table><tr><td> This Event is for:</td><td>
                           <? if ($thecollege =="All") { 
								?> <input type="radio" name="thecollege" value="All" checked/> All RCAs <br/><?
							}else {
								?> <input type="radio" name="thecollege" value="All"/> All RCAs <br/> <?
							}
							if ($thecollege ==$college) { 
								?> <input type="radio" name="thecollege" value="<? echo $college; ?>" checked/> <? echo $college; ?> RCAs<?
							}else {
								?> <input type="radio" name="thecollege" value="<? echo $college; ?>"/> <? echo $college; ?> RCAs <?
							}
							?></td></tr>
							<input type="hidden" name="numentry" value="<? echo $numentry; ?>"/>
							<input type="hidden" name="userid" value="<? echo $submitted; ?>"/>
							<input type="hidden" name="taskannounce" value="addedit"/>
							<input type="hidden" name="whichform" value="calendar"/>
							<tr><td><input type="submit" value="submit" /></td></tr></table>
							</form><?
							if (($numentry) AND ($whichform=='calendar')) {
								echo ("<a href='?numentry=".$numentry."&taskannounce=deleteevent&userid=".$submitted."&whichform=calendar'>".'DELETE THIS EVENT'."</a>"); 
							} //
				?>
                </div>  <!--- <div class="adminmessage66"> --->
                <? 
				} else { //what RCAs see instead of DSLs
					?><div class="adminmessage67"> <div id='ten'><?
					echo "<strong>ON CALL SYNOPSIS</strong><br/>";
					?></div><?
					$queryo="SELECT * FROM rca WHERE netid='".$submitted."'";
					$resulto = mysql_query($queryo);
					$rowstuffo = mysql_fetch_array($resulto, MYSQL_ASSOC);
					$query1="SELECT COUNT(whooncall) AS numoncall FROM oncalltrack WHERE whooncall='".$submitted."'";
					$result1= mysql_query($query1);
					$result_row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
					$query2="SELECT COUNT(rounds) AS numrounds FROM oncalltrack WHERE rounds='".$submitted."'";
					$result2= mysql_query($query2);
					$result_row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
					$whatistoday = date("U");
					
					$query4="SELECT oncalldate FROM oncalltrack WHERE oncalldate>='".$whatistoday."' AND rounds='".$submitted."' ORDER BY oncalldate";
					$result4= mysql_query($query4);
					$result_row4 = mysql_fetch_array($result4, MYSQL_ASSOC);
					?><table><tr><td> <?
					echo "# ON-CALL: ";
					?></td><td> <?
					echo $result_row1['numoncall'];
					?></td></tr><tr><td> <?
					echo "# ROUNDS: ";
					?></td><td> <?
					echo $result_row2['numrounds'];
					?></td></tr><tr><td> <?
					echo "NEXT ON-CALL: ";
					?></td><td id="four"> <?
					$query3="SELECT oncalldate FROM oncalltrack WHERE oncalldate>='".$whatistoday."' AND whooncall='".$submitted."' ORDER BY oncalldate";
					$result3= mysql_query($query3);
					$result_row3 = mysql_fetch_array($result3, MYSQL_ASSOC);
					if ($result_row3[oncalldate] !=0) {
						echo (date('l, n/j/Y', $result_row3[oncalldate]));
					} else {
						echo "Nada. Zip. Nothing.";
						}
					?></td></tr><tr><td> <?
					echo "NEXT ROUNDS: ";
					?></td><td id='one'> <?
					if ($result_row4[oncalldate] !=0) {
						echo (date('l, n/j/Y', $result_row4[oncalldate]));
					} else {
						echo "Too busy to do rounds, eh?";
					}
					?></td></tr></table><br/><br/>
                    <div id='ten'><strong>INSERVICES</strong><br/></div>
                    
                    <table><tr><td> <?
					echo "# REQUIRED: ";
					?></td><td> <?
					if ($rowstuffo['fyear']=="first") {
						echo 4;
					} else  {
						echo 2;
					}
					?></td></tr><tr><td> <?
					echo "# SIGNED-UP: ";
					?></td><td> <?
						$query1="SELECT COUNT(inserviceid) AS signedup FROM track WHERE rca='".$submitted."'";
						$result1= mysql_query($query1);
						$result_row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
						echo "".$result_row1['signedup']."<br/>";
					?></td></tr><tr id="four"><td> <?
					echo "# COMPLETED: ";
					?></td><td> <?
						$query2="SELECT COUNT(track.attended) AS attended FROM track WHERE attended='Y' AND rca='".$submitted."'";
						$result2= mysql_query($query2);
						$result_row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
						echo "".$result_row2['attended']."<br/>";
					?></td></tr><tr><td> <?
					echo "# SKIPPED: ";
					?></td><td> <?
						$query3="SELECT COUNT(track.attended) AS notattended FROM track, inservices WHERE rca='".$submitted."' AND inservices.time<'".date('U')."' AND track.attended='N' AND track.inserviceid=inservices.id";
						$result3= mysql_query($query3);
						$result_row3 = mysql_fetch_array($result3, MYSQL_ASSOC);
						echo "".$result_row3['notattended']."<br/>";
					?></td></tr></table><br/><br/>
                    
                    <div id='ten'><strong>BUDGET SNAPSHOT </strong><br/></div>
                    <?
                    $queryc="SELECT * FROM rca WHERE netid='".$submitted."'";
                    $resultc=mysql_query($queryc);
                    $result_rowc=mysql_fetch_array($resultc, MYSQL_ASSOC);
                    $queryf="SELECT * FROM collegebudgets WHERE rcaid='".$submitted."' AND year= '2013' AND college='".$result_rowc['college']."'";
					$resultf=mysql_query($queryf);
					$rowstuffrcauser=mysql_fetch_array($resultf, MYSQL_ASSOC);
					$rcabudget=0;
					
					if ($rowstuffrcauser['rcaid']!="") {
						$rcabudget=$rowstuffrcauser['budget'];
					} else { // if (!$rowstuffrcauser['rcaid']=="")
						$queryg="SELECT * FROM collegebudgets WHERE rcaid='defaultbud' AND year= '2013' AND college='".$result_rowc['college']."'";
						$resultg=mysql_query($queryg);
						$rowstuff=mysql_fetch_array($resultg, MYSQL_ASSOC);
						if ($rowstuff['rcaid'] =="") {
                    		echo "Sadly, no budgets for your college";
                    	} else {
                    		$rcabudget=$rowstuff['budget'];
                    	}
                    }
                    
                    if ($rcabudget != 0) {	
                    	$queryd="SELECT * FROM rcaexpenses WHERE rcaname='".$submitted."' ORDER BY whenentered";
						$resultd=mysql_query($queryd);
						$lastentry="-";
						$totalspent=0;
						$totalentries=0;
						while ($result_row = mysql_fetch_array($resultd, MYSQL_ASSOC)){
							$totalspent=$totalspent+$result_row['expenditure'];
							$totalentries=$totalentries+1;
							if ($result_row['whenentered']!="") { // 
								$lastentry=$result_row['whenentered'];
							} // if (!$result_row['whenentered']=="")
						} // while ($result_row = mysql_fetch_array($resultd, MYSQL_ASSOC))
						$leftremaining=$rcabudget-$totalspent;
					
					
						?><table><tr>
						<td>Budget:</td>
						<td>  <? echo $rcabudget; ?> </td></tr>
						<tr><td>Spent:</td>
						<td> <? echo $totalspent; ?></td></tr>
						<tr id='one'><td>Remaining: </td>
						<td> <? echo $leftremaining; ?></td></tr>
						<tr><td># of Entries:</td>
						<td> <? echo $totalentries; ?></td></tr>
						<tr><td>Last Entry: </td>
						<td>
						<?
						if ($lastentry=="-") { // 
							echo $lastentry;
						}else{
							echo (date("n/j/Y", $lastentry));
						} // if ($lastentry=="-")
						?>
						</td></tr>
						</table>
						
						<?
                    }
                    ?>
                    
                    </div> <!--- <div class="adminmessage67"> ---><?
				} 
				$rightnow=date("U");
				?>  <div id="titlestuff"><?
                echo "ANNOUNCEMENTS"; 
				?></div>
				<div class="adminmessage5">
				<?
				$query="SELECT * FROM rcaannounce WHERE whenend>'".$rightnow."'ORDER BY posted DESC";
				$result = mysql_query($query);
				while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC)){ //
					if (($result_row['college']=="All") OR ($result_row['college']==$college)) {
						?> <div class="<? echo $result_row['college']."background"; ?>">  
							<div class="insidetitle">                  
						<? 
						if ($dsl) {
							echo ("<a href='?numentry=".$result_row['numentry']."&userid=".$submitted."&whichform=announce'>".$result_row['college']." RCAs: ".$result_row['thetitle']."</a>");
						} else {
							echo $result_row['college']." RCAs: ".$result_row['thetitle'];
						}
						?> <div id="littleguy"><?
							echo "posted: ".date("m",$result_row['posted'])."/".date("j",$result_row['posted'])."/".date("Y",$result_row['posted']);
						?></div> <!---- <div class="littleguy"> --->
                        </div> <!---- <div class="insidetitle"> --->
                        <div class="insideannounce">  <?
						echo $result_row['tbody'];						
						?></div> <!---- <div class="insideannounce"> --->
                        </div> <!--- <div class "background"> ----> <?
					} // if (($result_row['college']=="all") OR ($result_row['college']==$college))
				} //while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
				
				?>
                </div> <!--- <div class="adminmessage"> --->
                 
                <?
				$rightnow=date("U");
				?>  <div id="titlestuff22"><?
                echo "CALENDAR"; 
				
				?></div><div class="adminmessage55">
                <div id="titlestuff2"><?
				echo date("F");
				?></div><?
				$currentm = date('m');
				$query="SELECT * FROM rcacallendar WHERE thedate>='".date('U')."' ORDER BY thedate ASC";
				$result = mysql_query($query);
				while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC)){ //
					if (date('m', $result_row['thedate']) == $currentm) {
						if (($result_row['college']=="All") OR ($result_row['college']==$college)) {
							?> <div class="<? echo $result_row['college']."background"; ?>">  
								<div class="insidetitle">                  
							<? 
							if ($dsl) {
								echo ("<a href='?numentry=".$result_row['numentry']."&userid=".$submitted."&whichform=calendar'>".date('l, F j',$result_row['thedate'])."</a>");
							} else {
								echo date('l, F j',$result_row['thedate']);
							}
							?> 
							
							</div> <!---- <div class="insidetitle"> --->
							<div class="insideannounce">  <?
							echo $result_row['college']." RCAs: <strong>".$result_row['thetitle']."</strong>, ";	
							echo $result_row['tbody'];							
							?></div> <!---- <div class="insideannounce"> --->
							</div> <!--- <div class "background"> ----> <?
						} // if (($result_row['college']=="all") OR ($result_row['college']==$college))
					} //if (date('m', $result_row['thedate']) == $currentm)
				} //while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
				$query="SELECT * FROM rcacallendar WHERE thedate>='".date('U')."' ORDER BY thedate ASC";
				$result = mysql_query($query);
				if ($currentm<12) {
					$nextm=$currentm+1;
					
				} else {
					$nextm=1;
				}
				$y= date("Y"); 
				$nextmn=mktime(0, 0, 0, $nextm, 1, $y);
				?><div id="titlestuff2"><?
				echo date('F',$nextmn);
				?></div><?
				while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC)){ //
					if (date('m', $result_row['thedate']) == $nextm) {
						if (($result_row['college']=="All") OR ($result_row['college']==$college)) {
							?> <div class="<? echo $result_row['college']."background"; ?>">  
								<div class="insidetitle">                  
							<? 
							if ($dsl) {
								echo ("<a href='?numentry=".$result_row['numentry']."&userid=".$submitted."&whichform=calendar'>".date('l, F j',$result_row['thedate'])."</a>");
							} else {
								echo date('l, F j',$result_row['thedate']);
							}
							?> 
							</div> <!---- <div class="insidetitle"> --->
							<div class="insideannounce">  <?
							echo $result_row['college']." RCAs: <strong>".$result_row['thetitle']."</strong>, ";	
							echo $result_row['tbody'];						
							?></div> <!---- <div class="insideannounce"> --->
							</div> <!--- <div class "background"> ----> <?
						} // if (($result_row['college']=="all") OR ($result_row['college']==$college))
					} //if (date('m', $result_row['thedate']) == $currentm)
				} //while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
				?>
                </div> <!--- <div class="adminmessage"> --->
                </div> <!--- <div id="workarearelative"> --->
                </div> <!--- <div id="workarea"> --->
                <?
					$dsluser="";
					$rcauser="";
					if ($dsl) {
						$dsluser=$submitted;
					}else {
						$rcauser=$submitted;
					}
				?>
                
<? include("../includes/layouts/menu.php"); ?>

				<?
			} // if ($submitted)
		} // if ($submitted)
		if (!$submitted) {
			?> <div id="squirrelpic">
</div>
<div id="loginalign"><div id="logintop"></div><div id="loginmid"><?
			$leaving=isset($_GET['outofhere']) ? $_GET['outofhere'] : "" ;
			if ($leaving) {
			echo "You are logged off the RCA<br/>Resource Site.<br/><br/>";
			} else {
				if ($submitteda) {echo "Welcome<br/><br/>"; } else {echo "Try again<br/><br/>";}
			 } // if ($leaving)
			echo "Please enter the following<br/><br/>";
		?>
					<form action="<? echo $self; ?>" method="POST">
					NetID: <input type="text" name="userid" /></br><br/>
                    <input type="hidden" name="task" value=""/>
					<input type="submit" value="Go!" />
					</form> </div><div id="loginbot"></div></div><?
					
		} // if (!$submitted)
		break;
	case "otheradmin":
		switch ($dsluser){
			case "mfrawley":
			$college = "Mathey";
			break;
			case "mellisat":
			$college = "Forbes";
			break;
			case "amyham":
			$college = "Rocky";
			break;
			case "rehunt":
			$college = "Wilson";
			break;
			case "aandres":
			$college = "Butler";
			break;
			case "dwessman":
			$college = "Whitman";
			break;
			default:
			echo "yikes";
		} // switch ($dsluser)
		switch ($taskadmin){
			case "":
			$query = "SELECT * FROM otheradmin WHERE college = '".$college."' ORDER BY Netid";
 			$result = mysql_query ($query);
			if (mysql_num_rows($result) < 1) {
				echo "There are no other administrators from ".$college." to which you have given access.<br/><br/>";
				}else{
				echo "Below are the administrators from ".$college." who have access to this site.<br/>";
				echo "Click on an administrator to delete or edit level of access.<br/><br/>";
					while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$person=$result_row['Netid'];
					echo '<a href="?otheradid='.$person.'&dslid='.$dsluser.'&task=otheradmin&tasksecond=editadmin">'.$person.'</a><br/>';
					} // while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
				} // if (mysql_num_rows($result) < 1)
			echo ("<br/>If you would like to enter a new administrator, please complete the form below.<br/><br/>");
			?> <form action="<? echo $self; ?>" method="post">
				UserID: <input type="text" name="otheradid" /><br/>
                Below click on the level of access you want for this administrator. <br/>
                Leaving Expenses or Inservices blank we prevent this admin from access. <br/><br/>
                RCA Expenses<br/>
				<input type="radio" name="adexpense" value="see"/> Read only <br/>
				<input type="radio" name="adexpense" value="do"/> Editing Privileges <br/>
                RCA Inservice Records<br/>
				<input type="radio" name="adinservice" value="see"/> Read only <br/>
				<input type="radio" name="adinservice" value="do"/> Editing Privileges <br/>
				<input type="hidden" name="adcollege" value=<? echo $college; ?>>           
				<input type="hidden" name="task" value="otheradmin"/>   
				<input type="hidden" name="tasksecond" value="enteradmin"/>   
  				<input type="hidden" name="dslid" value="<? echo $dsluser; ?>"/>                         
				<input type="submit" value="submit" />
				</form> <?		
			break;
			case "enteradmin":
					$newadmin=isset($_GET['otheradid']) ? $_GET['otheradid'] : "" ;
					if (!$newadmin) $newadmin= $_POST['otheradid'];
					$college = $_POST['adcollege'];
					$inlevel=isset($_POST['adinservice']) ? $_POST['adinservice'] : "" ;
					$exlevel=isset($_POST['adexpense']) ? $_POST['adexpense'] : "" ;
					$query = sprintf("SELECT * FROM otheradmin WHERE netid='%s'",
						mysql_real_escape_string($newadmin));
					$result = mysql_query($query);
					if (mysql_num_rows($result)<1){
						$query = "INSERT INTO otheradmin SET netid='".$newadmin."', College='".$college."', inservices = '".$inlevel."', expenses = '".$exlevel."'";
						$result=mysql_query($query);
						if ($result){
							echo "".$newadmin." has been entered with the following privileges for ".$college.":<br/>";
							if (!$exlevel == "") {
								if ($exlevel == "see") {
								echo "Read, but not edit/manage, RCA expenses for your college.<br/><br/>";
								}else{
								echo "Edit/manage RCA epenses for your college.<br/><br/>";
								} // if ($exlevel == "see")
							} // if (!$exlevel == "")
							if (!$inlevel == "") {
								if ($inlevel == "see") {
								echo "Read, but not edit/manage, RCA inservice records for your college.<br/><br/>";
								}else{
								echo "Edit/manage RCA inservice records for your college.<br/><br/>";
								} // if ($inlevel == "see")
							} // if (!$inlevel == "")
						} // if ($result)
					} else {
						echo 'That netid already is already listed. Please try again.<br/>';
					} // if (mysql_num_rows($result)<1)
			break;
			case "editadmin":
			$newadmin=isset($_GET['otheradid']) ? $_GET['otheradid'] : "" ;
			if (!$newadmin) $newadmin=isset($_POST['otheradid']) ? $_POST['otheradid'] : "" ;
			$inlevel=isset($_POST['adinservice']) ? $_POST['adinservice'] : "" ;
			$exlevel=isset($_POST['adexpense']) ? $_POST['adexpense'] : "" ;
			if ($taskedit){
				if (($inlevel) AND ($exlevel)){
				$query = "UPDATE otheradmin SET inservices = '$inlevel', expenses = '$exlevel' WHERE netid = '$newadmin'";
				}else{
					if ($inlevel) {
					$query = "UPDATE otheradmin SET inservices = '$inlevel' WHERE netid = '$newadmin'";
					}else{
					$query = "UPDATE otheradmin SET expenses = '$exlevel' WHERE netid = '$newadmin'";
					} // if ($inlevel)
				} // if (($inlevel) AND ($exlevel))
				$result = mysql_query($query);
			}else{
			$query = "SELECT * FROM otheradmin WHERE Netid='".$newadmin."'";
			$result = mysql_query($query);
			$rowstuff = mysql_fetch_array ($result, MYSQL_ASSOC);
			?> <form action="<? echo $self; ?>" method="post"> <?
				if ($rowstuff['inservices'] == "") {
					echo "".$newadmin." currently does not have access to the RCA inservice records. <br/>If you would like to provide access, click on the level of access below or leave blank:<br/>";
					?><input type="radio" name="adinservice" value="see"/> Read only <br/>
					<input type="radio" name="adinservice" value="do"/> Editing Privileges <br/>
					<? }else {
					echo "Currently ".$newadmin." has ";
					if ($rowstuff['inservices'] == 'see'){
						echo "only the ability to read RCA inservice records. If you would like to give ".$newadmin." full editing priveleges, please check the box below or leave blank if you want to keep this administrator at current level.<br/><br/>";
					?>
					<input type="checkbox" name="adinservice" value="do" /> Grant <? echo ($newadmin); ?> full editing privileges for RCA inservice records.<br/>
					<?
				}else{
					echo "has full editing privileges RCA inservice records. If you would like to limit ".$newadmin." to read only status, please check the box below or leave blank if you want to keep this administrator at current level.<br/><br/>";
					?>
               		<input type="checkbox" name="adinservice" value="see" /> Grant <? echo ($newadmin); ?> READ only status for RCA inservice records.<br/><?
                } // if ($rowstuff['inservices'] == 'see')
			} // if ($taskedit)
			if ($rowstuff['expenses'] == "") {
				echo "".$newadmin." currently does not have access to the RCA expense records. <br/>If you would like to provide access, click on the level of access below or leave blank:<br/>";
				?><input type="radio" name="adexpense" value="see"/> Read only <br/>
				<input type="radio" name="adexpense" value="do"/> Editing Privileges <br/><br/><?
			}else {
				echo "<br/><br/>Currently ".$newadmin." has ";
				if ($rowstuff['expenses'] == 'see'){
					echo "only the ability to read RCA inservice records. If you would like to give ".$newadmin." full editing priveleges, please check the box below or leave blank if you want to keep this administrator at current level.<br/><br/>";
					?>
               		<input type="checkbox" name="adexpense" value="do" /> Grant <? echo $newadmin; ?> full editing privileges for RCA expense records.<br/>
               		<?
				}else{
				echo "has full editing privileges RCA expense records. If you would like to limit ".$newadmin." to read only status, please check the box below or leave blank if you want to keep this administrator at current level.<br/><br/>";
				?>
                <input type="checkbox" name="adexpense" value="see" /> Grant <? echo $newadmin; ?> READ only status for RCA expense records.<br/><?
                } // if ($rowstuff['expenses'] == 'see')
			} // if ($rowstuff['expenses'] == "")
				?> 
				<input type="hidden" name="task" value="otheradmin"/>   
				<input type="hidden" name="tasksecond" value="editadmin"/>  
				<input type="hidden" name="taskthird" value="addedit"/>  
  				<input type="hidden" name="dslid" value="<? echo $dsluser; ?>"/>                                        
  				<input type="hidden" name="otheradid" value="<? echo $newadmin; ?>"/>                                        
				<input type="submit" value="submit" />
				</form> 
				<form action="<? echo $self; ?>" method="post"> <?
				echo "<br/><br/>You also have the option of deleting ".$newadmin." if you feel like being a cold-hearted bastard.<br/><br/>";
				echo "To do so, simply click on the button below.<br/<br/>";
				?>
				<input type="hidden" name="task" value="otheradmin"/>   
				<input type="hidden" name="tasksecond" value="deleteadmin"/>  
  				<input type="hidden" name="dslid" value="<? echo $dsluser; ?>"/>                                        
  				<input type="hidden" name="otheradid" value="<? echo $newadmin; ?>"/>                                        
				<input type="submit" value="delete" />
				</form> <?
		} // if ($taskedit)
			break;
			case "deleteadmin":
			$newadmin = $_POST['otheradid'];
			$query = "SELECT * FROM otheradmin WHERE Netid='".$newadmin."'";
			$result = mysql_query($query);
			$rowstuff = mysql_fetch_array ($result, MYSQL_ASSOC);
			$query = "DELETE FROM otheradmin WHERE Netid= '".$rowstuff['Netid']."' AND College='".$rowstuff['College']."' AND inservices= '".$rowstuff['inservices']."' AND expenses= '".$rowstuff['expenses']."'";
			$result = mysql_query ($query);
			if ($result) {
				echo "".$newadmin." has been deleted and will no longer have access to this site...sad as that may be.<br/><br/>";
			} // if ($result)
			break;
			default:
			echo "yikes ";
			} // switch ($taskadmin)
			echo "What would you like to do now? <br/><br/>";
					echo '<a href="?userid='.$dsluser.'&backagain=yes">Main Menu</a><br />';
					echo '<a href="?dslid='.$dsluser.'&task=otheradmin">Back to the Other Admin Page</a><br />';
					echo '<a href="?outofhere=yes">Logout</a><br/>';
		break;
	default:
		echo "yikes first admin";
	}  // switch ($task)
 
	?>

<? include("../includes/layouts/footer.php"); ?>

