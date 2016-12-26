<?php
/*COMPLETEAGENT
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

$graphcolor2 = "&bgcolor=0xF0ffff&bgcolorchart=0xdfedf3&fade1=0xff6600&fade2=0x528252&colorbase=0xfff3b3&reverse=1";
$graphcolor  = "&bgcolor=0xF0ffff&bgcolorchart=0xdfedf3&fade1=0xff6600&fade2=0xff6600&colorbase=0xfff3b3&reverse=1";
// This query shows the hangup cause, how many calls an
// agent hanged up, and a caller hanged up.
$query = "SELECT COUNT(event) AS num, event AS action  FROM queue_log ";
$query.= "WHERE time >= '$start' AND time <= '$end' AND queuename IN ($queue) ";
$query.= "AND event IN ('COMPLETECALLER', 'COMPLETEAGENT') GROUP BY event ORDER BY time";

$hangup_cause["COMPLETECALLER"]=0;
$hangup_cause["COMPLETEAGENT"]=0;
$res = consulta_db($query,$DB_DEBUG,$DB_MUERE,0,$midb);
while($row=db_fetch_row($res)) {
  $hangup_cause["$row[1]"]=$row[0];
  $total_hangup+=$row[0];
}

//service level
$query = "SELECT time, queuename, agent, event, data1, data2, data3 ";
$query.= "FROM queue_log WHERE time >= '$start' AND time <= '$end' ";
$query.= "AND queuename IN ($queue) AND agent in ($agent) AND event IN ('COMPLETECALLER','COMPLETEAGENT','TRANSFER','CONNECT') ORDER BY time";

$answer["15"]=0;
$answer["30"]=0;
$answer["45"]=0;
$answer["60"]=0;
$answer["75"]=0;
$answer["90"]=0;
$answer["91+"]=0;

$abandoned         = 0;
$transferidas      = 0;
$totaltransfers    = 0;
$total_hangup      = 0;
$total_calls       = 0;
$total_calls2      = Array();
$total_duration    = 0;
$total_calls_queue = Array();

$res = consulta_db($query,$DB_DEBUG,$DB_MUERE,0,$midb);
if($res) {
    while($row=db_fetch_row($res)) {
        if($row[3] <> "TRANSFER" && $row[3]<>"CONNECT") {
            $total_hold     += $row[4];
            $total_duration += $row[5];
            $total_calls++;
            $total_calls_queue["$row[1]"]++;
        } elseif($row[3]=="TRANSFER") {
            $transferidas++;
        }
        if($row[3]=="CONNECT") {

            if ($row[4] >=0 && $row[4] <= 15) {
                $answer["15"]++;
            }

            if ($row[4] >=16 && $row[4] <= 30) {
                $answer["30"]++;
            }

            if ($row[4] >=31 && $row[4] <= 45) {
              $answer["45"]++;
            }

            if ($row[4] >=46 && $row[4] <= 60) {
              $answer["60"]++;
            }

            if ($row[4] >=61 && $row[4] <= 75) {
              $answer["75"]++;
            }

            if ($row[4] >=76 && $row[4] <= 90) {
              $answer["90"]++;
            }

            if ($row[4] >=91) {
              $answer["91+"]++;
            }
        }
    }
} 

if($total_calls > 0) {
    ksort($answer);
    $average_hold     = $total_hold     / $total_calls;
    $average_duration = $total_duration / $total_calls;
    $average_hold     = number_format($average_hold     , 2);
    $average_duration = number_format($average_duration , 2);
} else {
    // There were no calls
    $average_hold = 0;
    $average_duration = 0;
}

$total_duration_print = seconds2minutes($total_duration);
// TRANSFERS
$query = "SELECT agent, data1, data2 FROM queue_log ";
$query.= "WHERE time >= '$start' AND time <= '$end' ";
$query.= "AND queuename IN ($queue) AND agent in ($agent) AND event = 'TRANSFER'";


$res = consulta_db($query,$DB_DEBUG,$DB_MUERE,0,$midb);
if($res) {
    while($row=db_fetch_row($res)) {
        $keytra = "$row[0]^$row[1]@$row[2]";
        $transfers["$keytra"]++;
        $totaltransfers++;
    }
} else {
   $totaltransfers=0;
}

// ABANDONED CALLS
$query = "SELECT event, data1, data2, data3 FROM queue_log ";
$query.= "WHERE time >= '$start' AND time <= '$end' AND queuename IN ($queue) "; 
$query.= "AND agent IN ($agent) AND  event IN ('ABANDON', 'EXITWITHTIMEOUT', 'TRANSFER') ";
$query.= "ORDER BY event, data3";

$res = consulta_db($query,$DB_DEBUG,$DB_MUERE,0,$midb);

while($row=db_fetch_row($res)) {

    if($row[0]=="ABANDON") {
         $abandoned++;
        $abandon_end_pos+=$row[1];
        $abandon_start_pos+=$row[2];
        $total_hold_abandon+=$row[3];
    }
    if($row[0]=="EXITWITHTIMEOUT") {
         $timeout++;
        $timeout_end_pos+=$row[1];
        $timeout_start_pos+=$row[2];
        $total_hold_timeout+=$row[3];
    }
}

if($abandoned > 0) {
    $abandon_average_hold = $total_hold_abandon / $abandoned;
    $abandon_average_hold = number_format($abandon_average_hold,2);

    $abandon_average_start = floor($abandon_start_pos / $abandoned);
    $abandon_average_start = number_format($abandon_average_start,2);

    $abandon_average_end = floor($abandon_end_pos / $abandoned);
    $abandon_average_end = number_format($abandon_average_end,2);
} else {
    $abandoned = 0;
    $abandon_average_hold  = 0;
    $abandon_average_start = 0;
    $abandon_average_end   = 0;
}

// This query shows every call for agents, we collect into a named array the values of holdtime and calltime
$query = "SELECT time, queuename, agent, event, data1, data2, data3 FROM queue_log ";
$query.= "WHERE time >= '$start' AND time <= '$end' ";
$query.= "AND queuename IN ($queue) AND agent in ($agent) AND event IN ('COMPLETECALLER', 'COMPLETEAGENT') ORDER BY agent";

$res = consulta_db($query,$DB_DEBUG,$DB_MUERE,0,$midb);
while($row=db_fetch_row($res)) {
    $total_calls2["$row[2]"]++;
    $record["$row[2]"][]=$row[0]."|".$row[1]."|".$row[3]."|".$row[4];
    $total_hold2["$row[2]"]+=$row[4];
    $total_time2["$row[2]"]+=$row[5];
    $grandtotal_hold+=$row[4];
    $grandtotal_time+=$row[5];
    $grandtotal_calls++;
}

$start_parts = explode(" ,:", $start);
$end_parts   = explode(" ,:", $end);

$cover_pdf = $lang["$language"]['queue'].": ".$queue."\n";
$cover_pdf.= $lang["$language"]['start'].": ".$start_parts[0]."\n";
$cover_pdf.= $lang["$language"]['end'].": ".$end_parts[0]."\n";
$cover_pdf.= $lang["$language"]['period'].": ".$period." ".$lang["$language"]['hours']."\n\n";
$cover_pdf.= $lang["$language"]['answered_calls'].": ".$total_calls." ".$lang["$language"]['calls']."\n";
$cover_pdf.= $lang["$language"]['avg_calltime'].": ".$average_duration." ".$lang["$language"]['secs']."\n";
$cover_pdf.= $lang["$language"]['total'].": ".$total_duration_print." ".$lang["$language"]['minutes']."\n";
$cover_pdf.= $lang["$language"]['avg_holdtime'].": ".$average_hold." ".$lang["$language"]['secs']."\n";

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
                <CAPTION><?php echo $lang["$language"]['answered_calls']?></CAPTION>
                <TBODY>
                <TR> 
                  <TD><?php echo $lang["$language"]['answered_calls']?></TD>
                  <TD><?php echo $total_calls?> <?php echo $lang["$language"]['calls']?></TD>
                </TR>
                <TR>
                  <TD><?php echo $lang["$language"]['avg_calltime']?>:</TD>
                  <TD><?php echo $average_duration?> <?php echo $lang["$language"]['secs']?></TD>
                </TR>
                <TR>
                  <TD><?php echo $lang["$language"]['total']?> <?php echo $lang["$language"]['calltime']?>:</TD>
                  <TD><?php echo $total_duration_print?> <?php echo $lang["$language"]['minutes']?></TD>
                </TR>
                <TR>
                  <TD><?php echo $lang["$language"]['avg_holdtime']?>:</TD>
                  <TD><?php echo $average_hold?> <?php echo $lang["$language"]['secs']?></TD>
                </TR>
                </TBODY>
              </TABLE>

            </TD>
        </TR>
        </THEAD>
        </TABLE>
        <BR>    
        <a name='1'></a>
        <TABLE width='99%' cellpadding=3 cellspacing=3 border=0 class='sortable' id='table1' >
        <CAPTION>
        <a href='#0'><img src='images/go-up.png' border=0 class='icon' width=16 height=16 
        <?php 
        tooltip($lang["$language"]['gotop'],200);
        ?> 
        ></a>&nbsp;&nbsp;
        <?php echo $lang["$language"]['answered_calls_by_agent']?>
        </CAPTION>
            <THEAD>
            <TR>
                  <TH><?php echo $lang["$language"]['agent']?></TH>
                  <TH><?php echo $lang["$language"]['Calls']?></TH>
                  <TH><?php echo $lang["$language"]['percent']?> <?php echo $lang["$language"]['Calls']?></TH>
                  <TH><?php echo $lang["$language"]['calltime']?></TH>
                  <TH><?php echo $lang["$language"]['percent']?> <?php echo $lang["$language"]['calltime']?></TH>
                  <TH><?php echo $lang["$language"]['avg']?> <?php echo $lang["$language"]['calltime']?></TH>
                  <TH><?php echo $lang["$language"]['holdtime']?></TH>
                  <TH><?php echo $lang["$language"]['avg']?> <?php echo $lang["$language"]['holdtime']?></TH>
            </TR>
            </THEAD>
            <TBODY>
                <?php
                $header_pdf=array($lang["$language"]['agent'],$lang["$language"]['Calls'],$lang["$language"]['percent'],$lang["$language"]['calltime'],$lang["$language"]['percent'],$lang["$language"]['avg'],$lang["$language"]['holdtime'],$lang["$language"]['avg']);
                $width_pdf=array(25,23,23,23,23,25,25,20);
                $title_pdf=$lang["$language"]['answered_calls_by_agent'];

                $contador=0;
                $query1 = "";
                $query2 = "";
                $data_pdf = array();
                if($total_calls2>0) {
                foreach($total_calls2 as $agent=>$val) {
                    $contavar = $contador +1;
                    $cual = $contador % 2;
                    if($cual>0) { $odd = " class='odd' "; } else { $odd = ""; }
                    $query1 .= "val$contavar=".$total_time2["$agent"]."&var$contavar=$agent&";
                    $query2 .= "val$contavar=".$val."&var$contavar=$agent&";

                    $time_print = seconds2minutes($total_time2["$agent"]);
                    $avg_time = $total_time2["$agent"] / $val;
                    $avg_time = round($avg_time,2);

                    $avg_print = seconds2minutes($avg_time);

                    echo "<TR $odd>\n";
                    echo "<TD>$agent</TD>\n";
                    echo "<TD>$val</TD>\n";
                    if($grandtotal_calls > 0) {
                       $percentage_calls = $val * 100 / $grandtotal_calls;
                    } else {
                       $percentage_calls = 0;
                    }
                    $percentage_calls = number_format($percentage_calls,2);
                    echo "<TD>$percentage_calls ".$lang["$language"]['percent']."</TD>\n";
                    echo "<TD>$time_print ".$lang["$language"]['minutes']."</TD>\n";
                    if($grandtotal_time > 0) {
                       $percentage_time = $total_time2["$agent"] * 100 / $grandtotal_time;
                    } else {
                       $percentage_time = 0;
                    }
                    $percentage_time = number_format($percentage_time,2);
                    echo "<TD>$percentage_time ".$lang["$language"]['percent']."</TD>\n";
                    //echo "<TD>$avg_time ".$lang["$language"]['secs']."</TD>\n";
                    echo "<TD>$avg_print ".$lang["$language"]['minutes']."</TD>\n";
                    echo "<TD>".$total_hold2["$agent"]." ".$lang["$language"]['secs']."</TD>\n";
                    $avg_hold = $total_hold2["$agent"] / $val;
                    $avg_hold = number_format($avg_hold,2);
                    echo "<TD>$avg_hold ".$lang["$language"]['secs']."</TD>\n";
                    echo "</TR>\n";

                    $linea_pdf = array($agent,$val,"$percentage_calls ".$lang["$language"]['percent'],$total_time2["$agent"],"$percentage_time ".$lang["$language"]['percent'],"$avg_time ".$lang["$language"]['secs'],$total_hold2["$agent"]." ".$lang["$language"]['secs'], "$avg_hold ".$lang["$language"]['secs']);
                       $data_pdf[]=$linea_pdf;
                    $contador++;
                }
                
                $query1.="title=".$lang["$language"]['total_time_agent']."$graphcolor";
                $query2.="title=".$lang["$language"]['no_calls_agent']."$graphcolor";
                }
                ?>
            </TBODY>
        </TABLE>
            <?php 
                if($total_calls2>0) {
                print_exports($header_pdf,$data_pdf,$width_pdf,$title_pdf,$cover_pdf);
                }
            ?>

        <BR>    
            <?php
            if($total_calls2>0) {
                echo "<TABLE width='99%' cellpadding=3 cellspacing=3 border=0>\n";
                echo "<THEAD>\n";
                echo "<TR><TD align=center bgcolor='#fffdf3' width='100%'>\n";
                echo "</TD><TD align=center bgcolor='#fffdf3' width='100%'>\n";
                echo "</TD></TR>\n";
                echo "</THEAD>\n";
                echo "</TABLE><BR>\n";
            }
            ?>

            <a name='2'></a>
            <TABLE width='99%' cellpadding=3 cellspacing=3 border=0 >
            <CAPTION>
            <a href='#0'><img src='images/go-up.png' border=0 class='icon' width=16 height=16 
            <?php 
            tooltip($lang["$language"]['gotop'],200);
            ?>
            ></a>&nbsp;&nbsp;
            <?php echo $lang["$language"]['call_response']?></CAPTION>
            <THEAD>
            <TR>
            <TD valign=top width='50%' bgcolor='#fffdf3'>
                <TABLE width='99%' cellpadding=1 cellspacing=1 border=0 class='sortable' id='table2'>
                <THEAD>
                <TR> 
                  <TH><?php echo $lang["$language"]['answer']?></TH>
                  <TH><?php echo $lang["$language"]['count']?></TH>
                  <TH><?php echo $lang["$language"]['delta']?></TH>
                  <TH><?php echo $lang["$language"]['percent']?></TH>
                </TR>
                </THEAD>
                <TBODY>
                <?php
                $countrow=0;
                $partial_total = 0;
                $query2="";
                $total_y_transfer = $answer['15'] + $answer['30'] +  $answer['45'] + $answer['60'] +  $answer['75'] + $answer['90'] +  $answer['91+'];
                foreach($answer as $key=>$val)
                {
                    $newcont = $countrow+1;
                    $query2.="val$newcont=$val&var$newcont=$key%20".$lang["$language"]['secs']."&";
                    $cual = ($countrow%2);
                    if($cual>0) { $odd = " class='odd' "; } else { $odd = ""; }
                    echo "<TR $odd>\n";
                    echo "<TD>".$lang["$language"]['within']."$key ".$lang["$language"]['secs']."</TD>\n";
                    $delta = $val;
                    if($delta > 0) { $delta = "+".$delta;}
                    $partial_total += $val;
                    if($total_y_transfer > 0) {
                    $percent=$partial_total*100/$total_y_transfer;
                    } else {
                    $percent = 0;
                    }
                    $percent=number_format($percent,2);
                    if($countrow==0) { $delta = ""; }
                    echo "<TD>$partial_total ".$lang["$language"]['calls']."</TD>\n";
                    echo "<TD>$delta</TD>\n";
                    echo "<TD>$percent ".$lang["$language"]['percent']."</TD>\n";
                    echo "</TR>\n";
                    $countrow++;
                }
                $query2.="title=".$lang["$language"]['call_response']."$graphcolor";
                ?>
                </TBODY>
              </TABLE>
              </TD>
                <TD valign=top width="50%" align=center  bgcolor='#fffdf3'>
                <?php
                ?>
                </TD>
            </TR>
            </THEAD>
          </TABLE>
          <BR>
            <a name='3'></a>
            <TABLE width='99%' cellpadding=3 cellspacing=3 border=0>
            <CAPTION>
            <a href='#0'><img src='images/go-up.png' border=0 class='icon' width=16 height=16 

            <?php 
            tooltip($lang["$language"]['gotop'],200);
            ?>
            ></a>&nbsp;&nbsp;
            <?php echo $lang["$language"]['answered_calls_by_queue']?></CAPTION>
            <THEAD>
            <TR>
            <TD valign=top width='50%'  bgcolor='#fffdf3'  >
                <TABLE width='99%' cellpadding=1 cellspacing=1 border=0 class='sortable' id='table3' >
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
                if(count($total_calls_queue)==0) {
                        $total_calls_queue[""]=0;
                }
                asort($total_calls_queue);
                foreach($total_calls_queue as $key=>$val) {
                    $cual = $countrow%2;
                    if($cual>0) { $odd = " class='odd' "; } else { $odd = ""; }
                    if($total_calls>0) {
                    $percent = $val * 100 / $total_calls;
                    } else {
                    $percent=0;
                    }
                    $percent =number_format($percent,2);
                    echo "<TR $odd><TD>$key</TD><TD>$val ".$lang["$language"]['calls']."</TD><TD>$percent %</TD></TR>\n";
                    $countrow++;
                    $query2.="var$countrow=$key&val$countrow=$val&";
                }
                $query2.="title=".addslashes($lang[$language]['answered_calls_by_queue'])."$graphcolor";
                ?>
              </TBODY>
              </TABLE>
            </TD>
            </TR>
            </THEAD>
            </TABLE>
            <BR>

            <a name='4'></a>
            <TABLE width='99%' cellpadding=3 cellspacing=3 border=0>
            <CAPTION>
            <a href='#0'><img src='images/go-up.png' border=0 class='icon' width=16 height=16 

            <?php 
            tooltip($lang["$language"]['gotop'],200);
            ?>
            ></a>&nbsp;&nbsp;
            <?php echo $lang["$language"]['disconnect_cause']?></CAPTION>
            <THEAD>
            <TR>
            <TD valign=top width='50%'  bgcolor='#fffdf3' >
                <TABLE width='99%' cellpadding=1 cellspacing=1 border=0 class='sortable' id='table4'>
                <THEAD>
                <TR>
                    <TH><?php echo $lang["$language"]['cause']?></TH>
                    <TH><?php echo $lang["$language"]['count']?></TH>
                    <TH><?php echo $lang["$language"]['count']?></TH>
                </TR>
                </THEAD>
                <TBODY>
                <TR> 
                  <TD><?php echo $lang["$language"]['agent_hungup']?>:</TD>
                  <TD><?php echo $hangup_cause["COMPLETEAGENT"]?> <?php echo $lang["$language"]['calls']?></TD>
                  <TD>
                      <?php
                        if($total_hangup > 0 ) {
                            $percent=$hangup_cause["COMPLETEAGENT"]*100/$total_hangup;
                        } else {
                            $percent=0;
                        }
                        $percent=number_format($percent,2);
                        echo $percent;
                      ?> 
                   <?php echo $lang["$language"]['percent']?></TD>
                </TR>
                <TR> 
                  <TD><?php echo $lang["$language"]['caller_hungup']?>:</TD>
                  <TD><?php echo $hangup_cause['COMPLETECALLER']?> <?php echo $lang["$language"]['calls']?></TD>
                  <TD>
                      <?php
                        if($total_hangup > 0 ) {
                            $percent=$hangup_cause["COMPLETECALLER"]*100/$total_hangup;
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
            <TD align=center  bgcolor='#fffdf3'>
                <?php
                $query2 = "var1=".$lang["$language"]['agent']."&val1=".$hangup_cause["COMPLETEAGENT"]."&";
                $query2 .= "var2=".$lang["$language"]['caller']."&val2=".$hangup_cause["COMPLETECALLER"];
                $query2.="&title=".$lang["$language"]['disconnect_cause']."$graphcolor2";
                ?>
            </TD>
            </TR>
            </THEAD>
            </TABLE>

            <BR>

            <?php
            if($totaltransfers>0) {
            ?>
            <TABLE width='99%' cellpadding=1 cellspacing=1 border=0 class='sortable' id='table5'>
            <CAPTION><?php echo $lang["$language"]['transfers']?></CAPTION>
            <THEAD>
            <TR>
                 <TH><?php echo $lang["$language"]['agent']?></TH>
                 <TH><?php echo $lang["$language"]['to']?></TH>
                 <TH><?php echo $lang["$language"]['count']?></TH>
            </TR>
            </THEAD>
            <TBODY>
            <?php
            foreach($transfers as $key=>$val) {
                $partes = explode("\^",$key);
                $agent = $partes[0];
                $extension = $partes[1];
                echo "<TR>\n";
                echo "<TD>$agent</TD>\n";
                echo "<TD>$extension</TD>\n";
                echo "<TD>$val</TD>\n";
                echo "</TR>";
            }
            ?>
            </TBODY>
            </TABLE>
        <?php } ?>
</div>
</div>
</div>
<script type="text/javascript" src="js/wz_tooltip.js"></script>
</body>
</html>
