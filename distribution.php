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

$graphcolor = "&bgcolor=0xF0ffff&bgcolorchart=0xdfedf3&fade1=ff6600&fade2=ff6314&colorbase=0xfff3b3&reverse=1";
$graphcolorstack = "&bgcolor=0xF0ffff&bgcolorchart=0xdfedf3&fade1=ff6600&colorbase=fff3b3&reverse=1&fade2=0x528252";

// ABANDONED CALLS

$query = "SELECT time, queuename, agent, event data1, data2, data3 FROM queue_log ";
$query.= "WHERE time >= '$start' AND time <= '$end' ";
$query.= "AND queuename IN ($queue,'NONE') AND event IN ('ABANDON', 'EXITWITHTIMEOUT','COMPLETECALLER','COMPLETEAGENT','ADDMEMBER','REMOVEMEMBER','AGENTCALLBACKLOGIN','AGENTCALLBACKLOGOFF') ORDER BY time";

$query_comb     = "";
$login          = 0;
$logoff         = 0;
$dias           = Array();
$logout_by_day  = Array();
$logout_by_hour = Array();
$logout_by_dw   = Array();
$login_by_day   = Array();
$login_by_hour  = Array();
$login_by_dw    = Array();

$res = consulta_db($query,$DB_DEBUG,$DB_MUERE,0,$midb);

if(db_num_rows($res)>0) {

	while($row=db_fetch_row($res)) {
		$partes_fecha = explode(" ",$row[0]);
		$partes_hora  = explode(":",$partes_fecha[1]);

		$timestamp = return_timestamp($row[0]);
		$day_of_week = date('w',$timestamp);
			
		$dias[] = $partes_fecha[0];
		$horas[] = $partes_hora[0];

		if($row[3]=="ABANDON" || $row[3]=="EXITWITHTIMEOUT") {
 			$unanswered++;
			$unans_by_day["$partes_fecha[0]"]++;
			$unans_by_hour["$partes_hora[0]"]++;
			$unans_by_dw["$day_of_week"]++;
		}
		if($row[3]=="COMPLETECALLER" || $row[3]=="COMPLETEAGENT") {
 			$answered++;
			$ans_by_day["$partes_fecha[0]"]++;
			$ans_by_hour["$partes_hora[0]"]++;
			$ans_by_dw["$day_of_week"]++;

			$total_time_by_day["$partes_fecha[0]"]+=$row[5];
			$total_hold_by_day["$partes_fecha[0]"]+=$row[4];

			$total_time_by_dw["$day_of_week"]+=$row[5];
			$total_hold_by_dw["$day_of_week"]+=$row[4];
		
			$total_time_by_hour["$partes_hora[0]"]+=$row[5];
			$total_hold_by_hour["$partes_hora[0]"]+=$row[4];
		}
		if($row[3]=="ADDMEMBER" || $row[3]=="AGENTCALLBACKLOGIN") {
 			$login++;
			$login_by_day["$partes_fecha[0]"]++;
			$login_by_hour["$partes_hora[0]"]++;
			$login_by_dw["$day_of_week"]++;
		}
		if($row[3]=="REMOVEMEMBER" || $row[3]=="AGENTCALLBACKLOGOFF") {
 			$logoff++;
			$logout_by_day["$partes_fecha[0]"]++;
			$logout_by_hour["$partes_hora[0]"]++;
			$logout_by_dw["$day_of_week"]++;
		}
	}
	$total_calls = $answered + $unanswered;
	$dias  = array_unique($dias);
	$horas = array_unique($horas);
    asort($dias);
	asort($horas);
} else {
 	// No rows returned
	$answered = 0;
	$unanswered = 0;
}


$start_parts = explode(" ,:", $start);
$end_parts   = explode(" ,:", $end);

