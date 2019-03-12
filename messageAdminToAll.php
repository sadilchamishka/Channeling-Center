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
    $errors = array();
    $message;
    if(isset($_POST['sendMessage'])){
      if(!isset($_POST['message']) || strlen(trim($_POST['message']))<1){
        $errors[] = 'Type  the message';
      }
      if(strlen($_POST['message'])>250){
        $errors[] = 'number of characters should be less than 250';
      }
     
      if(empty($errors)){
        $message = mysqli_real_escape_string($connection,$_POST['message']);
        $query = "INSERT INTO messages(senderId,senderType,recieverId,recieverType,message,isRead,senderName) VALUES('{$adminid}','admin',99999,'all','{$message}',0,'{$name}')";
        $result = mysqli_query($connection,$query);
        verifyQuery($result);
        header('Location:admin.php?msg=message_sent_successfully');
      }
    }
   ?>


 <!DOCTYPE html>
 <html>
 <head>
  <title>Send Message</title>
  <?php include('inc/adminHeader.php') ?>
 
 
    <form for='' method='post'>
      <select name='selectReciever'>
          <option value='selectReciever'>Select Reviever</option>
          <?php echo $option ?>
      </select>
      <textarea placeholder="type the message" name='message' col='10' row='15'></textarea>
      <button type='submit' name='sendMessage'>Send</button>
    </form>
  </main>
 
<?php include('inc/adminFooter.php') ?>
 