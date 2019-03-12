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

 <!DOCTYPE html>
 <html>
 <head>
 	<title>View Allegies</title>
 	<?php include('inc/userHeader.php') ?>
	<main>
		<?php 
			$userObj->displayAllegies();
			
		 ?>
            <p><div class="btnlinks"><a href="viewUserDetails.php">Back</a></div></p>
	</main>
 	<?php include('inc/userFooter.php') ?>