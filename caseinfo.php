<body><?php
session_id('id');
session_start();
			$tmp= $_SESSION['imagepath'];
			$tmp.="/casefile.txt";
			$lines = file($tmp);
			foreach ($lines as $line_num => $line) 
			{
				echo "" . htmlspecialchars($line) . "<br />\n";
			}
			
?>

</body>
