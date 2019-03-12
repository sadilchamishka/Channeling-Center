<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php
if (!isset($_SESSION['adminid'])) {
    header('Location:index.php');
}
$adminid = $_SESSION['adminid'];
?>



<?php
$query = "SELECT * FROM messages WHERE recieverType='admin' AND (recieverId='{$adminid}' OR recieverId=99999)";
$resultSet = mysqli_query($connection, $query);
verifyQuery($resultSet);
while ($message = mysqli_fetch_assoc($resultSet)) {
    $id = $message['id'];
    if ($message['timeStamp'] + 60 * 60 * 24 * 30 < time()) {
        $query = "DELETE FROM messages WHERE id='$id'";
        $result = mysqli_query($connection, $query);
        verifyQuery($result);
    }
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Admin</title>
        <?php include('inc/adminHeader.php') ?>
    <div class='topnav'>
        <a class="active" href="admin.php">Home</a>
        <a href="registerAdmin.php">Register a New Admin</a>
        <a href="manageDoctor.php">Manage Doctors</a>
        <a href="managepharmacyStaff.php">Manage Pharmacy Staff</a>
        <a href="adminMessageType.php" class="badge badge-dark"><span>Messages&nbsp;[<?php echo $unread ?>]</span></a>
    </div>
    <main>
        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br><br></p>
        <p><br></p>
    </main>

    <?php include('inc/adminFooter.php') ?>