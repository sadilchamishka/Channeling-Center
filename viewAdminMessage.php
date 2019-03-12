<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
	if(!isset($_SESSION['adminid'])){
		header('Location:index.php');
	}
  if(!isset($_GET['messageDetails'])){
    header('Location:adminMessage.php?msg=invalid_message');
  }
 ?>


 <?php 
 	$adminid = $_SESSION['adminid'];
 	$query = "SELECT * FROM admins WHERE id='{$adminid}' LIMIT 1";
 	$result = mysqli_query($connection,$query);
 	verifyQuery($result);
 	$admin = mysqli_fetch_assoc($result);
 	$adminObj = $admin['object'];
 	$adminObj = unserialize($adminObj);
 	$name = $adminObj->getFullName();
  $messageDetails = $_GET['messageDetails'];
  $messageDetails = unserialize(base64_decode($messageDetails));
  $doctorId = $messageDetails['senderId'];
  $messageToDisplay = '';
  $messageToDisplay.= "Name: ".$messageDetails['senderName']."<br>";
  $messageToDisplay.="Message: ".$messageDetails['message']."<br>";
  $messageToDisplay.=" &nbsp <a href='replyAdminToDoctor.php?doctorId=$doctorId'>Reply</a>";
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
  <?php include('inc/adminHeader.php') ?>
  <?php 
      echo $messageToDisplay;
     
   ?>
    <a href='adminInbox.php?msg=back'>Back To Inbox</a>
</main>

<?php include('inc/adminFooter.php') ?>
