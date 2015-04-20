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
function file_find($string, $path, $color){
	$z = new XMLReader;
	$z->open($path);
	$doc = new DOMDocument;

	#xmlfilter setup
	#setup filter xml reader
	$xmlpath= $_SESSION['imagepath'];
	$xmlpath.="/tags.xml";

	$tagxml = new XMLReader;
	$tagxml->open($xmlpath);
	$doc2= new DomDocument;
	echo "<ul class='dir'>";
	// move to the first <product /> node
	while ($z->read() && $z->name !== 'fileobject');
	while ($tagxml->read() && $tagxml->name !== 'fileobject');
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
			echo "<li class='list'><span>/".$node->filename."/</span>";

			file_find($node->filename, $path, $color);
			echo "</li>";
		}
		elseif($node->name_type =='r' && strcmp($dir, $string)==0 && $node->unalloc !=1)
		{
			$tmp=trim($node->id);
			$file=explode('/', $node->filename);
			if(trim($color[$tmp]) != null) #the file has a tag already, set li id
			{
				$tmp2=trim($color[$tmp]);
				echo "<li class='list' onclick=fileOP('$tmp') id='".$tmp2."'><span>".end($file)."</span></li>";
			}
			else	#file doesnt have tag
			{
				echo "<li class='list' onclick=fileOP('$tmp')><span>".end($file)."</span></li>";
			}
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

#beginning of page
#setup filepath variable using GET
$filter="";
$tmp=$_GET["q"];
$path="/var/www/html/Cases/";
$path.=$tmp;
$_SESSION['imagepath']=$path;
$path.="/PrimaryXML.xml";
$string='<filename>';

#open file
$z = new XMLReader;
$z->open($path);
$doc = new DOMDocument;
$_SESSION['path']=$path;
session_write_close();

#setup filter xml reader

#filter dropdown choices, filters hardcoded for now
echo"<body>
<select id='filter'>  
<option value=''>none</option>   
<option value='review'>review</option>     
<option value='important'>important</option>     
<option value='useless'>useless</option>  
</select>";
echo "<input type='button' value='filter' onclick=filtertest('$tmp') >";

//create array of id's that have the selected filter
$color= array();
$xmlpath= $_SESSION['imagepath'];
$xmlpath.="/tags.xml";
$doc2= new DomDocument;
$tagxml = new XMLReader;
$tagxml->open($xmlpath);
while ($tagxml->read() && $tagxml->name !== 'fileobject');
while($tagxml->name=='fileobject')
{
	$node = simplexml_import_dom($doc2->importNode($tagxml->expand(), true));
	$tmp=trim($node->id);
	$tmp2=trim($node->tag);
	$color[$tmp]=$tmp2;
	$tagxml->next('fileobject');
}
#begin making XML list
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
		echo "<li class='list'><span>/".$node->filename."/</span>";
		file_find($node->filename, $path, $color);
		echo "</li>";
	}
	elseif($node->name_type =='r' && strpos($node->filename, '/')==false  && $node->unalloc !=1)
	{
			$tmp=trim($node->id);
			if(trim($color[$tmp]) != null) #the file has a tag already, set li id
			{
				$tmp2=trim($color[$tmp]);
				echo "<li class='list' onclick=fileOP('$tmp') id='".$tmp2."'><span>".$node->filename."</span></li>";
			}
			else	#file doesnt have tag
			{
				echo "<li class='list' onclick=fileOP('$tmp')><span>".$node->filename."</span></li>";
			}		
	}

    // go to next <product />
    $z->next('fileobject');
}
echo "</ul></body>";


//close php
?>
</body></html>







