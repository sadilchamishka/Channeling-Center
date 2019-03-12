<?php 

	function printErrors($errors){
		echo "<p class='error'>";
			foreach ($errors as $error) {
			echo $error.'<br>';
		}
		echo "</p>";
	}

	function verifyQuery($query){
		global $connection;
		if(!isset($query)){
			die("Database query failed").mysqli_error($connection);
		}
	} 

	function checkMaxLenFields($maxLenFields){
		$errors = array();
		foreach ($maxLenFields as $field => $value) {
			if (trim(strlen($_POST[$field]))>$value){
				$errors[] = $field." must be less than ".$value;
			}
		}
		return $errors;
	}

	function checkReqFields($requireFields){
		$errors = array();
		foreach ($requireFields as $field) {
			if(!isset($_POST[$field]) || strlen(trim($_POST[$field]))<1 ){
				$errors[] = $field." missing / invalid";
			}

		}
		return $errors;
	}

	function checkIsSet($field){
		if(isset($_POST[$field]) && strlen(trim($_POST[$field]))>0){
			return 1;
		}
		else{
			return 0;
		}
	}

	
 ?>