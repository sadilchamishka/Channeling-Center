<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
	if(!isset($_SESSION['userid'])){
		header('Location:index.php');
	}if(!isset($_GET['appObj'])){
		header('Location:user.php?msg=invalid_attempt');
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
 	$appObj = $_SESSION['appObj'];
 	$appObj = unserialize(base64_decode($appObj));
 	$doctorObj = $appObj->getDoctor();
 	

 	
 	
  ?>





<!DOCTYPE html>
<html>
<head>
	<title>appointment2</title>
	<?php include('inc/userHeader.php') ?>

	<main>
		<div>
	
		<fieldset>
			<legend>Appointment Complete</legend>
			<p>Customer's Name: <?php echo $userObj->getFullName(); ?></p>
			<p>Doctor's Name: <?php echo $doctorObj->getFullName(); ?></p>
			<p>Appointment Number: <?php echo $appObj->getAppointmentNumber() ?></p> 
			<p>Date: <?php echo $appObj->getDate(); ?></p>
			<p>Day: <?php echo $appObj->getDay(); ?></p>
			<p>Time: <?php echo $appObj->getTime(); ?></p>
			<p>fee: <?php echo $appObj->getCost(); ?>
				<?php 
					if($appObj->getState()==='paid'){
						echo "paid";
					}
				?>
			</p>

		</fieldset>
	</div>

	</main>
<?php include('inc/userFooter.php') ?>