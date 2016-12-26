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

function return_timestamp($date_string)
{
  list ($year,$month,$day,$hour,$min,$sec) = preg_split("/-|:| /",$date_string,6);
  $u_timestamp = mktime($hour,$min,$sec,$month,$day,$year);
  return $u_timestamp;
}

function swf_bar($values,$width,$height,$divid,$stack) {

	if($stack==1) {
		$chart = "barstack.swf";
	} else {
		$chart = "bar.swf";
	}
?>
<div id="<?php echo $divid?>">
<?php echo $values?>
</div>

<script type="text/javascript">
   var fo = new FlashObject("<?php echo $chart?>", "barchart", "<?php echo $width?>", "<?php echo $height?>", "7", "#336699");
   fo.addParam("wmode", "transparent");
//   fo.addParam("salign", "t");
	<?php
		$variables = explode("&",$values);
		foreach ($variables as $deauna) {
			echo "//$deauna\n";
			$pedazos = explode("=",$deauna);
			echo "fo.addVariable('".$pedazos[0]."','".$pedazos[1]."');\n";
		}
	?>
   fo.write("<?php echo $divid?>");
</script>

<?php
}

function tooltip($texto,$width) {
 echo " onmouseover=\"this.T_WIDTH=$width;this.T_PADDING=5;this.T_STICKY = false; return escape('$texto')\" ";
}


function print_exports($header_pdf,$data_pdf,$width_pdf,$title_pdf,$cover_pdf) {
		global $lang;
		global $language;
		$head_serial = serialize($header_pdf);
		$data_serial = serialize($data_pdf);
		$width_serial = serialize($width_pdf);
		$title_serial = serialize($title_pdf);
		$cover_serial = serialize($cover_pdf);
		$head_serial = rawurlencode($head_serial);
		$data_serial = rawurlencode($data_serial);
		$width_serial = rawurlencode($width_serial);
		$title_serial = rawurlencode($title_serial);
		$cover_serial = rawurlencode($cover_serial);
		echo "<BR><form method=post action='export.php'>\n";
		echo $lang["$language"]['export'];
		echo "<input type='hidden' name='head' value='".$head_serial."' />\n";
		echo "<input type='hidden' name='rawdata' value='".$data_serial."' />\n";
		echo "<input type='hidden' name='width' value='".$width_serial."' />\n";
		echo "<input type='hidden' name='title' value='".$title_serial."' />\n";
		echo "<input type='hidden' name='cover' value='".$cover_serial."' />\n";
//		echo "<input type=image name='pdf' src='images/pdf.gif' ";
//		tooltip($lang["$language"]['pdfhelp'],200);
//		echo ">\n";
		echo "<input type=image name='csv' src='images/excel.gif' "; 
		tooltip($lang["$language"]['csvhelp'],200);
		echo ">\n";
		echo "</form>";
}

function seconds2minutes($segundos) {
    $minutos = intval($segundos / 60);
    $segundos = $segundos % 60;
    if(strlen($segundos)==1) {
		$segundos = "0".$segundos;
	}
    return "$minutos:$segundos";
}
?>
