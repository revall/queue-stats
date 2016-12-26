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

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$begintime = $time;
$inuse      = Array();
$dict_queue = Array();

require("config.php");
require("asmanager.php");
require("realtime_functions.php");
if(isset($_SESSION['QSTATS']['hideloggedoff'])) {
    $ocultar= $_SESSION['QSTATS']['hideloggedoff'];
} else {
    $ocultar="false";
}
if(isset($_SESSION['QSTATS']['filter'])) {
    $filter= $_SESSION['QSTATS']['filter'];
} else {
    $filter="";
}


$am=new AsteriskManager();
$am->connect($manager_host,$manager_user,$manager_secret);

$channels = get_channels ($am);
//echo "<pre>";print_r($channels);echo "</pre>";
foreach($channels as $ch=>$chv) {
  list($chan,$ses) = explode("-",$ch,2);
  $inuse["$chan"]=$ch;
}

$queues   = get_queues   ($am,$channels);
//echo "<pre> queue";print_r($queues);echo "</pre>";

foreach ($queues as $key=>$val) {
  $queue[] = $key;
}

echo "<input type=checkbox name='hidelogedoff' onClick='sethide(this)' ";
if($ocultar=="true") echo " checked ";
echo "> ".$lang[$language]['hide_loggedoff']."\n";

include("realtime_agents.php");
include("realtime_qsummary.php");
include("realtime_qdetail.php");

$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$endtime = $time;
$totaltime = ($endtime - $begintime);
echo "<br/><br/>".$lang[$language]['server_time']." ".date('Y-m-d H:i:s')."<br/>";
echo $lang[$language]['php_parsed']." $totaltime ".$lang[$language]['seconds'];
?>

