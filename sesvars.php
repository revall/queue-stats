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

if(isset($_POST['List_Queue'])) {
	$queue="";
	foreach($_POST['List_Queue'] as $valor) {
		$queue.=stripslashes($valor).",";
	}
	$queue=substr($queue,0,-1);
    $_SESSION['QSTATS']['queue']=$queue;
} else {
	$queue="'NONE'";
}

if(isset($_POST['List_Agent'])) {
    $agent="";
	foreach($_POST['List_Agent'] as $valor) {
		$agent.=stripslashes($valor).",";
	}
	$agent=substr($agent,0,-1);
    $_SESSION['QSTATS']['agent']=$agent;
} else {
	$agent="''";
}

/*
if(isset($_POST['queue'])) {
   $queue = stripslashes($_POST['queue']);
   $_SESSION['QSTATS']['queue']=$queue;
} else {
   $queue="'NONE'";
}
*/

if(isset($_POST['start'])) {
   $start = $_POST['start'];
   $_SESSION['QSTATS']['start']=$start;
} else {
   $start = date('Y-m-d');
}

if(isset($_POST['end'])) {
   $end = $_POST['end'];
   $_SESSION['QSTATS']['end']=$end;
} else {
   $end = date('Y-m-d');
}

if(isset($_SESSION['QSTATS']['start'])) {
   $start = $_SESSION['QSTATS']['start'];
}

if(isset($_SESSION['QSTATS']['end'])) {
   $end = $_SESSION['QSTATS']['end'];
}

if(isset($_SESSION['QSTATS']['queue'])) {
   $queue = $_SESSION['QSTATS']['queue'];
}

if(isset($_SESSION['QSTATS']['agent'])) {
   $agent = $_SESSION['QSTATS']['agent'];
}

$fstart_year  = substr($start,0,4);
$fstart_month = substr($start,5,2);
$fstart_day = substr($start,8,2);


$fend_year  = substr($end,0,4);
$fend_month = substr($end,5,2);
$fend_day = substr($end,8,2);


$timestamp_start = return_timestamp($start);
$timestamp_end   = return_timestamp($end);
$elapsed_seconds = $timestamp_end - $timestamp_start;
//$period          = floor(($elapsed_seconds / 60) / 60 / 24) + 1; 
$period          = floor(($elapsed_seconds / 60) /60) + 1; 

if(!isset($_SESSION['QSTATS']['start'])) {
	if(basename($self)<>"index.php") {
		Header("Location: ./index.php");
	}
}


?>
