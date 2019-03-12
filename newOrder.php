<?php session_start() ?>
<?php require_once("inc/connection.php"); ?>
<?php require_once("inc/functions.php"); ?>
<?php require_once('inc/class.php') ?>

<?php
?>
<?php
$name = '';
$quantity = '';
$brand = '';
if (isset($_GET['name']) && isset($_GET['brand'])) {
    $name = $_GET['name'];
    $brand = $_GET['brand'];
}
if (isset($_POST['order'])) {
    $errors = array();
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $brand = $_POST['brand'];
    $date = date('Y-m-d');

    // checking required fields
    $reqFields = array('name', 'quantity', 'brand');
    $errors = array_merge($errors, checkReqFields($reqFields));

    // check max len fields
    $maxLenFields = array('name' => 100, 'quantity' => 100, 'brand' => 100);
    $errors = array_merge($errors, checkMaxLenFields($maxLenFields));

    if (empty($errors)) {
        $name = mysqli_real_escape_string($connection, $_POST['name']);
        $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
        $brand = mysqli_real_escape_string($connection, $_POST['brand']);

        $item = new Item($name, $quantity, $brand);
        $itemObject = serialize($item);
        $query = "INSERT INTO orders(order_date,name,order_obj,order_status, is_deleted) VALUES('{$date}','{$name}','{$itemObject}','Pending',0)";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            echo "Database query failed";
        } else {
            header('Location:orders.php?msg=Insert_successful');
        }
    }
}
?>


<!DOCTYPE html>

<head>
    <title>Order</title>
    <?php include("inc/pharmacyheader.php"); ?>


<div class="topnav"><h2>Orders</h2>
    <div class="topnav-right">
        <a href="orders.php">Home</a>   
        <a class="active" href="#newOrder">New</a>
        <a href="pharmacyDashboard.php">Dashboard</a>
    </div>
</div>



<form action='newOrder.php' method='post'>
    <div class="formContainer">
        <p>Please fill the information to order an item to the inventory.</p>

        <?php
        if (isset($errors) && !empty($errors)) {
            printErrors($errors);
        }
        ?>

        <hr>
        <label for=""><b>Name:</b></label>
        <input type="text" name="name" placeholder="Enter the name of the medicine" <?php echo 'value="' . $name . '"'; ?> required>

        <label for=""><b>Quantity:</b></label>
        <input type="number" name="quantity" placeholder="Enter the quantity of the medicine" <?php echo 'value="' . $quantity . '"'; ?> required>

        <label for=""><b>Brand:</b></label>
        <input type="text" name="brand" placeholder="Enter the brand of the medicine" <?php echo 'value="' . $brand . '"'; ?> required>

        <hr>

        <div class="clearfix">
            <button type="button" class="cancelbtn" name="cancel" onclick="location = 'orders.php'">Cancel</button>
            <button type="submit" class="insertbtn" name="order" >Order</button>
        </div>

</form>
</div>

</body> 


