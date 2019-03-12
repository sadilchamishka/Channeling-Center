<?php session_start() ?>
<?php require_once("inc/connection.php"); ?>
<?php require_once("inc/functions.php"); ?>
<?php require_once('inc/class.php') ?>
<?php 
 	$staffid = $_SESSION['staffId'];
 	$query = "SELECT * FROM pharmacyStaff WHERE id='{$staffid}' LIMIT 1";
    $result = mysqli_query($connection,$query);
    verifyQuery($result);
    $staff = mysqli_fetch_assoc($result);
    $staffObj = $staff['object'];
    $staffObj = unserialize($staffObj);
    $name = $staffObj->getFullName();
 	
  ?>
<?php 

if(!isset($_GET['messageList']) || !isset($_GET['messageId'])){
	header("Location:messages.php?msg=invalid attempt");

}
?>
<?php 
	$userId;
	$display='';
	$messageListDetails;
	$messageId;
	$messageListDetails = $_GET['messageList'];
	$messageId = $_GET['messageId'];
	
	
	
	$messageListDetail=unserialize(base64_decode($messageListDetails));
	
	$messageList = $messageListDetail['medicineList'];
	$appointmentNumber = $messageListDetail['appointmentNumber'];
	$issuingDetails = array();
	$totalCost = 0;
	foreach ($messageList as $medicineName => $medicineDetails) {
		$medName = $medicineName;
		$medicineQuentity;
		$numberOfDays = $medicineDetails['numberOfDays'];
		$medQuentity = $medicineDetails['medicineQuentity'];
		$userId = $medicineDetails['userId'];
		$timeForDay=0;
		
		switch ($medicineDetails['usingTime']) {
			case '3-1':
   				$timeForDay=3;
    			break;
    		case '2-1':
    			$timeForDay=2;
   				break;
   			case '1-1':
    			$timeForDay=1;
    			break;
		}
		$medicineQuentity = $numberOfDays*$medQuentity*$timeForDay;
		$messages='';
		$cost ='';
		$display .= "<tr> <td width='40%'>";
          $display .= "{$medicineName} </td>";
          $display .= "<td width='20%'>";
          $display .= "{$medicineQuentity} </td>";
          $display .= "<td width='20%'></td>";
        
		  

		  $query = "SELECT *FROM inventory WHERE name='{$medicineName}' LIMIT 1";
		  $result = mysqli_query($connection,$query);
		  verifyQuery($result);
		  if(mysqli_num_rows($result)!=1){
		  	$messages='not available';
		  	$issuingDetails[$medicineName] = array('avaialble'=>'no','quentity'=>0,'object'=>'');
		  	$cost.="cost = 0";
		  }else{
		  	$medicineOrder =mysqli_fetch_assoc($result);
		  	$medicineObj = $medicineOrder['object'];
		  	$medicine = unserialize($medicineObj);
		  	if($medicine->getQuantity()<$medicineQuentity){
		  		$messages ="not enough medicine";
		  		$cost.="cost = 0";
		  		$issuingDetails[$medicineName] = array('available'=>'no','quentity'=>0,'object'=>'');
		  		
		  	}else{
		  		$messages='available';
		  		$issuingDetails[$medicineName] = array('available'=>'yes','quentity'=>$medicineQuentity,'object'=>$medicineObj);
		
		  		$medicineCost = $medicine->getPrice()*$medicineQuentity;
		  		
		  		$cost.="cost = ".$medicineCost;
		  		
		  		$totalCost+=$medicineCost;
		  	}

		  } $display .= "<td width='10%'>";
		  $display.=$messages."</td>";
		  $display .= "<td width='10%'>";
		  $display.=$cost."</td>";
	}
	$display .= "</td> </tr>";
	if(isset($_POST['cancel'])){

		header("Location:messages.php?msg=cancel_payment");
	}
	if(isset($_POST['order'])){
		
		foreach ($issuingDetails as $key => $value) {
			if($value['available']==='yes'){
				$medicineObj = $value['object'];
				$medicineObj = unserialize($medicineObj);
				$medicineQuentity = $value['quentity'];
				$medicineObj->issueMedicine($medicineQuentity);
				$medicineObj = serialize($medicineObj);

				//update inventory
				$query = "UPDATE inventory SET object='{$medicineObj}' WHERE name='{$key}'";
				$result = mysqli_query($connection,$query);
				verifyQuery($result);

				
			}
		}
		//send message
		$time = time();
		
		$query = "INSERT INTO messages(senderId,senderType,recieverId,recieverType,message,isRead,senderName,timeStamp) VALUES('{$staffid}','pharmacyStaff','{$userId}','user','{$display}',0,'Vertex Medical Center','{$time}')";
		$result = mysqli_query($connection,$query);
		verifyQuery($result);

		//delete pharmacy record
		$query = "DELETE FROM messages WHERE id='{$messageId}'";
		$result = mysqli_query($connection,$query);
		verifyQuery($result);
		header("Location:messages.php?msg=order_success");
	}
	
 ?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>

 
<table class="masterlist">
			<tr>
				<th width='40%'>Medicine Name</th>
				<th width='20%' >Medicine Cost</th>
				<th width='10%'>Avalability</th>
				<th width='10%'>cost</th>

			</tr>


			
				<?php echo $display ?>

		</table>
		<form action="issueMedicine.php?messageList=<?php echo $medicineListDetails ?>&&messageId=<?php echo $messageId ?>" method='POST'>
			<button name='cancel' type='submit' onclick= "return confirm('Are You Sure?');">Cancel</button>
			<button name='order' type='submit' onclick= "return confirm('Are You Sure?');">Order</button>
		</form>

<body   style="background: url(images/drug.jpg);background-size: 1550px 800px;background-repeat: no-repeat;">
	
</body>
</html>
