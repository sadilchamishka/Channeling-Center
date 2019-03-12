<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/functions.php') ?>

<?php 
	$email = "";
	//checking if username and password entered
	if(isset($_POST['submit'])){
		$errors = array();
		if(!isset($_POST['email']) || strlen(trim($_POST['email']))<1){
			$errors[] = "Username missing / invalid";
		}
		if(!isset($_POST['password']) || strlen(trim($_POST['password']))<1){
			$errors[] = "Password missing / invalid";
		}
		if(empty($errors)){
			$email = mysqli_real_escape_string($connection,$_POST['email']);
			$password = mysqli_real_escape_string($connection,$_POST['password']);
			$hashed_password = sha1($password);
			$query = "SELECT * FROM admins WHERE email='{$email}' AND password='{$hashed_password}' AND is_deleted=0 LIMIT 1";
			$result_set = mysqli_query($connection,$query);
			verifyQuery($result_set);

			if(mysqli_num_rows($result_set) ==1){
				$admin = mysqli_fetch_assoc($result_set);
				$_SESSION['adminid'] = $admin['id'];
				$_SESSION['adminObject'] = $admin['object'];
				header("Location: admin.php?msg=login_success&&userid={$_SESSION['adminid']}");

			}else{
				$errors[] = "invalid Username / Password";
			}


		}
	}
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Log In</title>
	<?php include('inc/Header.php') ?>
	<main>
		<div class='Login'>
			<form action='loginAdmin.php' method='post'>
				<fieldset>
					<legend>Log In</legend>
					<?php 
						if(isset($errors) && !empty($errors)){
							echo "<p class='error'>Invalid Username / Password</p>";
						}
					 ?>
					<p> 
						<label for="">UserName</label>
						<input type="text" name="email" placeholder="username" value="<?php echo $email?>">

					</p>
					<p> 
						<label for="">Password</label>
						<input type="password" name="password" placeholder="password">
					</p>
					<p> 
						<button type="submit" name="submit">Log In</button>
					</p>
					
					<p><a href="loginDoctor.php" class='loginoption'>Log In as a Doctor</a></p>
					<p><a href="index.php" class='loginoption'>Log In as An User</a></p>
					
				
				</fieldset>
			</form>
		</div>
	</main>
<?php include('inc/Footer.php') ?>

<?php mysqli_close($connection) ?>