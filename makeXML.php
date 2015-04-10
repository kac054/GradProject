<?php
// Start the session
session_id('id');
session_start();
?>
<!DOCTYPE html PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML>
	<HEAD>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
		<script type="text/javascript" src="script.js"></script>
	</head>
<body>
<?php
function get_string_between($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);   
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}

#do a function for files and one for directories
#will need to pass an int to signify how may tokens to pull off filename front for directory traversal
function file_find($string, $path){
	$z = new XMLReader;
	$z->open($path);
	$doc = new DOMDocument;
	echo "<ul class='dir'>";
	// move to the first <product /> node
	while ($z->read() && $z->name !== 'fileobject');
	// now that we're at the right depth, hop to the next <product/> until the end of the tree
	while ($z->name === 'fileobject')
	{	
		$node = simplexml_import_dom($doc->importNode($z->expand(), true));
	    // now you can use $node without going insane about parsing
		#$test= explode('/',$node->filename);
		#$test= array_slice($test, 0, $GLOBALS['depth']);
		$dir=pathinfo($node->filename, PATHINFO_DIRNAME);	
		#$temp= explode('/', $dir);
		if ($node->name_type == 'd' && strpos($node->filename, '.') ==false && strcmp($dir, $string)==0)
		{	
			echo "<li class='list'><span>".$node->filename."</span>";

			file_find($node->filename, $path);
			echo "</li>";
		}
		elseif($node->name_type =='r' && strcmp($dir, $string)==0 && $node->unalloc !=1)
		{
			$tmp=$node->id;
			echo "<li class='list' onclick=fileOP('$tmp') onclick><span>".$node->filename."</span></li>";
		}
				// go to next <product />
		$z->next('fileobject');
	}
	echo "</ul>";
}
function str_startswith($source, $prefix)
{
   return strncmp($source, $prefix, strlen($prefix)) == 0;
}
#setup filepath variable using GET
$path="/var/www/html/Cases/";
$path.=$_GET["q"];
$_SESSION['imagepath']=$path;
$path.="/primaryXML.xml";
$string='<filename>';
#open file
$z = new XMLReader;
$z->open($path);
$_SESSION['path']=$path;
session_write_close();
$doc = new DOMDocument;

echo "<ul class='dir'>";
// move to the first <product /> node
while ($z->read() && $z->name !== 'fileobject');
// now that we're at the right depth, hop to the next <product/> until the end of the tree
while ($z->name === 'fileobject')
{
    $node = simplexml_import_dom($doc->importNode($z->expand(), true));
    // now you can use $node without going insane about parsing
	$temp= explode('/', $node->filename);
	if ($node->name_type == 'd' && strpos($node->filename, '.') ==false && sizeof($temp) ==1)
	{	
		$tmp = "fileOP.php?q="+$node->id;
		echo "<li class='list'><span>".$node->filename."</span>";
		file_find($node->filename, $path);
		echo "</li>";
	}
	elseif($node->name_type =='r' && strpos($node->filename, '/')==false  && $node->unalloc !=1)
	{
		$tmp=$node->id;
		echo "<li class='list' onclick=fileOP('$tmp')><span>".$node->filename."</span></li>";
	}

    // go to next <product />
    $z->next('fileobject');
}
echo "</ul>";


//close php
?>

<script> 
$('.dir ul').hide();
$('.list').click(function() {
    $(this).find('ul').slideToggle();
    });</script>
</body></html>







