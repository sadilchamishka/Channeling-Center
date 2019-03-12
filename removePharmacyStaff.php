<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
	if(!isset($_SESSION['adminid'])){
		header('Location:index.php');
	}
 ?>
 

 <?php 
 	$id;
 	if(isset($_GET['pharmacyStaffId'])){
 		$id = $_GET['pharmacyStaffId'];
 		$msg = "pharmacyStaffId_{$id}_successfully_removed";
 		$query = "UPDATE pharmacyStaff SET is_deleted=1 WHERE id='{$_GET['pharmacyStaffId']}'";
 		$result = mysqli_query($connection,$query);
 		verifyQuery($result);
 		header('Location:managePharmacyStaff.php?msg=successfully_Removed');
 	}
  ?>