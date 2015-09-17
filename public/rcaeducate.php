<? include_once("../includes/rcainitialize.php");
include("../includes/layouts/header.php");   
if (isset($_SESSION["casnetid"])) {
include("../includes/layouts/setdsl.php");
include("../includes/layouts/menu.php"); 



$self = htmlentities($_SERVER['PHP_SELF']);
$todisplay=isset($_GET['tdisplay']) ? $_GET['tdisplay'] : "none";

$numentry=isset($_GET['numentry']) ? $_GET['numentry'] : "";
	if (!$numentry) 	$numentry=isset($_POST['numentry']) ? $_POST['numentry'] : "";	

$task=isset($_GET['task']) ? $_GET['task'] : "" ;
	if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
$college=isset($_POST['SelCollege']) ? $_POST['SelCollege'] : "" ;
$whatstep=isset($_POST['whatstep']) ? $_POST['whatstep'] : "" ;
$thewhat=isset($_POST['thewhat']) ? $_POST['thewhat'] : "" ;

if ($college=="") {$college = getdslcollege($dsluser);}
?>
<div id="blanket" style="display: <? echo $todisplay; ?>"></div>

<div class="displaymod2" style="display:<? echo $todisplay; ?>">
<div id="oncalllogstuff2">
<div class="goingout">
<? echo ('<a href="?">CLOSE</a>'); ?>
</div> <!-- <div id="goinout"> -->
<div id="oncallposition">

<? 
	if ($whatstep) {
		$sql = "UPDATE rcamanual SET ";
		$sql .= "thewhat='". $database->escape_value($thewhat) ."' ";
		$sql .= "WHERE numentry='". $database->escape_value($numentry)."'";
		$database->query($sql);
		if ($database->affected_rows() == 1) {
			echo "<br/>That entry has been updated.";
			$whatstep="alldone";
		} else {echo "<br/> Announcement was not updated.";}
		
	} else {
		$sql="SELECT thewhat FROM rcamanual WHERE numentry='".$numentry."'";
		$result_set = $database->query($sql);
		$rowstuffa = $database->fetch_array($result_set);
		?>
        <form action = "<? echo $self; ?>" method="post">

        <textarea name="thewhat" id="editor1" rows="30" cols="100"><? echo $rowstuffa['thewhat']; ?>
        </textarea><br/>
            <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'editor1' );
            </script>
        <input type="hidden" name="dslid" value="<? echo $dsluser; ?>"/>
				<input type="hidden" name="numentry" value="<? echo $numentry; ?>"/>
				<input type="hidden" name="whatstep" value="update"/>
				<br/><input type="submit" value="submit" />
				</form><br/><br/>
        <? } //if ($whatstep)
		
?>
</div> <!-- <div id="oncallposition"> -->
</div> <!-- <div id="oncallstuff"> -->
</div> <!-- <div class="displaymod2" : > -->
<div id="workarea"> 
		<div id="workarearelative"><div id="upperbox">
        <?
		if ($whatstep=="alldone"){
			echo "That entry has been updated.";
		}
		?></div>
        <div id="accordian">
