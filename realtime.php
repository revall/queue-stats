<?php
/*
   Copyright 2007, 2008 Nicolás Gudiño

   This file is part of Asternic Call Center Stats.

    Asternic Call Center Stats is free software: you can redistribute it 
    and/or modify it under the terms of the GNU General Public License as 
    published by the Free Software Foundation, either version 3 of the 
    License, or (at your option) any later version.

    Asternic Call Center Stats is distributed in the hope that it will be 
    useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Asternic Call Center Stats.  If not, see 
    <http://www.gnu.org/licenses/>.
*/

require_once("config.php");
include("sesvars.php");
if(isset($_SESSION['QSTATS']['hideloggedoff'])) {
    $ocultar=$_SESSION['QSTATS']['hideloggedoff'];
} else {
    $ocultar="false";
}
?>
<!-- http://www.house.com.ar/quirksmode -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Asternic Call Center Stats</title>
	<style type="text/css" media="screen">@import "css/basic.css";</style>
	<style type="text/css" media="screen">@import "css/tab.css";</style>
	<style type="text/css" media="screen">@import "css/table.css";</style>
	<style type="text/css" media="screen">@import "css/fixed-all.css";</style>
	<script type="text/javascript" src="js/sorttable.js"></script>
	<script type="text/javascript" src="js/prototype-1.4.0.js"></script>
        <script language='javascript'>
		Event.observe(window, 'load', init, false);
		function init(){
			getdata();
		}
		function getdata(){
 		    var url = 'auxstate_helper.php';
		    var target = 'content_refresh';
		    var myAjax = new Ajax.PeriodicalUpdater(target, url, {asynchronous:true, frequency:5});
		}
		function sethide(elemento) {
		   var url  = 'set_sesvar.php';
                   if(elemento.checked==true) {
		   	var pars = 'sesvar=hideloggedoff&value=true';
		    } else {
		   	var pars = 'sesvar=hideloggedoff&value=false';
                    }
			var myAjax = new Ajax.Request(
				url, 
				{
				method: 'get', 
				parameters: pars
				});
                }
 
	</script>
</head>
<body>
<?php include("menu.php"); ?>
<div id="main">
        <div id="contents" style='min-width: 900px'>

<div style='min-width: 870px'>

<?php
echo "<h2>".$lang[$language]['current_agent_status']."</h2>";

/*
// Populates an array with the EVENTS ids
$query = "SELECT * FROM qevent ORDER BY event_id";
$res = consulta_db($query,0,0);
while($row = db_fetch_row($res)) {
        $event_array["$row[0]"] = $row[1];
}


$color['unavailable']="#dadada";
$color['available']="#00ff00";

for($a=1;$a<10;$a++) {
    $b=$a-1;
    $color[$a]=$auxcolor[$b];
}



$popup['unavailable']="Logged off";
$popup['available']="Staffed";
for($a=1;$a<10;$a++) {
    $b=$a-1;
    $popup["$a"]=$aux[$b];
}
*/
?>

<div id='content_refresh'>
</div>



</div>
</div>
</div>
<script type="text/javascript" src="js/wz_tooltip.js"></script>
</body>
</html>
