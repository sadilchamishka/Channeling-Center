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
  
  $messageDetails = $_GET['messageDetails'];
  $messageDetails = unserialize(base64_decode($messageDetails));
  $messageToDisplay = '';
  $messageToDisplay.= "Name: ".$messageDetails['senderName']."<br>";
  $messageToDisplay.="Message: ".$messageDetails['message']."<br>";
  $messageId = $messageDetails['id'];

  //mark as read
  $query = "SELECT * FROM messages WHERE id='{$messageId}' AND isRead=0 LIMIT 1";
  $result = mysqli_query($connection,$query);
  verifyQuery($result);
  if(mysqli_num_rows($result)==1){
    $query = "UPDATE messages SET isRead=1 WHERE id='{$messageId}'";
    $result = mysqli_query($connection,$query);
    verifyQuery($result);
  }
  ?>

 
    
    


  



  <!DOCTYPE html> 
 <html>
 <head>
  <title>Admin</title>
  <?php include('inc/userHeader.php') ?>
  <?php 
      echo $messageToDisplay;
     
   ?>
    <a href='userInbox.php?msg=back'>Back To Inbox</a>
</main>

<?php include('inc/userFooter.php') ?>
