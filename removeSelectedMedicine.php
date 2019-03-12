<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
	if(!isset($_SESSION['doctorid'])){
		header('Location:logout.php?msg=invalid_attempt');
	}
  if(!isset($_GET['medicineName']) || !isset($_GET['appObj'])){
    header('Location:examinePatient.php?msg=invalid_attempt');
  }
 ?>
 <?php 
    $appObj = $_GET['appObj'];
    $medicineList = $_SESSION['medicineList'];
    unset($medicineList[$_GET['medicineName']]);
    $_SESSION['medicineList'] = $medicineList;
    header("Location:examinePatient.php?appObj=$appObj&&msg=removed_medicine_successfully");
  ?>
  