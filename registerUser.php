<?php session_start() ?>
<?php require_once('inc/functions.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/connection.php') ?>

<?php
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
    $firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($connection, $_POST['lastname']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $streetAddress1 = mysqli_real_escape_string($connection, $_POST['streetAddress1']);
    $streetAddress2 = mysqli_real_escape_string($connection, $_POST['streetAddress2']);
    $city = mysqli_real_escape_string($connection, $_POST['city']);
    $province = mysqli_real_escape_string($connection, $_POST['province']);
    $password = $_POST['password'];
    $dateOfBirth = $_POST['dob'];
    $errors = array();
    $gender = $_POST['gender'];
    // checking required fields
    $reqFields = array('firstname', 'lastname', 'email', 'streetAddress1', 'city', 'province', 'password', 'dob', 'gender');
    $errors = array_merge($errors, checkReqFields($reqFields));

    // check max len fields
    $maxLenFields = array('email' => 100, 'password' => 40);
    $errors = array_merge($errors, checkMaxLenFields($maxLenFields));

    //checking if passwords match
    if (!($_POST['password'] === $_POST['confirmPassword'])) {
        $errors[] = "password not match";
    }
    //check if emain already exixsts...
    $query = "SELECT * FROM doctors WHERE email='{$email}'";
    $resultSet = mysqli_query($connection, $query);
    verifyQuery($resultSet);
    if (mysqli_num_rows($resultSet) > 0) {
        $errors[] = "email already exists";
    }
    $query = "SELECT * FROM users WHERE email='{$email}'";
    $result = mysqli_query($connection, $query);
    verifyQuery($result);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = 'email already exists';
    }
    $query = "SELECT * FROM admins WHERE email='{$email}'";
    $result = mysqli_query($connection, $query);
    verifyQuery($result);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = 'email already exists';
    }

    $time = time();
    if (strtotime($dateOfBirth) > $time) {
        $errors[] = 'invalid birthday';
    }
    if (empty($errors)) {

        $firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($connection, $_POST['lastname']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $streetAddress1 = mysqli_real_escape_string($connection, $_POST['streetAddress1']);
        $streetAddress2 = mysqli_real_escape_string($connection, $_POST['streetAddress2']);
        $city = mysqli_real_escape_string($connection, $_POST['city']);
        $province = mysqli_real_escape_string($connection, $_POST['province']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $hashedPassword = sha1($password);
        $dateOfBirth = $_POST['dob'];
        $gender = $_POST['gender'];
        $age = floor((time() - strtotime($dateOfBirth)) / 31556926);
        $user = new user($firstname, $lastname, $age, $streetAddress1, $streetAddress2, $dateOfBirth, $city, $province, $gender, $email);
        $userObject = serialize($user);
        $query = "INSERT INTO users(email,password,object,is_deleted) VALUES('{$email}','{$hashedPassword}','{$userObject}',0)";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
        $query = "SELECT * FROM users WHERE email='{$email}' LIMIT 1";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
        if (mysqli_num_rows($result) != 1) {
            echo 'Error';
        } else {
            $user = mysqli_fetch_assoc($result);
            $userObject = $user['object'];
            $userId = $user['id'];
            $userObject = unserialize($userObject);
            $userObject->setuserId($userId);

            $userObject = serialize($userObject);

            //save back in data base
            $query = "UPDATE users SET object='{$userObject}' WHERE id='{$userId}'";
            $result = mysqli_query($connection, $query);
            verifyQuery($result);

            //send welcome message
            $message = "Welcome To Vertex Medical Center";
            $time = time();
            $query = "INSERT INTO messages(senderId,senderType,recieverId,recieverType,message,isRead,senderName,timeStamp) VALUES(99999,'system','{$userId}','user','{$message}',0,'vertex medical center','{$time}')";
            $result = mysqli_query($connection, $query);
            verifyQuery($result);

            header('Location:index.php?msg=register_successful');
        }
    }
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
<?php include('inc/Header.php') ?>
    <main>
        <form action='registerUser.php' method='post'>
            <div class="formContainer">
                <p>Please fill following information to register</p><hr> 
<?php
if (isset($errors) && !empty($errors)) {
    printErrors($errors);
}
?>

                <label for="inputFirstName"><b>First Name:</b></label>
                    <input type="text" name="firstname" class="form-control " id="inputFirstName" placeholder="firstname" <?php echo 'value="' . $firstname . '"'; ?>>

                    <label for="inputlastName"><b>Last Name:</b></label>
                    <input type="text" name="lastname" class="form-control" id="inputlastName" placeholder="lastname" <?php echo 'value="' . $lastname . '"'; ?> >

                    <label for="inputEmail4"><b>Email:</b></label>
                    <input type="email" name="email" class="form-control" id="inputEmail4" placeholder="someone@example.com" value = '<?php echo $email ?>'>

                    <label for="dateOfBirth"><b>Date Of Birth:</b></label>
                    <input type="date" name="dob" class="form-control" id="dateOfBirth" value = '<?php echo $dob ?>'>

                    <label for="gender1"><b>Gender:</b> </label>
                    <select name='gender' class="custom-select mb-3">
                        <option value='male' class="form-control" id="gender">Male</option>
                        <option value='female' class="form-control" id="gender">Female</option>
                    </select>

                    <br><br><label for="address"><b>Street Address:</b></label>
                    <input type="text" name="streetAddress1" class="form-control" id="address" placeholder="street address " value = '<?php echo $streetAddress1 ?>'>

                    <label for="address2"><b>Street Address Line2(optional):</b></label>
                    <input type="text" name="streetAddress2" class="form-control" id="address2" placeholder="street address line2 " value = '<?php echo $streetAddress2 ?>'>

                    <label for="city"><b>City:</b></label>
                    <input type="text" name="city" class="form-control" id="city" placeholder="city" value = '<?php echo $city ?>'>

                    <label for="province"><b>Province:</b></label>
                    <input type="text" name="province" class="form-control" id="province" placeholder="province" value = '<?php echo $province ?>'>


                    <label for="password"><b>Password:</b></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="password">

                    <label for="confirmPassword"><b>Confirm Password:</b></label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="confirm password">
                    <div class="clearfix" style="width: 75%; margin-left: auto; margin-right: auto">
                    <button type="submit" name="register">Register </button><hr>
                    <p style="float: left">Already have an account?&emsp;</p>
                    <button type="button" class="cancelbtn" name="login" onclick="location = 'index.php'">Log In</button>
                    </div>
            </div>
        </form>
</main>
<?php include('inc/Footer.php') ?>