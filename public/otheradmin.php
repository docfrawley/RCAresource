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
		break;

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
					echo '<a href="">'.$person.'</a><br/>';
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