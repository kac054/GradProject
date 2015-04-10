<html>
<body>

<?php 
	#drive, threads, case, filename
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$filename=test_input($_POST["filename"]);
	}

	$drive= $_POST["drive"];
	$threads= $_POST["threads"];
	$case= $_POST["case"];
	$drive=rtrim($drive, ":");
	$folder = "/var/www/html/Cases/$case/$filename";
	exec("sudo mkdir $folder", $output);
	echo $output;	
	$filename.=".img";
	$save= "$folder/$filename";

 
	$command= "sudo python /var/www/html/pyrun.py -i $drive -o $save -t $threads -f $folder 2>&1";
	shell_exec($command);
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
}
?>

</body>
</html>
