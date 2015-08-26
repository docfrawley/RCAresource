<? include_once("../includes/rcainitialize.php");
include("../includes/layouts/header.php"); 
if (isset($_SESSION["casnetid"])) {
include("../includes/layouts/setdsl.php");  
include("../includes/layouts/menu.php"); 

?>

<div id="workarea">
<div id="workarearelative">

<div id="downloads">
    	
        <ul>
            <li> FALL TRAINING GENERAL
                <ul>
                	<li><a href="pdfs/2014schedule.pdf">Fall Training Schedule</a></li>
                    <li><a href="pdfs/2014manual.pdf">RCA Manual</a></li>
                </ul>
    		</li>
            <li> FOR TUESDAY TRAINING
            	<ul>
                	<li><a href="https://www.youtube.com/watch?v=UXI9w0PbBXY">LGBT Video: Transgender Basics (19 minutes)</a></li>
                    <li><a href="https://mediacentral.princeton.edu/media/0_f8fwqv25">LGBT Video: Student Panel at the Every Voice Conference (22 minutes)</a></li>
                    <li><a href="https://lgbt.ucsf.edu/glossary-terms">LGBT Reading: Glossary of Terms From the UCSF LGBT Resource Center</a></li>
                    <li><a href="pdfs/TAH.pdf">LGBT Reading: Being an Ally to Transgender Students</a></li>
                    <li><a href="pdfs/BTI.pdf">LGBT Reading: Homophobia, Biphobia, Transphobia & Heterosexism</a></li>
                    <li><a href="pdfs/comingout.pdf">LGBT Reading: Coming Out</a></li>
                    <li><a href="pdfs/roommates.pdf">LGBT Reading: Suggested Roommate Intervention Strategy</a></li>
                    <li><a href="https://www.youtube.com/watch?v=5t4HifzzDdQ">LGBT Video: Brief Bonus Video (6 minutes)</a></li>
                </ul>
            </li>
            <li> FOR WEDNESDAY TRAINING
            	<ul>
                	<li><a href="pdfs/2014share.pdf">SHARE Reading</a></li>
                </ul>
            </li>
            <li> FOR THURSDAY TRAINING
            	<ul>
                	<li><a href="http://www.mtvu.com/video/?vid=994169">Video for CPS Training</a></li>
                    <li><a href="#" onclick="getnewview('video6')">Eating Concerns Video</a></li>
                    <li><a href="https://www.princeton.edu/concur">Complete Concur Profile</a></li>
                </ul>
            </li>
        </ul>
    </div>
    
    <div id="GoodToHave">
    </div>
<div class="displaymod22" id="video6" style="display:none">
<div id="oncalllogstuff2">
Eating Concerns Video
        <div class="goingout">
        <a href="#" onclick="getnewview('video6')">CLOSE</a>
        </div> 
<div id="kaltura_player_1389044240" style="width: 720px; height: 435px; float:left" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
<span itemprop="name" content="finalV"></span>
<span itemprop="description" content=""></span>
<span itemprop="duration" content=""></span>
<span itemprop="thumbnail" content=""></span>
<span itemprop="width" content="720"></span>
<span itemprop="height" content="435"></span>
</div>
<script src="http://cdnapi.kaltura.com/p/1449362/sp/144936200/embedIframeJs/uiconf_id/21499231/partner_id/1449362?autoembed=true&entry_id=1_9cjudyxn&playerId=kaltura_player_1389044240&cache_st=1389044240&width=720&height=435"></script>
    </div>
    </div>
</div>
</div> <!-- <div id="workarearelative"> -->
</div> <!-- <div id="workarea"> -->

<? 
}
include("../includes/layouts/footer.php"); ?>