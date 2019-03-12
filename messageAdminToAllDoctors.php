<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php
if (!isset($_SESSION['adminid'])) {
    header('Location:index.php');
}
?>

<?php
$adminid = $_SESSION['adminid'];
$query = "SELECT * FROM admins WHERE id='{$adminid}' LIMIT 1";
$result = mysqli_query($connection, $query);
verifyQuery($result);
$admin = mysqli_fetch_assoc($result);
$adminObj = $admin['object'];
$adminObj = unserialize($adminObj);
$name = $adminObj->getFullName();
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
        $query = "INSERT INTO messages(senderId,senderType,recieverId,recieverType,message,isRead,senderName,timeStamp) VALUES('{$adminid}','admin',99999,'doctor','{$message}',0,'{$name}','{$time}')";
        $result = mysqli_query($connection, $query);
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
    <div class='topnav'>
        <a href="admin.php">Home</a>
        <a href="registerAdmin.php">Register a New Admin</a>
        <a href="manageDoctor.php">Manage Doctors</a>
        <a href="managepharmacyStaff.php">Manage Pharmacy Staff</a>
        <a class="active" href="adminMessageType.php" class="badge badge-dark"><span>Messages&nbsp;[<?php echo $unread ?>]</span></a>
    </div>
 <h4>Message to all doctors</h4><hr>
    <form for='' method='post'>
        <div class="formContainer">
            <textarea placeholder="type the message" name='message' col='10' row='15'></textarea>

            <div class="clearfix">
                <button style="width:200px; float: right;" type='submit' name='sendMessage'>Send</button>
                <button style="width:200px; float: left;" class="cancelbtn" type='button' name='sendMessage' onclick="location = 'adminCompose.php?msg=Back'">Back</button>
            </div>        </div>
    </form>
</main>

<?php include('inc/adminFooter.php') ?>
 