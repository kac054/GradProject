<!DOCTYPE html PUBLIC "-//IETF//DTD HTML 2.0//EN">
<?php
session_id('id');
session_start();
session_unset();
?>

<HTML>
   <HEAD>
	<link rel="stylesheet" href="styles.css">
	<script type="text/javascript" src="script.js"></script>
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	
      <TITLE>
         A Small Hello 
      </TITLE>
   </HEAD>
<BODY>
	<div id="leftside">
		<div id="cases">
			<FORM> 
			<INPUT TYPE="BUTTON" VALUE="New Image" onclick="newImage()">
	<?php
		#make this variable, allow storage elsewhere
			exec("sudo ls /var/www/html/Cases", $cases);
			echo "<ul>";
			foreach ($cases as $casename)
			{
				$temp = 0;
				exec("sudo ls /var/www/html/Cases/$casename", $temp);
				echo "<li class='list'><span>$casename</span>";
				echo "<ul class='evidence'>";			
				foreach($temp as $evidence)
				{
					$pass= "$casename/$evidence";
					echo "<li id='entry' onclick=popXML('$pass'),caseinfo()><span>$evidence</span></li>";
				}
				echo "</ul></li>";
			}
			echo "</ul>";
	?>
			</FORM>
		</div>
	<div id="caseinfo">
		
	</div>
	</div>
	<div id= "rightside">
		<div id="XMLholder">
		<!--div for XML tree display, populated by onclick=popXML -->	
		</div>
		<div id="bottom">
			<div id="metadata">
			</div>
			
		</div>
	</div>
</BODY>
</HTML>
