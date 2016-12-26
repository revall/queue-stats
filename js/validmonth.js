/********************************************************************************************************
                                         Valid month script
                               Written by Mark Wilton-Jones, 6-7/10/2002
********************************************************************************************************

Please see http://www.howtocreate.co.uk/jslibs/ for details and a demo of this script
Please see http://www.howtocreate.co.uk/jslibs/termsOfUse.html for terms of use

This script monitors years and months and makes sure that the correct number of days are provided.

To use:

Inbetween the <head> tags, put:

	<script src="PATH TO SCRIPT/validmonth.js" type="text/javascript" language="javascript1.2"></script>

To have a static year box (only allows the years defined in the HTML)

	Day of month select box should be in the format:
		<select name="day" size="1">
		<option value="1" selected>1</option>
		<option value="2">2</option>
		...
		<option value="31">31</option>
		</select>

	Month select box should be in the format:
		<select name="month" size="1" onchange="dateChange('day','month','year');">
		<option value="January" selected>January</option>
		<option value="February">February</option>
		...
		<option value="December">December</option>
		</select>

	Year select box should be in the format:
		<select name="year" size="1" onchange="dateChange('day','month','year');">
		<option value="1980" selected>1980</option>
		<option value="1981">1981</option>
		...
		<option value="2010">2010</option>
		</select>

	You can now use:
		setToday('day','month','year');
	to set the date to today's date (after the page has loaded)

To have an extendible year box (creates dates lower/higher than the current range)

	Year select box should be in the format:
		<select name="year" size="1" onchange="checkMore( this, 1980, 2005, 1840, 2010 );dateChange('day','month','year');">
		<option value="MWJ_DOWN">Lower ...</option>
		<option value="1980" selected>1980</option>
		<option value="1981">1981</option>
		...
		<option value="2005">2005</option>
		<option value="MWJ_UP">Higher ...</option>
		</select>
	If you do not want to have higher / lower values, simply omit the relevant option

	Function format:
		checkMore( this, CURRENT LOWEST YEAR, CURRENT HIGHEST YEAR, LOWEST POSSIBLE YEAR, HIGHEST POSSIBLE YEAR )

	You can now use:
		reFill( 'year', 1980, 2005, true, true );setToday('day','month','year');
	to set the date to today's date (after the page has loaded)

	Function format (make sure the range of years includes the current year):
		reFill( name of year select box, LOWEST YEAR, HIGHEST YEAR, ALLOW HIGHER (true/false), ALLOW LOWER (true/false) )
_____________________________________________________________________________________________________________________*/

//Opera 7 has a bug making it fail to set selectedIndex after dynamic generation of options unless there is a 0ms+ delay
//I have put fixes in in all necessary places