$cover_pdf = $lang["$language"]['queue'].": ".$queue."\n";
$cover_pdf.= $lang["$language"]['start'].": ".$start_parts[0]."\n";
$cover_pdf.= $lang["$language"]['end'].": ".$end_parts[0]."\n";
$cover_pdf.= $lang["$language"]['period'].": ".$period." ".$lang["$language"]['hours']."\n\n";
$cover_pdf.= $lang["$language"]['number_answered'].": ".$answered." ".$lang["$language"]['calls']."\n";
$cover_pdf.= $lang["$language"]['number_unanswered'].": ".$unanswered." ".$lang["$language"]['calls']."\n";
$cover_pdf.= $lang["$language"]['agent_login'].": ".$login."\n";
$cover_pdf.= $lang["$language"]['agent_logoff'].": ".$logoff."\n";
?>
<body>
<?php include("menu.php"); ?>
<div id="main">
	<div id="contents">
		<TABLE width=99% cellpadding=3 cellspacing=3 border=0>
		<THEAD>
		<TR>
			<TD valign=top width=50%>
				<TABLE width=100% border=0 cellpadding=0 cellspacing=0>
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
			<TD valign=top width=50%>

				<TABLE width=100% border=0 cellpadding=0 cellspacing=0>
				<CAPTION><?php echo $lang["$language"]['totals']?></CAPTION>
				<TBODY>
		        <TR> 
                  <TD><?php echo $lang["$language"]['number_answered']?>:</TD>
		          <TD><?php echo $answered?> <?php echo $lang["$language"]['calls']?></TD>
	            </TR>
                <TR>
                  <TD><?php echo $lang["$language"]['number_unanswered']?>:</TD>
                  <TD><?php echo $unanswered?> <?php echo $lang["$language"]['calls']?></TD>
                </TR>
		        <TR>
                  <TD><?php echo $lang["$language"]['agent_login']?>:</TD>
		          <TD><?php echo $login?></TD>
	            </TR>
                <TR>
                  <TD><?php echo $lang["$language"]['agent_logoff']?>:</TD>
                  <TD><?php echo $logoff?></TD>
                </TR>
				</TBODY>
	          </TABLE>

			</TD>
		</TR>
		</THEAD>
		</TABLE>
		<BR>	
			<?php
				if(count($dias)<=0) {
					$dias['']=0;
				}
			?>
			<a name='1'></a>
			<TABLE width=99% cellpadding=1 cellspacing=1 border=0 class='sortable' id='table1'>
			<CAPTION>
			<a href='#0'><img src='images/go-up.png' border=0 width=16 height=16 class='icon' 
			<?php 
			tooltip($lang["$language"]['gotop'],200);
			?>
			></a>&nbsp;&nbsp;
			<?php echo $lang["$language"]['call_distrib_day']?>
			</CAPTION>
				<THEAD>
				<TR>
					<TH><?php echo $lang["$language"]['date']?></TH>
					<TH><?php echo $lang["$language"]['answered']?></TH>
					<TH><?php echo $lang["$language"]['percent_answered']?></TH>
					<TH><?php echo $lang["$language"]['unanswered']?></TH>
					<TH><?php echo $lang["$language"]['percent_unanswered']?></TH>
					<TH><?php echo $lang["$language"]['avg_calltime']?></TH>
					<TH><?php echo $lang["$language"]['avg_holdtime']?></TH>
					<TH><?php echo $lang["$language"]['login']?></TH>
					<TH><?php echo $lang["$language"]['logoff']?></TH>
				</TR>
				</THEAD>
				<TBODY>
				<?php
				$header_pdf=array($lang["$language"]['date'],$lang["$language"]['answered'],$lang["$language"]['percent_answered'],$lang["$language"]['unanswered'],$lang["$language"]['percent_unanswered'],$lang["$language"]['avg_calltime'],$lang["$language"]['avg_holdtime'],$lang["$language"]['login'],$lang["$language"]['logoff']);
				$width_pdf=array(25,23,23,23,23,25,25,20,20);
				$title_pdf=$lang["$language"]['call_distrib_day'];

				$count=1;
				foreach($dias as $key) {
					$cual = $count%2;
					if($cual>0) { $odd = " class='odd' "; } else { $odd = ""; }
					if(!isset($ans_by_day["$key"])) {
						$ans_by_day["$key"]=0;
					}
					if(!isset($unans_by_day["$key"])) {
						$unans_by_day["$key"]=0;
					}
					if($answered > 0) {
						$percent_ans   = $ans_by_day["$key"]   * 100 / $answered;
					} else {
						$percent_ans = 0;
					}
					if($ans_by_day["$key"] >0) {
						$average_call_duration = $total_time_by_day["$key"] / $ans_by_day["$key"];
						$average_hold_duration = $total_hold_by_day["$key"] / $ans_by_day["$key"];
					} else {
						$average_call_duration = 0;
						$average_hold_duration = 0;
					}
					if($unanswered > 0) {
						$percent_unans = $unans_by_day["$key"] * 100 / $unanswered;
					} else {
						$percent_unans = 0;
					}
					$percent_ans   = number_format($percent_ans,  2);
					$percent_unans = number_format($percent_unans,2);
					$average_call_duration_print = seconds2minutes($average_call_duration);
					if($key<>"") {
					$linea_pdf = array($key,$ans_by_day["$key"],"$percent_ans ".$lang["$language"]['percent'],$unans_by_day["$key"],"$percent_unans ".$lang["$language"]['percent'],$average_call_duration_print,number_format($average_hold_duration,0),$login_by_day["$key"],$logout_by_day["$key"]);

					echo "<TR $odd>\n";
					echo "<TD>$key</TD>\n";
					echo "<TD>".$ans_by_day["$key"]."</TD>\n";
					echo "<TD>$percent_ans ".$lang["$language"]['percent']."</TD>\n";
					echo "<TD>".$unans_by_day["$key"]."</TD>\n";
					echo "<TD>$percent_unans".$lang["$language"]['percent']."</TD>\n";
					echo "<TD>".$average_call_duration_print." ".$lang["$language"]['minutes']."</TD>\n";
					echo "<TD>".number_format($average_hold_duration,0)." ".$lang["$language"]['secs']."</TD>\n";
					echo "<TD>".$login_by_day["$key"]."</TD>\n";
					echo "<TD>".$logout_by_day["$key"]."</TD>\n";
					echo "</TR>\n";
					$count++;
					$data_pdf[]=$linea_pdf;
					}
				}
				?>
			</TBODY>
			</TABLE>
			
			<?php
				if($count>1) {
					print_exports($header_pdf,$data_pdf,$width_pdf,$title_pdf,$cover_pdf); 
				}
			?>
			<BR>
			
			<a name='2'></a>
			<TABLE width='99%' cellpadding=1 cellspacing=1 border=0 class='sortable' id='table2'>
			<CAPTION>
			<a href='#0'><img src='images/go-up.png' border=0 width=16 height=16 class='icon' 
			<?php 
			tooltip($lang["$language"]['gotop'],200);
			?>
			></a>&nbsp;&nbsp;
			<?php echo $lang["$language"]['call_distrib_hour']?>
			</CAPTION>
				<THEAD>
				<TR>
                    <TH><?php echo $lang["$language"]['hour']?></TH>
                    <TH><?php echo $lang["$language"]['answered']?></TH>
                    <TH><?php echo $lang["$language"]['percent_answered']?></TH>
                    <TH><?php echo $lang["$language"]['unanswered']?></TH>
                    <TH><?php echo $lang["$language"]['percent_unanswered']?></TH>
                    <TH><?php echo $lang["$language"]['avg_calltime']?></TH>
                    <TH><?php echo $lang["$language"]['avg_holdtime']?></TH>
                    <TH><?php echo $lang["$language"]['login']?></TH>
                    <TH><?php echo $lang["$language"]['logoff']?></TH>
				</TR>
				</THEAD>
				<TBODY>
				<?php

				$header_pdf=array($lang["$language"]['hour'],$lang["$language"]['answered'],$lang["$language"]['percent_answered'],$lang["$language"]['unanswered'],$lang["$language"]['percent_unanswered'],$lang["$language"]['avg_calltime'],$lang["$language"]['avg_holdtime'],$lang["$language"]['login'],$lang["$language"]['logoff']);
				$width_pdf=array(25,23,23,23,23,25,25,20,20);
				$title_pdf=$lang["$language"]['call_distrib_hour'];
				$data_pdf = array();

				$query_ans = "";
				$query_unans = "";
				$query_time="";
				$query_hold="";
				for($key=0;$key<24;$key++) {
					$cual = ($key+1)%2;
					if($cual>0) { $odd = " class='odd' "; } else { $odd = ""; }
					if(strlen($key)==1) { $key = "0".$key; }
					if(!isset($ans_by_hour["$key"])) {
						$ans_by_hour["$key"]=0;
						$average_call_duration = 0;
						$average_hold_duration = 0;
					} else {
						$average_call_duration = $total_time_by_hour["$key"] / $ans_by_hour["$key"];
						$average_hold_duration = $total_hold_by_hour["$key"] / $ans_by_hour["$key"];
					}
					if(!isset($unans_by_hour["$key"])) {
						$unans_by_hour["$key"]=0;
					}
					if($answered > 0) {
						$percent_ans   = $ans_by_hour["$key"]   * 100 / $answered;
					} else {
						$percent_ans = 0;
					}
					if($unanswered > 0) {
						$percent_unans = $unans_by_hour["$key"] * 100 / $unanswered;
					} else {
						$percent_unans = 0;
					}
					$percent_ans   = number_format($percent_ans,  2);
					$percent_unans = number_format($percent_unans,2);

					if(!isset($login_by_hour["$key"])) {
					    $login_by_hour["$key"]=0;
                    }
					if(!isset($logout_by_hour["$key"])) {
					    $logout_by_hour["$key"]=0;
                    }

					$linea_pdf = array($key,$ans_by_hour["$key"],"$percent_ans ".$lang["$language"]['percent'],$unans_by_hour["$key"],"$percent_unans ".$lang["$language"]['percent'],number_format($average_call_duration,0),number_format($average_hold_duration,0),$login_by_hour["$key"],$logout_by_hour["$key"]);

					echo "<TR $odd>\n";
					echo "<TD>$key</TD>\n";
					echo "<TD>".$ans_by_hour["$key"]."</TD>\n";
					echo "<TD>$percent_ans".$lang["$language"]['percent']."</TD>\n";
					echo "<TD>".$unans_by_hour["$key"]."</TD>\n";
					echo "<TD>$percent_unans".$lang["$language"]['percent']."</TD>\n";
					echo "<TD>".number_format($average_call_duration,0)." ".$lang["$language"]['secs']."</TD>\n";
					echo "<TD>".number_format($average_hold_duration,0)." ".$lang["$language"]['secs']."</TD>\n";
					echo "<TD>".$login_by_hour["$key"]."</TD>\n";
					echo "<TD>".$logout_by_hour["$key"]."</TD>\n";
					echo "</TR>\n";
					$gkey = $key+1;
					$query_ans  .="var$gkey=$key&val$gkey=".$ans_by_hour["$key"]."&";
					$query_unans.="var$gkey=$key&val$gkey=".$unans_by_hour["$key"]."&";
					$query_comb.= "var$gkey=$key%20".$lang["$language"]['hours']."&valA$gkey=".$ans_by_hour["$key"]."&valB$gkey=".$unans_by_hour["$key"]."&";
					$query_time.="var$gkey=$key&val$gkey=".intval($average_call_duration)."&";
					$query_hold.="var$gkey=$key&val$gkey=".intval($average_hold_duration)."&";
					$data_pdf[]=$linea_pdf;
				}
				$query_ans.="title=".$lang["$language"]['answ_by_hour']."$graphcolor";
				$query_unans.="title=".$lang["$language"]['unansw_by_hour']."$graphcolor";
				$query_time.="title=".$lang["$language"]['avg_call_time_by_hr']."$graphcolor";
				$query_hold.="title=".$lang["$language"]['avg_hold_time_by_hr']."$graphcolor";
				$query_comb.="title=".$lang["$language"]['anws_unanws_by_hour']."$graphcolorstack&tagA=".$lang["$language"]['answered_calls']."&tagB=".$lang["$language"]['unanswered_calls'];
				?>
			</TBODY>
			</TABLE>
			<?php
				print_exports($header_pdf,$data_pdf,$width_pdf,$title_pdf,$cover_pdf); 
			?>

			<BR>

			<TABLE width='99%' cellpadding=1 cellspacing=1 border=0>
			<THEAD>
			<TR>
				<TD align=center bgcolor='#fffdf3'>
				<hr>
				</TD>
			</TR>
			<TR>
				<TD align=center bgcolor='#fffdf3'>
    				<?php
