<?php include_once("setdsl.php");  	?>
    
    			<div id="menulow">
                        <div id="llmenu">
                        <?
                        echo '<a href="rcahome.php">HOME</a>';
                        ?></div>

                                         
               
               <div id="llmenu">
                    <?
					echo '<a href="rcaoncall.php">ON-CALL SCHEDULE</a>';
					?></div>
                    
                    <div id="llmenu">
                    <?
					echo '<a href="rcainservices.php">INSERVICES</a>';
					?></div>    
                    
		<? if ($dsluser) { ?>
                   <div id="llmenu">
                   <?
					echo '<a href="rcainservices.php?task=listRCAs">RCA INSERVICES</a>';
					?></div>
                     
                	<div id="llmenu">
                    <?
					echo '<a href="rcaadmin.php">RCA ADMIN</a>';
					?></div>
                   
                    
                    <div id="llmenu">
                    <?
					echo '<a href="rcaexpenses.php">RCA EXPENSES</a>';
					?></div>
                  
                    <div id="llmenu">
                    <?
					echo '<a href="SetDates.php">CALENDAR SETUP</a>';
					?></div>
				<!--	<div id="llmenu">
                   <?
					// echo '<a href="rcaorderins.php?task=listrcas">ORDER-IN ADMIN</a>';
					?></div> -->
					
					<?
	} else {
	?> 
                    <div id="llmenu">
                    <?
						echo '<a href="rcaexpenses.php?task=enterexpense">EXPENSES</a>';
						?></div>
                    
				<!--	<div id="llmenu">
                <? 
					// echo '<a href="rcaorderins.php">ORDER-IN MENU</a>';
					?></div> -->
                   
    <?
	} // if($dsluser)
	?>
					<div id="llmenu">
                   <?
						echo '<a href="rcaeducate.php">RCA MANUAL</a>';
					?></div>
                    <div id="llmenu">
                   <?
						echo '<a href="downloads.php">FALL TRAINING</a>';
					?></div>
                    <div id="llmenu">
                   <?
						echo '<a href="videos.php">OTHER RESOURCES</a>';
					?></div>
                    <div id="llmenu">
                    <?	
					echo '<a href="rcalogout.php">LOGOUT</a>';
					?></div><!-- <div id="llmenu"> -->
                
                </div> <!-- <div id="menulow"> -->