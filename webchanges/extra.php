<?
				
				$enterchanges=isset($_POST['enterchanges']) ? $_POST['enterchanges'] : "" ;
				if ($enterchanges) { // 
					$thedefault=(int)$_POST['defaultbud'];
					$sql="SELECT * FROM collegebudgets WHERE budgetid='".$budgetid."' AND rcaid='defaultbud'";
					$result_set = $database->query($sql);
					$barry = $database->fetch_array($result_set);
					$prevdefault=(int)$barry['budget'];
					
					if (($thedefault>$prevdefault) OR ($thedefault<$prevdefault)) { // 
						$sql = "UPDATE collegebudgets SET ";
						$sql .= "budget='". $database->escape_value($thedefault) ."', ";
						$sql .= "WHERE budgetid=". $database->escape_value($budgetid) ."' AND rcaid='defaultbud'";
	  					$database->query($sql);
					} // if (($thedefault>$prevdefault) OR ($thedefault<$prevdefault))
					$sql="SELECT netid FROM rca WHERE college='".$college."' ORDER BY netid";
					$result_set = $database->query($sql);
					//$result = $database->fetch_array($result_set);
					while($value = $database->fetch_array($result_set)){ //
						$name = $value['netid'];
						$diffbud=$_POST["$name"];
						$sql="SELECT * FROM collegebudgets WHERE rcabudgetid='".$budgetid."' AND rcaid='".$name."'";
						$result_set = $database->query($sql);
						$result_rowc = $database->fetch_array($result_set);
						if ($result_rowc['budgetid' != '']){ //
							
							if ($diffbud==$thedefault) { //
								$queryz = "DELETE FROM collegebudgets WHERE budgetid='".$budgetid."' AND rcaid='".$name."'";
								$resultz = mysql_query($queryz);
							} else { // if ($diffbud==$thedefault)
								$sql = "UPDATE collegebudgets SET ";
								$sql .= "budget='". $database->escape_value($diffbud) ."', ";
								$sql .= "WHERE budgetid='". $database->escape_value($budgetid) ."' AND rcaid=". $database->escape_value($name) ."";
	  							$database->query($sql);
							} // if ($diffbud==$thedefault)
							
						} else { // if ($resultc)
							
							if (($thedefault>$diffbud) OR ($thedefault<$diffbud)) {
								$thesemester = GetSem();
								$theyear = date('Y');
								$queryx="INSERT INTO collegebudgets SET college='".$college."', year='".$theyear."',  semester='".$thesemester."', rcaid='".$name."', budget='".$diffbud."', budgetid='".$budgetid."'";
								$resultx=mysql_query($queryx);
							} // if (($thedefault>$diffbud) OR ($thedefault<$diffbud))
							
						} // if ($resultc)
						
					} // foreach ($result as $value){ //
					echo "The RCA budgets have been updated<br/><br/>";
				}  else { // 
					
					
				} // if (!$thebudget=="")
				
				?></div> <!--- <div class="adminmessage4"> --> <?
	

	case "listrca":
	?><div class="adminmessage4"> <?
		$whichrca=isset($_GET['whichrca']) ? $_GET['whichrca'] : "" ;
			if (!$whichrca) $whichrca=isset($_POST['whichrca']) ? $_POST['whichrca'] : "" ;
			$sql="SELECT * FROM collegebudgets WHERE budgetid='".$budgetid."' AND rcaid='defaultbud'";
			$result_set = $database->query($sql);
			$rowstuffa = $database->fetch_array($result_set);
			if ($rowstuffa['budgetid'] =='') {
				echo "You currently do not have RCA budgets for this semester.<br/>";
			} else {
				$thedefault=$rowstuffa['budget'];
				echo "RCA Expenses for ".GetSem()." ".date('Y')."<br/>";
				echo "Click on the RCA ID to see individual expenses.<br/><br/>";
				?> 
                <table width="700" cellspacing="5">
                <tr id="one"><td>
                <?
				echo "RCA";
				?>
                </td><td>
                <?
				echo "Budget";
				?>
                </td><td>
                <?
				echo "Total Expenses";
				?>
                </td><td>
                <?
				echo "Remaining Budget";
				?>
                </td><td>
                <?
				echo "Total Entries";
				?>
                </td><td>
                <?
				echo "Last Entry";
				?>
                </td></tr>
                <?
				$sql="SELECT netid FROM rca WHERE college='".$college."' ORDER BY netid";
				$result_set = $database->query($sql);
				while ($result_row = $database->fetch_array($result_set)){ // 
					?>
					<tr><td>
					<?
					$therca=$result_row['netid'];
					
					echo '<a href="?task=listrca&tdisplay=yes&rcaperson='.$therca.'">'.$therca.'</a>';
					?>
					</td><td>
					<?
						$sql="SELECT budget FROM collegebudgets WHERE rcaid='".$therca."' AND budgetid='".$budgetid."'";
						$result_set = $database->query($sql);
						$rowstuffc=$database->fetch_array($result_set);
						
						if (!$rowstuffc['budget']=="") {
							$rcabudget=$rowstuffc['budget'];
						} else {
							$rcabudget=$thedefault;
						} // if (!$rowstuffc['budget']=="")
						
					echo $rcabudget;
					?>
					</td><td>
					<?
					$sql="SELECT * FROM rcaexpenses WHERE rcaname='".$therca."' AND budgetid='".$budgetid."' ORDER BY whenentered";
					$result_set = $database->query($sql);
					$lastentry="-";
					$totalspent=0;
					$totalentries=0;
					
					while ($result_row = $database->fetch_array($result_set)){
						$totalspent=$totalspent+$result_row['expenditure'];
						$totalentries=$totalentries+1;
						
						if ($result_row['whenentered']!="") { // 
							$lastentry=$result_row['whenentered'];
						} // if (!$result_row['whenentered']=="")
						
					} // while ($result_row = mysql_fetch_array($resultd, MYSQL_ASSOC))
					
					$leftremaining=$rcabudget-$totalspent;
					echo $totalspent;
					?>
					</td><td>
					<?
					echo $leftremaining;
					?>
					</td><td>
					<?
					echo $totalentries;
					?>
					</td><td>
					<? 
					
					if ($lastentry=="-") { // 
						echo $lastentry;
					}else{
						echo (date("n/j/Y", $lastentry));
					} // if ($lastentry=="-")
					
					?>
					</td></tr>
					<?
				} // while ($result_row = mysql_fetch_array($resultb, MYSQL_ASSOC))
				
				?>
				</table>
				<?	
			}
		?></div> <!--- <div class="adminmessage4"> --> 
        
        <div id="upperboxexp"><?
		WhichBudDo();
	?></div> <?
	break;
	
	case "enterexpense":
	?><div class="adminmessage4"> <?
		$numberofent=0;
		$keepgoing=true;
		$theexpense=isset($_POST['expense']) ? $_POST['expense'] : "" ;
		$creddeb=isset($_POST['creddeb']) ? $_POST['creddeb'] : "" ;
		$listind=isset($_GET['gotoind']) ? $_GET['gotoind'] : "" ;
			if (!$listind) $listind=isset($_POST['gotoind']) ? $_POST['gotoind'] : "" ;
		$therca=isset($_GET['whichrca']) ? $_GET['whichrca'] : "" ;
			if (!$therca) $therca=isset($_POST['whichrca']) ? $_POST['whichrca'] : ""; 	
		
			$sql = "SELECT * FROM collegebudgets WHERE budgetid='".$budgetid."' AND rcaid='defaultbud'";
			$result_set = $database->query($sql);
			$result_row=$database->fetch_array($result_set);

			if ($result_row['budgetid']=='') {
				echo "Tell your DSL to get going; budgets haven't been set for this semester yet!<br/>";
			} 
		
		if (($rcauser) AND ($theexpense)) { // 
			#//enter expense form
			$vendor=$_POST['vendor'];
			$thenum=$_POST['numentry'];
			$datereceipt= mktime(10,0,0, $_POST['month'], $_POST['day1'], $_POST['year']);
			$whenentered=date("U");
			$sql = "INSERT INTO rcaexpenses (";
	  		$sql .= "rcaname, budgetid, datereceipt, expenditure, numentry, vendor, creddeb, whenentered";
	  		$sql .= ") VALUES ('";
			$sql .= $database->escape_value($rcauser) ."', '";
			$sql .= $database->escape_value($budgetid) ."', '";
			$sql .= $database->escape_value($datereceipt) ."', '";
			$sql .= $database->escape_value($theexpense) ."', '";
			$sql .= $database->escape_value($thenum) ."', '";
			$sql .= $database->escape_value($vendor) ."', '";
			$sql .= $database->escape_value($creddeb) ."', '";
			$sql .= $database->escape_value($whenentered) ."')";
			if ($database->query($sql)) {echo "Entry Successful and listed below.<br/>";} 
			else {echo "nothing doing<br/>";}
		} // if (($rcauser) AND ($theexpense)) 
		
		if ($keepgoing) { // 
			$sql="SELECT * FROM collegebudgets WHERE budgetid='".$budgetid."' AND rcaid='".$rcauser."'";
			$result_set = $database->query($sql);
			$rowstuffrcauser=$database->fetch_array($result_set);
			if ($rowstuffrcauser['rcaid']!="") {
				$rcabudget=$rowstuffrcauser['budget'];
			} else { // if (!$rowstuffrcauser['rcaid']=="")
				$sql="SELECT * FROM collegebudgets WHERE budgetid='".$budgetid."' AND rcaid='defaultbud'";
				$result_set = $database->query($sql);
				$rowstuff=$database->fetch_array($result_set);
				$rcabudget=$rowstuff['budget'];
			} // if (!$rowstuffrcauser['rcaid']=="")
			$sql="SELECT * FROM rcaexpenses WHERE budgetid='".$budgetid."' AND rcaname='".$rcauser."' ORDER BY numentry";
			$result_set = $database->query($sql);
			$rowstuffrca=$database->fetch_array($result_set);
				if ($rowstuffrca['rcaname']=="") {
					echo "There are no entries from ".$rcauser.".<br/>";
					echo "The budget is: $".$rcabudget."<br/><br/>";
				} else { // if (($rowstuffrca['rcaname']=="") AND ($totalbudgets != 0))
					echo "Below are your expense entries.<br/>To edit or delete an entry, click on the entry number.<br/><br/>";
					?> 
					<table width="850" cellspacing="3">
					<tr id="one"><td>
						<?
						echo "Entry #";
						?>
						</td><td>
						<?
						echo "Date";
						?>
						</td><td>
						<?
						echo "Date Entered";
						?>
						</td><td>
						<?
						echo "Vendor";
						?>
						</td><td>
						<?
						echo "Amount";
						?>
                        </td><td>
						<?
						echo "Credit/Debit";
						?>
						</td></tr>
						<?
						$totalspent=0;
						$sql="SELECT * FROM rcaexpenses WHERE budgetid='".$budgetid."' AND rcaname='".$rcauser."' ORDER BY numentry";
						$result_set = $database->query($sql);
						while ($result_row =$database->fetch_array($result_set)){ // 
							$numberofent=$numberofent+1;
							?>
							<tr><td>
							<?
							echo '<a href="?rcaid='.$rcauser.'&thedisplay=block&budgetid='.$budgetid.'&numentry='.$result_row['numentry'].'">'.$result_row['numentry'].'</a>'; 
							?>
							</td><td>
							<?
							echo (date("n/j/Y", $result_row['datereceipt']));
							?>
							</td><td>
							<?
							echo (date("n/j/Y", $result_row['whenentered']));
							?>
							</td><td>
							<?
							echo $result_row['vendor'];
							?>
							</td><td>
							<?
							echo $result_row['expenditure'];
							$totalspent=$totalspent+$result_row['expenditure'];
							?>
                            </td><td>
							<?
							echo $result_row['creddeb'];
							?>
							</td></tr>
							<?
						} // while ($result_row = mysql_fetch_array($resulto, MYSQL_ASSOC))
						?>
						<tr><td></td><td align="right">
						
						</td><td align="left">
						
						</td><td align="right" id="ten">
						<?
						echo "Total Spent:";
						?>
						</td><td align="left" id="ten">
						<?
						echo $totalspent;
						?>
						</td></tr></table>
						<?
						$remaining=$rcabudget-$totalspent;
						?><div id="tablehead"><?
						echo "Remaining Budget= ".$remaining."<br/><br/>";
						?></div><?
				} // if ($rowstuffrca['rcaname']=="")

				if (($rcauser) AND (!$dsluser)) { // 
					echo "If you would like to enter a new expense, please complete the form below.<br/><br/>";
					$numberofent=$numberofent+1;
					?>
					<table><form action = "<? echo $self; ?>" method="post">
					<tr><td>Date of Purchase: </td><td>
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
					<tr><td>Vendor</td><td>
					<input type="text" name="vendor" />
					</td></tr>
					<tr><td>Amount:</td><td>
					<input type="text" name="expense"/></td></tr>
                    <tr><td>Credit/Debit</td><td>
					<input type="radio" name="creddeb" value="Credit"/>Credit<br/>
                    <input type="radio" name="creddeb" value="Debit"/>Debit<br/>
                    </td></tr>
					<input type="hidden" name="numentry" value="<? echo $numberofent; ?>"/>
					<input type="hidden" name="task" value="enterexpense"/>
					<tr><td><input type="submit" value="submit" /></td></tr>
					</form></table>
						<? 
				} else { // if (($rcauser) AND (!$dsluser)) 
					WhichBudDo();
					echo '<a href="?task=listrca">RCA EXPENSES</a>';	
				} // if (($rcauser) AND (!$dsluser)) 
			
		} // if ($keepgoing)
						
	?></div> <?
	break;
	case "editexpenses":
	break;
	default:
	echo "yikes end";
} // switch ($task)
?>
                </div> <!-- <div id="calenderwork"> -->
                </div> <!-- <div id="workarea"> -->

   