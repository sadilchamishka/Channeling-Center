<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

 <?php 
 	$id;
 	if(isset($_GET['id'])){
 		$id = $_GET['id'];
 		$query = "UPDATE inventory SET is_deleted=1 WHERE item_id='{$id}'";
 		$result = mysqli_query($connection,$query);
 		verifyQuery($result);
 		header('Location:inventory.php?msg=successfully_Removed');
 	}
  ?>