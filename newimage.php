<!DOCTYPE html PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML>
   <HEAD>
      <TITLE>
         A Small Hello 
      </TITLE>
   </HEAD>
<BODY>
<?php

	$disklist = "sudo parted -l | grep 'Disk /'";
	exec($disklist, $output);
	echo $output[0];
	$caselist = "ls -d /var/www/html/Cases/*";
	exec($caselist, $cases);
	echo "Please select the drive you wish to image <br/>";
	echo "<form action='start.php' method='post'>
		<select name='drive'> ";
	foreach ($output as $temp)
	{
		$token= explode(" ", $temp);
		
		echo "<option value= $token[1]>$temp</option>";
	}
	echo "</select><br /><br />Please select the number of threads to create(enter 0 for default, based on CPU core count)<br />";
	echo "<input type='number' name='threads' min='0' max='8'><br /><br />";
	echo "Please select the case<br /><select name='case'>";
	foreach ($cases as $temp)
	{
		$token= explode(" ", $temp);
		foreach ($token as $temp2);
			$tk=explode("/", $temp2);
			echo "<option value= $tk[5]>$tk[5]</option>";
	}
	echo"</select><br /><br />";
	echo"Please name the image to be made:<br /><input type='text' name='filename'><br/><br />";
	echo"<input type='submit' value='Submit' name='btnSub'></form";
	

?>
</BODY>
</HTML>
