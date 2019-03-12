<?php session_start() ?>
<?php require_once("inc/connection.php"); ?>
<?php require_once('inc/class.php') ?>
<?php require_once("inc/functions.php"); ?>
<?php 
if(!isset($_SESSION['staffId'])){
    Location("logout.php");
}

    $staffid = $_SESSION['staffId'];
    $query = "SELECT * FROM pharmacystaff WHERE id='{$staffid}' LIMIT 1";
    $result = mysqli_query($connection,$query);
    verifyQuery($result);
    $staff = mysqli_fetch_assoc($result);
    $staffObj = $staff['object'];
    $staffObj = unserialize($staffObj );
    $name = $staffObj->getFullName();
  


 ?>
   

<!DOCTYPE html>
<html>
    <head>
        <p style="float:right"><font color="white">Welcome <?php echo $name ;?>!</font> <a href="logout.php"><font color="red">Log Out</font></a></p> 
        <title>Home</title>

    <?php include("inc/pharmacyheader.php"); ?>
    <img id="backgroundimg" src="images/background.jpg">
    <!--    <div class="w3-content w3-section" >
            <img class="mySlides w3-animate-top" src="images/two.jpg" style="width:100%;">
            <img class="mySlides w3-animate-bottom" src="images/2.jpg" style="width:100%;">
            <img class="mySlides w3-animate-top" src="images/8.jpg" style="width:100%;">
            <img class="mySlides w3-animate-bottom" src="images/4.jpg" style="width:100%;">
        </div>-->

<!--    <script>
        var myIndex = 0;
        carousel(); 

        function carousel() {
            var i;
            var x = document.getElementsByClassName("mySlides");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            myIndex++;
            if (myIndex > x.length) {
                myIndex = 1
            }
            x[myIndex - 1].style.display = "block";
            setTimeout(carousel, 2500);
        }
    </script>-->


    <div class="btn-group">
        <button onclick="location = 'invoice.php'" style="background-color: #000000; color: white" onmouseover="this.style = 'background-color:#0066cc; color: black';" onmouseout="this.style = 'background-color:#000000; color: white';">Invoice</button>
        <button onclick="location = 'inventory.php'" style="background-color: #333333; color: white" onmouseover="this.style = 'background-color:#0066cc; color: black';" onmouseout="this.style = 'background-color:#333333; color: white';">Inventory</button>
        <button onclick="location = 'orders.php'" style="background-color: #666666; color: black" onmouseover="this.style = 'background-color:#0066cc';" onmouseout="this.style = 'background-color:#666666';">Order</button>
        <button onclick="location ='messages.php'" style="background-color: #999999; color: black" onmouseover="this.style = 'background-color:#0066cc';" onmouseout="this.style = 'background-color:#999999';">Messages</button>
        <button onclick="location = 'modifyPharmacyStaff.php'" style="background-color: #cccccc; color: black" onmouseover="this.style = 'background-color:#0066cc';" onmouseout="this.style = 'background-color:#cccccc';">My Account</button>
    </div>
    <?php include("inc/footer.php"); ?>