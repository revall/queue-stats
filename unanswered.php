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
	<script type="text/javascript" src="js/flashobject.js"></script>
	<script type="text/javascript" src="js/sorttable.js"></script>

<!--[if gte IE 5.5000]>
<style type='text/css'> img { behavior:url(pngbehavior.htc) } </style>
<![endif]-->

<!--[if IE]>
<link 
 href="css/fixed-ie.css" 
 rel="stylesheet" 
 type="text/css" 
 media="screen"> 
<script type="text/javascript"> 
onload = function() { content.focus() } 
</script> 
<![endif]-->
</head>
<?php

$graphcolor =  "&bgcolor=0xF0ffff&bgcolorchart=0xdfedf3&fade1=ff6600&fade2=ff6600&colorbase=0xfff3b3&reverse=1";
$graphcolor2 = "&bgcolor=0xF0ffff&bgcolorchart=0xdfedf3&fade1=ff6600&colorbase=fff3b3&reverse=1&fade2=0x528252";

// ABANDONED CALLS

$query = "SELECT time, queuename, agent, event, ";
$query.= "data1, data2, data3 FROM queue_log ";
$query.= "WHERE time >= '$start' AND time <= '$end' ";
$query.= "AND queuename IN ($queue) AND event IN ('ABANDON', 'EXITWITHTIMEOUT') ORDER BY time";
$res = consulta_db($query,$DB_DEBUG,$DB_MUERE,0,$midb);

$abandon_calls_queue = Array();
$abandon=0;
$abandoned=0;
$timeout=0;

if(db_num_rows($res)>0) {

while($row=db_fetch_row($res)) {

	if($row[3]=="ABANDON") {
 		$abandoned++;
		$abandon_end_pos+=$row[4];
		$abandon_start_pos+=$row[5];
		$total_hold_abandon+=$row[6];
	}
	if($row[3]=="EXITWITHTIMEOUT") {
 		$timeout++;
	}
	$abandon_calls_queue["$row[1]"]++;
}

if($abandoned > 0) {
	$abandon_average_hold = $total_hold_abandon / $abandoned;
} else {
	$abandon_average_hold = 0;
}
$abandon_average_hold = number_format($abandon_average_hold,0);

if($abandoned > 0) {
	$abandon_average_start = round($abandon_start_pos / $abandoned);
} else {
	$abandon_average_start = 0;
}
$abandon_average_start = number_format($abandon_average_start,0);

if($abandoned > 0) {
	$abandon_average_end = floor($abandon_end_pos / $abandoned);
} else {
	$abandon_average_end = 0;
}
$abandon_average_end = number_format($abandon_average_end,0);

$total_abandon = $abandoned + $timeout;

} else {
 	// No rows returned
	$abandoned = 0;
	$timeout = 0;
	$abandon_average_hold  = 0;
	$abandon_average_start = 0;
	$abandon_average_end   = 0;
	$total_abandon         = 0;
}


$start_parts = explode(" ,:", $start);
$end_parts   = explode(" ,:", $end);

