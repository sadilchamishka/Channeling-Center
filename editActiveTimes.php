<?php session_start() ?>
<?php require_once('inc/functions.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/connection.php') ?>

<?php
if (!isset($_SESSION['adminid'])) {
    header('Location:index.php');
}
?>
<?php
if (!isset($_GET['doctorId']) || !isset($_GET['doctorObj'])) {
    header('editDoctorDetails.php?msg=No_Info_set');
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
$doctorObj = $_GET['doctorObj'];
$doctorObject = unserialize(base64_decode($doctorObj));
$doctorId = $_GET['doctorId'];
$activeTime = '';
?>

<?php
$errors = array();
$day = '';
$startTime ='';
$endTime = '';
if (isset($_POST['add'])) {
    $startTime = $_POST['startTime'];
$endTime = $_POST['endTime'];
$startTimeStamp = strtotime($startTime);
$endTimeStamp = strtotime($endTime);
    if ($_POST['startTime'] === '' || $_POST['endTime'] === '') {
        $errors[] = "incomplete Infomation";
        
    }
    if($startTimeStamp>$endTimeStamp){
        $errors[] = "invalid time";
    }
    
    if (empty($errors)) {

        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        switch ($_POST['day']) {

            case 'Monday':
                $day = "Mon";
                break;

            case 'Tuesday':
                $day = "Tue";
                break;
            case 'Wednesday':
                $day = "Wed";
                break;
            case 'Thursday':
                $day = "Thu";
                break;
            case 'Friday':
                $day = "Fri";
                break;
            case 'Saturday':
                $day = "Sat";
                break;
            case 'Sunday':
                $day = "Sun";
                break;
        }
        $doctorObject->setAvailableTime($day, $startTime, $endTime);
        $doctorObj = base64_encode(serialize($doctorObject));
        $doctor = unserialize(base64_decode($doctorObj));
        $doctor = serialize($doctor);
        $query = "UPDATE doctors SET object='{$doctor}' WHERE id='{$doctorId}'";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
    }
}
?>
<?php
$activeTimeList = $doctorObject->getAvailableTime();
$activeTime.="<table>";
foreach ($activeTimeList as $day1 => $time) {
    $activeTime.= "<tr><td>" . $day1 . " " . "&nbsp" . "From " . $time['startTime'] . "&nbsp To " . $time['endTime'] . "</td>" . "<td><button type=\"button\" class=\"removebtn\" name=\"remove\" onClick=\"if(confirm('Are you sure you want to delete?')){location.href='removeActiveTimes.php?doctorId={$doctorId}&&day={$day1}&&doctor={$doctorObj}'}\">Remove</button></td></tr>";
}
$activeTime.="</table>";
?>



<!DOCTYPE html>
<html>
    <head>
        <title>Edit Active Times</title>
        <?php include('inc/adminHeader.php') ?>
    <div class='topnav'>
        <a href="admin.php">Home</a>
        <a href="registerAdmin.php">Register a New Admin</a>
        <a class="active" href="manageDoctor.php">Manage Doctors</a></li>
    <a href="managepharmacyStaff.php">Manage Pharmacy Staff</a>
</div>
    <?php 
    if(!empty($errors)){
        printErrors($errors);
    }
    ?>

<h3>Edit Active Times</h3>
<div style="width: 50%; margin-left: auto; margin-right: auto;"><hr>
    <?php echo $activeTime; ?>
</div>
<br>
<h3>Add New Time Slot</h3>
<form action='editActiveTimes.php?doctorId=<?php echo $doctorId ?>&&doctorObj=<?php echo $doctorObj ?>' method='post' >
    <div class="formContainer" style="border-style: dotted; border-color: #cccccc">	<hr>
        <label><b> Day</b></label>
        <select name='day'>

            <?php
            $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
            foreach ($days as $day) {
                echo "<option value='{$day}'>{$day}</option>";
            }
            ?>
        </select>
        <br>
        <br><b>From</b>
        <input type="time" name="startTime">
        <b>To</b>
        <input type="time" name="endTime">
        <div class="clearfix">
            <button name='add' type='submit'>Add</button>
        </div>

    </div>
</form>

<div class="clearfix">
    <button name='add' class="cancelbtn" type='button' onclick="location = 'manageDoctor.php?doctorId=<?php echo $doctorId ?>'">Add</button>
</div>



</body>
</html>