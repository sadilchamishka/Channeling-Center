<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 

	if(!isset($_SESSION['userid'])){
		header('Location:logout.php?msg=invalid_attempt');
	}
	$appointment = $_SESSION['appObj'];
	$appObj = unserialize(base64_decode($appointment));
	$doctorId = $appObj->getDoctorId();
	if($_SESSION['appObj'] === '' || $_SESSION['doctorID']!= $appObj->getDoctorId() ){
		header("Location:appointment.php");
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
 	$errors = array();
 	if(isset($_POST['pay'])){
 		if((!isset($_POST['ccnumber'])) || strlen(trim($_POST['ccnumber']))!=16 || !is_numeric(trim($_POST['ccnumber']))){
 			$errors[] = 'invalid credit card number';
 		}
 		if((!isset($_POST['month'])) || $_POST['month']==='SelectMonth'){
 			$errors[] = 'invalid expiration month';
 		}
 		if((!isset($_POST['year'])) || $_POST['year']==='SelectYear'){
 			$errors[] = 'invalid expiration year';
 		}
 	
 		
 		if((!isset($_POST['securityNumber'])) || strlen(trim($_POST['securityNumber']))!=3 || !is_numeric(trim($_POST['securityNumber']))){
 			$errors[] = 'invalid security number';
 		}
                if($_POST['securityNumber']<1){
                    $errors[] = 'invalid credit card number';
                }
                 if($_POST['ccnumber']<1){
                    $errors[] = 'invalid credit card number';
                }
 		
 		if(empty($errors)){
 			$appObj->pay();
 			$date =$appObj->getDate();
 			$startTime = $appObj->getStartTime();
 			$EndTime = $appObj->getEndTime();
 			$timeStampStart = $date." ".$startTime;
 			$timeStampEnd = $date." ".$EndTime;
 			$timeStampStart = strtotime($timeStampStart);
 			$timeStampEnd = strtotime($timeStampEnd);
 			$appObj = serialize($appObj);
 			$query = "INSERT INTO appointment(userId,doctorId,appointmentObject,is_expire,is_used,timeStampStart,timeStampEnd) VALUES('{$userid}','{$doctorId}','{$appObj}',0,0,'{$timeStampStart}','{$timeStampEnd}')";
 			$result = mysqli_query($connection,$query);
 			verifyQuery($result);
	
			$query = "SELECT * FROM appointment WHERE appointmentObject = '{$appObj}' LIMIT 1";
			$result = mysqli_query($connection,$query);
			verifyQuery($result);
			$appointmentDetails = mysqli_fetch_assoc($result);
			$appointmentNumber = $appointmentDetails['appointmentNumber'];
			$appointment = $appointmentDetails['appointmentObject'];
			$appObj = unserialize($appointment);
			$appObj->setAppointmentNumber($appointmentNumber);
			$serielizedObj = serialize($appObj);
			$query = "UPDATE appointment SET appointmentObject = '{$serielizedObj}' WHERE appointmentNumber = '{$appointmentNumber}'";
			$result = mysqli_query($connection,$query);
 			verifyQuery($result);
 			$appObj = base64_encode(serialize($appObj));
 			$_SESSION['appObj'] = $appObj;
 			header("Location:paymentComplete.php?appObj=$appObj");
 		}
 		
 	}

  ?>


 <!DOCTYPE html>
 <html>
 <head>
 	<title>CreditCardPayment</title>
 	<?php include('inc/userHeader.php') ?>
	<main>
 	<div>
            <form action='creditCard.php?appObj=<?php echo $appointment; ?>' method='post'>
	 		
	 			<h1>Pay Via Credit Card</h1>
	 			<?php  
	 			if(!empty($errors)){
 					printErrors($errors);
 				} ?>
	 			<div class="form-row col-md-6">

	 				<label for="number">Credit Card Number: </label>
	 				<input type="text" name="ccnumber" maxlength="16" placeholder="Credit Card Number" class="form-control col-md-4" id="number">
	 			</div>
	 			
	 				<div class="form-row col-md-6">
	 				<label for="selectExDate">Card Expiration Date: </label>
	 				

		 				<div class='form-group'>
		 					
		 				<select name='month' class="form-control col-md-20" id="selectExDate">
		 					
		 					<option value='SelectMonth'>Select Month</option>
		 					<?php 
		 						$months = array('January','February','March','April','May','June','July','August','Septemeber','October','November','December');
		 						foreach ($months as $month) {
		 							echo "<option value='{$month}'>{$month}</option>";
		 						}
		 					 ?>
		 				</div>	
		 				</select>
		 				</div>

		 				<div class="form-group">
		 					
		 				<select name='year' class="form-control col-md-20" id="selectExDate">
		 					
		 					<option value='SelectYear'>Select Year</option>
		 					<?php 
		 						$years = range(2018,2050);
		 						foreach ($years as $year) {
		 							echo "<option value='{$year}' >{$year}</option>";
		 						}
		 					 ?>
		 				</select>
		 				</div>
	 				</div>

	 			
	 			<div class="form-group">
	 			<div class="form-row col-md-8">
	 				<label for="secNum">Security Number: </label>
	 				<input type="text" name="securityNumber" maxlength="3", placeholder="3 digit" class="form-control col-md-4" id="secNum">
	 			</div>
	 			<div class='row col-sm-10'>
	 				(Enter 3 digit security number in the back of your credit card)
	 			</div>
	 		</div>

	 			<p>
	 				<button type='submit' name='pay' class="btn btn-warning btn-lg">Pay</button>
	 			</p>

	 		</fieldset>
	 	</form>
 	</div>
 </main>

<?php include('inc/userFooter.php') ?>