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
$doctorList = '';
$query = "SELECT * FROM doctors WHERE is_deleted=0 ORDER BY speciality";
$doctors = mysqli_query($connection, $query);
verifyQuery($doctors);

while ($doctor = mysqli_fetch_assoc($doctors)) {
    $doctorObject = $doctor['object'];
    $doctorObj = base64_encode($doctorObject);
    $doctorObject = unserialize($doctorObject);
    $first_name = $doctorObject->getFirstName();
    $last_name = $doctorObject->getLastName();
    $Hospital = $doctorObject->getHospital();
    $doctorId = $doctor['id'];
    $activeTimeList = $doctorObject->getAvailableTime();
    $activeTime = '';
    foreach ($activeTimeList as $day => $time) {
        $activeTime.=$day . ' &nbsp ' . 'From ' . $time['startTime'] . 'To ' . $time['endTime'] . '<br>';
    }
    $doctorList.="<tr>";
    $doctorList.="<td>{$first_name}</td>";
    $doctorList.="<td>{$last_name}</td>";
    $doctorList.="<td>{$Hospital}</td>";
    $doctorList.="<td>{$activeTime}</td>";
    $doctorList.="<td>{$doctor['speciality']}</td>";
    $doctorList.="<td>{$doctor['id']}</td>";

    $doctorList.="<td><button type=\"button\" class=\"editbtn\" name=\"edit\" style=\"width:150px\" onclick=\"location = 'editActiveTimes.php?doctorId={$doctorId}&&doctorObj={$doctorObj}'\">Edit active time<//button></td>";

    $doctorList.="<td><button type=\"button\" class=\"removebtn\" name=\"remove\" onClick=\"if(confirm('Are you sure you want to delete?')){location.href='removeDoctor.php?doctorId={$doctorId}'}\">Remove</button></td></tr>";
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>manageDoctors</title>

        <?php include('inc/adminHeader.php') ?>
    <div class='topnav'>
        <a href="admin.php">Home</a>
        <a href="registerAdmin.php">Register a New Admin</a>
        <a class="active" href="manageDoctor.php">Manage Doctors</a>
        <a href="managepharmacyStaff.php">Manage Pharmacy Staff</a>
        <a href="adminMessageType.php" class="badge badge-dark"><span>Messages&nbsp;[<?php echo $unread ?>]</span></a>

    </div>

    <main>
        <table >             
            <tr><th><h1>Doctors</h1><th><td><div class="btnlinks" style="float: right;"> <a href="registerDoctor.php">Add new Doctor</a></div></td><br>
        </table>
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Hospital</th>
                <th>Active Time</th>
                <th>specialty</th>
                <th>Doctor Id</th>
                <th>Edit Active Time&emsp;&emsp;</th>
                <th>Remove Doctor</th>
                <?php echo $doctorList ?>

            </tr>

        </table>

        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br></p>



    </main>

    <?php include('inc/adminFooter.php') ?>