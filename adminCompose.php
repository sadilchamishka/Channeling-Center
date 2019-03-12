<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
  if(!isset($_SESSION['adminid'])){
    header('Location:index.php');
  }
?>

  <!DOCTYPE html>
  <html>
  <head>
    <title>Message Box</title>
  <?php include('inc/adminHeader.php') ?>
     <div class='topnav'>
        <a href="admin.php">Home</a>
        <a href="registerAdmin.php">Register a New Admin</a>
        <a href="manageDoctor.php">Manage Doctors</a>
        <a href="managepharmacyStaff.php">Manage Pharmacy Staff</a>
        <a class="active" href="adminMessageType.php" class="badge badge-dark"><span>Messages&nbsp;[<?php echo $unread ?>]</span></a>
    </div>
    <h4>Compose</h4><hr>
    <h4 style="color:#333333">Whom do you want to send message?</h4>
    <main>
        <div class="formContainer">
            <div class="row">
                <div class="column">
                    <img class="mailbox" src="images/doctor.png" style="width:100%" onclick="location = 'messageAdminToDoctor.php?doctorId=0'">
                    <div class="centered"><b>Doctor</b></div>
                    <span class="tooltiptext">Send a Message To a Doctor</span>
                </div>
                <div class="column">
                    <img class="mailbox" src="images/doctors.png" style="width:100%" onclick="location = 'messageAdminToAllDoctors.php?doctorId=0'">
                    <div class="centered"><b>All Doctors</b></div>
                    <span class="tooltiptext">Send a Message To All Doctors</span>
                </div>
                <div class="column">
                    <img class="mailbox" src="images/users.png" style="width:100%" onclick="location = 'messageAdminToAllUsers.php'">
                    <div class="centered"><b>All Users</b></div>
                    <span class="tooltiptext">Send a Message To All Users</span>
                </div>
            </div>
        </div>

    </main>
    <div class="btnlinks" style="margin-top: -90px">
        <a href="adminMessageType.php?msg=Back" style="margin-left: 450px;">Back To Messages</a>
        <p><br><br></p>
        <p><br><br></p>
    </div>


  </main>
  <?php include('inc/adminFooter.php') ?>