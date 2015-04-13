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
$tmp=$_GET["q"];
$filter=$_GET['z'];
$path="/var/www/html/Cases/";
$path.=$tmp;
$_SESSION['imagepath']=$path;
$path.="/primaryXML.xml";
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
echo "<input type='button' value='Tag' onclick=filtertest('$tmp') >";


$z = new XMLReader;
$z->open($path);


//$tagxml = new XMLReader;
//$tagxml->open($xmlpath);
$doc2= new DomDocument;

#begin making XML list
echo "<ul class='dir'>";
// move to the first <product /> node
while ($z->read() && $z->name !== 'fileobject');
/**
// now that we're at the right depth, hop to the next <product/> until the end of the tree
while ($z->name === 'fileobject')
{
	$tagxml = new XMLReader;
	$tagxml->open($xmlpath);
	while ($tagxml->read() && $tagxml->name !== 'fileobject');
	$node = simplexml_import_dom($doc->importNode($z->expand(), true));
    // now you can use $node without going insane about parsing
	$temp= explode('/', $node->filename);
	if ($node->name_type == 'd' && strpos($node->filename, '.') ==false && sizeof($temp) ==1)
	{	
		echo "<li class='list'><span>/".$node->filename."/</span>";
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
**/
















?>
</body></html>
