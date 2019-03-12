<?php session_start() ?>
<?php require_once("inc/connection.php"); ?>
<?php require_once("inc/functions.php"); ?>
<?php require_once('inc/class.php') ?>

<?php
$name = '';
$quantity = '';
$brand = '';
$price = '';

if (isset($_POST['insert'])) {
    $errors = array();
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    
    // checking required fields
    $reqFields = array('name', 'quantity', 'brand', 'price');
    $errors = array_merge($errors, checkReqFields($reqFields));

    // check max len fields
    $maxLenFields = array('name' => 100, 'quantity' => 100, 'brand' => 100);
    $errors = array_merge($errors, checkMaxLenFields($maxLenFields));

    if (empty($errors)) {
        $name = mysqli_real_escape_string($connection, $_POST['name']);
        $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
                $brand = mysqli_real_escape_string($connection, $_POST['brand']);
$price = mysqli_real_escape_string($connection, $_POST['price']);
        echo "Done";
        $item = new Item($name, $quantity, $brand, $price);
        $itemObject = serialize($item);
        $query = "INSERT INTO inventory(name,object,is_deleted, is_ordered) VALUES('{$name}','{$itemObject}',0,0)";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            echo "Database query failed";
        } else {
            header('Location:inventory.php?msg=Insert_successful');
        }
    }
}
?>


<!DOCTYPE html>

<head>
    <title>Inventory</title>
    <?php include("inc/pharmacyheader.php"); ?>


<div class="topnav"><h2>Inventory</h2>
    <div class="topnav-right">
        <a href="inventory.php">Home</a>   
        <a class="active" href="#new">New</a>
        <a href="pharmacyDashboard.php">Dashboard</a>
    </div>
</div>


<form action='new.php' method='post'>
    <div class="formContainer">
        <p>Please fill the information to add new item to the inventory.</p>

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
 
        <label for=""><b>Price(Rs/=):</b></label>
        <input type="number" name="price" placeholder="Enter the price of the medicine" <?php echo 'value="' . $price . '"'; ?> required>

        <hr>

        <div class="clearfix">
            <button type="button" class="cancelbtn" name="cancel" onclick="location = 'inventory.php'">Cancel</button>
            <button type="submit" class="insertbtn" name="insert" >Insert</button>
        </div>

</form>
</div>

    <?php include("inc/footer.php"); ?>

