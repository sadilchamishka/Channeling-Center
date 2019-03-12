<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
	if(!isset($_SESSION['userid'])){
		header('Location:logout.php?msg=invalid_attempt');
	}if(!isset($_GET['userId'])){
		header('Location:editUserDetails.php?msg=invalid_attempt');
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
 	$currentPass = $user['password'];
  ?>


 <?php 
 	if(isset($_GET['userId']) && $_SESSION['userid'] == $_GET['userId']){
 		$errors = array(); 
 		if(isset($_POST['update'])){
 			if(!isset($_POST['password']) || strlen(trim($_POST['password']))<1){
 				$errors[] = 'Enter Password';
 			}
 			if(strlen($_POST['password'])>40){
 				$errors[] = "Password should be less than 40 charactors";

 			}
 			$newPass = mysqli_real_escape_string($connection,$_POST['password']);
 			$newConfirmPass = mysqli_real_escape_string($connection,$_POST['confirmPassword']);
 			$currentInputPass = mysqli_real_escape_string($connection,$_POST['currentPass']);
 			$currentInputPass = sha1($currentInputPass);
 			if($newPass!=$newConfirmPass){
 				$errors[] = 'confirm password not matched';
 			}
 			$newPass = sha1($newPass);
 			if($currentInputPass!=$currentPass){
 				$errors[] = "current Password Not matched";
 			}
 			if(empty($errors)){
 				$query = "UPDATE users SET password='{$newPass}' WHERE id='{$userid}'";
 				$result = mysqli_query($connection,$query);
 				verifyQuery($result);
 				header('Location:editUserDetails.php?msg=Password_changed_successfully');
 			}
 			
 		}
 	}

 ?>	

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Change Password</title>
 	<?php include('inc/userHeader.php') ?>
	<main>
		<?php if(!empty($errors)){
			printErrors($errors);
		} ?>
		<form method="post" action="changeUserPassword.php?userId=<?php echo $userid?>">
		<label>Current Password</label>
		<input type="password" name="currentPass">
		<label>New Password:</label>
		<input type="password" name="password">
		<label>Confirm Password</label>
		<input type="password" name="confirmPassword">
		<button type='submit' name='update'>Update Password</button>
	</form>
	</main>
 
<?php include('inc/userFooter.php') ?>