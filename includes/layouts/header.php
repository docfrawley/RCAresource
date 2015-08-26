<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RCA Resource Site</title>
<link href="css/rcaresource.css" rel="stylesheet" type="text/css" />
<link href="css/calendarcss.css" rel="stylesheet" type="text/css" />
<script src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="js/jquery-1.5.2.min.js"></script>
<script type="text/javascript" src="js/scriptbreaker-multiple-accordion-1.js"></script>

<script>
$(document).ready(function() {
	$(".topnav").accordion({
		accordion:false,
		speed: 500,
		closedSign: '[+]',
		openedSign: '[-]'
	});
});

</script>

<script language="JavaScript">
  function tdisplay(whatup) {
	var second=whatup.options[whatup.selectedIndex].value;
	if (second=='other') {
      document.getElementById("othertyped").style.display = "block";
	} else {
		document.getElementById("othertyped").style.display = "none";
	}
  }
</script>

<script> 
	function getnewview(whatup)  {
		var first = document.getElementById(whatup);
		var second = document.getElementById('blanket2');	
		if (first.style.display=="none") {
			first.style.display="block";
			second.style.display="block";
		} else {
			first.style.display="none";	
			second.style.display="none";
		}
		}
		</script>
</head>

<body>
<div id="topbanner"></div>
<div id="namesite">
    Princeton University RCA Resource Site</div>