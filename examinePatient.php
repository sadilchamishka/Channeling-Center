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
$appObject = $_GET['appObj'];
$appObj = unserialize(base64_decode($appObject));
$appointmentNumber = $appObj->getAppointmentNumber();
$user = $appObj->getUser();
$userId = $user->getUserId();
$userObject = base64_encode(serialize($user));
?>

<?php
$reportDetails = '';
if (isset($_SESSION['report'])) {
    $reportDetails = $_SESSION['report'];
}
$medicineList;
if (isset($_SESSION['medicineList'])) {
    $medicineList = $_SESSION['medicineList'];
}

$errors;
$selectedMedicine = '';
$numberOfDays = 0;
?>

<?php
if (isset($_POST['add'])) {
    $reportDetails = mysqli_real_escape_string($connection, $_POST['report']);
    $_SESSION['report'] = $reportDetails;
    $errors = array();
    $medicineName = mysqli_real_escape_string($connection, $_POST['medicineName']);
    $medicineQuentity = mysqli_real_escape_string($connection, $_POST['medicineQuentity']);
    $numberOfDays = mysqli_real_escape_string($connection, $_POST['numberOfDays']);
    $timeForDay = 0;


    $usingTime = '';
    $usingTime2 = '';
    switch ($_POST['usingTime']) {
        case 'timeSelect':
            $errors[] = 'select using time';
        case '3-1':
            $usingTime = 'Three Times a Day';
            $timeForDay = 3;
            $usingTime2 = '3-1';
            break;
        case '2-1':
            $usingTime = 'Morning And Night';
            $timeForDay = 2;
            $usingTime2 = '2-1';

            break;
        case '1-1':
            $usingTime = 'Night only';
            $timeForDay = 1;
            $usingTime2 = '1-1';

            break;
    }
    if (!isset($_POST['medicineQuentity']) || strlen(trim($_POST['medicineQuentity'])) < 1) {
        $errors[] = "enter the quentity to use";
    }if (!isset($_POST['medicineName']) || strlen(trim($_POST['medicineName'])) < 1) {
        $errors[] = "enter the name of the medicine";
    }if (!isset($_POST['numberOfDays']) || strlen(trim($_POST['numberOfDays'])) < 1) {
        $errors[] = "enter the number of days";
    }

    if ($_POST['medicineQuentity'] < 1) {
        $errors[] = "Medicine quantity should be a positive value";
    }
    if ($_POST['numberOfDays'] < 1) {
        $errors[] = "Number of days should be a positive value";
    }
    if (empty($errors)) {
        $medicineList[$medicineName] = array('medicineQuentity' => $medicineQuentity, 'usingTime' => $usingTime2, 'numberOfDays' => $numberOfDays, 'userId' => $userId);
        $_SESSION['medicineList'] = $medicineList;
    }
}
if (isset($_SESSION['medicineList'])) {
    foreach ($_SESSION['medicineList'] as $medicineName => $details) {
        $selectedMedicine.=$medicineName . " - " . $details['medicineQuentity'] . ' pills ' . $details['usingTime'] . ' for ' . $details['numberOfDays'] . " days &nbsp <a href='removeSelectedMedicine.php?medicineName=$medicineName&&appObj=$appObject' onclick= \"return confirm('Are You Sure?');\">Remove</a><br>";
    }
}

if (isset($_POST['save'])) {
    $savingErrors = array();
    if (!isset($_POST['report']) || strlen(trim($_POST['report'])) < 1) {
        $savingErrors[] = 'submit the report';
    }if (empty($savingErrors)) {
        $date = date('y-m-d');
        $time = date('h:i:sa');
        $reportDetails = mysqli_real_escape_string($connection, $_POST['report']);
        $report = new report($date, $time, $reportDetails, $medicineList);
        $user->addNewReport($report);
        $userObject = serialize($user);
        $appObj->setUser($user);
        $appObj->setAppointmentState(new medicineIssuingAppointment());
        $appObject = serialize($appObj);

        //save user object
        $query = "UPDATE users SET object='{$userObject}' WHERE id='{$userId}' ";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);

        //save appointmnet object

        $query = "UPDATE appointment SET appointmentObject='{$appObject}',is_used=1 WHERE appointmentNumber='{$appointmentNumber}'";

        $result = mysqli_query($connection, $query);
        verifyQuery($result);
        $time = time();
        $messageList = array();
        $messageList['appointmentNumber'] = $appointmentNumber;
        $messageList['medicineList'] = $_SESSION['medicineList'];
        $messageList = base64_encode(serialize($messageList));
        $query = "INSERT INTO messages(senderId,senderType,recieverId,recieverType,message,isRead,senderName,timeStamp) VALUES('{$doctorid}','doctor',99999,'pharmacyStaff','{$messageList}',0,'{$name}','{$time}')";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
        if (isset($_SESSION['medicineList'])) {
            unset($_SESSION['medicineList']);
        }if (isset($_SESSION['report'])) {
            unset($_SESSION['report']);
        }

        header('Location:doctor.php');
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Examine Patient</title>
        <?php include('inc/doctorHeader.php') ?>
    <div class="topnav">

        <a  class="active" href="appointment.php">Search Appointment</a>

    </div>

    <main>

        <form action="examinePatient.php?appObj=<?php echo $appObject ?>" method='post'>
            <div class="formContainer">
                <div class="btnlinks">
                    <a href="showPatientDetails.php?appObj=<?php echo $appObject ?>">Show Patient Details</a>
                </div><br>
                <?php
                if (!empty($savingErrors)) {
                    printErrors($savingErrors);
                }
                ?>
                <?php
                if (!empty($errors) && isset($errors)) {
                    printErrors($errors);
                }
                ?>
                <p>Please fill following information </p><hr>
                <label><b>Report</b></label><br>
                <p><textarea name='report' cols="60" rows="15"><?php echo $reportDetails ?></textarea></p>
                <?php echo $selectedMedicine ?>

                <div id="medicineCheckContainer">
                    <div class="formContainer">
                        <br><label><b>Select Medicine</b></label><br>
                        <p><input type="text" name="medicineName" placeholder="medicine type">
                            <input type="number" name="medicineQuentity" placeholder="quentity">
                            <input type="number" name="numberOfDays" placeholder="number of days">
                            <select name='usingTime' >
                                <option value='timeSelect'>Select Using Time</option>
                                <option value='3-1'>Three Times a Day</option>
                                <option value='2-1'>Morning and Night</option>
                                <option value='1-1'>One at Night</option>
                            </select>
                        <div class="clearfix" style="width: 30%">
                            <button type='submit' name='add'>Check</button>
                        </div>
                        </p></div>
                </div>
                <div class="clearfix">

                    <button type='submit' name='save'>Save Details</button>
                </div>
            </div>
        </form>	



    </div>
</main>

<?php include('inc/doctorFooter.php') ?>