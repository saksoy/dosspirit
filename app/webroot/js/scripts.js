// JavaScript Document
function addRemove() {
	var ni = document.getElementById('omtalebilder');
	numi = document.getElementById('antallbilder');
	var num = (document.getElementById("antallbilder").value -1)+ 2;
	numi.value = num; // oppdaterer hiddenfield med antall divs
	var newdiv = document.createElement('p');
	newdiv.setAttribute('id','omtalebilde'+num);
	newdiv.setAttribute('name','omtalebilde'+num);
	newdiv.innerHTML = '<input type=\'file\' name=\'omtalebilder[]\' id=\'bildearray\'><img src=\'../../images/icons/report_icon.png\' onclick=\'document.getElementById("omtalebilder").removeChild(document.getElementById("'+'omtalebilde'+num+'"));\' border=\'0\'>';
	ni.appendChild(newdiv);
}

$('#addimage').livequery('click', function() {
	var num = parseInt($('#antallbilder').val());
	$('#omtalebilder').append('<p id="omtalebilde' + num + '"><input type="file" name="omtalebilder[]" id="bildearray" size="80"> <img src="../../images/icons/report_icon.png"></p>');
	num = (num + 1);
	$('#antallbildertekst').text('Antall bilder: ' + (num +1));
	$('#antallbilder').val(num);
});

$("p[id^='omtalebilde'] img").livequery('click', function() {
	var num = parseInt($('#antallbilder').val());
	num = (num - 1 );
	$('#antallbilder').val(num);
	$('#antallbildertekst').text('Antall bilder: ' + (num + 1));
	$(this).parent().remove();
});

$("p[id^='kategori'] img").livequery('click', function() {
	var num = parseInt($('#antallkategorier').text());
	num = (num - 1);
	$('#antallkategorier').text(num);
	$(this).parent().remove();
});

//Legger til sokemotoren for The DOS Spirit i Firefox
function addSearch() {
window.sidebar.addSearchEngine("http://www.dosspirit.net/tds_search.src", "http://www.dosspirit.net/tds_search.png", "The DOS Spirit", "Norske omtaler av gamle DOS-spill");
}

//Legger til ny bilde-filboks under innleggelse av ny omtale
function nyttBilde() {
	document.getElementById("bilder").innerHTML += "<p><input type=\"file\" name=omtalebilder[] id=omtalebilder[] /></p>";
}

// Brukerskjermskudd
function nyttBrukerBilde() {
	document.getElementById("brukerbilder").innerHTML += "<h4>Choose screenshot</h4>"+
	"<em>Only JPG, GIF AND PNG allowed</em><br>"+
	"<input type=\"file\" name=\"skjermskudd[]\" id=\"skjermskudd[]\" />"+
	"<h4>Choose what kind of image this is</h4>"+
	"<select name=\"bildetype[]\" id=\"bildetype[]\">"+
	"<option value=\"1\" selected=\"true\">Screenshot</option>"+
	"<option value=\"2\">Cover (Front)</option>"+
	"<option value=\"3\">Cover (Back)</option>"+
	"<option value=\"4\">Image of game media</option>"+
	"<option value=\"5\">Advertisement (for this game)</option>"+
	"<option value=\"6\">Other</option>"+
	"</select>"+
	"<h4>Screenshot description</h4>"+
	"<em>Max 200 characters, this is optional</em><br>"+
	"<textarea name=\"skjermbildebeskrivelser[]\" cols=\"35\" rows=\"5\" id=\"skjermbildebeskrivelser[]\"></textarea>"+
  	"<hr />";
}

function retroPlayer() {
	newwindow=window.open('http://dosspirit.net/php/retroplayer.php','The DOS Spirit Retro Player!','height=620,width=350,resizeable=0,menubar=0');
	if (window.focus) {newwindow.focus()}
	return false;

}


//Begrenser tegninput
function textCounter(field,limit) {
	countfield = document.getElementById("textLength");
	maxlimit = limit; // set to max number of chars
	if (field.value.length > maxlimit) /* if the current length is more than allowed  */ {
		field.value = field.value.substring(0, maxlimit); // don't allow further input
	} else { 
		countfield.innerHTML = (maxlimit - field.value.length); 
	}
} // set the display field to remaining number
