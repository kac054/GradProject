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
function file_find($string, $path, $tagar, $color){
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

			file_find($node->filename, $path, $tagar, $color);
			echo "</li>";
		}
		elseif($node->name_type =='r' && strcmp($dir, $string)==0 && $node->unalloc !=1)
		{
			while(current($tagar)!= False)
			{
				$file=explode('/', $node->filename);
				$tmp=trim($node->id);
				if(strcmp(current($tagar),$tmp) ==0)
				{
					$tmp2=trim($color[$tmp]);
					echo "<li class='list' onclick=fileOP('$tmp') id='".$tmp2."'><span>".end($file)."</span></li>";
				}
				next($tagar);		
			}
		reset($tagar);
		}
				// go to next <product />
		$z->next('fileobject');
	}
	echo "</ul>";
}

//page start
$tmp=$_GET["q"];
$filter=$_GET['z'];
$path="/var/www/html/Cases/";
$path.=$tmp;
$_SESSION['imagepath']=$path;
$path.="/PrimaryXML.xml";
$string='<filename>';

echo"
<select id='filter'>  
<option value=''>none</option>   
<option value='review'";

if($filter=='review') echo"selected='selected'";
echo">review</option>     
<option value='important'";

if($filter=='important') echo"selected='selected'";
echo">important</option>     
<option value='useless'";

if($filter=='useless') echo"selected='selected'";
echo">useless</option>  
</select>";
echo "<input type='button' value='Filter' onclick=filtertest('$tmp') >";


$z = new XMLReader;
$z->open($path);
$doc = new DOMDocument;

//create array of id's that have the selected filter
$tagar = array();
$color = array();
$xmlpath= $_SESSION['imagepath'];
$xmlpath.="/tags.xml";
$doc2= new DomDocument;
$tagxml = new XMLReader;
$tagxml->open($xmlpath);
while ($tagxml->read() && $tagxml->name !== 'fileobject');
while($tagxml->name=='fileobject')
{
	$node = simplexml_import_dom($doc2->importNode($tagxml->expand(), true));
	if($node->tag==$filter) 
	{
		array_push($tagar,$node->id);
		$tmp=trim($node->id);
		$tmp2=trim($node->tag);
		$color[$tmp]=$tmp2;
	}
	
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
		file_find($node->filename, $path, $tagar, $color);
		echo "</li>";
	}
	elseif($node->name_type =='r' && strpos($node->filename, '/')==false  && $node->unalloc !=1)
	{
		while(current($tagar)!= False)
		{
			$tmp=trim($node->id);
			if(strcmp(current($tagar),$tmp) ==0)
			{
				$tmp2=trim($color[$tmp]);
				echo "<li class='list' onclick=fileOP('$tmp') id='".$tmp2."'><span>".$node->filename."</span></li>";
			}
			next($tagar);		
		}
		reset($tagar);
	}

	// go to next <product />
	$z->next('fileobject');
}
echo "</ul>";

?>
</body></html>
