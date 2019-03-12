<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php
$List = array();
$doctorList = array();
$_SESSION['doctorID'] = '';
$_SESSION['appObj'] = '';

if (!isset($_SESSION['userid'])) {
    header('Location:index.php');
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

function displayDetails($doctor, $day, $select, $date) {

    $available = '';
    $availableTime = $doctor->getAvailableTime();
    $doctorId = $doctor->getId();
    $count = 0;

    if ($day === '') {

        foreach ($availableTime as $d => $time) {
            $availableDate = '';
            $name = $select . $count;
            $count++;
            $timeStamp = time();
            while (1) {
                if (date('D', $timeStamp) === $d) {
                    $availableDate = date('y-m-d', $timeStamp);

                    break;
                }
                $timeStamp+=60 * 60 * 24;
            }

            $available .= $availableDate . " - " . $d . ' - From ' . $time['startTime'] . 'To' . $time['endTime'] . "<form action='appointment.php' method='post'><div class=\"formContainer\">
		 		<div class=\"clearfix\"><button type='submit' name={$name} >select</button</div></div></form>" . "&nbsp";
        }
        if ($available === '') {
            $name = $select . $count;
            $available .= "<form action='appointment.php' method='post'><div class=\"formContainer\">
		 		<div class=\"clearfix\"><button type='submit' name={$name}>select</button></div></div></form>" . "&nbsp";
        }
    } else {
        $name = $select . $count;
        $available = $date . " - " . $day . ' - From ' . $availableTime[$day]['startTime'] . ' To ' . $availableTime[$day]['endTime'] . "<form action='appointment.php' method='post'><div class=\"formContainer\">
		 	<div class=\"clearfix\"><button type='submit' name={$name} >select</button></div></div></form>" . "&nbsp";
    }
    $a = "<div id=\"medicineCheckContainer\">
 		<h3>{$doctor->getFullName()}</h3>
 		<p>Working Hospital:{$doctor->getHospital()}</p>
 		<p>Available Time: {$available}</p>
 		<p></p>
 		</div>";

    return $a;
}
?>

<?php

function getDoctorList($doctor, $day, $select, $date) {
    $List = array();
    $availableTime = $doctor->getAvailableTime();
    $doctorId = $doctor->getId();
    $count = 0;
    if ($day === '') {

        foreach ($availableTime as $d => $time) {
            $availableDate = '';
            $name = $select . $count;
            $count++;
            $timeStamp = time();
            while (1) {
                if (date('D', $timeStamp) === $d) {
                    $availableDate = date('y-m-d', $timeStamp);
                    break;
                }
                $timeStamp += 60 * 60 * 24;
            }
            $details = array('day' => $d, 'date' => $availableDate, 'startTime' => $time['startTime'], 'endTime' => $time['endTime'], 'DId' => $doctorId);

            $List[$name] = $details;
        }
    } else {
        $name = $select . $count;
        $count++;
        if (array_key_exists($day, $availableTime)) {
            $details = array('day' => $day, 'date' => $date, 'startTime' => $availableTime[$day]['startTime'], 'endTime' => $availableTime[$day]['endTime'], 'DId' => $doctorId);

            $List[$name] = $details;
        }
    }
    return $List;
}
?>




<?php
$query = '';
$doctor_list = '';
if (isset($_POST['search'])) {
    $date = $_POST['date'];
    $timeStamp = strtotime($date);
    $day = date('D', $timeStamp);

    if (!checkIsSet('speciality')) {
        $query = "SELECT * FROM doctors WHERE is_deleted=0";
    } else {
        $query = "SELECT * FROM doctors WHERE speciality=\"{$_POST['speciality']}\" AND is_deleted=0";
    }
    $result_set = mysqli_query($connection, $query);
    verifyQuery($result_set);
    if (mysqli_num_rows($result_set) < 1) {
        $doctor_list = "No available doctor found. try different speciality";
    } else {
        $count = 0;
        $select1 = 'select';
        while ($doctor = mysqli_fetch_assoc($result_set)) {
            $select = $select1 . $count;
            $count++;
            $doctorObj = $doctor['object'];
            $UnserializedDoctorObj = unserialize($doctorObj);

            if ((checkIsSet('name') && $UnserializedDoctorObj->getFirstName() === trim($_POST['name']))) {
                if ((checkIsSet('hospital') && $UnserializedDoctorObj->getHospital() === trim($_POST['hospital']))) {
                    if ((checkIsSet('date') && $UnserializedDoctorObj->isAvailable($day))) {
                        if (strtotime($date) < time()) {
                            
                        } else {
                            $array = getDoctorList($UnserializedDoctorObj, $day, $select, $date);
                            $List = array_merge($List, $array);
                            $doctor_list .= "<p>" . displayDetails($UnserializedDoctorObj, $day, $select, $date) . "</p>";
                        }
                    } elseif (!checkIsSet('date')) {
                        $array = getDoctorList($UnserializedDoctorObj, $_POST['date'], $select, $date);
                        $List = array_merge($List, $array);
                        $doctor_list .= "<p>" . displayDetails($UnserializedDoctorObj, $_POST['date'], $select, $date) . "</p>";
                    }
                } elseif (!checkIsSet('hospital')) {
                    if ((checkIsSet('date') && $UnserializedDoctorObj->isAvailable($day))) {
                        if (strtotime($date) < time()) {
                            
                        } else {
                            $array = getDoctorList($UnserializedDoctorObj, $day, $select, $date);
                            $List = array_merge($List, $array);
                            $doctor_list .= "<p>" . displayDetails($UnserializedDoctorObj, $day, $select, $date) . "</p>";
                        }
                    } elseif (!checkIsSet('date')) {
                        $array = getDoctorList($UnserializedDoctorObj, $_POST['date'], $select, $date);
                        $List = array_merge($List, $array);
                        $doctor_list .= "<p>" . displayDetails($UnserializedDoctorObj, $_POST['date'], $select, $date) . "</p>";
                    }
                }
            } elseif (!checkIsSet('name')) {
                if ((checkIsSet('hospital') && $UnserializedDoctorObj->getHospital() === trim($_POST['hospital']))) {
                    if ((checkIsSet('date') && $UnserializedDoctorObj->isAvailable($day))) {
                        if (strtotime($date) < time()) {
                            
                        } else {
                            $array = getDoctorList($UnserializedDoctorObj, $day, $select, $date);
                            $List = array_merge($List, $array);
                            $doctor_list .= "<p>" . displayDetails($UnserializedDoctorObj, $day, $select, $date) . "</p>";
                        }
                    } elseif (!checkIsSet('date')) {
                        $array = getDoctorList($UnserializedDoctorObj, $_POST['date'], $select, $date);
                        $List = array_merge($List, $array);
                        $doctor_list .= "<p>" . displayDetails($UnserializedDoctorObj, $_POST['date'], $select, $date) . "</p>";
                    }
                } elseif (!checkIsSet('hospital')) {
                    if ((checkIsSet('date') && $UnserializedDoctorObj->isAvailable($day))) {
                        if (strtotime($date) < time()) {
                            
                        } else {
                            $array = getDoctorList($UnserializedDoctorObj, $day, $select, $date);
                            $List = array_merge($List, $array);
                            $doctor_list .= "<p>" . displayDetails($UnserializedDoctorObj, $day, $select, $date) . "</p>";
                        }
                    }
                }
            }
        }
        if ($doctor_list === "") {
            $doctor_list = "<p class=\"error\">No avaliable doctor matching the requirements</p>";
        }
        $_SESSION['doctorList'] = $List;
    }
}
?>







<!DOCTYPE html>
<html>
    <head>
        <title>Make an Appointment</title>

<?php include('inc/userHeader.php') ?>
    <div class="topnav">

        <a  href="user.php">Home</a>
        <a  class="active" href="appointment.php">Make An Appointment</a>
        <a  href="viewAppointmentDetails.php">View Appointment Details</a>
        <a  href="viewUserDetails.php">View User Details</a>
        <a  href="editUserDetails.php">Edit Details</a>
    </div>


    <main>


        <form action='appointment.php' method='post' style="max-width: 100%">
            <div class="formContainer">


                <p>Please fill the information to make new appointment.</p>
                <hr>
                <p>
                    <label><b>Doctor's Name:</b></label>
                    <input type="text" name="name" placeholder="doctor's name" ">
                </p>

                <p>
                    <label><b>Working Hospital:</b></label>
                    <input type="text" name="hospital" placeholder="Working hospital">
                </p>

                <p> 
                    <label><b>Speciality:</b></label>
                    <input type="text" name="speciality" placeholder="speciality">
                </p>

                <p>
                    <label><b>Date:</b></label>
                    <input type="date" name="date">
                </p>

                <p>
                <div class="clearfix">
                    <button type='submit' name='search' role="button" aria-pressed="true">Search</button>
                </div>
                </p>



            </div>
        </form>



<?php
if (isset($_POST['search'])) {
    echo $doctor_list;
}
?>

    </main>


<?php
if (isset($_SESSION['doctorList'])) {
    foreach ($_SESSION['doctorList'] as $name => $details) {
        if (isset($_POST[$name])) {
            $_SESSION['day'] = $details['day'];
            $_SESSION['date'] = $details['date'];
            $_SESSION['startTime'] = $details['startTime'];
            $_SESSION['endTime'] = $details['endTime'];
            $_SESSION['doctorID'] = $details['DId'];
            $di = $details['DId'];
            header("Location: appointment2.php?doctorID={$di}&&day={$_SESSION['day']}&&date={$_SESSION['date']}&&startTime={$_SESSION['startTime']}&&endTime={$_SESSION['endTime']}");
        }
    }
}
?>
        <?php include('inc/userFooter.php') ?>

