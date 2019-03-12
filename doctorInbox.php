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
$query = "SELECT * FROM messages WHERE recieverType='doctor' AND (recieverId='{$doctorid}' OR recieverId=99999)";
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
        <title>Doctor Inbox</title>
        <?php include('inc/doctorHeader.php') ?>
    <div class="topnav">

        <a  href="doctor.php">Search Appointment</a>
        <a  class="active" href="doctorMessageType.php">Messages <span class="badge badge-pile badge-light" ><?php echo $unread ?></span></a>

    </div>
    <h4>Inbox</h4><hr>
    <div class="formContainer">
        <?php
        foreach ($unread1 as $messageDetails) {
            $messageDetail = base64_encode(serialize($messageDetails));
            $showMessage = mb_substr($messageDetails['message'], 0, 20);
            echo "<a href='viewDoctorMessage.php?messageDetails=$messageDetail'>" . $messageDetails['senderName'] . " : " . $showMessage . "</a><br><hr>";
        }foreach ($read as $messageDetails) {
            $messageDetail = base64_encode(serialize($messageDetails));
            $showMessage = mb_substr($messageDetails['message'], 0, 20);
            echo "<a href='viewDoctorMessage.php?messageDetails=$messageDetail'>" . $messageDetails['senderName'] . " : " . $showMessage . "</a><br><hr>";
        }
        ?>
    </div>
    <br>
    <div class="btnlinks">
        &emsp;&emsp;<a href="doctorMessageType.php?msg=Back">Back To Messages</a>
        <p><br><br></p>
        <p><br><br></p>
    </div>
</main>

<?php include('inc/doctorFooter.php') ?>
