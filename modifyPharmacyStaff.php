<?php session_start() ?>
<?php require_once('inc/functions.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/connection.php') ?>


<?php 
	if(!isset($_SESSION['staffId'])){
		header('Location:pharmacyDashboard.php');
	}
 ?>
 <?php 
 	$staffid = $_SESSION['staffId'];
    $query = "SELECT * FROM pharmacystaff WHERE id='{$staffid}' LIMIT 1";
    $result = mysqli_query($connection,$query);
    verifyQuery($result);
    $staff = mysqli_fetch_assoc($result);
    $staffObj = $staff['object'];
    $staffObj = unserialize($staffObj );
    $name = $staffObj->getFullName();


$firstname =$staffObj->getFirstName();
$lastname = $staffObj->getLastName();
$email ="";
$streetAddress1="";
$streetAddress2 ="";
$city ="";
$province ="";
$password = "";
$confirmPassword ="";
$dateOfBirth =$staffObj->getDOB();
$gender =$staffObj->getGender();

    

  ?>

<?php
	
	

 	if(isset($_POST['register'])){
 		$firstname = $_POST['firstname'];
		$lastname =  $_POST['lastname'];
		$email = $_POST['email'];
		$streetAddress1 = $_POST['streetAddress1'];
		$streetAddress2 = $_POST['streetAddress2'];
		$city = $_POST['city'];
		$province = $_POST['province'];
		$password = $_POST['password'];
		$dateOfBirth = $_POST['dob'];
		
 		$errors = array();
 		$gender = $_POST['gender'];
 		 // checking if first name entered
 		$reqFields = array('firstname','lastname','email','streetAddress1','city','province','password','dob','gender');
 		$errors = array_merge($errors,checkReqFields($reqFields));
 		
 		// check max len fields
 		$maxLenFields = array('email'=>100,'password'=>40,'firstname'=>100,'lastname'=>100);
		$errors = array_merge($errors,checkMaxLenFields($maxLenFields));

		//check if email already exists

		$query = "SELECT * FROM doctors WHERE email='{$email}'";
		$resultSet = mysqli_query($connection,$query);
		verifyQuery($resultSet);
		if(mysqli_num_rows($resultSet)>0){
			$errors[] = "email already exists";
		}
		$query = "SELECT * FROM users WHERE email='{$email}'";
			$result = mysqli_query($connection,$query);
			verifyQuery($result);
			if(mysqli_num_rows($result)!=0){
				$error[] = 'email already exists';
			}
		$query = "SELECT * FROM admins WHERE email='{$email}'";
		$result = mysqli_query($connection,$query);
		verifyQuery($result);
		if(mysqli_num_rows($result)!=0){
			$error[] = 'email already exists';
		}
		$query = "SELECT * FROM pharmacystaff WHERE email='{$email}'";
		$result = mysqli_query($connection,$query);
		verifyQuery($result);
		if(mysqli_num_rows($result)!=0){
			$error[] = 'email already exists';
		}
		

		
		 if(empty($errors)){

		 	$firstname = mysqli_real_escape_string($connection,$_POST['firstname']);
		 	$lastname = mysqli_real_escape_string($connection,$_POST['lastname']);
		 	$email = mysqli_real_escape_string($connection,$_POST['email']);
		 	
		 	$password = mysqli_real_escape_string($connection,$_POST['password']);
		 	$hashedPassword = sha1($password);
		 	$age = floor((time() - strtotime($dateOfBirth))/31556926);
		 	$fullAddress = $streetAddress1." ".$streetAddress2;
		 	$pharmacyStaffObject=$staffObj;
		 	$pharmacyStaffObject->setFirstName($firstname);
		 	$pharmacyStaffObject->setLastName($lastname);
		 	$pharmacyStaffObject->setGender($gender);
		 	//$pharmacyStaffObject->setCity($city);
		 	$pharmacyStaffObject->setDOB($dateOfBirth);
		 	//$pharmacyStaffObject->setAge($age);
		 	//$staffObj->setFirstName($firstname);
		 	//$pharmacyStaffObject = unserialize($staffObj);
		 	//$pharmacyStaffObject->pharmacyStaff($firstname,$lastname,$age,$fullAddress,$dateOfBirth,$city,$province,$gender);
		 	//$pharmacyStaffObject = serialize($pharmacyStaffObject);
		 	$query = "UPDATE pharmacystaff  SET email='{$email}',password='{$hashedPassword}',object='{$pharmacyStaffObject}',is_deleted=0 WHERE id={$staffid}";
		 	$result = mysqli_query($connection,$query);
		 	if(!$result){
		 		echo "Database query failed";
		 	}else{
			 	$query = "SELECT * FROM pharmacystaff WHERE password='{$hashedPassword}' AND object='{$pharmacyStaffObject}' LIMIT 1";
			 	$result = mysqli_query($connection,$query);
			 	verifyQuery($result);
			 	if(!$result){
			 		echo "Database query failed";
			 	}else{
			 		$pharmacyStaffObject = mysqli_fetch_assoc($result);
			 		$pharmacyStaff = unserialize($pharmacyStaffObject['object']);
			 		$pharmacyStaff->setId($pharmacyStaffObject['id']);
			 		$pharmacyStaff = serialize($pharmacyStaff);
			 		$query = "UPDATE pharmacystaff SET object='{$pharmacyStaff}' WHERE id={$pharmacyStaffObject['id']}";
			 		$result_set = mysqli_query($connection,$query);
			 		if(!$result_set){
			 			echo "Database Query failed";
			 		}
			 		else{
			 			header('Location:pharmacyDashboard.php?msg=update_successful');
			 		}
			 	}
			 }
		 }


 	}

 ?>