<ul class="topnav">
	<li><a href="#">I. Role of the RCA</a>
    	<ul>
					
                    <li><a href="#">A. Mission of the RCA Program at Princeton</a>
                    	<ul>
							<li>
                            <?
							$numentry=2;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>   
					<li><a href="#">B. RCA Job Description</a>
                    	<ul>
							<li>
                            <?
							$numentry=1;
							spitout($numentry, $dsluser);
							?> 
                            </li>
						</ul></li>	 
					<li><a href="#">C. Connecting Mission to Action</a>
                    	<ul>
							<li>
                            <?
							$numentry=3;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
					<li><a href="#">D. Detailed Expectations of RCAs</a>
                     	<ul>
							<li>
                             <?
							$numentry=4;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
                    <li><a href="#">E. Important RCA Policies</a>
                     	<ul>
							<li>
                             <?
							$numentry=5;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
					
				</ul>
    </li>            
	<li><a href="#">II. Community Building</a>
		<ul>
			 <li><a href="#">A. What Makes for a Good Community</a>
              	<ul>
							<li>
                             <?
                            $numentry=8;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
			 <li><a href="#">B. Your First Zee Meeting and Orientation Week</a>
              	<ul>
							<li>
                             <?
                            $numentry=9;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>

             <li><a href="#">C. One on One Conversations</a>
              	<ul>
							<li>
                             <?
                            $numentry=10;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">D. Group Dynamics</a>
              	<ul>
							<li>
                             <?
                            $numentry=11;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">E. Residential Education Program</a>
              	<ul>
							<li>
                             <?
                            $numentry=12;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>                                 
			 <li><a href="#">F. Engaging with Diversity</a>
              	<ul>
							<li>
                             <?
                            $numentry=13;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
			 <li><a href="#">G. Considerations When Advising Students from Different Countries</a>
              	<ul>
							<li>
                             <?
                            $numentry=61;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
 			 <li><a href="#">H. Advising First-Generation Students</a>
              	<ul>
							<li>
                             <?
                            $numentry=71;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
			 <li><a href="#">I. How to Handle Roommate Situations or Other Conflicts</a>
              	<ul>
							<li>
                             <?
                            $numentry=14;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
			 <li><a href="#">J. Role of DSL in Supporting RCAs with Community Building Efforts</a>
              	<ul>
							<li>
                             <?
                            $numentry=15;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
		</ul>
	</li>
	<li><a href="#">III. Community Caretaking</a>
		<ul>
			 <li><a href="#">A. <i>Rights, Rules, Responsibilities</i> and Your Zees</a>
              	<ul>
							<li>
                             <?
                            $numentry=16;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
			 <li><a href="#">B. The On-Call System</a>
              	<ul>
							<li>
                             <?
                            $numentry=17;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">C. Caretaking and Obligations around Alcohol and RCA Drug Policy</a>
              	<ul>
							<li>
                             <?
                            $numentry=18;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">D. Crisis Response</a>
              	<ul>
							<li>
                             <?
                            $numentry=19;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">E. Lines of Communication and the Role of the DSL</a>
              	<ul>
							<li>
                             <?
                            $numentry=20;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>                                 
		</ul>
	</li>
    
	<li><a href="#">IV. Health and Wellness</a>
		<ul>
			 <li><a href="#">A. Having Conversations with Your Zees about Alcohol: Alcohol and the Princeton Social Scene </a>
              	<ul>
							<li>
                             <?
                            $numentry=21;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
			 <li><a href="#">B. Helping Zees Find Their Niche, Make Friends, and Combat Homesickness</a>
              	<ul>
							<li>
                             <?
                            $numentry=22;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">C. Helping Zees with Mental Health Concerns</a>
              	<ul>
							<li>
                             <?
                            $numentry=23;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             
             <li><a href="#">D. How to Make Referrals to Zees for Professional Help </a>
              	<ul>
							<li>
                             <?
                            $numentry=25;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>      
			<li><a href="#">E. Role of the DSL in Supporting Students of Concern</a>
              	<ul>
							<li>
                             <?
                            $numentry=26;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li> 
             <li><a href="#">F. Community Health and Wellness Resources</a>
              	<ul>
							<li>
                             <?
                            $numentry=27;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>                              
		</ul>
	</li> 
    
    <li><a href="#">V. What Every RCA Should Know about Academic Support</a>
		<ul>
			 <li><a href="#">A. Role of College Dean and Director of Studies </a>
              	<ul>
							<li>
                             <?
                            $numentry=30;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
			 <li><a href="#">B. A-Team System; What This Means for You as an RCA</a>
              	<ul>
							<li>
                             <?
                            $numentry=31;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">C. Academic Support Services</a>
              	<ul>
							<li>
                             <?
                            $numentry=32;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">D. Academic Regulations and the Honor System</a>
              	<ul>
							<li>
                             <?
                            $numentry=33;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>                            
		</ul>
	</li> 
    
    <li><a href="#">VI. Key Documents and Tools</a>
		<ul>
			 <li><a href="pdfs/zeecheck.pdf">First Zee Meeting Script</a>
              </li>
			 <li><a href="pdfs/rcontract.pdf">Roommate Contract</a>
              	</li>                   
		</ul>
	</li> 
    
    <li><a href="#">VII. Important University Resources</a>
		<ul>
			 <li><a href="#">A. Resources at a Glance </a>
              	<ul>
							<li>
                             <?
                            $numentry=41;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
			 <li><a href="#">B. The College Staff</a>
              	<ul>
							<li>
                             <?
                            $numentry=42;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">C. Counseling and Psychological Services</a>
              	<ul>
							<li>
                             <?
                            $numentry=43;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">D. Davis International Center</a>
              	<ul>
							<li>
                             <?
                            $numentry=44;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">E. Campus Dining </a>
              	<ul>
							<li>
                             <?
                            $numentry=45;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>      
			<li><a href="#">F. Office of Disability Services</a>
              	<ul>
							<li>
                             <?
                            $numentry=46;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>       
               <li><a href="#">G. Facilities and Building Services</a>
              	<ul>
							<li>
                             <?
                            $numentry=47;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">H. Carl A. Fields Center for Equality and Cultural Understanding</a>
              	<ul>
							<li>
                             <?
                            $numentry=48;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>      
			<li><a href="#">I. University Health Services</a>
              	<ul>
							<li>
                             <?
                            $numentry=49;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>  
                         <li><a href="#">J. Athletic Medicine</a>
              	<ul>
							<li>
                             <?
                            $numentry=50;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">K. LGBT (Lesbian Gay Bisexual Transgender) Center</a>
              	<ul>
							<li>
                             <?
                            $numentry=51;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>       
            <li><a href="#">L. Ombuds Office</a>
              	<ul>
							<li>
                             <?
                            $numentry=53;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">M. Pace Center for Civic Engagement</a>
              	<ul>
							<li>
                             <?
                            $numentry=54;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>      
			<li><a href="#">N. Public Safety</a>
              	<ul>
							<li>
                             <?
                            $numentry=55;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li> 
             <li><a href="#">O. Office of Religious Life</a>
              	<ul>
							<li>
                             <?
                            $numentry=56;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>
             <li><a href="#">P. Sexual Harassment/Assault Adivising, Resource, and Education</a>
              	<ul>
							<li>
                             <?
                            $numentry=57;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>      
			<li><a href="#">Q. Women*s Center</a>
              	<ul>
							<li>
                             <?
                            $numentry=58;
							spitout($numentry, $dsluser);
							?>
                            </li>
						</ul></li>   
                                                     
		</ul>
	</li> 
</ul>

</div> <!--- <div id="accordian"> --->



	
 </div>  <!---- <div id="workarearelative">  --->
 </div> <!-- <div id="workarea"> -->

<div id="oncallpdf"><a href="pdfs/RCAManual.pdf">PDF OF MANUAL</a></div> 
<? 

}
include("../includes/layouts/footer.php"); ?>

