<?php session_start() ?>
<?php require_once('includes/connection.php') ?>
<?php require_once('includes/class.php') ?>
<?php require_once('includes/functions.php') ?> 

 <?php 
 	$id;
 	if(isset($_GET['id'])){
 		$id = $_GET['id'];
 		$query = "UPDATE orders SET is_deleted=1 WHERE order_id='{$id}'";
 		$result = mysqli_query($connection,$query);
 		verifyQuery($result);
 		header('Location:orders.php?msg=successfully_Removed');
 	}
  ?>
