<!DOCTYPE HTML>

<?php
session_start();
require_once("storeHeader.html");
include("dbconn.php");
?>

<td id='info' colspan="5">
    

    <?php
    if (isset($_SESSION['customerID'])) {
        if (!isset($_SESSION['cart_items']) or count($_SESSION['cart_items']) == 0){
            echo "<H2>Checkout</H2>";
            echo "<h3>Nothing in your cart!</h3>";
        }else {
            echo "<H2>Checkout</H2>";
            db_connect();
            displayCartItems($connection);
            db_close();
        }
    } else {
            echo "  <H2>You are not logged in!</H2>
                    <H3>Please log in to checkout.</H3>
                    <a href='store.php'>
                    <input id='submit' type='button' value='Home'></a>
                    <a href='login.php'>
                    <input id='submit' type='button' value='Login'>
                    </a>";
    }

    //Reads all items from the cart session variable and outputs them in a list
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
            $_SESSION['orderDetails'][$itemID] = $lineTotal * $userQuantity;
            
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
        echo "</form><!--</table>-->";
        $basicPrice = round($basicPrice, 2);
        $shippingCost = calculateShipping($totalQuantity);
        $_SESSION['order']['shippingCost'] = $shippingCost;
        $tax = round($basicPrice * .06, 2);
        $_SESSION['order']['tax'] = $tax;
        $totalPrice = round($tax + $basicPrice + $shippingCost, 2);
        $_SESSION['order']['total'] = $totalPrice;
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

        echo "  <a href='post_order.php'>
                    <input id='submit' type='button' value='Confirm Order'>
                </a>";
        echo "  <a href='cart.php'>
                    <input id='submit' type='button' value='Go Back'>
                </a>";

    }

    //Calculates the shipping cost based on the ammount of items in the cart
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

    ?>
    
    <style type="text/css">
        form {
            margin: 0 auto;
            display: block;
            border: none;
            background-color: #44475a;
            text-align: left;
        }

        #info {
            text-align: center;
            padding: 24px;
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


<?php
require_once("storeFooter.html");