<?php
//load messages
$unread = 0;
$query = "SELECT * FROM messages WHERE recieverType='doctor' AND (recieverId='{$doctorid}' OR recieverId=99999)";
$resultSet = mysqli_query($connection, $query);
verifyQuery($resultSet);
while ($message = mysqli_fetch_assoc($resultSet)) {
    if ($message['isRead'] == 0) {
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
   