function MWJ_findSelect( oName, oDoc ) { //get a reference to the select box using its name
	if( !oDoc ) { oDoc = window.document; }
	for( var x = 0; x < oDoc.forms.length; x++ ) { if( oDoc.forms[x][oName] ) { return oDoc.forms[x][oName]; } }
	for( var x = 0; document.layers && x < oDoc.layers.length; x++ ) { //scan layers ...
		var theOb = MWJ_findObj( oName, oDoc.layers[x].document ); if( theOb ) { return theOb; } }
	return null;
}
function dateChange( d, m, y ) {
	d = MWJ_findSelect( d ), m = MWJ_findSelect( m ), y = MWJ_findSelect( y );
	//work out if it is a leap year
	var IsLeap = parseInt( y.options[y.selectedIndex].value );
	IsLeap = !( IsLeap % 4 ) && ( ( IsLeap % 100 ) || !( IsLeap % 400 ) );
	//find the number of days in that month
	IsLeap = [31,(IsLeap?29:28),31,30,31,30,31,31,30,31,30,31][m.selectedIndex];
	//store the current day - reduce it if the new month does not have enough days
	var storedDate = ( d.selectedIndex > IsLeap - 1 ) ? ( IsLeap - 1 ) : d.selectedIndex;
	while( d.options.length ) { d.options[0] = null; } //empty days box then refill with correct number of days
	for( var x = 0; x < IsLeap; x++ ) { d.options[x] = new Option( x + 1, x + 1 ); }
	d.options[storedDate].selected = true; //select the number that was selected before
	if( window.opera && document.importNode ) { window.setTimeout('MWJ_findSelect( \''+d.name+'\' ).options['+storedDate+'].selected = true;',0); }
}
function setToday( d, m, y ) {
	d = MWJ_findSelect( d ), m = MWJ_findSelect( m ), y = MWJ_findSelect( y );
	var now = new Date(); var nowY = ( now.getYear() % 100 ) + ( ( ( now.getYear() % 100 ) < 39 ) ? 2000 : 1900 );
	//if the relevant year exists in the box, select it
	for( var x = 0; x < y.options.length; x++ ) { if( y.options[x].value == '' + nowY + '' ) { y.options[x].selected = true; if( window.opera && document.importNode ) { window.setTimeout('MWJ_findSelect( \''+y.name+'\' ).options['+x+'].selected = true;',0); } } }
	//select the correct month, redo the days list to get the correct number, then select the relevant day
	m.options[now.getMonth()].selected = true; dateChange( d.name, m.name, y.name ); d.options[now.getDate()-1].selected = true;
	if( window.opera && document.importNode ) { window.setTimeout('MWJ_findSelect( \''+d.name+'\' ).options['+(now.getDate()-1)+'].selected = true;',0); }
}
function checkMore( y, curBot, curTop, min, max ) {
	var range = curTop - curBot;
	if( typeof( y.nowBot ) == 'undefined' ) { y.nowBot = curBot; y.nowTop = curTop; }
	if( y.options[y.selectedIndex].value == 'MWJ_DOWN' ) { //they have selected 'lower'
		while( y.options.length ) { y.options[0] = null; } //empty the select box
		y.nowBot -= range + 1; y.nowTop = range + y.nowBot; //make note of the start and end values
		//adjust the values as necessary if we will overstep the min value. If not, refill with the
		//new option for 'lower'
		if( min < y.nowBot ) { y.options[0] = new Option('Lower ...','MWJ_DOWN'); } else { y.nowBot = min; }
		for( var x = y.nowBot; x <= y.nowTop; x++ ) { y.options[y.options.length] = new Option(x,x); }
		y.options[y.options.length] = new Option('Higher ...','MWJ_UP');
		y.options[y.options.length - 2].selected = true; //select the nearest number
		if( window.opera && document.importNode ) { window.setTimeout('MWJ_findSelect( \''+y.name+'\' ).options['+(y.options.length - 2)+'].selected = true;',0); }
	} else if( y.options[y.selectedIndex].value == 'MWJ_UP' ) { //A/A except upwards
		while( y.options.length ) { y.options[0] = null; }
		y.nowTop += range + 1; y.nowBot = y.nowTop - range;
		y.options[0] = new Option('Lower ...','MWJ_DOWN');
		if( y.nowTop > max ) { y.nowTop = max; }
		for( var x = y.nowBot; x <= y.nowTop; x++ ) { y.options[y.options.length] = new Option(x,x); }
		if( max > y.nowTop ) { y.options[y.options.length] = new Option('Higher ...','MWJ_UP'); }
		y.options[1].selected = true;
		if( window.opera && document.importNode ) { window.setTimeout('MWJ_findSelect( \''+y.name+'\' ).options[1].selected = true;',0); }
	}
}
function reFill( y, oBot, oTop, oDown, oUp ) {
	y = MWJ_findSelect( y ); y.nowBot = oBot; y.nowTop = oTop;
	//empty and refill the select box using the range of numbers specified
	while( y.options.length ) { y.options[0] = null; }
	if( oDown ) { y.options[0] = new Option('Lower ...','MWJ_DOWN'); }
	for( var x = oBot; x <= oTop; x++ ) { y.options[y.options.length] = new Option(x,x); }
	if( oUp ) { y.options[y.options.length] = new Option('Higher ...','MWJ_UP'); }
}
