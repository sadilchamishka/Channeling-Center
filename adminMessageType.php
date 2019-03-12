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
$unreadMessagsCount = 0;
$query = "SELECT * FROM messages WHERE recieverType='admin' AND (recieverId='{$adminid}' OR recieverId=99999)";
$resultSet = mysqli_query($connection, $query);
verifyQuery($resultSet);
$read = array();
$unread = array();
while ($message = mysqli_fetch_assoc($resultSet)) {
    if ($message['isRead'] == 0) {
        $unread[] = $message;
        $unreadMessagsCount++;
    } else {
        $read[] = $message;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Message Box</title>
<?php include('inc/adminHeader.php') ?>
    <div class='topnav'>
        <a href="admin.php">Home</a>
        <a href="registerAdmin.php">Register a New Admin</a>
        <a href="manageDoctor.php">Manage Doctors</a>
        <a href="managepharmacyStaff.php">Manage Pharmacy Staff</a>
        <a class="active" href="adminMessageType.php" class="badge badge-dark"><span>Messages&nbsp;[<?php echo $unread ?>]</span></a>
    </div>

    <main>
        <div class="formContainer">
            <div class="row">
                <div class="column">
                    <img class="mailbox" src="images/inbox.png" style="width:100%" onclick="location = 'adminInbox.php'">
                    <span class="tooltiptext">Inbox</span>
                </div>
                <div class="column">
                    <img class="mailbox" src="images/compose.png" style="width:100%" onclick="location = 'adminCompose.php?adminId=0'">
                    <span class="tooltiptext">Compose</span>
                </div>
                <div class="column">
                    <img class="mailbox" src="images/outbox.png" style="width:100%" onclick="location = '#.php'">
                    <span class="tooltiptext">Sent Items</span>
                </div>
            </div>
        </div>
        <br><br>

    </main>


</main>
<?php include('inc/adminFooter.php') ?>