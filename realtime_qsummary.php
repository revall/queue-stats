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

echo "<h2>".$lang[$language]['queue_summary']."</h2><br/>";
echo "<table width='700' cellpadding=3 cellspacing=3 border=0>\n";
echo "<thead>";
echo "<tr>";
echo "<th>".$lang[$language]['queue']."</th>";
echo "<th>".$lang[$language]['staffed']."</th>";
echo "<th>".$lang[$language]['talking']."</th>";
echo "<th>".$lang[$language]['paused']."</th>";
echo "<th>".$lang[$language]['calls_waiting']."</th>";
echo "<th>".$lang[$language]['oldest_call_waiting']."</th>";
echo "</tr>\n";
echo "</thead>\n";
echo "<tbody>\n";

$contador=1;
foreach($queue as $elementq) {
    if($filter=="" || stristr($elementq,$filter)) {
        $qt=$elementq;
        if($elementq<>"") {
            if(isset($dict_queue[$elementq])) {
                if($dict_queue[$elementq]<>"") {
                    $qt=$dict_queue[$elementq];
                }
            }

            if($contador%2) { $odd="class='odd'"; } else { $odd=""; }
            echo "<tr $odd><td>$qt</td>";

            $datos = get_queue_numbers($elementq);
            if(is_array($datos)) {
                $staffed = $datos['agents_ready'] ;
                $auxi    = $datos['agents_paused'];
                $talki   = $datos['agents_busy'];
                $off     = $datos['agents_logedof'];
                $queued  = $datos['settext'];
                $maxwait  = $datos['maxwait'];
                if($maxwait=="") { $maxwait="0"; }
            } else {
                $staffed = "n/a";
                $auxi = "n/a";
                $talki = "n/a";
                $off = "n/a";
                $queued = "n/a";
            }
            echo "<td>$staffed</td>\n";
            echo "<td>$talki</td>\n";
            echo "<td>$auxi</td>\n";
            echo "<td>$queued</td>\n";
            echo "<td>$maxwait ".$lang[$language]['minutes']."</td>\n";
            echo "</tr>\n";
            $contador++;
        }
    }   
}
echo "</tbody></table>";
?>
