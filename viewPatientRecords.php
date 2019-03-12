<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
	if(!isset($_SESSION['doctorid'])){
		header('Location:logout.php?msg=invalid_attempt');
	}
	if(!isset($_GET['appObj'])){
		header('Location:doctor.php');
	}
 ?>
  <?php 
 	$doctorid = $_SESSION['doctorid'];
 	$query = "SELECT * FROM doctors WHERE id='{$doctorid}' LIMIT 1";
 	$result = mysqli_query($connection,$query);
 	verifyQuery($result);
 	$doctor = mysqli_fetch_assoc($result);
 	$doctorObj = $doctor['object'];
 	$doctorObj = unserialize($doctorObj);
 	$name = $doctorObj->getFullName();

  ?>
  <?php
  	$appObj = $_GET['appObj'];
  	$appointment = unserialize(base64_decode($appObj));
  	$patient = $appointment->getUser();
   ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>View Reports</title>
 	<?php include('inc/doctorHeader.php') ?>
	<main>
		<?php 
			$record=$patient->getRecord();
			$record->displayReports('patientReport.php',$appObj);
		 ?>
		 <p><a href="showPatientDetails.php?appObj=<?php echo $appObj ?>">Back</a></p>
	</main>
 	<?php include('inc/doctorFooter.php') ?>