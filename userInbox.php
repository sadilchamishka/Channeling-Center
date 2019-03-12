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
  	//load messages
  	$query = "SELECT * FROM messages WHERE recieverType='user' AND (recieverId='{$userid}' OR recieverId=99999)";
  	$resultSet = mysqli_query($connection,$query);
  	verifyQuery($resultSet);
    $read = array();
    $unreadArray = array();
  	while($message = mysqli_fetch_assoc($resultSet)){
        if($message['isRead']==0){
          $unreadArray[] = $message;
        }else{
          $read[] = $message;
        }
    }
    
    


  ?>



  <!DOCTYPE html> 
 <html>
 <head>
  <title>User Inbox</title>
  <?php include('inc/userHeader.php') ?>
<?php  
    foreach ($unreadArray as $messageDetails) {
        $messageDetail = base64_encode(serialize($messageDetails));
        $showMessage = mb_substr($messageDetails['message'], 0,20);
        echo "<a href='viewUserMessage.php?messageDetails=$messageDetail'>".$messageDetails['senderName']." : ".$showMessage."</a><br />";
    }foreach ($read as $messageDetails) {
        $messageDetail = base64_encode(serialize($messageDetails));
        $showMessage = mb_substr($messageDetails['message'], 0,20);
        echo "<a href='viewUserMessage.php?messageDetails=$messageDetail'>".$messageDetails['senderName']." : ".$showMessage."</a><br />";
    }

?>
    <a href="user.php?msg=Back">Back</a>
</main>

<?php include('inc/userFooter.php') ?>
