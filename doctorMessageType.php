<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php
if (!isset($_SESSION['doctorid'])) {
    header('Location:logout.php?msg=invalid_attempt');
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
?>

<?php
//load messages
$unreadMessagsCount = 0;
$query = "SELECT * FROM messages WHERE recieverType='doctor' AND (recieverId='{$doctorid}' OR recieverId=99999)";
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
        <?php include('inc/doctorHeader.php') ?>
    <div class="topnav">
        <a   href="doctorHome.php">Home</a>
        <a   href="doctor.php">Search Appointment</a>
        <a   href="editDoctorDetails.php">Edit details</a>
        <a class="active" href="doctorMessageType.php">Messages <span class="badge badge-pile badge-light" ><?php echo $unread ?></span></a>

    </div>
    <main>
        <div class="formContainer">
            <div class="row">
                <div class="column">
                    <img class="mailbox" src="images/inbox.png" style="width:100%" onclick="location = 'doctorInbox.php'">
                    <span class="tooltiptext">Inbox</span>
                </div>
                <div class="column">
                    <img class="mailbox" src="images/compose.png" style="width:100%" onclick="location = 'messageDoctorToAdmin.php?adminId=99999'">
                    <span class="tooltiptext">Compose message to admin</span>
                </div>
                <div class="column">
                    <img class="mailbox" src="images/outbox.png" style="width:100%" onclick="location = '#'">
                    <span class="tooltiptext">Sent Items</span>
                </div>
            </div>
        </div>
        <br><br>
        
    </main>
    <?php include('inc/doctorFooter.php') ?>