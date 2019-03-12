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

  <?php 
      $query = "SELECT * FROM messages WHERE recieverType='user' AND (recieverId='{$userid}' OR recieverId=99999)";
      $resultSet=mysqli_query($connection,$query);
      verifyQuery($resultSet);
      $time = time();
      while($message = mysqli_fetch_assoc($resultSet)){
        $id = $message['id'];
        if($message['timeStamp']+60*60*24*30 < $time){
          $query = "DELETE FROM messages WHERE id='{$id}'";
          $result = mysqli_query($connection,$query);
          verifyQuery($result);
        }
      }

   ?>

  <?php
    //load messages
    $unread = 0;
    $query = "SELECT * FROM messages WHERE recieverType='user' AND (recieverId='{$userid}' OR recieverId=99999)";
    $resultSet = mysqli_query($connection,$query);
    verifyQuery($resultSet);
    while($message = mysqli_fetch_assoc($resultSet)){
      if($message['isRead']==0){
        $unread+=1;
      }
    }
  ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>USER</title>
 	<?php include('inc/userHeader.php') ?>
        <div class="topnav">
        
                <a  class="active" href="user.php">Home</a>
                <a  href="appointment.php">Make An Appointment</a>
                <a  href="viewAppointmentDetails.php">View Appointment Details</a>
                <a  href="viewUserDetails.php">View User Details</a>
                <a  href="editUserDetails.php">Edit Details</a>
        </div>
 
	<main>
		 <p style="float:left"><a href="userInbox.php" class="badge badge-light">Messages <span class="badge badge-pile badge-light"><?php echo $unread ?></span></a></p>
      </main>
      <body style="background: url(images/theme1.jpg);">
        
<p><br><br></p>
    <p><br><br></p>
    <p><br><br></p>
    <p><br><br></p>
    <p><br><br></p>
    <p><br><br></p>
    <p><br><br></p>
    <p><br><br></p>
                <p><br><br></p>
    <p><br><br></p>
    
   
    

      </body>
		

 	<?php include('inc/Footer.php') ?>