//					swf_bar($query_comb,'718','433',"chart1",1);
					?>
				</TD>
			</TR>
			<TR>
				<TD align=center bgcolor='#fffdf3'>
					<?php
//					swf_bar($query_time,'718','433',"chart3",0);
					?>
				</TD>
			</TR>
			<TR>
				<TD align=center bgcolor='#fffdf3'>
					<?php
//					swf_bar($query_hold,'718','433',"chart4",0);
					?>
				</TD>
			</TR>
			</THEAD>
			</TABLE>

			<BR>

			<a name='3'></a>
			<TABLE width='99%' cellpadding=1 cellspacing=1 border=0 class='sortable' id='table3'>
			<CAPTION>
			<a href='#0'><img src='images/go-up.png' border=0 width=16 height=16 class='icon' 
			<?php 
			tooltip($lang["$language"]['gotop'],200);
			?>
			></a>&nbsp;&nbsp;

			<?php echo $lang["$language"]['call_distrib_week']?>
			</CAPTION>
				<THEAD>
				<TR>
                    <TH><?php echo $lang["$language"]['day']?></TH>
                    <TH><?php echo $lang["$language"]['answered']?></TH>
                    <TH><?php echo $lang["$language"]['percent_answered']?></TH>
                    <TH><?php echo $lang["$language"]['unanswered']?></TH>
                    <TH><?php echo $lang["$language"]['percent_unanswered']?></TH>
                    <TH><?php echo $lang["$language"]['avg_calltime']?></TH>
                    <TH><?php echo $lang["$language"]['avg_holdtime']?></TH>
                    <TH><?php echo $lang["$language"]['login']?></TH>
                    <TH><?php echo $lang["$language"]['logoff']?></TH>
				</TR>
				</THEAD>
				<TBODY>
				<?php

				$header_pdf=array($lang["$language"]['day'],$lang["$language"]['answered'],$lang["$language"]['percent_answered'],$lang["$language"]['unanswered'],$lang["$language"]['percent_unanswered'],$lang["$language"]['avg_calltime'],$lang["$language"]['avg_holdtime'],$lang["$language"]['login'],$lang["$language"]['logoff']);
				$width_pdf=array(25,23,23,23,23,25,25,20,20);
				$title_pdf=$lang["$language"]['call_distrib_week'];
				$data_pdf = array();


				$query_ans="";
				$query_unans="";
				$query_time="";
				$query_hold="";
				for($key=0;$key<7;$key++) {
					$cual = ($key+1)%2;
					if($cual>0) { $odd = " class='odd' "; } else { $odd = ""; }
					if(!isset($total_time_by_dw["$key"])) {
						$total_time_by_dw["$key"]=0;
					}
					if(!isset($total_hold_by_dw["$key"])) {
						$total_hold_by_dw["$key"]=0;
					}
					if(!isset($ans_by_dw["$key"])) {
						$ans_by_dw["$key"]=0;
						$average_call_duration = 0;
						$average_hold_duration = 0;
					} else {
						$average_call_duration = $total_time_by_dw["$key"] / $ans_by_dw["$key"];
						$average_hold_duration = $total_hold_by_dw["$key"] / $ans_by_dw["$key"];
					}

					if(!isset($unans_by_dw["$key"])) {
						$unans_by_dw["$key"]=0;
					}
					if($answered > 0) {
						$percent_ans   = $ans_by_dw["$key"]   * 100 / $answered;
					} else {
						$percent_ans = 0;
					}
					if($unanswered > 0) {
						$percent_unans = $unans_by_dw["$key"] * 100 / $unanswered;
					} else {
						$percent_unans = 0;
					}
					$percent_ans   = number_format($percent_ans,  2);
					$percent_unans = number_format($percent_unans,2);

					if(!isset($login_by_dw["$key"])) {
					    $login_by_dw["$key"]=0;
                    }
					if(!isset($logout_by_dw["$key"])) {
					    $logout_by_dw["$key"]=0;
                    }

					$linea_pdf = array($dayp["$key"],$ans_by_dw["$key"],"$percent_ans ".$lang["$language"]['percent'],$unans_by_dw["$key"],"$percent_unans ".$lang["$language"]['percent'],number_format($average_call_duration,0),number_format($average_hold_duration,0),$login_by_dw["$key"],$logout_by_dw["$key"]);

					echo "<TR $odd>\n";
					echo "<TD>".$dayp["$key"]."</TD>\n";
					echo "<TD>".$ans_by_dw["$key"]."</TD>\n";
					echo "<TD>$percent_ans".$lang["$language"]['percent']."</TD>\n";
					echo "<TD>".$unans_by_dw["$key"]."</TD>\n";
					echo "<TD>$percent_unans".$lang["$language"]['percent']."</TD>\n";
					echo "<TD>".number_format($average_call_duration,0)." secs</TD>\n";
					echo "<TD>".number_format($average_hold_duration,0)." secs</TD>\n";
					echo "<TD>".$login_by_dw["$key"]."</TD>\n";
					echo "<TD>".$logout_by_dw["$key"]."</TD>\n";
					echo "</TR>\n";
					$gkey = $key + 1;
					$query_ans  .="var$gkey=".$dayp["$key"]."&val$gkey=".intval($ans_by_dw["$key"])."&";
					$query_unans.="var$gkey=".$dayp["$key"]."&val$gkey=".intval($unans_by_dw["$key"])."&";
					$query_time.="var$gkey=".$dayp["$key"]."&val$gkey=".intval($average_call_duration)."&";
					$query_hold.="var$gkey=".$dayp["$key"]."&val$gkey=".intval($average_hold_duration)."&";
					$data_pdf[]=$linea_pdf;
				}
				$query_ans.="title=".$lang["$language"]['answ_by_day']."$graphcolor";
				$query_unans.="title=".$lang["$language"]['unansw_by_day']."$graphcolor";
				$query_time.="title=".$lang["$language"]['avg_call_time_by_day']."$graphcolor";
				$query_hold.="title=".$lang["$language"]['avg_hold_time_by_day']."$graphcolor";
				?>
			</TBODY>
			</TABLE>
			<?php
				print_exports($header_pdf,$data_pdf,$width_pdf,$title_pdf,$cover_pdf); 
			?>
			<BR>

			<TABLE width=99% cellpadding=1 cellspacing=1 border=0>
			<THEAD>
			<TR>
				<TD align=center bgcolor='#fffdf3'>
					<?php
//					swf_bar($query_ans,359,217,"chart5",0);
					?>
				</TD>
				<TD align=center bgcolor='#fffdf3'>
					<?php
//					swf_bar($query_unans,359,217,"chart6",0);
					?>
				</TD>
			</TR>
			<TR>
				<TD align=center bgcolor='#fffdf3'>
					<?php
//					swf_bar($query_time,359,217,"chart7",0);
					?>
				</TD>
				<TD align=center bgcolor='#fffdf3'>
					<?php
//					swf_bar($query_hold,359,217,"chart8",0);
					?>
				</TD>
			</TR>
			</THEAD>
			</TABLE>

</div>
</div>
<script type="text/javascript" src="js/wz_tooltip.js"></script>
</body>
</html>
