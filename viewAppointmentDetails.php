<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php
$_SESSION['doctorId'] = '';
$_SESSON['appObj'] = '';
$userid = $_SESSION['userid'];
if (!isset($_SESSION['userid'])) {
    header('Location:logout.php?msg=invalid_attempt');
}
?>
<?php
$userid = $_SESSION['userid'];
$query = "SELECT * FROM users WHERE id='{$userid}' LIMIT 1";
$result = mysqli_query($connection, $query);
verifyQuery($result);
$user = mysqli_fetch_assoc($result);
$userObj = $user['object'];
$userObj = unserialize($userObj);
$name = $userObj->getFullName();
?>

<?php
$paidAppointment = array();
$activeAppointment = array();
$closedAppointment = array();
$canceledAppointment = array();

$query = "SELECT * FROM appointment WHERE userId='{$userid}'";
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

$query = "SELECT * FROM appointment WHERE userId='{$userid}'";
$resultSet = mysqli_query($connection, $query);
verifyQuery($resultSet);
$time = time();
while ($appointmentDetails = mysqli_fetch_assoc($resultSet)) {
    $appointment = $appointmentDetails['appointmentObject'];
    $appointment = unserialize($appointment);
    switch ($appointment->getState()) {
        case 'active':
            $activeAppointment[] = $appointment;
            break;
        case 'closed':
            $closedAppointment[] = $appointment;
            break;
        case 'paid':
            $paidAppointment[] = $appointment;
            break;
        case 'canceled':
            $canceledAppointment[] = $appointment;
            break;
    }
}
?>

<?php
$details = '';
if (!empty($paidAppointment)) {
    $details.= "<ul>Paid Appointment<br>";
    foreach ($paidAppointment as $appointment) {
        $appointmentObj = serialize($appointment);
        $details.="<li><a href='viewAppointment.php?appointment=$appointmentObj'>" . $appointment->getDate() . "<a/></li>";
    }
    $details.="</ul>";
}if (!empty($activeAppointment)) {
    $details.= "<ul>active Appointment<br>";
    foreach ($activeAppointment as $appointment) {
        $appointmentObj = serialize($appointment);
        $details.="<li><a href='viewAppointment.php?appointment=$appointmentObj'>" . $appointment->getDate() . "<a/></li>";
    }
    $details.="</ul>";
}if (!empty($closedAppointment)) {
    $details.= "<ul>closed Appointment<br>";
    foreach ($closedAppointment as $appointment) {
        $appointmentObj = serialize($appointment);
        $details.="<li><a href='viewAppointment.php?appointment=$appointmentObj'>" . $appointment->getDate() . "<a/></li>";
    }
    $details.="</ul>";
}if (!empty($canceledAppointment)) {
    $details.= "<ul>Paid Appointment<br>";
    foreach ($canceledAppointment as $appointment) {
        $appointmentObj = serialize($appointment);
        $details.="<li><a href='viewAppointment.php?appointment=$appointmentObj'>" . $appointment->getDate() . "<a/></li>";
    }
    $details.="</ul>";
}
?>



<!DOCTYPE html>
<html>
    <head>
        <title>View Appointment Details</title>
        <?php include('inc/userHeader.php') ?>
    <div class="topnav">
        <a  href="user.php">Home</a>
        <a  href="appointment.php">Make An Appointment</a>
        <a  class="active" href="viewAppointmentDetails.php">View Appointment Details</a>
        <a  href="viewUserDetails.php">View User Details</a>
        <a  href="editUserDetails.php">Edit Details</a>
    </div>
    <main>
        <div>
            <?php
            if ($details === '') {
                echo "No Appointment Available";
            } else {
                echo $details;
            }
            ?>
        </div>
        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br></p>
    </main>
    <?php include('inc/userFooter.php') ?>