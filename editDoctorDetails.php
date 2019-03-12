<?php session_start() ?>
<?php require_once('inc/functions.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/connection.php') ?>

<?php
if(!isset($_SESSION['doctorid'])){
header('Location:index.php');
}
?>
<?php
$doctorid = $_SESSION['doctorid'];
$query = "SELECT * FROM doctors WHERE id='{$doctorid}' LIMIT 1";
$result = mysqli_query($connection, $query);
verifyQuery($result);
$doctor = mysqli_fetch_assoc($result);
$doctorObj = $doctor['object'];
$doctorObject = unserialize($doctorObj);
$name = $doctorObject->getFullName();
$doctorObj = '';
$doctorId = 0;
$firstname = '';
$lastname = '';
$email = '';
$streetAddress1 = '';
$streetAddress2 = " ";
$city = '';
$province = '';
$activeTime = '';
$dob;
?>

<?php
$firstname = $doctorObject->getFirstName();
$lastname = $doctorObject->getLastName();
$email = $doctor['email'];
$streetAddress1 = $doctorObject->getStreatAddress1();
$streetAddress2 = $doctorObject->getStreatAddress2();
$city = $doctorObject->getCity();
$province = $doctorObject->getProvince();
$activeTimeList = $doctorObject->getAvailableTime();
$dob = $doctorObject->getDOB();


if(isset($_POST['update'])){
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
$reqFields = array('firstname', 'lastname', 'email', 'streetAddress1', 'city', 'province', 'dob');
$errors = array_merge($errors, checkReqFields($reqFields));

// check max len fields
$maxLenFields = array('email' => 100, 'firstname' => 100, 'lastname' => 100);
$errors = array_merge($errors, checkMaxLenFields($maxLenFields));

//checking if passwords match
//checking if email exists

$query = "SELECT * FROM doctors WHERE email='{$email}' AND id!={$doctorid}";
$result = mysqli_query($connection, $query);
verifyQuery($result);
if(mysqli_num_rows($result)!=0){
$errors[] = 'email already exists';
}
$query = "SELECT * FROM users WHERE email='{$email}'";
$result = mysqli_query($connection, $query);
verifyQuery($result);
if(mysqli_num_rows($result)!=0){
$errors[] = 'email already exists';
}
$query = "SELECT * FROM admins WHERE email='{$email}'";
$result = mysqli_query($connection, $query);
verifyQuery($result);
if(mysqli_num_rows($result)!=0){
$errors[] = 'email already exists';
}
$time = time();
if (strtotime($dob)>$time) {
$errors[] = 'invalid birthday';
}

if(empty($errors)){

$firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
$lastname = mysqli_real_escape_string($connection, $_POST['lastname']);
$email = mysqli_real_escape_string($connection, $_POST['email']);
$streatAddress1 = mysqli_real_escape_string($connection, $_POST['streetAddress1']);
$streatAddress2 = mysqli_real_escape_string($connection, $_POST['streetAddress2']);
$city = mysqli_real_escape_string($connection, $_POST['city']);
$province = mysqli_real_escape_string($connection, $_POST['province']);
$dob = mysqli_real_escape_string($connection, $_POST['dob']);

$doctorObject->setFirstName($firstname);
$doctorObject->setLastName($lastname);
$doctorObject->setStreatAddress1($streatAddress1);
$doctorObject->setStreatAddress2($streatAddress2);
$doctorObject->setCity($city);
$doctorObject->setProvince($province);
$doctorObject->setDOB($dob);

$doctorObject = serialize($doctorObject);
$query = "UPDATE doctors SET email='{$email}',object='{$doctorObject}',FirstName='{$firstname}',LastName='{$lastname}' WHERE id='{$doctorid}'";

$result = mysqli_query($connection, $query);
if(!$result){
echo "Database query failed";
}else{
header('Location:doctorHome.php?msg=update_successful');
}
}


}

?>


<!DOCTYPE html>
<html>
    <head>
        <title>Update</title>
<?php include('inc/doctorHeader.php') ?>
    <div class="topnav">
        <a   href="doctorHome.php">Home</a>
        <a   href="doctor.php">Search Appointment</a>
        <a  class="active" href="editDoctorDetails.php">Edit details</a>
        <a href="doctorMessageType.php">Messages <span class="badge badge-pile badge-light" ><?php echo $unread ?></span></a>

    </div>
<main>


    <form action='editDoctorDetails.php' method='post'>

        <div class="formContainer">
<?php
if (isset($errors) && !empty($errors)) {
    printErrors($errors);
}
?>

            <p>Please fill following information to edit doctor's details.</p>
            <hr>

            <p>
                <label ><b>First Name:</b></label>
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
                <label><b>dob:</b></label>
                <input type="date" name="dob" placeholder="dob" value="<?php echo $dob ?>">
            </p>

            <p>
                <label><b>Address:</b><br></label>	
                <input type="text" name="streetAddress1" placeholder="street address " value = '<?php echo $streetAddress1 ?>'>
                <br><br><b>street Address</b><br>
                <input type="text" name="streetAddress2" placeholder="street address line2 " value = '<?php echo $streetAddress2 ?>'>
                <br><br><b>street Address Line2(optional)</b><br>
                <input type="text" name="city" placeholder="city" value = '<?php echo $city ?>'>
                <input type="text" name="province" placeholder="province" value = '<?php echo $province ?>'>
                <br><b> City State / Province</b><br>
            </p>
            <p>



            </p>


            <p>
                <br><br><br><br>
            <div class="clearfix">
                <button type="submit" name="update">Update</button>
            </div>
            </p>
            <p>
                <label><a href="deactivateUserAccount.php">Deactivate Account</a></label>
            </p>

        </div>
    </form>

</main>
<?php include('inc/adminFooter.php') ?>