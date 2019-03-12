<?php session_start() ?>
<?php require_once("inc/connection.php"); ?>
<?php require_once("inc/functions.php"); ?>
<?php require_once('inc/class.php') ?>

<?php
$name = '';
$quantity = '';
$brand = '';
$price = '';

if (isset($_POST['submit'])) {
    $errors = array();
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $brand = $_POST['brand'];

// checking required fields
    $reqFields = array('name', 'quantity');
    $errors = array_merge($errors, checkReqFields($reqFields));

// check max len fields
    $maxLenFields = array('name' => 100, 'quantity' => 100, 'brand' => 100);
    $errors = array_merge($errors, checkMaxLenFields($maxLenFields));

    if (empty($errors)) {
        $name = mysqli_real_escape_string($connection, $_POST['name']);
        $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
        $brand = mysqli_real_escape_string($connection, $_POST['brand']);

        $query = "SELECT * FROM inventory WHERE name='{$name}'";

        $resultSet = mysqli_query($connection, $query);
        verifyQuery($resultSet);

        if (!empty($resultSet)) {
            echo"<script type=\"text/javascript\">
                    var x=window.confirm(\"Sorry! The product does not exist in the inventory! Try Again?\")
                    if (x)
                        window.location = \"invoice.php?msg=invoice retry\";
                    else
                        window.location = \"pharmacyDashboard.php?msg=invoice cancelled\";                
                </script>";
        } else {
            header('Location:bill.php?msg=finalinvoice');
        }
    }

    while ($item = mysqli_fetch_assoc($resultSet)) {
        $itemObject = $item['object'];
        $itemObject = unserialize($itemObject);
        $id = $item['item_id'];
//$quantity = $itemObject->getQuantity();
        $brand = $itemObject->getBrand();
        $price = $itemObject->getPrice();
    }
}
?>


<!DOCTYPE html>

<head>
    <title>Inventory</title>
    <?php include("inc/pharmacyheader.php"); ?>


<div class="topnav"><h2>Invoice</h2>
    <div class="topnav-right">
        <a class="active" href="#home">Home</a>   
        <!--<a href="new">New</a>-->
        <a href="pharmacyDashboard.php">Dashboard</a>
    </div>
</div>


<form action='invoice.php' method='post'>
    <div class="formContainer">
        <p>Please fill the information create an invoice.</p>

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
        <input type="text" name="brand" placeholder="Enter the brand of the medicine" <?php echo 'value="' . $brand . '"'; ?> >

        <hr>

        <div class="clearfix">
            <button type="button" class="cancelbtn" name="cancel" onclick="location = 'invoice.php'">Cancel</button>
            <button type="submit" class="insertbtn" name="submit" >Submit</button>
        </div>

</form>
</div>
<?php include("inc/footer.php"); ?>

