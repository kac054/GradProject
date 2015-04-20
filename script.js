function newImage() 
{
	var left = (screen.width/2) - 200;
	var top = (screen.height/2) - 200;
	window.open('newimage.php', 'image_start_window', 'width=400, height=400, toolbar=no, top='+top+', left='+left+'');
}
function caseinfo() 
{
	var xmlhttp=null;
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("caseinfo").innerHTML=xmlhttp.responseText;
		}
	}
	 xmlhttp.open("GET","caseinfo.php",true);
	xmlhttp.send();
}
function popXML(str) 
{
	var xmlhttp=null;
	caseinfo();
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("XMLholder").innerHTML=xmlhttp.responseText;
		}
	}
	 xmlhttp.open("GET","makeXML.php?q="+str,true);
	xmlhttp.send();
	
}
function fileOP(str) 
{
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("metadata").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","fileOP.php?q="+str,true);
	xmlhttp.send();
}
function extract() 
{
	var left = (screen.width/2) - 200;
	var top = (screen.height/2) - 200;
	var popup= window.open('extractdiag.php', 'image_start_window', 'width=0, height=0, toolbar=no, top='+top+', left='+left+'');
}

function tag(id)
{ 
	var obj = document.getElementById("taglist").options[document.getElementById("taglist").selectedIndex].value;
	var left = (screen.width/2) - 200;
	var top = (screen.height/2) - 200;
	var popup= window.open('tag.php?q='+obj+'&z='+id, 'image_start_window', 'width=0, height=0, toolbar=no, top='+top+', left='+left+'');
	popup.close();
}

function filtertest(tmp)
{
	var obj = document.getElementById("filter").options[document.getElementById("filter").selectedIndex].value;
//if statement works, else doesnt	
	if(obj.trim() == '') popXML(tmp);
	else
	{
		popfilter(tmp);
	}
}
function popfilter(str) 
{
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("XMLholder").innerHTML=xmlhttp.responseText;
		}
	}
	var obj = document.getElementById("filter").options[document.getElementById("filter").selectedIndex].value;
	xmlhttp.open("GET","filtermakexml.php?q="+str+"&z="+obj,true);
	xmlhttp.send();
}
