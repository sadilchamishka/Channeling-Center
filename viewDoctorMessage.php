<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
  if(!isset($_SESSION['doctorid'])){
    header('Location:logout.php?msg=invalid_attempt');
  }
  if(!isset($_GET['messageDetails'])){
    header('Location:doctorMessage.php?msg=invalid_message');
  }
  
 ?>

  <?php 
  
  $doctorid = $_SESSION['doctorid'];
  $query = "SELECT * FROM doctors WHERE id='{$doctorid}' LIMIT 1";
  $result = mysqli_query($connection,$query);
  verifyQuery($result);
  $doctor = mysqli_fetch_assoc($result);
  $doctorObj = $doctor['object'];
  $doctorObj = unserialize($doctorObj);
  $name = $doctorObj->getFullName();
  ?>
  
 


 <?php 
 
  $messageDetails = $_GET['messageDetails'];
  $messageDetails = unserialize(base64_decode($messageDetails));
  $adminId = $messageDetails['senderId'];
  $messageToDisplay = '';
  $messageToDisplay.= "Name: ".$messageDetails['senderName']."<br>";
  $messageToDisplay.="Message: ".$messageDetails['message']."<br>";
  $messageToDisplay.=" &nbsp <a href='messageDoctorToAdmin.php?adminId=$adminId'>Reply</a>";
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
  <?php include('inc/doctorHeader.php') ?>
  <?php 
      echo $messageToDisplay;
     
   ?>
    <a href='doctorInbox.php?msg=back'>Back To Inbox</a>
</main>

<?php include('inc/doctorFooter.php') ?>
