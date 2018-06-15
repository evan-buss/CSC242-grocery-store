<!DOCTYPE HTML>

<?php
session_start();
require_once("storeHeader.html");
?>

<tr>
    <td> Categories</td>
    <td colspan="4">
        <form id="searchForm" method="GET" action="browse.php">
            Product Search &nbsp;
            <input type="text" name="search">
            <input type="submit" value="Search">
        </form>
    </td> 
</tr>

<?php
include('dbconn.php');
db_connect();
//var_dump($_SESSION);

$categoryArray = array();
//if the user has selected an option, change the contents of the page
if (count($_GET) > 0) {
    //user selects a product category so display all elements of that category
    if (isset($_GET['categoryID'])) {   
        $categoryID = $_GET['categoryID'];
        displayCategories($connection, $categoryArray, $categoryID - 1);
        echo "<td id='categoryView' colspan='4'>";
        $query = "SELECT Name, ProductID, Price, Quantity, CategoryID
                  FROM Products 
                  WHERE CategoryID=$categoryID";
        displayItemSelectionForm($connection, $categoryID, $categoryArray, $query);
        echo "</td>";

    //user enters a search so display the results
    } else if (isset($_GET['search'])) {    
        $searchString = $_GET['search'];
        displayCategories($connection, $categoryArray, 0);
        echo "<td id='categoryView' colspan='4'>";
        $query = "SELECT Name, ProductID, Price, Quantity, CategoryID
                  FROM  Products 
                  WHERE Name 
                  LIKE '%$searchString%'";
        displayItemSelectionForm($connection, -1, $categoryArray, $query);
        echo "</td>";
    }

} else {
    //the first time user enters page display the defaults
    //ie. category selection and prompt to select a category
    displayCategories($connection, $categoryArray);
    echo "<td id='categoryView' colspan='4'>
            <h2> Product Inventory</h2>
            <p>Select a Category!</p>
          </td>";
}

//this will display the results of a query to the database in a form
function displayItemSelectionForm($connection, $categoryID, $categoryArray, $query)
{
    $result = mysqli_query($connection, $query);
    $numCat = mysqli_num_rows($result);

    if ($numCat == 0) {
        echo "<H2>Sorry, we don't have that product.</H2>";
    } else {
        for ($i = 0; $i < $numCat; $i++) {
            $name = get_mysqli_result($result, $i, "Name");
            $productID = get_mysqli_result($result, $i, "ProductID");
            $price = get_mysqli_result($result, $i, "Price");
            $quantity = get_mysqli_result($result, $i, "Quantity");

            if ($i == 0) {
                if ($categoryID == -1) {
                    echo "<H2>Search Results</H2>";
                } else {
                    echo "<H2>$categoryArray[$categoryID]</H2>";
                }
                echo "<table id='infoTable'>";
                echo "<tr>
                    <th id='itemHeader'>Product Name</th>
                    <th id='itemHeader'>ID</th>
                    <th id='itemHeader'>Price</th>
                    <th id='itemHeader'>Available</th>
                    <th id='itemHeader'>Quantity</th>
                  </tr>";
                echo "<form method='post' action='cart.php'>";
            }
            echo "<tr>
                <td id='item'>$name</td>
                <td id='item'>$productID</td>
                <td id='item'>$$price</td>
                <td id='item'>$quantity</td>
                <td id='item'>
                <input id='numField' type='number' name=$productID min='0' 
                    max='25' value='0'>
                </td>
                </tr>";

                // <input id='numField' type='number' name=$productID min='0' 
                // max=$quantity value='0'>
        }
        echo "</table>
          <input id='submit' type='submit' value='Add to Cart'>
          </form>";
    }
}


//outputs the current database categories in the form of links
//  each link takes you to a browse page with that category
function displayCategories($connection, &$categoryArray, $defaultValue = 0)
{
    echo "<td>
            <div id='categoryDiv'>";

    $query = "SELECT CategoryName, CategoryID FROM Categories";
    $result = mysqli_query($connection, $query);
    $numCat = mysqli_num_rows($result);

    for ($i = 0; $i < $numCat; $i++) {
        $categoryName = get_mysqli_result($result, $i, "CategoryName");
        $categoryID = get_mysqli_result($result, $i, "CategoryID");

        $categoryArray[$categoryID] = $categoryName;
        echo "<p><a href='browse.php?categoryID=$categoryID'>$categoryName</a></p>";

    }
    echo "</div>
          </td>";
}

db_close();
require_once("storeFooter.html");
?>

<style type="text/css">
    
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
        margin: 0 auto;
        display: block;
        border: none;
        background-color: #44475a;
    }

    #categoryDiv {
        text-align: center;
        margin: 20px;
    }

    p, td {
        color: white;
        text-align: center;
    }
    </style>