<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php
if (!isset($_SESSION['doctorid'])) {
    header('Location:logout.php?msg=invalid_attempt');
}
if (!isset($_GET['appObj']) || !isset($_GET['msg'])) {
    header("Location:doctor.php?msg=invalidAttempt");
}
$message = '';
if ($_GET['msg'] === 'appointment_expired') {
    $message.="The Appointment Has Been Expired.";
}if ($_GET['msg'] === 'appointment_not_active') {
    $message.="The Appointment is Not Active Yet.";
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
if (isset($_POST['cancel'])) {
    header("Location:doctor.php?msg={$_GET['msg']}");
}if (isset($_POST['Continue'])) {
    header("Location:examinePatient.php?appObj={$_GET['appObj']}");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Doctor</title>
        <?php include('inc/doctorHeader.php') ?>
    <div class="topnav">

        <a  class="active" href="appointment.php">Search Appointment</a>

    </div>
    <div>
        <br>
        <div class="info">
            <?php echo $message ?>;
        </div>
        <form method='post'>
            <div class="formContainer">
                <div class="clearfix">
                    <div style="width: 600px; float: left">
                        <button class="cancelbtn" name='cancel' type='submit'>Cancel</button >
                    </div>
                    <div style="width: 300px; float: right">
                        <button name='Continue' type='submit'>Continue</button>
                    </div>
                </div>
            </div>
        </form>
        <p><br><br></p>
		<p><br><br></p>
		<p><br><br></p>
    </div>

</main>

<?php include('inc/doctorFooter.php') ?>
