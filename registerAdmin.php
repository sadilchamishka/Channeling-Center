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
$admin = $_SESSION['adminObject'];
$admin2 = unserialize($admin);
$name = $admin2->getFullName();
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
$position = '';
$gender = '';
$age = 0;
$fullAddress = '';

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
    $position = $_POST['position'];
    $gender = $_POST['gender'];
    $errors = array();
    // checking if first name entered
    $reqFields = array('firstname', 'lastname', 'email', 'position', 'streetAddress1', 'city', 'province', 'password', 'dob', 'gender');
    $errors = array_merge($errors, checkReqFields($reqFields));

    // check max len fields
    $maxLenFields = array('email' => 100, 'password' => 40);
    $errors = array_merge($errors, checkMaxLenFields($maxLenFields));

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
        $errors[] = 'email already exists';
    }
    $query = "SELECT * FROM admins WHERE email='{$email}'";
    $result = mysqli_query($connection, $query);
    verifyQuery($result);
    if (mysqli_num_rows($result) != 0) {
        $errors[] = 'email already exists';
    }
    $query = "SELECT * FROM pharmacyStaff WHERE email='{$email}'";
    $result = mysqli_query($connection, $query);
    verifyQuery($result);
    if (mysqli_num_rows($result) != 0) {
        $errors[] = 'email already exists';
    }
    $time = time();
    if (strtotime($dateOfBirth) > $time) {
        $errors[] = 'invalid birthday';
    }

    if (empty($errors)) {

        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $hashedPassword = sha1($password);
        $age = floor((time() - strtotime($dateOfBirth)) / 31556926);
        $fullAddress = $streetAddress1 . " " . $streetAddress2;
        $adminObject = $admin2->registerAdmin($firstname, $lastname, $age, $fullAddress, $dateOfBirth, $city, $province, $position, $connection, $gender);
        $query = "INSERT INTO admins(email,password,object,is_deleted) VALUES('{$email}','{$hashedPassword}','{$adminObject}',0)";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);

        $query = "SELECT * FROM admins WHERE email='{$email}' LIMIT 1";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
        if (mysqli_num_rows($result) != 1) {
            echo 'Error';
        } else {
            $admin = mysqli_fetch_assoc($result);
            $adminObject = $admin['object'];
            $adminId = $admin['id'];
            $adminObject = unserialize($adminObject);
            $adminObject->setAdminId($adminId);

            $adminObject = serialize($adminObject);

            //save back in data base
            $query = "UPDATE admins SET object='{$adminObject}' WHERE id='{$adminId}'";
            $result = mysqli_query($connection, $query);
            verifyQuery($result);

            //send welcome message
            $message = "Welcome To Vertex Medical Center";
            $time = time();
            $query = "INSERT INTO messages(senderId,senderType,recieverId,recieverType,message,isRead,senderName,timeStamp) VALUES(99999,'system','{$adminId}','admin','{$message}',0,'vertex medical center','{$time}')";
            $result = mysqli_query($connection, $query);
            verifyQuery($result);

            header('Location:admin.php?msg=register_successful');
        }
    }
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Register  Admin</title>
        <?php include('inc/adminHeader.php') ?>
    <div class='topnav'>
        <a  href="admin.php">Home</a>
        <a class="active" href="registerAdmin.php">Register a New Admin</a>
        <a href="manageDoctor.php">Manage Doctors</a>
        <a href="managepharmacyStaff.php">Manage Pharmacy Staff</a>
        <a href="adminMessageType.php" class="badge badge-dark"><span>Messages&nbsp;[<?php echo $unread ?>]</span></a>

    </div>
    <main>
        <form action='registerAdmin.php' method='post'>

            <div class="formContainer">
                <?php
                if (isset($errors) && !empty($errors)) {
                    printErrors($errors);
                }
                ?>

                <p>Please fill following information to edit your details.</p>
                <hr>

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
                    <label><b>Position:</b></label>
                    <input type="text" name="position" placeholder="position" value='<?php echo $position ?>'>
                </p>

                <p>
                    <label><b>Date Of Birth:</b></label>
                    <input type="date" name="dob" >
                </p>
                <p>
                    <label><b>Select Gender:</b> </label>
                    <select name='gender'>
                        <option value='male'>Male</option>
                        <option value='female'>Female</option>
                    </select>
                </p>

                <p><br>
                    <label><b>Street Address:</b><br></label>	
                    <input type="text" name="streetAddress1" placeholder="street address " value='<?php echo $streetAddress1 ?>'>
                    <br><br><b>Street Address 2(optional):</b><br>
                    <input type="text" name="streetAddress2" placeholder="street address " value='<?php echo $streetAddress2 ?>'>

                    <br><br><b>City:</b><br>

                    <input type="text" name="city" placeholder="city" value='<?php echo $city ?>' >
                    <br><br><b>Province:</b><br>
                    <input type="text" name="province" placeholder="province" value='<?php echo $province ?>'>

                </p>

                <p>
                    <label><b>Password:</b></label>
                    <input type="password" name="password" placeholder="password">
                </p>

                <p>
                <div class="clearfix">
                    <button type="submit" name="register">Register</button>
                </div>
                </p>

            </div>
        </form>

    </main>
    <?php include('inc/adminFooter.php') ?>