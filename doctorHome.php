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

<html>
    <head>
        <title>Doctor</title>
        <?php include('inc/doctorHeader.php') ?>
    <div class="topnav">
        <a  class="active" href="doctorHome.php">Home</a>
        <a   href="doctor.php">Search Appointment</a>
        <a  href="editDoctorDetails.php">Edit details</a>
        <a href="doctorMessageType.php">Messages <span class="badge badge-pile badge-light" ><?php echo $unread ?></span></a>

    </div>
    <main>
      
       
    </main>

    <?php include('inc/doctorFooter.php') ?>