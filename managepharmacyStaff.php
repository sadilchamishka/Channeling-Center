<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php
if (!isset($_SESSION['adminid'])) {
    header('Location:index.php');
}
?>


<?php
$pharmacyStaffList = '';
$query = "SELECT * FROM pharmacyStaff WHERE is_deleted=0 ORDER BY id";
$pharmacyStaffs = mysqli_query($connection, $query);
verifyQuery($pharmacyStaffs);

while ($pharmacyStaff = mysqli_fetch_assoc($pharmacyStaffs)) {
    $pharmacyStaffObject = $pharmacyStaff['object'];
    $pharmacyStaffObject = unserialize($pharmacyStaffObject);
    $first_name = $pharmacyStaffObject->getFirstName();
    $last_name = $pharmacyStaffObject->getLastName();

    $pharmacyStaffId = $pharmacyStaff['id'];


    $pharmacyStaffList.="<tr>";
    $pharmacyStaffList.="<td>{$first_name}</td>";
    $pharmacyStaffList.="<td>{$last_name}</td>";
    $pharmacyStaffList.="<td>{$pharmacyStaff['id']}</td>";
    $pharmacyStaffList.="<td ><button type=\"button\" class=\"removebtn\" name=\"remove\" onClick=\"if(confirm('Are you sure you want to delete?')){location.href='removepharmacyStaff.php?pharmacyStaffId={$pharmacyStaffId}'}\">Remove</button></td></tr>";

    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Manage Pharmacy Staff</title>

<?php include('inc/adminHeader.php') ?>
    <div class='topnav'>
        <a href="admin.php">Home</a>
        <a href="registerAdmin.php">Register a New Admin</a>
        <a href="manageDoctor.php">Manage Doctors</a>
        <a class="active" href="managepharmacyStaff.php">Manage Pharmacy Staff</a>
        <a href="adminMessageType.php" class="badge badge-dark"><span>Messages&nbsp;[<?php echo $unread ?>]</span></a>

    </div>
    <table>
        <tr><th><h1>Staff Member</h1><th><td><div class="btnlinks" style="float: right;"> <a href="registerPharmacyStaff.php">Add new member</a></div></td><br>
    </table>
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Member Id</th>
            <th style='width: 200px'>Remove Member</th>
<?php echo $pharmacyStaffList ?>

        </tr>

    </table>

    <p><br><br></p>
    <p><br><br></p>
    <p><br><br></p>
    <p><br></p>



</main>

<?php include('inc/adminFooter.php') ?>