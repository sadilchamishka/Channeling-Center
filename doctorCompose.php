<?php session_start() ?>
<?php require_once('inc/connection.php') ?>
<?php require_once('inc/class.php') ?>
<?php require_once('inc/functions.php') ?> 

<?php 
  if(!isset($_SESSION['doctorid'])){
    header('Location:logout.php?msg=invalid_attempt');
  }
  if(!isset($_GET['adminId'])){
    header('Location: doctor.php?msg=invalid_attemt');
  }
  
 ?>
  <?php 
  $doctorid = $_SESSION['doctorid'];
  $query = "SELECT * FROM doctors WHERE id='{$doctorid}' LIMIT 1";
  $result = mysqli_query($connection,$query);
  verifyQuery($result);
  $doctor = mysqli_fetch_assoc($result);
  $doctorObj = $doctor['object'];
  $doctorObj = unserialize($doctorObj);
  $name = $doctorObj->getFullName();
  $adminId = $_GET['adminId'];
  if($adminId == 0){
    $adminId=99999;
  }
  ?>
  
 



    

  <!DOCTYPE html>
  <html>
  <head>
    <title>Message Box</title>
  <?php include('inc/doctorHeader.php') ?>
     <div class="topnav">

        <a  href="doctor.php">Search Appointment</a>
        <a  class="active" href="doctorMessageType.php">Messages <span class="badge badge-pile badge-light" ><?php echo $unread ?></span></a>

    </div>
  <h4>Compose</h4><hr>
      <main>
        <div class="formContainer">
            <div class="row">
                <div class="column">
                    <img class="mailbox" src="images/adminX.jpg" style="width:100%;" onclick="location = 'messageDoctorToAdmin.php?adminId=<?php echo $adminId ?>'">
              
                    <span class="tooltiptext">Send a Message To a Admin</span>
                </div>
                
            </div>
        </div>

    </main>
  <div class="btnlinks" style="margin-top: -60px">
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<a href=doctorMessageType.php?msg=Back">Back To Messages</a>
        <p><br><br></p>
        <p><br><br></p>
    </div>


  </main>

  </main>
  <?php include('inc/doctorFooter.php') ?>