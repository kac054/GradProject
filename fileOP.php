<!DOCTYPE html PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML>
   <HEAD>
	<script type="text/javascript" src="script.js"></script>
   </HEAD>
<BODY>
<?php

session_id('id');
session_start();

$id=$_GET["q"];
$path= $_SESSION['path'];
$z = new XMLReader;
$z->open($path);
$doc = new DOMDocument;

// move to the first <product /> node
while ($z->read() && $z->name !== 'fileobject');

// now that we're at the right depth, hop to the next <product/> until the end of the tree
	while ($z->name === 'fileobject')
	{	
		$node = simplexml_import_dom($doc->importNode($z->expand(), true));
		if(strcmp($node->id, $id) ==0)
		{
			echo "fullname:".$node->filename;
			$tmp= $node->filename;
			$tmp=explode('/', $tmp);
			$tmp=end($tmp);
			
			$_SESSION['search']= "$tmp";
			echo "<br/>";
			echo "filesize:".$node->filesize." bytes";
			echo "<br/>";
			$tmp= explode('T', $node->mtime);
			echo "last modified:".$tmp[0]." ".$tmp[1];
			echo "<br/>";
			$tmp= explode('T', $node->crtime);
			echo "Creation:".$tmp[0]." ".$tmp[1];	
			echo "<br/>";
			$tmp= explode('T', $node->atime);
			echo "Access:".$tmp[0]." ".$tmp[1];
			echo "<br/>";
			echo "md5:".$node->hashdigest;
		}
		$z->next('fileobject');
	}

echo "
<br/>
<input type='button' value='extract' onclick='extract();'><br/>
select a tag:
<select id='taglist'>     
<option value='review'>review</option>     
<option value='important'>important</option>     
<option value='useless'>useless</option>  
</select> ";

echo "<input type='button' value='Tag' onclick='tag($id) '>";

?>
</body>
</html>
