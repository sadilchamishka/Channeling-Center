
<?php
//load messages
$unread = 0;
$query = "SELECT * FROM messages WHERE recieverType='user' AND (recieverId='{$userid}' OR recieverId=99999)";
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
<!--    <p style="float:right"><a href="userInbox.php" class="badge badge-light">Messages </a><span class="badge badge-pile badge-light"><?php echo $unread ?></span>-->


        <!--    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>-->
        
<!--</nav>-->
