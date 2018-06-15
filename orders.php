<!DOCTYPE HTML>
<?php
session_start();
require_once("storeHeader.html");
include("dbconn.php");
?>

<td id='info' colspan='5'>
    

<?php
    if (isset($_SESSION['customerID'])) {
        echo"<H2>Your Orders</H2>";
        db_connect();
        displayOrders($connection);
        db_close();
    } else {
            echo "  <H2>You are not logged in!</H2>
                    <H3>Please log in to see your past orders.</H3>
                    <a href='store.php'>
                    <input id='submit' type='button' value='Home'></a>
                    <a href='login.php'>
                    <input id='submit' type='button' value='Login'>
                    </a>";
    }

    //displays all the orders found on the customers account
    //user can click on the order number link to see more details
    function displayOrders($connection){
        $query = "SELECT OrderID, OrderDate, ShippingCost, Tax, Total from Orders WHERE CustomerID='" . $_SESSION["customerID"] . "'";
        $result = mysqli_query($connection, $query);
        $numCat = mysqli_num_rows($result);    

        if ($numCat == 0) {
            echo"<H3>You do not have any orders!</H3>";
        } else {
            echo "<table id ='infoTable'>
            <tr>
                <th id='itemHeader'>Order Number</th>
                <th id='itemHeader'>Order Date</th>
                <th id='itemHeader'>Shipping + Handling</th>
                <th id='itemHeader'>Tax</th>
                <th id='itemHeader'>Total Cost</th>
            </tr>";
        }

        for ($i = 0; $i < $numCat; $i++) {
            $orderID = get_mysqli_result($result, $i, "OrderID");
            $orderDate = get_mysqli_result($result, $i, "OrderDate");
            $shipping = get_mysqli_result($result, $i, "ShippingCost");
            $tax = get_mysqli_result($result, $i, "Tax");
            $total = get_mysqli_result($result, $i, "Total");

            
            echo "  <tr>
                        <td id='item'><a id='orderLink' href='order_details.php?orderID=$orderID'>$orderID</a></td>
                        <td id='item'><p>$orderDate</p></td>
                        <td id='item'><p>$$shipping</p></td>
                        <td id='item'><p>$$tax</p></td>
                        <td id='item'><p>$$total</p></td>
                    </tr>";
        }
        echo "</table>";
    }
    require_once("storeFooter.html");
    ?>

<style>
    #info {
        text-align: center;
        padding: 24px;
    }

    #orderLink {
        font-size: 18px;
        color: #50fa7b;
    }

    p, td {
        color: white;
        text-align: center;
    }
</style>