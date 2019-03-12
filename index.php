<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/functions.php') ?>

<?php
$email = "";
//checking if username and password entered
if (isset($_POST['submit'])) {
    $errors = array();
    if (!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1) {
        $errors[] = "Username missing / invalid";
    }
    if (!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1) {
        $errors[] = "Password missing / invalid";
    }
    if (empty($errors)) {
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $hashed_password = sha1($password);
        $query = "SELECT * FROM users WHERE email='{$email}' AND password='{$hashed_password}' AND is_deleted=0 LIMIT 1";
        $result_set = mysqli_query($connection, $query);
        verifyQuery($result_set);

        if (mysqli_num_rows($result_set) == 1) {
            $user = mysqli_fetch_assoc($result_set);
            $_SESSION['userid'] = $user['id'];
            $_SESSION['uObject'] = $user['object'];
            header("Location: user.php?msg=login_success&&userid={$_SESSION['userid']}");
        }
        $query = "SELECT * FROM doctors WHERE email='{$email}' AND password='{$hashed_password}' AND is_deleted=0 LIMIT 1";
        $result_set = mysqli_query($connection, $query);
        verifyQuery($result_set);

        if (mysqli_num_rows($result_set) == 1) {
            $doctor = mysqli_fetch_assoc($result_set);
            $_SESSION['doctorid'] = $doctor['id'];
            header("Location: doctorHome.php?msg=login_success&&doctorid={$_SESSION['doctorid']}");
        }
        $query = "SELECT * FROM admins WHERE email='{$email}' AND password='{$hashed_password}' AND is_deleted=0 LIMIT 1";
        $result_set = mysqli_query($connection, $query);
        verifyQuery($result_set);

        if (mysqli_num_rows($result_set) == 1) {
            $admin = mysqli_fetch_assoc($result_set);
            $_SESSION['adminid'] = $admin['id'];
            $_SESSION['adminObject'] = $admin['object'];
            header("Location: admin.php?msg=login_success&&adminid={$_SESSION['adminid']}");
        }
        $query = "SELECT * FROM pharmacyStaff WHERE email='{$email}' AND password='{$hashed_password}' AND is_deleted=0 LIMIT 1";
        $result_set = mysqli_query($connection, $query);
        verifyQuery($result_set);

        if (mysqli_num_rows($result_set) == 1) {
            $staff = mysqli_fetch_assoc($result_set);
            $_SESSION['staffId'] = $staff['id'];
            $_SESSION['pharmacyStaffObject'] = $admin['object'];
            header("Location:pharmacyDashboard.php?msg=login_success&&pharmacyStaffId={$_SESSION['staffId']}");
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Log In</title>
        <?php include('inc/Header.php') ?>
    <main>

        <form class="login" action='index.php' method='post'>


            <?php
            if (isset($errors) && !empty($errors)) {
                echo "<p class='error'>Invalid Username / Password</p>";
            }
            ?>
            <?php
            if (isset($_GET['logout'])) {
                echo '<p class="info">You have successfully logged out</p>';
            }
            ?>
            <div class="loginContainer " >
                <h3 style="text-align: center">Log In</h3>
                <label  for="exampleInputEmail1">Email Address</label>
                <input type="text" id="exampleInputEmail1" name="email" placeholder="username" value="<?php echo $email ?>">


                <label for="exampleInputPassword1">Password</label>
                <input type="password"  id="exampleInputPassword1" name="password" placeholder="password">

                <div class="clearfix">
                    <button type="submit" name="submit">Log In</button><hr>
                    <p style="float: left">Don't have an account?&emsp;</p>
                    <button type="button" style="float: left" onclick="location = 'registerUser.php'" class="cancelbtn">click here for register</button>
                
                </div>
        </form>

    </main>

    <?php include('inc/Footer.php') ?>

    <?php mysqli_close($connection) ?>