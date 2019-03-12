<?php session_start() ?>
<?php require_once('inc/functions.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/connection.php') ?>

<?php 
	if(!isset($_SESSION['adminid'])){
		header('Location:index.php');
	}
 ?>
 <?php 
 	if(!isset($_GET['doctorId']) || !isset($_GET['day'])){
 		header('editDoctorDetails.php?msg=No_Info_set');
 	}
  ?>
 <?php 
 	$adminid = $_SESSION['adminid'];
 	$query = "SELECT * FROM admins WHERE id='{$adminid}' LIMIT 1";
 	$result = mysqli_query($connection,$query);
 	verifyQuery($result);
 	$admin = mysqli_fetch_assoc($result);
 	$adminObj = $admin['object'];
 	$adminObj = unserialize($adminObj);
 	$name = $adminObj->getFullName();
 	$doctorObj='';
 	$doctorObject='';
 	$doctorId = $_GET['doctorId'];
  $day = $_GET['day'];
  $doctorObject = $_GET['doctor'];
  $doctorObject = unserialize(base64_decode($doctorObject));

 	
  ?>
  <?php 
  	
      
  		
      $doctorObject->removeTime($day);
      $doctorObject = base64_encode(serialize($doctorObject));
      header("Location:editActiveTimes.php?doctorId=$doctorId&&doctorObject=$doctorObject");
    
	 ?>


