<?php include_once('../public/CAS.php');

phpCAS::client(CAS_VERSION_2_0,'fed.princeton.edu',443,'cas','false');
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();

$casnetid = phpCAS::getUser();
if ($casnetid) {

	$_SESSION["casnetid"] = phpCAS::getUser();
}
$dslsrock = array ("mfrawley", "mellisat", "amyham", "momo", "aandres", "molin", "ak19");
$dsl = (in_array($casnetid, $dslsrock));				

$sql = "SELECT * FROM rca WHERE netid = '".$casnetid."'";
$result_set = $database->query($sql);
$found_user = $database->fetch_array($result_set);


if(!isset($found_user['netid']) && !$dsl)
{
        include_once('unauth.php');
        exit();
        
} 

?>