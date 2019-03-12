<?php 

	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'project';

	$connection = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

	if (!$connection){
		die("Database connection failed ".mysqli_connect_error());
	}
 ?>