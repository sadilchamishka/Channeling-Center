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
//load messages
$query = "SELECT * FROM messages WHERE recieverType='admin' AND (recieverId='{$adminid}' OR recieverId=99999)";
$resultSet = mysqli_query($connection, $query);
verifyQuery($resultSet);
$read = array();
$unread1 = array();
while ($message = mysqli_fetch_assoc($resultSet)) {
    if ($message['isRead'] == 0) {
        $unread1[] = $message;
    } else {
        $read[] = $message;
    }
}
?>



<!DOCTYPE html> 
<html>
    <head>
        <title>Admin</title>
        <?php include('inc/adminHeader.php') ?>
    <div class='topnav'>
        <a href="admin.php">Home</a>
        <a href="registerAdmin.php">Register a New Admin</a>
        <a href="manageDoctor.php">Manage Doctors</a>
        <a href="managepharmacyStaff.php">Manage Pharmacy Staff</a>
        <a class="active" href="adminMessageType.php" class="badge badge-dark" ><span>Messages&nbsp;[<?php echo $unread ?>]</span></a>
    </div>
    <h4>Inbox</h4><hr>
    <div class="formContainer">
        <?php
        foreach ($unread1 as $messageDetails) {
            $messageDetail = base64_encode(serialize($messageDetails));
            $showMessage = mb_substr($messageDetails['message'], 0, 20);
            echo "<div class=\"msglinks\" ><b><a style=\"background-color: #999999\" href='viewAdminMessage.php?messageDetails=$messageDetail'><div style=\"font-size: 17px\">".$messageDetails['senderName'] . "  :  </div>" . $showMessage . "</a></b></div><br><br><hr>";
        }foreach ($read as $messageDetails) {
            $messageDetail = base64_encode(serialize($messageDetails));
            $showMessage = mb_substr($messageDetails['message'], 0, 20);
            echo "<div class=\"msglinks\" ><a href='viewAdminMessage.php?messageDetails=$messageDetail'><div style=\"font-size: 17px\">" . $messageDetails['senderName'] . "  :</div>" . $showMessage . "</a></div><br><br><hr>";
        }
        ?>
    </div>

<!--    <span class="dot" style="background-color:#0099cc"></span>-->
    <div class="btnlinks">
        &emsp;&emsp;<a href="adminMessageType.php?msg=Back">Back To Messages</a>
        <p><br><br></p>
        <p><br><br></p>
    </div>
</main>

<?php include('inc/adminFooter.php') ?>
