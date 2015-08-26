<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="CSS/calendarcss.css" rel="stylesheet" type="text/css" />
<script> 
	function getnewview(whatup, yesno)  {
		var thetd = document.getElementById(whatup);
		if (yesno==1) {
			thetd.style.backgroundColor = "#3333FF";
			return 0;
		} else {
			thetd.style.backgroundColor = "#FF3300";
			return 1;
		}
		}
		</script>

<title>Set On-Call Calendar Dates</title>
</head>

<body>
<?
$self = htmlentities($_SERVER['PHP_SELF']);
$college=isset($_POST['SelCollege']) ? $_POST['SelCollege'] : "" ;
	@mysql_connect("localhost", "matheyhp", "*4MatheyC011ege")
		or die("Could not connect");
	@mysql_select_db("matheyhp_rcasite") 
		or die ("could not connect to database.");
$datearray = isset ($_POST['dates']) ? $_POST['dates'] : "" ;

$themonth=isset($_POST['mon']) ? $_POST['mon'] : "" ;
$theyear=isset($_POST['yr']) ? $_POST['yr'] : "" ;

$mstart=isset($_GET['prm']) ? $_GET['prm'] : "" ;
$chm=isset($_GET['chm']) ? $_GET['chm'] : "" ;
if ($themonth) {
	$m=$themonth;
} else {
	if ($chm){
		$m=$mstart+$chm;
		}else{
		$m= date("m");
		}}
$d= date("d");     // Finds today's date
if ($theyear) {
	$y = $theyear;
} else {
	$y= date("Y");     // Finds today's year
}

$no_of_days = date('t',mktime(0,0,0,$m,1,$y)); // This is to calculate number of days in a month
$nod = $m-1;
$nodayprev = date('t',mktime(0,0,0,$nod,1,$y)); //number of days of previous month to fill out calendar
$mn=date('M',mktime(0,0,0,$m,1,$y)); // Month is calculated to display at the top of the calendar

$yn=date('Y',mktime(0,0,0,$m,1,$y)); // Year is calculated to display at the top of the calendar

$j= date('w',mktime(0,0,0,$m,1,$y)); // This will calculate the week day of the first day of the month
if ($datearray) {
for ($z=0; $z<count($datearray); $z++) {
	$needtoadd = true;
	$querya = "SELECT * FROM oncalldates";
	$resulta = mysql_query($querya);
		while ($result_row = mysql_fetch_array($resulta, MYSQL_ASSOC)){
			if ($datearray[$z]==$result_row['thedate']) {
			$needtoadd = false;
			}
		}// while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC)
		if ($needtoadd) {
			$queryi = "INSERT INTO oncalldates SET thedate='".$datearray[$z]."' ";
			$resulti = mysql_query($queryi);
		}
}// ($z=0; $z<=count($datearray); $z++)
}// if ($datearray)	
if ($themonth){
$queryb = "SELECT * FROM oncalldates";
$resultb = mysql_query($queryb);
while ($result_row = mysql_fetch_array($resultb, MYSQL_ASSOC)){
	$needtodelete = true;
	if ((date('m', $result_row['thedate'])==$themonth)) {
		if ($datearray) {
		for ($z=0; $z<count($datearray); $z++) {
			if ($datearray[$z]==$result_row['thedate']) {
			$needtodelete = false;
			}//($datearray[$z]==$result_row['thedate'])
		}//for ($z=0; $z<count($datearray); $z++)
		} //if ($datearray)  don't check if nothing in datearray for that month. 
		if ($needtodelete) {
			$queryd = "DELETE FROM oncalldates WHERE thedate='".$result_row['thedate']."' ";
			$resultd = mysql_query($queryd);
		}//if ($needtodelete)
	}// if (date('m', $result_row['thedate'])==$themonth)

}// while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC)
} //if ($themonth) don't need to check to delete if nothing submitted.
				

$adj="";
if ($j>0) {
	$kstart=$nodayprev-$j+1;
for($k=$kstart; $k<=$nodayprev; $k++){ // Adjustment of date starting
$adj .="<td id='secondthat'>$k</td>";
}
}
/// Starting of top line showing name of the days of the week
echo "<table><tr><td> <a href='?prm=".$m."&chm=-1'><</a> </td><td>$mn $yn </td><td> <a href='?prm=".$m."&chm=1'>></a> </td></tr><tr>";

echo '<td id="first">Sun</td>
<td id="first">Mon</td>
<td id="first">Tue</td>
<td id="first">Wed</td>
<td id="first">Thu</td>
<td id="first">Fri</td>
<td id="first">Sat</td></tr><tr>';

////// End of the top line showing name of the days of the week//////////

//////// Starting of the days//////////

?><form action="<? echo $self; ?>" method="post"> <?
for($i=1;$i<=$no_of_days;$i++){
	$matchdate = false;
	$queryz = "SELECT * FROM oncalldates";
	$resultz = mysql_query($queryz);
	while ($result_row = mysql_fetch_array($resultz, MYSQL_ASSOC)){
		if ($result_row['thedate'] == mktime(0,0,0,$m, $i, $y)) {
			$matchdate = true;
		} //if ($result_row['thedate'] == mktime(1,1,1,$m, $i, $y))
		} //while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
		if ($matchdate) {
			echo $adj.'<td class="secondthis" id="'.$i.'" bgcolor="#FF3300">'.$i.'<br/>';
			?><input class="checkboxlook" type="checkbox" name="dates[]" value="<? echo mktime(0,0,0,$m, $i, $y); ?>" checked="checked"/><?
	} else {
			echo $adj.'<td class="secondthis" id="'.$i.'" bgcolor="#3333FF")">'.$i.'<br/>'; 
			?><input class="checkboxlook" type="checkbox" name="dates[]" value="<? echo mktime(0,0,0,$m, $i, $y); ?>"/><?
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
</tr><tr><td align="left">
<input type="hidden" name="mon" value="<? echo $m; ?>"/>
<input type="hidden" name="yr" value="<? echo $y; ?>"/>
<input type="submit" value="submit"/></td>
<td><input type="reset" value="reset"/></td>
</form>
</tr></table>
</body>
</html>