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
 	if(isset($_GET['doctorId'])){
 		$id = $_GET['doctorId'];
 		$msg = "doctorId_{$id}_successfully_removed";
 		$query = "UPDATE doctors SET is_deleted=1 WHERE id='{$_GET['doctorId']}'";
 		$result = mysqli_query($connection,$query);
 		verifyQuery($result);
 		header('Location:manageDoctor.php?msg=successfully_Removed');
 	}
  ?>