<? include_once("../includes/rcainitialize.php"); 
include_once("../includes/layouts/header.php");
if (isset($_SESSION["casnetid"])) {
include_once("../includes/layouts/setdsl.php");  
include_once("../includes/layouts/menu.php"); 

?>

    <div id="workarea"><div id="calenderwork">
<?

$datetocheck = isset ($_GET['thedate']) ? $_GET['thedate'] : "" ;
 if(!$datetocheck) $datetocheck=isset ($_POST['thedate']) ? $_POST['thedate'] : "" ;
 
$oncallp = isset ($_GET['oncallperson']) ? $_GET['oncallperson'] : "" ;
 if(!$oncallp) $oncallp=isset ($_POST['oncallperson']) ? $_POST['oncallperson'] : "" ;
$roundsp = isset ($_GET['roundsperson']) ? $_GET['roundsperson'] : "" ;
 if(!$roundsp) $roundsp=isset ($_POST['roundsperson']) ? $_POST['roundsperson'] : "" ;
 
$whichcollege = isset ($_GET['college']) ? $_GET['college'] : "" ;
 if(!$whichcollege) $whichcollege=isset ($_POST['college']) ? $_POST['college'] : "" ;
 
$frontm = isset ($_GET['mcheck']) ? $_GET['mcheck'] : "" ;
 if(!$frontm) $frontm=isset ($_POST['mcheck']) ? $_POST['mcheck'] : "" ; 
$themonth=isset($_POST['mon']) ? $_POST['mon'] : "" ;
$theyear=isset($_POST['yr']) ? $_POST['yr'] : "" ;
$mstart=isset($_GET['prm']) ? $_GET['prm'] : "" ;
$chm=isset($_GET['chm']) ? $_GET['chm'] : "" ;

$checkrca=isset($_GET['whichrca']) ? $_GET['whichrca'] : "" ;

$todisplay=isset($_GET['tdisplay']) ? $_GET['tdisplay'] : "none" ;
$todisplay2=isset($_GET['tdisplaytwo']) ? $_GET['tdisplaytwo'] : "" ;
 if(!$todisplay2) $todisplay2=isset ($_POST['tdisplaytwo']) ? $_POST['tdisplaytwo'] : "none" ; 

if ($dsluser) {$whichcollege = getdslcollege($dsluser);} 
else {
	$the_rca = new rcaobject($rcauser);
	$whichcollege = $the_rca->get_college();
} // if ($dsluser) 

if ($themonth) {$m=$themonth;} //get right month
else {
	if ($chm){$m=$mstart+$chm;}
	else{$m= date("m");} 
	if ($frontm) {$m=$frontm;}	
} // if ($themonth)

$d= date("d");     // Finds today's date

if ($theyear) {$y = $theyear;} //get right year
else {$y= date("Y");} 

$no_of_days = date('t',mktime(0,0,0,$m,1,$y)); // This is to calculate number of days in a month
$nod = $m-1;
$nodayprev = date('t',mktime(0,0,0,$nod,1,$y)); //number of days of previous month to fill out calendar
$mn=date('M',mktime(0,0,0,$m,1,$y)); // Month is calculated to display at the top of the calendar

$yn=date('Y',mktime(0,0,0,$m,1,$y)); // Year is calculated to display at the top of the calendar

$j= date('w',mktime(0,0,0,$m,1,$y)); // This will calculate the week day of the first day of the month

if ($dsluser) { //
	if (($oncallp) OR ($roundsp)) {
		
		$day_oncall = new oncallday($datetocheck, $whichcollege);
		if ($oncallp != "Select RCA") {$day_oncall->oncall_update($oncallp);}
		if ($roundsp != "Select RCA") {$day_oncall->rounds_update($roundsp);}
	}//  if (($oncallp) OR ($roundsp))
} // if ($dsluser)

?> <div id="upperbox"><?
	if ($datetocheck) {
		$day_oncall = new oncallday($datetocheck, $whichcollege);
		$day_oncall->print_form($dsluser, $m);
	} else{
		echo "Click on an on-call date below to check who is on-call<br/>";
		if ($dsluser) {echo "or to enter/edit RCAs for on-call and rounds.<br/>";}
	}//if ($datetocheck)
?> </div> <!-- <div id="upperbox"> --><?

$adj="";
if ($j>0) {
	$kstart=$nodayprev-$j+1;
for($k=$kstart; $k<=$nodayprev; $k++){ // Adjustment of date starting
$adj .="<td id='secondthat'>$k</td>";
}
}
/// Starting of top line showing name of the days of the week
?><table id='calendartable'><tr><td></td><td><?
echo "<a href='?prm=".$m."&chm=-1&thedate=".$datetocheck."'><img src='images/prevmonth.png' class='calendarpic' border='0'></a>";
?> </td><td></td><td> <?
$uppermn = strtoupper ($mn);
echo $uppermn." ".$yn;
?> </td><td></td><td>  <?
echo "<a href='?prm=".$m."&chm=1&thedate=".$datetocheck."'><img src='images/nextmonth.png' class='calendarpic' border='0'></a>";
?>
</td><td></td></tr><tr>
<td id="first">SUN</td>
<td id="first">MON</td>
<td id="first">TUE</td>
<td id="first">WED</td>
<td id="first">THU</td>
<td id="first">FRI</td>
<td id="first">SAT</td></tr><tr><?

for($i=1;$i<=$no_of_days;$i++){
	$matchdate = false;
	$sql = "SELECT * FROM oncalldates";
	$result_set = $database->query($sql);
	//$result_row = $database->fetch_array($result_set);
	while($value = $database->fetch_array($result_set)){
		if ($value['thedate'] == mktime(0,0,0,$m, $i, $y)) {
			$datetocheckon = $value["thedate"];
			$matchdate = true;
				$sql = "SELECT * FROM oncalltrack WHERE college = '".$whichcollege."' AND oncalldate = '".$datetocheckon."'";
				$result_set = $database->query($sql);
				$result_rowa = $database->fetch_array($result_set);
				$whichcolor = 0;
				if (($result_rowa['whooncall']!="") AND ($result_rowa['rounds']!="")) {
					$whichcolor = 2;
				} else {
					if (($result_rowa['whooncall']!="") OR ($result_rowa['rounds']!="")){$whichcolor = 1;}				
				} //if (($result_rowa['whooncall']!="") AND ($result_rowa['rounds']!=""))
				if (mktime() >= $datetocheckon) {
					$therca = $result_rowa['whooncall'];
					$sql = "SELECT * FROM oncalllog WHERE oncallp='".$therca."' AND oncalldate='".$datetocheckon."'";
					$result_set = $database->query($sql);
					$result_rowl = $database->fetch_array($result_set);			
					if ($result_rowl['oncallp'] =="") {
						$whichcolor =10;
					} // if (!$resultl)
				} //if (mktime() >= $datetocheckon)
		} //if ($result_row['thedate'] == mktime(1,1,1,$m, $i, $y))
	} //foreach ($result_row as $value)
	if ($matchdate) {
		echo $adj.'<td class="secondthis" id="cell'.$whichcolor.'"><a href="?thedate='.$datetocheckon.'&mcheck='.$m.'" >'.$i.'</a><br/>';
	} else {
		echo $adj.'<td class="secondthis" id="cell3")">'.$i.'<br/>'; 
	} //if ($matchdate)
	

echo "</td>";
$adj='';
$j ++;
if($j==7){
	echo "</tr><tr>";
	$j=0;
	} else {
	if ($i==$no_of_days) {
		$l=7-$j;
		for ($h=1; $h<=$l; $h++) {
			echo "<td id='secondthat'>$h<br/></td>"; //fills out the rest of the calendar with dates of next month
		} //for ($h=1; $h<=$l; $h++)
	} //if ($i==$no_of_days)
} //if($j==7)
} // for($i=1;$i<=$no_of_days;$i++)
?>
</tr></table>
<?
if ($dsluser) {
	?> <div id="counttable"><?
		$core_oncall = new coregroup($whichcollege);
		$core_oncall->list_oncall();
				
	?></div>  <!-- <div id="countable"> --><? 
} else {
		?><div id="counttable2"> <?
		$rca_oncall = new rcaoncall($rcauser);
		$rca_oncall->list_oncalls($rcauser, $whichcollege);
		echo "<br/><br/>";
		$rca_oncall->list_rounds();
		?> </div> <!-- <div id="countable"> --><?
} // if ($dsluser)
 ?>  
   <div id="keybox">
   	<ul>
    <li id="three">NON ON-CALL DATE</li>
    <li id="zero">ON-CALL DATE, BOTH OPEN</li>
    <li id="one">ON-CALL DATE, ONE OPEN</li>
    <li id="two">ON-CALL DATE, BOTH FILLED</li>
    <li id="ten">ON-CALL LOG NOT COMPLETED</li>
    </ul>
   </div> <!-- <div id="keybox"> -->
                </div> <!-- <div id="calenderwork"> -->
                </div> <!-- <div id="workarea"> -->
                
                



<? if($rcauser) {?>
<div id="blanket" style="display: <? echo $todisplay2; ?>"></div>

<div class="displaymod2" id="logdisplay" style="display: <? echo $todisplay2; ?>">
<div id="oncalllogstuff">
<div class="goingout">
<? echo ('<a href="?mcheck='.$m.'&thedate='.$datetocheck.'" >CLOSE</a>'); ?>
</div> <!-- <div id="goinout"> -->
<div id="oncallposition" style="display: <? echo $todisplay2; ?>">
<?

$timethrough=isset($_POST['timethrough']) ? $_POST['timethrough'] : "" ;

$the_log = new logobject($rcauser, $datetocheck, $whichcollege);

if ($timethrough=="three") { //	
	$the_log->add_log($_POST);
	$the_log->mail_log();
} else {
	$the_log->log_form();
} // if ($timethrough=="three")

?>

</div> <!-- <div id="oncallposition"> -->
</div> <!-- <div id="oncallstuff"> -->
</div> <!-- <div class="displaymod2" id="logdisplay" -->
<? } ?>

<div id="blanket" style="display: <? echo $todisplay; ?>"></div>
    <div class="displaymod2" id="individRCA" style="display:<? echo $todisplay; ?>">
        <div id="oncalllogstuff">
        <div class="goingout">
            <? echo ('<a href="?mcheck='.$m.'&thedate='.$datetocheck.'"">CLOSE</a>'); ?>
        </div> <!-- <div id="goinout"> -->
        <div id="oncallposition"><? 
            $the_rca = new rcaoncall($checkrca);
            echo "Rotation Schedule for: ".$checkrca."<br/><br/>";
            $the_rca->list_oncalls($rcauser, $whichcollege);
            $the_rca->list_rounds();
        
        ?>
        </div> <!-- <div id="oncallposition"> -->
    </div> <!-- <div id="oncallstuff"> -->
</div> <!-- <div class="displaymod2" id="individrca" style="display: > -->

 <div id="oncallpdf"><a href="pdfs/oncall.pdf">PDF OF CALENDAR</a></div> 
<? 
}
include("../includes/layouts/footer.php"); ?>
