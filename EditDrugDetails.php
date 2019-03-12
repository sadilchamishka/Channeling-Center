<?php session_start() ?>
<?php require_once("inc/connection.php"); ?>
<?php require_once("inc/functions.php"); ?>
<?php require_once('inc/class.php') ?>

<?php
if (isset($_GET['id'])) {
    $_SESSION['item_id'] = $_GET['id'];
}
?>
<?php
$item_id = $_SESSION['item_id'];

$query = "SELECT * FROM inventory WHERE item_id='{$item_id}' LIMIT 1";
$result = mysqli_query($connection, $query);
verifyQuery($result);
$item = mysqli_fetch_assoc($result);
$itemObj = $item['object'];
$itemObj = unserialize($itemObj);
$name = $itemObj->getName();
$quantity = $itemObj->getQuantity();
$brand = $itemObj->getBrand();
$price = $itemObj->getPrice();
?>



<?php
if (isset($_POST['save'])) {
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
        $itemObj->setName($name);
        $itemObj->setQuantity($quantity);
        $itemObj->setBrand($brand);
        $itemObj->setPrice($price);
        $itemObj = serialize($itemObj);

        $query = "UPDATE inventory SET name='{$name}',object='{$itemObj}' WHERE item_id='{$item_id}'";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            echo "Database query failed";
        } else {
            header('Location:inventory.php?msg=update_successful');
        }
    }
}
?>


<!DOCTYPE html>

<head>
    <title>Inventory</title>
    <?php include("inc/pharmacyheader.php"); ?>


<div class="topnav">Inventory
    <div class="topnav-right">
        <a class="active" href="#inventory">Home</a>   
        <a href="new.php">New</a>
        <a href="pharmacyDashboard.php">Dashboard</a>
    </div>
</div>


<form action='EditDrugDetails.php' method='post'>
    <div class="formContainer">
        <p>Please fill the information to edit this item in the inventory.</p>

        <?php
        if (isset($errors) && !empty($errors)) {
            printErrors($errors);
        }
        ?>

        <hr>
        <label for=""><b>Name:</b></label>
        <input type="text" name="name" <?php echo 'value="' . $name . '"'; ?> required>

        <label for=""><b>Quantity:</b></label>
        <input type="number" name="quantity" placeholder="Enter the quantity of the medicine" <?php echo 'value="' . $quantity . '"'; ?> required>

        <label for=""><b>Brand:</b></label>
        <input type="text" name="brand" placeholder="Enter the brand of the medicine" <?php echo 'value="' . $brand . '"'; ?> required>

        <label for=""><b>Price(Rs/=):</b></label>
        <input type="number" name="price" placeholder="Enter the price of the medicine" <?php echo 'value="' . $price . '"'; ?> required>

        <hr>

        <div class="clearfix">
            <button type="button" class="cancelbtn" name="cancel" onclick="location = 'inventory.php'">Cancel</button>
            <button type="submit" class="insertbtn" name="save" >Save</button>
        </div>

</form>
</div>

</body> 


