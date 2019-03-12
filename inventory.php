<?php session_start() ?>
<?php require_once("inc/connection.php"); ?>
<?php require_once("inc/functions.php"); ?>
<?php require_once('inc/class.php') ?>

<?php
$itemList = '';
$query = "SELECT * FROM inventory WHERE is_deleted=0";
$resultSet = mysqli_query($connection, $query);
verifyQuery($resultSet);

while ($item = mysqli_fetch_assoc($resultSet)) {
    $itemObject = $item['object'];
    $itemObject = unserialize($itemObject);
    $id = $item['item_id'];
    $name = $itemObject->getName();
    $quantity = $itemObject->getQuantity();
    $brand = $itemObject->getBrand();
    $price = $itemObject->getPrice();
    $itemList.="<tr>";
    $itemList.="<td>{$name}</td>";
    $itemList.="<td>{$quantity}</td>";
    $itemList.="<td>{$brand}</td>";
    $itemList.="<td>{$price}</td>";

    $itemList.="<td><button type=\"button\" class=\"editbtn\" name=\"edit\" onclick=\"location = 'EditDrugDetails.php?id={$id}'\">Edit</button></td>";
    $itemList.="<td><button type=\"button\" class=\"editbtn\" name=\"remove\" onClick=\"if(confirm('Are you sure you want to order?')){location.href='newOrder.php?name={$name}&brand={$brand}'}\">Order</button></td>";
    $itemList.="<td><button type=\"button\" class=\"removebtn\" name=\"remove\" onClick=\"if(confirm('Are you sure you want to delete?')){location.href='RemoveDrug.php?id={$id}'}\">Remove</button></td></tr>";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Inventory</title>
        <?php include("inc/pharmacyheader.php"); ?>
    <div class="topnav"><h2>Inventory</h2>

   
        <div class="topnav-right">
            <a class="active" href="#home">Home</a>
            <a href="new.php">New</a>
            <a href="pharmacyDashboard.php">Dashboard</a>
        </div>
    </div>

    <table>
        <tr>
            <th>Drug Name</th>
            <th>Quantity</th>
            <th>Brand</th>
            <th>Price</th>
            <th>Edit Details</th>
            <th>Order Drug</th>
            <th>Remove Drug</th>
        </tr>
        <?php echo $itemList ?>
    </table>
    <p><br><br></p>
    <p><br><br></p>
    <p><br><br></p>
    <p><br></p>


    <?php include("inc/footer.php"); ?>