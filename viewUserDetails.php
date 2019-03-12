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

<!DOCTYPE html>
<html>
    <head>
        <title>View Details</title>
        <?php include('inc/userHeader.php') ?>
      
    <div class="topnav">
        <a  href="user.php">Home</a>
        <a  href="appointment.php">Make An Appointment</a>
        <a  href="viewAppointmentDetails.php">View Appointment Details</a>
        <a class="active" href="viewUserDetails.php">View User Details</a>
        <a  href="editUserDetails.php">Edit Details</a>
    </div>
    <main>
        <div class="formContainer">
            <table>
                <tr><td><label><b>Name: </b></label><?php echo $name ?></p></td></tr>
                <tr><td><p><label><b>Age: </b></label><?php echo $userObj->getAge() ?></p></td></tr>
                <tr><td><p><label><b>Gender:</b></label><?php echo $userObj->getGender() ?></p></td></tr>
                <tr><td><p><label><b>DOB: </b></label><?php echo $userObj->getDob() ?></p></td></tr>
                <tr><td><p><label><b>Address:  </b></label><?php echo $userObj->getStreatAddress1() . " " . $userObj->getStreatAddress2() . " " . $userObj->getCity() . " " . $userObj->getProvince() ?></p></td></tr>

                <tr><td><p><div class="btnlinks" style="float: left"><a href="viewAllegies.php">View Allegies</a></div>
                <div class="btnlinks" style="float: right"><a href="viewRecords.php?">View Records</a></div></p></td></tr>               
                <tr><td><p><label></label></p></td></tr>
            </table>
        </div>
    </main>
    <?php include('inc/userFooter.php') ?>