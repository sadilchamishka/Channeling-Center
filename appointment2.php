<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
	if(!isset($_SESSION['userid'])){
		header('Location:index.php');
	}
 ?>

<?php 
	$_SESSION['appObj'] = '';
	 if($_SESSION['doctorID'] != $_GET['doctorID']){
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
	$query = "SELECT * FROM doctors WHERE id={$_GET['doctorID']} LIMIT 1";
	$result = mysqli_query($connection,$query);
	verifyQuery($result);
	
	if(mysqli_num_rows($result)==1){
		$doctor = mysqli_fetch_assoc($result);
		$doctorObject = unserialize($doctor['object']);
		$userobject = $_SESSION['uObject'];
		$userObject = unserialize($userobject);
		$fee = $doctorObject->getFee();
		$new_appointment = new appointment($_GET['date'],$_GET['day'],$_GET['startTime'],$_GET['endTime'],$doctorObject,$fee,$userObject);
		$serialized_appointmnent = base64_encode(serialize($new_appointment));

		
	}
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>appointment2</title>
	<?php include('inc/userHeader.php') ?>

	<main>
		<div>
	
		<fieldset>
			<legend>New Appointment</legend>
			<p>Customer's Name: <?php echo $userObject->getFullName(); ?></p>
			<p>Doctor's Name: <?php echo $doctorObject->getFullName(); ?></p>
			<p>Appointment Number:</p> 
			<p>Date: <?php echo $new_appointment->getDate(); ?></p>
			<p>Day: <?php echo $new_appointment->getDay(); ?></p>
			<p>Time: <?php echo $new_appointment->getTime(); ?></p>
			<p>fee: <?php echo $new_appointment->getCost(); ?>
				<?php if($new_appointment->getState() === 'paid'){
					echo "paid";
				}else{
					$_SESSION['appObj'] = $serialized_appointmnent;
					echo "unpaid =>"."&nbsp";
					echo "<a href='creditCard.php?appObj={$serialized_appointmnent}'>pay now</a>";
					
				}

				?>
			</p>

		</fieldset>
	</div>

	</main>
<?php include('inc/userFooter.php') ?>