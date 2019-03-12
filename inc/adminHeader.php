<?php 
 	$adminid = $_SESSION['adminid'];
 	$query = "SELECT * FROM admins WHERE id='{$adminid}' LIMIT 1";
 	$result = mysqli_query($connection,$query);
 	verifyQuery($result);
 	$admin = mysqli_fetch_assoc($result);
 	$adminObj = $admin['object'];
 	$adminObj = unserialize($adminObj);
 	$name = $adminObj->getFullName();
  ?> 
<?php
  	//load messages
  	$unread = 0;
  	$query = "SELECT * FROM messages WHERE recieverType='admin' AND (recieverId='{$adminid}' OR recieverId=99999)";
  	$resultSet = mysqli_query($connection,$query);
  	verifyQuery($resultSet);
  	while($message = mysqli_fetch_assoc($resultSet)){
  		if($message['isRead']==0){
  			$unread+=1;
  		}
  	}
    ?>
<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<header>
        <h1>Vertex Medical Center</h1>
        <p style="color: #cccccc; float:right;">Welcome <?php echo $name; ?>! <a href="logout.php">Log Out</a></p> 
    </header>
