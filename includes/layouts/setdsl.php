<? 
$dslsrock = array ("mfrawley", "mellisat", "amyham", "momo", "aandres", "ak19", "molin");
$dsluser = (in_array($_SESSION["casnetid"], $dslsrock));
if (!$dsluser) {
	$rcauser=$_SESSION["casnetid"];
} else {
	$dsluser=$_SESSION["casnetid"];
	$rcauser="";
}
?>