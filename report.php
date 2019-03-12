<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
	$_SESSION['doctorId'] = '';
	$_SESSON['appObj'] = '';
	$userid = $_SESSION['userid'];
	if(!isset($_SESSION['userid'])){
		header('Location:logout.php?msg=invalid_attempt');
	}
	if(!isset($_GET['report']) || !isset($_GET['date'])){
		header('Location:user.php');
	}
 ?>
 <?php 
 	$userid = $_SESSION['userid'];
 	$query = "SELECT * FROM users WHERE id='{$userid}' LIMIT 1";
 	$result = mysqli_query($connection,$query);
 	verifyQuery($result);
 	$user = mysqli_fetch_assoc($result);
 	$userObj = $user['object'];
 	$userObj = unserialize($userObj);
 	$name = $userObj->getFullName();

  ?>
  <?php 
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
 	<?php include('inc/userHeader.php') ?>
	<main>
		<?php echo $displayDetails; ?>
		 <p><a href="viewRecords.php">Back</a></p>
	</main>
 	<?php include('inc/userFooter.php') ?>