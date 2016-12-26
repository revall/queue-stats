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
?>
<div id="sidebar">&nbsp;</div>
<div id="content">
<a name='0'></a>
<div id='header'>
<ul id='primary'>
<?php
$menu[] = $lang["$language"]['menu_home'];
$menu[] = $lang["$language"]['menu_answered'];
$menu[] = $lang["$language"]['menu_unanswered'];
$menu[] = $lang["$language"]['menu_distribution'];
$menu[] = $lang["$language"]['menu_stats'];
$menu[] = "Realtime";

$link[] = "index.php";
$link[] = "answered.php";
$link[] = "unanswered.php";
$link[] = "distribution.php";
$link[] = "stats.php";
$link[] = "realtime.php";

$anchor = Array();

if(basename($self)=="answered.php")
{

$anchor[]=$lang["$language"]['answered_calls_by_agent'];
$anchor[]=$lang["$language"]['call_response'];
$anchor[]=$lang["$language"]['answered_calls_by_queue'];
$anchor[]=$lang["$language"]['disconnect_cause'];
$b=1;
} elseif (basename($self) =="unanswered.php") {

$anchor[]=$lang["$language"]['disconnect_cause'];
$anchor[]=$lang["$language"]['unanswered_calls_qu'];
$b=2;
} elseif (basename($self) =="distribution.php") {
$b=3;
$anchor[]=$lang["$language"]['call_distrib_day'];
$anchor[]=$lang["$language"]['call_distrib_hour'];
$anchor[]=$lang["$language"]['call_distrib_week'];
} elseif (basename($self) =="stats.php") {
/*
$anchor[]=$lang["$language"]['answered_calls_by_agent'];
$anchor[]=$lang["$language"]['call_response'];
$anchor[]=$lang["$language"]['answered_calls_by_queue'];
$anchor[]=$lang["$language"]['disconnect_cause'];
*/
$b=4;
}


for($a=0;$a<count($menu);$a++)
{
    if(basename($self)==$link[$a]) {
		echo "<li><span>".$menu[$a]."</span></li>\n";
		if(count($anchor)>0 && $a=$b) {
		    echo "<ul id='secondary'>\n";
			$contador=1;
			foreach ($anchor as $item) {
				echo "<li><a href='#$contador'>$item</a></li>\n";
				$contador++;
			}
		    echo "</ul>\n";
		}
	
	} else {
		if(isset($_SESSION['QSTATS']['start'])) {
		echo "<li><a href='".$link["$a"]."'>".$menu["$a"]."</a></li>\n";
	}
    }
}
?>
</ul>

</div>
