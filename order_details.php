<!DOCTYPE HTML>
<?php
session_start();
require_once("storeHeader.html");
include("dbconn.php");
?>

<td id='info' colspan='5'>

<?php
db_connect();
$orderID = $_GET['orderID'];
displayOrder($connection, $orderID);
displayOrderDetails($connection, $orderID);
echo "<a href='orders.php'>
        <input id='submit' type='button' value='Back to Orders'>
      </a>";
db_close();

function displayOrder($connection, $orderID) {

    $query = "SELECT ShippingCost, Tax, Total, OrderDate FROM Orders WHERE OrderID='$orderID'";
    $result = mysqli_query($connection, $query);
    $date = get_mysqli_result($result, 0, "OrderDate");
    $shipping = get_mysqli_result($result, 0, "ShippingCost");
    $tax = get_mysqli_result($result, 0, "Tax");
    $total = get_mysqli_result($result, 0, "Total");

    echo "  <H2>Order Number - $orderID</H2>
            <table id='infoTable'>
            <tr>
            <td id='priceInfo'>
               <p>Order Date: </p>
               <p>Shipping Cost: </p>
               <p>Shipping + Handling: </p>
               <p>Order Total: </p>
            </td>
            <td id='priceInfo'>
                <p>$date</p>
                <p>$$shipping</p>
                <p>$$tax</p>
                <p>$$total</p>
            </td>
        </tr> 
        </table>";
}

function displayOrderDetails($connection, $orderID) {
    $query = "SELECT ProductID, Quantity, LineTotal FROM OrderDetails WHERE OrderID='$orderID'";
    $result = mysqli_query($connection, $query);
    $numCat = mysqli_num_rows($result);

    echo "<table id ='infoTable'>
    <tr>
      <th id='itemHeader'>Product Description</th>
      <th id='itemHeader'>Product ID</th>
      <th id='itemHeader'>Quantity</th>
      <th id='itemHeader'>Line Total</th>
    </tr>";
    // echo "$numCat";
    for ($i = 0; $i < $numCat; $i++) {
        $productID = get_mysqli_result($result, $i, "ProductID");
        $quantity =  get_mysqli_result($result, $i, "Quantity");
        $lineTotal =  get_mysqli_result($result, $i, "LineTotal");
        //find real name of the product from its productID
        $productNameQuery = "SELECT Name FROM Products WHERE ProductID='$productID'";
        $queryResult = mysqli_query($connection, $productNameQuery);
        $productName = get_mysqli_result($queryResult, 0, "Name");
        
        // echo "$productName, $producstID, $quantity, $lineTotal";

        echo "  <tr>
                    <td id='item'><p>$productName</p></td>
                    <td id='item'><p>$productID</p></td>
                    <td id='item'><p>$quantity</p></td>
                    <td id='item'><p>$lineTotal</p></td>
                </tr>";
    }   
    echo "</table>";     
}

include_once("storeFooter.html");
?>

<style>
    #info {
        text-align: center;
        padding: 24px;
    }
    h3 {
        color: white;
    }
   
    p, td {
        color: white;
        text-align: center;
    }
</style>

