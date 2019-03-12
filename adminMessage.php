<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
	if(!isset($_SESSION['adminid'])){
		header('Location:index.php');
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
  ?>

  <?php
  	//load messages
  	$query = "SELECT * FROM messages WHERE recieverType='admin' AND (recieverId='{$adminid}' OR recieverId=99999)";
  	$resultSet = mysqli_query($connection,$query);
  	verifyQuery($resultSet);
    $read = array();
    $unread = array();
  	while($message = mysqli_fetch_assoc($resultSet)){
        if($message['isRead']==0){
          $unread[] = $message;
        }else{
          $read[] = $message;
        }
    }
    
    


  ?>



  <!DOCTYPE html> 
 <html>
 <head>
  <title>Admin</title>
  <?php include('inc/adminHeader.php') ?>
<?php  
    foreach ($unread as $messageDetails) {
        $messageDetail = base64_encode(serialize($messageDetails));
        $showMessage = mb_substr($messageDetails['message'], 0,20);
        echo "<a href='viewAdminMessage.php?messageDetails=$messageDetail'>".$messageDetails['senderName']." : ".$showMessage."</a><br />";
    }foreach ($read as $messageDetails) {
        $messageDetail = base64_encode(serialize($messageDetails));
        $showMessage = mb_substr($messageDetails['message'], 0,20);
        echo "<a href='viewAdminMessage.php?messageDetails=$messageDetail'>".$messageDetails['senderName']." : ".$showMessage."</a><br />";
    }
?>
</main>

<?php include('inc/adminFooter.php') ?>
