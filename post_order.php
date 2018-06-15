<!DOCTYPE HTML>
<?php
session_start();
require_once("storeHeader.html");
include('dbconn.php');
?>

<td colspan='5'>

<?php
if (isset($_SESSION['customerID'])) {
    db_connect();
    echo "<H2>Order Confirmation</H2>";
    displayCartItems($connection);
    orderToDatabase($connection);
    cartToDatabase($connection);

    echo "  <a href='orders.php'>
                <input id='submit' type='button' value='View Orders'>
            </a>";


    db_close();
} else {
    echo "  <H2>You are not logged in!</H2>
            <H3>Please log in to checkout.</H3>
            <span>
            <a href='store.php'>Home</a> &nbsp;
            <a href='login.php'>Log In</a>
        </span>
        </td>";
}

function displayCartItems($connection)
{
    echo "<table id ='infoTable'>
    <tr>
        <th id='itemHeader'>Product Name</th>
        <th id='itemHeader'>Product ID</th>
        <th id='itemHeader'>Available</th>
        <th id='itemHeader'>Quantity</th>
        <th id='itemHeader'>Line Total</th>
    </tr>";

    $basicPrice = 0;
    $totalQuantity = 0;

    foreach ($_SESSION['cart_items'] as $itemID => $userQuantity) {
        $query = "SELECT Name, Quantity, Price, ProductID FROM Products WHERE ProductID='$itemID'";
        $result = mysqli_query($connection, $query);
        $productName = get_mysqli_result($result, 0, "Name");
        $quantityAvail = get_mysqli_result($result, 0, "Quantity");
        $lineTotal = get_mysqli_result($result, 0, "Price");

        $lineTotal *= $userQuantity;
        $basicPrice += $lineTotal;
        $totalQuantity += $userQuantity;

        echo "<form method='post' action='cart.php' >
            <tr>
            <td id='item'>$productName</td>
            <td id='item'>$itemID</td>
            <td id='item'>$quantityAvail</td>
            <td id='item'>$userQuantity</td>
            <td id='item'>$lineTotal</td>
        </tr>";
    }
    echo "</form>";
    // <!--</table>-->
    $basicPrice = round($basicPrice, 2);
    $shippingCost = calculateShipping($totalQuantity);
    $tax = round($basicPrice * .06, 2);
    $totalPrice = round($tax + $basicPrice + $shippingCost, 2);
    echo "<tr>
        <td id='priceInfo' colspan='2.5'>
        <p>Order Sub Total: </p>
        <p>Tax: </p>
        <p>Shipping + Handling: </p>
        <p>Order Total: </p>
        </td>
        <td id='priceInfo' colspan='2.5'>
            <p>$$basicPrice</p>
            <p>$$tax</p>
            <p>$$shippingCost</p>
            <p>$$totalPrice</p>
        </td>
    </tr> 
    </table>";
}

function calculateShipping($itemCount)
{
    if ($itemCount <= 10) {
        return 3.95;
    } else if ($itemCount >= 11 and $itemCount <= 15) {
        return 4.95;
    } else if ($itemCount >= 16 and $itemCount <= 20) {
        return 5.45;
    } else if ($itemCount > 20) {
        return 6.95;
    }
    return -1;
}

//add the price and order info from session to "Order" table
function orderToDatabase($connection)
{
    $shippingCost = $_SESSION['order']['shippingCost'];
    $tax = $_SESSION['order']['tax'];
    $total = $_SESSION['order']['total'];
    $customerID = $_SESSION['customerID'];

    // echo "$customerID, $shippingCost, $tax, $total, $orderDate";
    //CURRENT_TIMESTAMP() is an SQL function that gets the current time and date
    $query = "INSERT INTO Orders (CustomerID, ShippingCost, Tax, Total, OrderDate) 
              VALUES ('$customerID','$shippingCost', '$tax', '$total', CURRENT_TIMESTAMP())";
    if(mysqli_query($connection, $query)) {
        // echo "<h3>Order Added to Database</h3>";
    }else {
        echo "Error entering CustomerId, ShippingCost, Tax, Total, and Date to Orders table";
    } 
    
    //then get the order ID number for use by the cartToDataBase function
    $query = "SELECT OrderID from Orders WHERE CustomerID= '" . $_SESSION["customerID"] . "'";
    $result = mysqli_query($connection, $query);
    $num_rows = mysqli_num_rows($result);
    //if there is more than 1 result, get the latest (last) one.
    if ($num_rows > 1) {
        $_SESSION['orderID'] = get_mysqli_result($result,$num_rows-1,"OrderID");
    } else {    //otherwise take the first one
        $_SESSION['orderID'] = get_mysqli_result($result,0,"OrderID");
    }
    //done with the order's details -> remove them from session
    unset($_SESSION['order']);
    // var_dump($_SESSION);
}

//add all the items in the cart to the database
function cartToDatabase($connection)
{   
    // var_dump($_SESSION);
    $orderID = $_SESSION['orderID'];
    // echo"second part id: $orderID";
    //OrderDetails requires orderID, productID, Quantity, Linetotal
    foreach ($_SESSION['cart_items'] as $itemID => $userQuantity) {
        
        $lineTotal = $_SESSION['orderDetails'][$itemID];

        $query = "INSERT INTO OrderDetails (OrderID, ProductID, Quantity,LineTotal) 
              VALUES ('$orderID', '$itemID', '$userQuantity', '$lineTotal')";

        if(mysqli_query($connection, $query)) {
            // echo "<h3>Order Details Added to Database</h3>";
        }else {
            echo "Error entering OrderDetails, ProductID, Quantity, LineTotal to OrdersDetails table";
        } 
    }
    //at this point all the cart info is saved, so unset the session variables. at this point, everything is saved in the database
    unset($_SESSION['cart_items']);
    unset($_SESSION['orderDetails']);
    unset($_SESSION['orderID']);
    // var_dump($_SESSION);
}

require_once("storeFooter.html");
?>
<style type="text/css">
    form {
        margin: 0 auto;
        display: block;
        border: none;
        background-color: #44475a;
        text-align: left;
    }

    table {
        width: 90%;
    }

    #submit {
        margin-bottom: 2%;
        display: block:
    }

    #numField {
        max-width: 50px;
    }

    #searchForm {
        text-align: center;
    }

    #categoryForm {
        text-align: center;
    }

    p, td {
        color: white;
        text-align: center;
    }
</style>