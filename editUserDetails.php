<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php
$_SESSION['doctorId'] = '';
$_SESSON['appObj'] = '';
if (!isset($_SESSION['userid'])) {
    header('Location:logout.php?msg=invalid_attempt');
}
?>

<?php
$firstname = '';
$lastname = '';
$email = '';
$streetAddress1 = '';
$streetAddress2 = " ";
$city = '';
$province = '';
$dob;
$userid = $_SESSION['userid'];
$query = "SELECT * FROM users WHERE id='{$userid}' LIMIT 1";
$result = mysqli_query($connection, $query);
verifyQuery($result);
$user = mysqli_fetch_assoc($result);
$userObj = $user['object'];
$userObj = unserialize($userObj);
$name = $userObj->getFullName();
$firstname = $userObj->getFirstName();
$lastname = $userObj->getLastName();
$email = $userObj->getEmail();
$streetAddress2 = $userObj->getStreatAddress2();
$streetAddress1 = $userObj->getStreatAddress1();
$city = $userObj->getCity();
$dob = $userObj->getDOB();
$province = $userObj->getProvince();
?>

<?php
if (isset($_GET['userId']) && $_SESSION['userid'] == $_GET['userId']) {

    $userid = $_SESSION['userid'];
    $query = "SELECT * FROM users WHERE id='{$userid}' LIMIT 1";
    $result = mysqli_query($connection, $query);
    verifyQuery($result);
    if (mysqli_num_rows($result) != 1) {
        echo "error";
    } elseif (isset($_POST['update'])) {
        
    } else {
        $user = mysqli_fetch_assoc($result);
        $userObject = $user['object'];
        $userObject = unserialize($userObject);
        $firstname = $userObject->getFirstName();
        $lastname = $userObject->getLastName();
        $email = $user['email'];
        $streetAddress1 = $userObject->getStreatAddress1();
        $streetAddress2 = $userObject->getStreatAddress2();
        $city = $userObject->getCity();
        $province = $userObject->getProvince();
    }

    if (isset($_POST['update'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $streetAddress1 = $_POST['streetAddress1'];
        $streetAddress2 = $_POST['streetAddress2'];
        $city = $_POST['city'];
        $province = $_POST['province'];
        $dob = $_POST['dob'];
        $errors = array();
        // checking required fields
        $reqFields = array('firstname', 'lastname', 'email', 'streetAddress1', 'city', 'province');
        $errors = array_merge($errors, checkReqFields($reqFields));

        // check max len fields
        $maxLenFields = array('email' => 100);
        $errors = array_merge($errors, checkMaxLenFields($maxLenFields));

        //checking if passwords match
        //checking if email exists

        $query = "SELECT * FROM users WHERE email='{$email}' AND id!={$userid}";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
        if (mysqli_num_rows($result) != 0) {
            $errors[] = 'email already exists';
        }$query = "SELECT * FROM doctors WHERE email='{$email}'";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
        if (mysqli_num_rows($result) != 0) {
            $errors[] = 'email already exists';
        }
        $query = "SELECT * FROM admins WHERE email='{$email}'";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
        if (mysqli_num_rows($result) != 0) {
            $errors[] = 'email already exists';
        }$query = "SELECT * FROM pharmacyStaff WHERE email='{$email}'";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
        if (mysqli_num_rows($result) != 0) {
            $errors[] = 'email already exists';
        }
        $time = time();
        if (strtotime($dob) > $time) {
            $errors[] = 'invalid birthday';
        }
        if (empty($errors)) {

            $firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
            $lastname = mysqli_real_escape_string($connection, $_POST['lastname']);
            $email = mysqli_real_escape_string($connection, $_POST['email']);
            $streatAddress1 = mysqli_real_escape_string($connection, $_POST['streetAddress1']);
            $streatAddress2 = mysqli_real_escape_string($connection, $_POST['streetAddress2']);
            $city = mysqli_real_escape_string($connection, $_POST['city']);
            $province = mysqli_real_escape_string($connection, $_POST['province']);

            $query = "SELECT * FROM users WHERE id='{$userid}' LIMIT 1";
            $result = mysqli_query($connection, $query);
            verifyQuery($result);
            $userDetails = mysqli_fetch_assoc($result);
            $userObject = $userDetails['object'];
            $user = unserialize($userObject);

            $user->setFirstName($firstname);
            $user->setLastName($lastname);
            $user->setStreatAddress1($streatAddress1);
            $user->setStreatAddress2($streatAddress2);
            $user->setCity($city);
            $user->setProvince($province);
            $user->setEmail($email);
            $user->setDOB($dob);

            $userObject = serialize($user);
            $query = "UPDATE users SET email='{$email}',object='{$userObject}' WHERE id='{$userid}'";
            $result = mysqli_query($connection, $query);
            if (!$result) {
                echo "Database query failed";
            } else {
                header('Location:user.php?msg=update_successful');
            }
        }
    }
}
?>



<!DOCTYPE html>
<html>
    <head>
        <title>Update</title>
<?php include('inc/userHeader.php') ?>
    <div class="topnav">
        <a  href="user.php">Home</a>
        <a  href="appointment.php">Make An Appointment</a>
        <a  href="viewAppointmentDetails.php">View Appointment Details</a>
        <a  href="viewUserDetails.php">View User Details</a>
        <a class="active" href="editUserDetails.php">Edit Details</a>
    </div>
    <main>


        <form action='editUserDetails.php?userId=<?php echo $userid ?>' method='post'>
            <div class='formContainer'>

<?php
if (isset($errors) && !empty($errors)) {
    printErrors($errors);
}
?>

                <p>Please fill following information to edit your details.</p>
                <hr>

                <p>
                    <label><b>First Name:</b></label>
                    <input type="text" name="firstname" placeholder="firstname" <?php echo 'value="' . $firstname . '"'; ?>>
                </p>

                <p>
                    <label><b>Last Name:</b></label>
                    <input type="text" name="lastname" placeholder="lastname" <?php echo 'value="' . $lastname . '"'; ?> >
                </p>

                <p>
                    <label><b>Email:</b></label>
                    <input type="email" name="email" placeholder="someone@example.com" value = '<?php echo $email ?>'>
                </p>

                <p>
                    <label><b>Date Of Birth:</b></label>
                    <input type="date" name="dob" value = '<?php echo $dob ?>'>
                </p>

                <p>
                    <label><b>Address:</b><br></label>	
                    <input type="text" name="streetAddress1" placeholder="street address " value = '<?php echo $streetAddress1 ?>'>
                    <br><br><b>street Address</b><br>
                    <input type="text" name="streetAddress2" placeholder="street address line2 " value = '<?php echo $streetAddress2 ?>'>
                    <br><br><b>street Address Line2(optional)</b><br>
                    <input type="text" name="city" placeholder="city" value = '<?php echo $city ?>'>
                    <input type="text" name="province" placeholder="province" value = '<?php echo $province ?>'>
                    <br><b> City State / Province</b><br><br>
                </p>

                <p>
                    <label><b>Password:</b></label>
                    <span>******</span> | <a href="changeUserpassword.php?userId=<?php echo $userid; ?>">Change Password</a> 
                </p>

                <div class="clearfix">
                    <button type="submit" name="update">Update</button>
                </div>
                <p>
                    <label><a href="deactivateUserAccount.php">Deactivate Account</a></label>
                </p>

            </div>
        </form>

    </main>
<?php include('inc/userFooter.php') ?>