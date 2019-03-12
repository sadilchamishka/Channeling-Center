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
$adminid = $_SESSION['adminid'];
$query = "SELECT * FROM admins WHERE id='{$adminid}' LIMIT 1";
$result = mysqli_query($connection, $query);
verifyQuery($result);
$admin = mysqli_fetch_assoc($result);
$adminObj = $admin['object'];
$adminObj = unserialize($adminObj);
$name = $adminObj->getFullName();
?>

<?php
$admin = $_SESSION['adminObject'];
$admin = unserialize($admin);
$firstname = '';
$lastname = '';
$email = '';
$streetAddress1 = '';
$streetAddress2 = '';
$city = '';
$province = '';
$password = '';
$confirmPassword = '';
$dateOfBirth = '';

$gender = '';

if (isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $streetAddress1 = $_POST['streetAddress1'];
    $streetAddress2 = $_POST['streetAddress2'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $password = $_POST['password'];
    $dateOfBirth = $_POST['dob'];

    $errors = array();
    $gender = $_POST['gender'];
    // checking if first name entered
    $reqFields = array('firstname', 'lastname', 'email', 'streetAddress1', 'city', 'province', 'password', 'dob', 'gender');
    $errors = array_merge($errors, checkReqFields($reqFields));

    // check max len fields
    $maxLenFields = array('email' => 100, 'password' => 40, 'firstname' => 100, 'lastname' => 100);
    $errors = array_merge($errors, checkMaxLenFields($maxLenFields));

    //check if email already exists

    $query = "SELECT * FROM doctors WHERE email='{$email}'";
    $resultSet = mysqli_query($connection, $query);
    verifyQuery($resultSet);
    if (mysqli_num_rows($resultSet) > 0) {
        $errors[] = "email already exists";
    }
    $query = "SELECT * FROM users WHERE email='{$email}'";
    $result = mysqli_query($connection, $query);
    verifyQuery($result);
    if (mysqli_num_rows($result) != 0) {
        $error[] = 'email already exists';
    }
    $query = "SELECT * FROM admins WHERE email='{$email}'";
    $result = mysqli_query($connection, $query);
    verifyQuery($result);
    if (mysqli_num_rows($result) != 0) {
        $error[] = 'email already exists';
    }
    $query = "SELECT * FROM pharmacystaff WHERE email='{$email}'";
    $result = mysqli_query($connection, $query);
    verifyQuery($result);
    if (mysqli_num_rows($result) != 0) {
        $error[] = 'email already exists';
    }

    $time = time();
    if (strtotime($dateOfBirth) > $time) {
        $errors[] = 'invalid birthday';
    }

    if (empty($errors)) {

        $firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($connection, $_POST['lastname']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);

        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $hashedPassword = sha1($password);
        $age = floor((time() - strtotime($dateOfBirth)) / 31556926);
        $fullAddress = $streetAddress1 . " " . $streetAddress2;
        $pharmacyStaffObject = $admin->registerPharmacyStaff($firstname, $lastname, $age, $fullAddress, $dateOfBirth, $city, $province, $connection, $gender);
        $query = "INSERT INTO pharmacystaff(email,password,object,is_deleted) VALUES('{$email}','{$hashedPassword}','{$pharmacyStaffObject}',0)";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            echo "Database query failed";
        } else {
            $query = "SELECT * FROM pharmacystaff WHERE password='{$hashedPassword}' AND object='{$pharmacyStaffObject}' LIMIT 1";
            $result = mysqli_query($connection, $query);
            verifyQuery($result);
            if (!$result) {
                echo "Database query failed";
            } else {
                $pharmacyStaffObject = mysqli_fetch_assoc($result);
                $pharmacyStaff = unserialize($pharmacyStaffObject['object']);
                $staffId = $pharmacyStaffObject['id'];
                $pharmacyStaff->setId($pharmacyStaffObject['id']);
                $pharmacyStaff = serialize($pharmacyStaff);
                $query = "UPDATE pharmacystaff SET object='{$pharmacyStaff}' WHERE id={$pharmacyStaffObject['id']}";
                $result_set = mysqli_query($connection, $query);
                verifyQuery($result_set);
                //send welcome message
                $message = "Welcome To Vertex Medical Center";
                $time = time();
                $query = "INSERT INTO messages(senderId,senderType,recieverId,recieverType,message,isRead,senderName,timeStamp) VALUES(99999,'system','{$staffId}','pharmacyStaff','{$message}',0,'vertex medical center','{$time}')";
                $result = mysqli_query($connection, $query);
                verifyQuery($result);

                header('Location:admin.php?msg=register_successful');
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Register Doctor</title>
        <?php include('inc/adminHeader.php') ?>
    <div class='topnav'>
        <a href="admin.php">Home</a>
        <a href="registerAdmin.php">Register a New Admin</a>
        <a href="manageDoctor.php">Manage Doctors</a>
        <a class="active" href="managepharmacyStaff.php">Manage Pharmacy Staff</a>
        <a href="adminMessageType.php" class="badge badge-dark"><span>Messages&nbsp;[<?php echo $unread ?>]</span></a>

    </div>
    <div>

        <form action='registerPharmacyStaff.php' method='post'>

            <fieldset>
                <?php
                if (isset($errors) && !empty($errors)) {
                    printErrors($errors);
                }
                ?>

                               <div class="formContainer">
                    <p>Please fill following information to add new pharmacy staff.</p><hr>
                    <p>
                        <label><b>First Name:</b></label>
                        <input type="text" name="firstname" placeholder="firstname" <?php echo 'value = ' . '"' . $firstname . '"'; ?>">
                    </p>

                    <p>
                        <label><b>Last Name:</b></label>
                        <input type="text" name="lastname" placeholder="lastname" value='<?php echo $lastname ?>'>
                    </p>

                    <p>
                        <label><b>Email:</b></label>
                        <input type="email" name="email" placeholder="someone@example.com" value='<?php echo $email ?>'>
                    </p>

                    <p>
                        <label><b>Date Of Birth:</b></label>
                        <input type="date" name="dob" >
                    </p>
                    <p>
                        <label><b>Select Gender: </b></label>
                        <select name='gender'>
                            <option value='male'>Male</option>
                            <option value='female'>Female</option>
                        </select>
                    </p>

                    <p><br>
                        <label><b>Street Address:</b><br></label>	
                        <input type="text" name="streetAddress1" placeholder="street address " value='<?php echo $streetAddress1 ?>'>
                        <br><br><b>Street Address Line2(optional):</b><br>
                        <input type="text" name="streetAddress2" placeholder="street address " value='<?php echo $streetAddress2 ?>'>

                        <br><br><b>City:</b><br>

                        <input type="text" name="city" placeholder="city" value='<?php echo $city ?>' >
                        <br><br><b> Province:</b><br>
                        <input type="text" name="province" placeholder="province" value='<?php echo $province ?>'>
                        <br>

                    </p>

                    <p>
                        <label><b>Password:</b></label>
                        <input type="password" name="password" placeholder="password">
                    </p>

                    <p>
                    <div class="clearfix">
                        <button type="submit" name="register" style="width: 50%; margin-left: 200px;">Register</button>
                    </div>
                    </p>


                </div>
            </fieldset>
        </form>
    </div>
</main>
</body>
</html>