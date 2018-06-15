<!DOCTYPE HTML>

<?php
session_start();
require_once("storeHeader.html");
include("dbconn.php");
?>
<td colspan='5'>
    <H2>Your Cart</H2>

    <?php
    db_connect();
    // var_dump($_POST);
    //if value of the first array element is Remove, run the remove logic
    if (reset($_POST) == "Remove") {
        echo "remove";
        removeCartItem();
        displayCartItems($connection);
    } else {
        postToCart();
        if (!isset($_SESSION['cart_items']) or count($_SESSION['cart_items']) == 0) {
            echo "<h3>Nothing in your cart!</h3>";
        } else {
            displayCartItems($connection);
        }
    }
    db_close();


    function removeCartItem()
    {
        $name = key($_POST);
        foreach ($_SESSION['cart_items'] as $itemName => $itemValue) {
            if ($name == $itemName) {
                unset($_SESSION['cart_items'][$itemName]);
            }
        }
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
                <td id='item'>
                    <input type='submit' name=$itemID value='Remove'>
                </td>
              </tr>";
        }
        echo "</form><!--</table>-->";
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

        echo "<a href='checkout.php'>
        <input id='submit' type='button' value='Checkout'>
      </a>";
        echo "<a href='browse.php'>
        <input id='submit' type='button' value='Go Back'>
        </a>";
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

    //<input id='submit' name=$itemID type='submit' content='Button' value='Remove'>
    //append post values to cart session array
    //only if the item does not exist already,
    //if it does, increase the item's count
    function postToCart()
    {
        foreach ($_POST as $postItemName => $postItemValue) {
            if ($postItemValue != 0) {
                if (isset($_SESSION['cart_items'][$postItemName])) {
                    $_SESSION['cart_items'][$postItemName] += $postItemValue;
                } else {
                    $_SESSION['cart_items'][$postItemName] = $postItemValue;
                }
            }
        }
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
    ?>
