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
  		$appointment = $_GET['appObj'];
  		$appObj = unserialize(base64_decode($appointment));
  		$patientObj = $appObj->getUser();
  		
   ?>





<!DOCTYPE html>
 <html>
 <head>
 	<title>show patient details</title>
 	<?php include('inc/doctorHeader.php') ?>
	<main>
		<table>
			
			<tr><td><p><label>Name: </label><?php echo $patientObj->getFullName() ?></p></td></tr>
			<tr><td><p><label>Age: </label><?php echo $patientObj->getAge() ?></p></td><td><p><a href="viewPatientAllegies.php?appObj=<?php echo $appointment; ?>">View Allegies</a></p></td></tr>
			<tr><td><p><label>Gender:</label><?php echo $patientObj->getGender() ?></p></td><td><p><a href="viewPatientRecords.php?appObj=<?php echo $appointment; ?>">View Records</a></p></td></tr>
			<tr><td><p><label>DOB: </label><?php echo $patientObj->getDob() ?></p></td></tr>
			<tr><td><p><label>Address:</label><?php echo $patientObj->getStreatAddress1()." ".$patientObj->getStreatAddress2()." ".$patientObj->getCity()." ".$patientObj->getProvince() ?></p></td></tr>
			<tr><td><p><label></label></p></td></tr>
		</table>
		<a href="examinePatient.php?appObj=<?php echo $appointment ?>">Back</a>
	</main>