?>
<body>
<?php include("menu.php"); ?>
<div id="main">
	<div id="contents">
		<TABLE width='99%' cellpadding=3 cellspacing=3 border=0>
		<THEAD>
		<TR>
			<TD valign=top width='50%'>
				<TABLE width='100%' border=0 cellpadding=0 cellspacing=0>
				<CAPTION><?php echo $lang["$language"]['report_info']?></CAPTION>
				<TBODY>
				<TR>
					<TD><?php echo $lang["$language"]['queue']?>:</TD>
					<TD><?php echo $queue?></TD>
				</TR>
    	        </TR>
        	       	<TD><?php echo $lang["$language"]['start']?>:</TD>
            	   	<TD><?php echo $start_parts[0]?></TD>
				</TR>
    	        </TR>
        	    <TR>
            	   	<TD><?php echo $lang["$language"]['end']?>:</TD>
               		<TD><?php echo $end_parts[0]?></TD>
	            </TR>
    	        <TR>
        	       	<TD><?php echo $lang["$language"]['period']?>:</TD>
            	   	<TD><?php echo $period?> <?php echo $lang["$language"]['hours']?></TD>
	            </TR>
				</TBODY>
				</TABLE>

			</TD>
			<TD valign=top width='50%'>

				<TABLE width='100%' border=0 cellpadding=0 cellspacing=0>
				<CAPTION><?php echo $lang["$language"]['unanswered_calls']?></CAPTION>
				<TBODY>
		        <TR> 
                  <TD><?php echo $lang["$language"]['number_unanswered']?>:</TD>
		          <TD><?php echo $total_abandon?> <?php echo $lang["$language"]['calls']?></TD>
	            </TR>
                <TR>
                  <TD><?php echo $lang["$language"]['avg_wait_before_dis']?>:</TD>
                  <TD><?php echo $abandon_average_hold?> <?php echo $lang["$language"]['secs']?></TD>
                </TR>
		        <TR>
                  <TD><?php echo $lang["$language"]['avg_queue_pos_at_dis']?>:</TD>
		          <TD><?php echo $abandon_average_end?></TD>
	            </TR>
                <TR>
                  <TD><?php echo $lang["$language"]['avg_queue_start']?>:</TD>
                  <TD><?php echo $abandon_average_start?></TD>
                </TR>
				</TBODY>
	          </TABLE>

			</TD>
		</TR>
		</THEAD>
		</TABLE>
		<BR>	

		<a name='1'></a>
		<TABLE width='99%' cellpadding=3 cellspacing=3 border=0>
		<CAPTION>
		<a href='#0'><img src='images/go-up.png' border=0 width=16 height=16 class='icon' 
		<?php 
		tooltip($lang["$language"]['gotop'],200);
		?>
		></a>&nbsp;&nbsp;
		<?php echo $lang["$language"]['disconnect_cause']?>
		</CAPTION>
			<THEAD>
			<TR>
			<TD valign=top width='50%' bgcolor='#fffdf3'>
				<TABLE width='99%' cellpadding=1 cellspacing=1 border=0>
				<THEAD>
				<TR>
					<TH><?php echo $lang["$language"]['cause']?></TH>
					<TH><?php echo $lang["$language"]['count']?></TH>
					<TH><?php echo $lang["$language"]['percent']?></TH>
				</TR>
				</THEAD>
				<TBODY>
                <TR> 
                  <TD><?php echo $lang["$language"]['user_abandon']?></TD>
			      <TD><?php echo $abandoned?> <?php echo $lang["$language"]['calls']?></TD>
			      <TD>
					  <?php
						if($total_abandon > 0 ) {
							$percent=$abandoned*100/$total_abandon;
						} else {
							$percent=0;
						}
						$percent=number_format($percent,2);
						echo $percent;
                      ?> 
                   <?php echo $lang["$language"]['percent']?></TD>
		        </TR>
			    <TR> 
                  <TD><?php echo $lang["$language"]['timeout']?></TD>
			      <TD><?php echo $timeout?> <?php echo $lang["$language"]['calls']?></TD>
			      <TD>
					  <?php
						if($total_abandon > 0 ) {
							$percent=$timeout*100/$total_abandon;
						} else {
							$percent=0;
						}
						$percent=number_format($percent,2);
						echo $percent;
                      ?> 
					<?php echo $lang["$language"]['percent']?></TD>
		        </TR>
				</TBODY>
			  </TABLE>
			</TD>
			<TD align=center bgcolor='#fffdf3'>
				<?php
				$query2 = "var1=".$lang["$language"]['abandon']."&val1=".$abandoned."&";
				$query2 .= "var2=".$lang["$language"]['timeout']."&val2=".$timeout;
				$query2.="&title=".$lang["$language"]['disconnect_cause']."$graphcolor2";
//				swf_bar($query2,350,211,"chart1",0);
				?>
			</TD>
			</TR>
			</THEAD>
			</TABLE>


		<?php
		if(count($abandon_calls_queue)<=0) {
			$abandon_calls_queue[""]=0;
		}
		?>
			<a name='2'></a>
			<TABLE width='99%' cellpadding=3 cellspacing=3 border=0>
			<CAPTION>
			<a href='#0'><img src='images/go-up.png' border=0 width=16 height=16 class='icon' 
			<?php 
			tooltip($lang["$language"]['gotop'],200);
			?>
			></a>&nbsp;&nbsp;
			<?php echo $lang["$language"]['unanswered_calls_qu']?>
			</CAPTION>
			<THEAD>
			<TR>
			<TD valign=top width='50%' bgcolor='#fffdf3'>
				<TABLE width='99%' cellpadding=1 cellspacing=1 border=0>
				<THEAD>
                <TR> 
				   	<TH><?php echo $lang["$language"]['queue']?></TH>
					<TH><?php echo $lang["$language"]['count']?></TH>
					<TH><?php echo $lang["$language"]['percent']?></TH>
                </TR>
				</THEAD>
				<TBODY>
				<?php
				$countrow=0;
				$query2="";
				asort($abandon_calls_queue);
				foreach($abandon_calls_queue as $key=>$val) {
					$cual = $countrow%2;
					if($cual>0) { $odd = " class='odd' "; } else { $odd = ""; }
					if($total_abandon > 0 ) {
						$percent = $val * 100 / $total_abandon;
					} else {
						$percent = 0;
					}
					$percent =number_format($percent,2);
					echo "<TR $odd><TD>$key</TD><TD>$val calls</TD><TD>$percent ".$lang["$language"]['percent']."</TD></TR>\n";
					$countrow++;
					$query2.="var$countrow=$key&val$countrow=$val&";
				}
				$query2.="title=".$lang["$language"]['unanswered_calls_qu']."$graphcolor";
				?>
			  </TBODY>
			  </TABLE>
			</TD>
			<TD valign=top width="50%" align=center bgcolor='#fffdf3'>
				<?php 
				//if ($countrow>1) {
//			    	swf_bar($query2,350,211,"chart2",0);
			   	//} 
               	?>
			</TD>
			</TR>
			</THEAD>
			</TABLE>
			<BR>
			<BR>
</div>
</div>
<script type="text/javascript" src="js/wz_tooltip.js"></script>
</body>
</html>
