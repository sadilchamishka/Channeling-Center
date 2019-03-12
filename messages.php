<?php session_start() ?>
<?php require_once("inc/connection.php"); ?>
<?php require_once("inc/functions.php"); ?>
<?php require_once('inc/class.php') ?>
<?php 
 	$staffid = $_SESSION['staffId'];
 	$query = "SELECT * FROM pharmacystaff WHERE id='{$staffid}' LIMIT 1";
    $result = mysqli_query($connection,$query);
    verifyQuery($result);
    $staff = mysqli_fetch_assoc($result);
    $staffObj = $staff['object'];
    $staffObj = unserialize($staffObj );
    $name = $staffObj->getFullName();
 	
  ?>

<?php 
	$query = "SELECT * FROM messages WHERE recieverType='pharmacyStaff' AND (recieverId='{$staffid}' OR recieverId=99999)";
	$resultSet = mysqli_query($connection,$query);
	verifyQuery($resultSet);

	$display='';  
	if(mysqli_num_rows($resultSet)<1){

	}else{

		while($appointment = mysqli_fetch_assoc($resultSet)){

	        $messageId = $appointment['id'];
			$messageList = $appointment['message'];
	        $messageListSerialized = $messageList;
			$messageList1 = unserialize(base64_decode($messageList));
			$appointmentNumber = $messageList1['appointmentNumber'];
			

			$query ="SELECT * FROM appointment WHERE appointmentNumber='{$appointmentNumber}' LIMIT 1";
			$result=mysqli_query($connection,$query);
			verifyQuery($result);
			if(mysqli_num_rows($result)!=1){
				echo "error1";
			}else{
				$appointmentDetails = mysqli_fetch_assoc($result);
				$appObj = $appointmentDetails['appointmentObject'];
				$appObj = unserialize($appObj);
				$userObj = $appObj->getUser();
				$userName = $userObj->getFullName();
				
	            $display .= "<tr> <td width='40%'>";
	            $display .= "{$userName} </td>";
	            $display .= "<td width='20%'>";
	            $display .= "{$appointmentNumber} </td>";
	            $display .= "<td width='20%'>";
	            $display .= "<a href='issueMedicine.php?messageList=$messageList&&messageId=$messageId'>Select</a>";
				$display .= "</td> </tr>"; 
			}
		}
	}

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<p style="float:right"><font color="white">Welcome <?php echo $name ;?>!</font> <a href="logout.php"><font color="red">Log Out</font></a></p>
 	<title>Messages</title>
 </head>
 <body>
 	<?php include("inc/pharmacyheader.php"); ?>
 	<table >
			<tr>
				<th width='40%'>Patient Name</th>
				<th width='20%' >Appointment Number </th>
				<th width='20%'></th>
			</tr>

			
				<?php echo $display ?>

		</table>
 		


 </body>
 </html>