<?php session_start() ?>
<?php require_once("inc/connection.php"); ?>
<?php require_once("inc/functions.php"); ?>
<?php require_once('inc/class.php') ?>

<?php
$itemList = '';
$query = "SELECT * FROM orders WHERE order_status='Pending' AND is_deleted=0 ORDER BY order_date ASC";
$resultSet = mysqli_query($connection, $query);
verifyQuery($resultSet);

while ($item = mysqli_fetch_assoc($resultSet)) {
    $itemObject = $item['order_obj'];
    $itemObject = unserialize($itemObject);
    $date = $id = $item['order_date'];
    $id = $item['order_id'];
    $status = $item['order_status'];
    $name = $itemObject->getName();
    $quantity = $itemObject->getQuantity();
    $brand = $itemObject->getBrand();
    $itemList.="<tr>";
    $itemList.="<td>{$date}</td>";
    $itemList.="<td>{$id}</td>";
    $itemList.="<td>{$name}</td>";
    $itemList.="<td>{$quantity}</td>";
    $itemList.="<td>{$brand}</td>";
    $itemList.="<td>{$status}</td>";
    $itemList.="<td><button type=\"button\" class=\"removebtn\" name=\"remove\" onClick=\"if(confirm('Are you sure you want to cancel order?')){location.href='removeOrder.php?id={$id}'}\">Remove</button></td></tr>";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Orders</title>
    <?php include("inc/pharmacyheader.php"); ?>


    <div class="topnav"><h2>Orders</h2>
        <div class="topnav-right">
            <a class="active" href="#home">Home</a>
            <a href="newOrder.php">New</a>
            <a href="pharmacyDashboard.php">Dashboard</a>
        </div>
    </div>
    <div id="formContainer">
    <table>
        <tr>
            <th>Order Date</th>
            <th>Order Number</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Brand</th>
            <th>Order Status</th>
            <th>Remove Order</th>
        </tr>
        <?php echo $itemList ?>
    </table>
    </div>
    <p><br><br></p>
    <p><br><br></p>
    <p><br><br></p>
    <p><br></p>


    <?php include("inc/footer.php"); ?>