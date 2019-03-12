<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php
if (!isset($_SESSION['doctorid'])) {
    header('Location:logout.php?msg=invalid_attempt');
}
if (!isset($_GET['adminId'])) {
    header("Location: doctor.php?msg=invalid_attempt");
}
?>
<?php
$doctorid = $_SESSION['doctorid'];
$query = "SELECT * FROM doctors WHERE id='{$doctorid}' LIMIT 1";
$result = mysqli_query($connection, $query);
verifyQuery($result);
$doctor = mysqli_fetch_assoc($result);
$doctorObj = $doctor['object'];
$doctorObj = unserialize($doctorObj);
$name = $doctorObj->getFullName();
$adminId = $_GET['adminId'];
if ($adminId === 0) {
    $adminId = 99999;
}
?>

<?php
$errors = array();
$message;
$time = time();
if (isset($_POST['sendMessage'])) {
    if (!isset($_POST['message']) || strlen(trim($_POST['message'])) < 1) {
        $errors[] = 'Type  the message';
    }
    if (strlen($_POST['message']) > 250) {
        $errors[] = 'number of characters should be less than 250';
    }
    if (empty($errors)) {
        $message = mysqli_real_escape_string($connection, $_POST['message']);
        $query = "INSERT INTO messages(senderId,senderType,recieverId,recieverType,message,isRead,senderName,timeStamp) VALUES('{$doctorid}','doctor',$adminId,'admin','{$message}',0,'{$name}','{$time}')";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
        header('Location:doctorMessageType.php?msg=message_sent_successfully');
    }
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Send Message</title>
        <?php include('inc/doctorHeader.php') ?>
    <div class="topnav">

        <a  href="doctor.php">Search Appointment</a>
        <a  class="active" href="doctorMessageType.php">Messages <span class="badge badge-pile badge-light" ><?php echo $unread ?></span></a>

    </div>

    <h4>Message to Admin</h4><hr>
    <form for='' method='post' action="messageDoctorToAdmin.php?adminId=99999">
        <div class="formContainer">
            <textarea placeholder="type the message" name='message' col='10' row='15'></textarea>

            <div class="clearfix">
                <button style="width:200px; float: right;" type='submit' name='sendMessage'>Send</button>
                <button style="width:200px; float: left;" class="cancelbtn" type='button' name='back' onclick="location='doctorMessageType.php?msg=Back'">Back</button>
            </div> 
   
        </div>
    </form>
</body>
</html>

