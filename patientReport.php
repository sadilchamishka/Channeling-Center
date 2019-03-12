<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
	if(!isset($_SESSION['doctorid'])){
		header('Location:logout.php?msg=invalid_attempt');
	}
	if(!isset($_GET['date']) || !isset($_GET['report']) || !isset($_GET['appObj'])){
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
  		$reportObject = $_GET['report'];
  		$report = unserialize(base64_decode($reportObject));
  		$time = $report->getTime();
  		$medicineList = $report->getMedicine();
  		$reportDetails = $report->viewDetails();
  		$displayDetails = '';
  		$displayDetails.="Date Submitted: {$_GET['date']}<br />";
  		$displayDetails.="Time Submitted: {$time}<br />";
  		$displayDetails.= "Report:<br>";
  		$displayDetails.="<p>{$reportDetails}</p><br>";
  		$displayDetails.="Medicine Given<br />";
  		$displayDetails.="<ul>";
  		foreach ($medicineList as $medicineName => $details) {
  			$displayDetails.="<li>".$medicineName." - ".$details['medicineQuentity'].' pills '.$details['usingTime'].' for '.$details['numberOfDays']." days &nbsp</li>";
  		}


   ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>View Report</title>
 	<?php include('inc/doctorHeader.php') ?>
	<main>
		<?php echo $displayDetails; ?>
		 <p><a href="viewPatientRecords.php?appObj=<?php echo $appObj ?>">Back</a></p>

	</main>
 	<?php include('inc/doctorFooter.php') ?>