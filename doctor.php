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
$query = "SELECT * FROM messages WHERE recieverType='doctor' AND (recieverId='{$doctorid}' OR recieverId=99999)";
$resultSet = mysqli_query($connection, $query);
verifyQuery($resultSet);
while ($message = mysqli_fetch_assoc($resultSet)) {
    $id = $message['id'];
    if ($message['timeStamp'] + 60 * 60 * 24 * 30 < time()) {
        $query = "DELETE FROM messages WHERE id='{$id}'";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
    }
}
?>

<?php
$patient;
$appObj;
$set = 0;
$time = time();
if (isset($_POST['search'])) {
    $query = "SELECT * FROM appointment WHERE doctorId='{$doctorid}'";
    $resultSet = mysqli_query($connection, $query);
    verifyQuery($resultSet);
    $time = time();
    while ($appointmentDetails = mysqli_fetch_assoc($resultSet)) {
        if ($time > $appointmentDetails['timeStampStart']) {
            $id = $appointmentDetails['appointmentNumber'];
            $appointment = $appointmentDetails['appointmentObject'];
            $appointment = unserialize($appointment);
            $appointment->active();
            $appointment = serialize($appointment);
            $query = "UPDATE appointment SET appointment='{$appointment}' WHERE appintmentNumber = '{$id}' ";
            $result = mysqli_query($connection, $query);
            verifyQuery($result);
        }if ($time > $appointmentDetails['timeStampEnd']) {
            $id = $appointmentDetails['appointmentNumber'];
            $appointment = $appointmentDetails['appointmentObject'];
            $appointment = unserialize($appointment);
            $appointment->canceled();
            $appointment = serialize($appointment);
            $query = "UPDATE appointment SET appointment='{$appointment}' is_expire=1 WHERE appintmentNumber = '{$id}' ";
            $result = mysqli_query($connection, $query);
            verifyQuery($result);
        }
        if ($appointmentDetails['timeStampEnd'] + 60 * 60 * 24 * 30 < $time) {
            $id = $appointmentDetails['appointmentNumber'];

            $query = "DELETE FROM appointment WHERE appointmentNumber='{$id}'";
            $result = mysqli_query($connection, $query);
            verifyQuery($result);
        }
    }
    $appNum = $_POST['appNum'];
    $query = "SELECT * FROM appointment WHERE appointmentNumber='{$appNum}' AND doctorId='{$doctorid}' AND is_used=0 LIMIT 1";
    $result = mysqli_query($connection, $query);
    verifyQuery($result);
    if (mysqli_num_rows($result) == 1) {

        $appointment = mysqli_fetch_assoc($result);
        $appObj = $appointment['appointmentObject'];
        $appObject = base64_encode($appObj);
        $appObj = unserialize($appObj);
        $patient = $appObj->getUser();
        if ($appointment['is_expire'] == 1) {
            header("Location:appointmentContinueConfirm.php?appObj={$appObject}&&msg=appointment_expired");
        }if ($appointment['timeStampStart'] > $time) {
            header("Location:appointmentContinueConfirm.php?appObj={$appObject}&&msg=appointment_not_active");
        }
        $set = 1;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Doctor</title>
        <?php include('inc/doctorHeader.php') ?>
    <div class="topnav">
        <a  href="doctorHome.php">Home</a>
        <a  class="active" href="doctor.php">Search Appointment</a>
        <a  href="editDoctorDetails.php">Edit details</a>
        <a href="doctorMessageType.php">Messages <span class="badge badge-pile badge-light" ><?php echo $unread ?></span></a>

    </div>
    <main>
        <div>
            <form action='doctor.php' method='post'>
                <div class="formContainer">
                    <p>Please fill following data to search appointment</p><hr>
                    <p><label><b>Appointment Number</b></label>
                        <input type="text" name="appNum">
                    </p>
                    <div class="clearfix">
                        <p><button name='search'>Search</button></p>
                    </div>

                </div>
            </form>	

            <?php
            if (isset($_POST['search'])) {
                if ($set == 1) {
                    $appObject = base64_encode(serialize($appObj));
                    echo "<a href='examinePatient.php?appObj={$appObject}'>{$patient->getFullName()}</a>";
                } else {
                    echo"<div class=\"error\">Appointment not valid</div>";
                }
            }
            ?>

        </div>
    </main>

    <?php include('inc/doctorFooter.php') ?>