<!DOCTYPE html>
<html>
<head>
	<p style="float:right"><font color="red">Welcome <?php echo $name ;?>!</font> <a href="logout.php"><font color="red">Log Out</font></a></p> 
	
    
</head>
	<title>Modify Pharmacy Records</title>
	<body>
		 <?php include("inc/pharmacyheader.php"); ?>
	<div class="pharmacy">
		
		<form action='modifyPharmacyStaff.php' method='post'>
			
			<fieldset>
				<?php 
					if(isset($errors) && !empty($errors)){
						printErrors($errors);
					}
				 ?>
			

				<p>
					<label>First Name:</label>
					<input type="text" name="firstname" placeholder="firstname" <?php  echo 'value = '.'"'.$firstname.'"'; ?>">
				

				
					<label>Last Name:</label>
					<input type="text" name="lastname" placeholder="lastname" value='<?php  echo $lastname ?>'>
				</p>

				<p>
					<label>Email:</label>
					<input type="email" name="email" placeholder="someone@example.com" value='<?php  echo $email ?>'>
				
				</p>
				<p>

				
					<label>Date Of Birth:</label>
					<input type="date" name="dob" >
				</p>
				<p>
					<label>Select Gender: </label>
					<select name='gender'>
						<option value='male'>Male</option>
						<option value='female'>Female</option>
					</select>
				</p>

				<p>
					<label>Address:<br><br></label>	
					<input type="text" name="streetAddress1" placeholder="street address " value='<?php  echo $streetAddress1 ?>'>
					<br>street Address<br><br>
					<input type="text" name="streetAddress2" placeholder="street address " value='<?php  echo $streetAddress2 ?>'>
					
					street Address Line2(optional)
					<br><br>
					<input type="text" name="city" placeholder="city" value='<?php echo $city ?>' >
					City  <br><br>
					<input type="text" name="province" placeholder="province" value='<?php echo $province ?>'>
					Province<br>
					
				</p>

				<p>
					<label>Password:</label>
					<input type="password" name="password" placeholder="password">
				</p>

				<p>
					<button type="submit" name="register">Update</button>
				</p>
				
				
			</fieldset>
		</form>
	</div>
</main>
</body>
</html>  





