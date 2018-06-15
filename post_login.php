<!DOCTYPE HTML>
<?php
session_start();
require_once("storeHeader.html");
include('dbconn.php');
db_connect();
// var_dump($_POST);
if (count($_POST) == 2) {
    //posted from login page, so check the database for matching email password pair
    $query = "SELECT CustomerID, FirstName FROM Customers WHERE Email='" . $_POST['email'] . "'
                                         and Passwd='" . $_POST['password'] . "'";
    $result = mysqli_query($connection, $query);
    $customerID = get_mysqli_result($result, 0, "CustomerID");
    //email exists, log them in
    if ($customerID != null) {  //user has a customerID assigned to their account
        //make their customerID a session variable
        //the customerID will be used to make sure that a user is logged in
        $_SESSION['customerID'] = $customerID;
        $name = get_mysqli_result($result, 0, "FirstName");
        echo "<td id='info' colspan='5'>
                <H2>Welcome back, $name!</H2>
                <a href='store.php'>
                <input id='submit' type='button' value='Home'></a>
                <a href='browse.php'>
                <input id='submit' type='button' value='Start Shopping'>
                </a>
            </td>";
    } else { //returns false if not found, display error
        echo "<td id='info' colspan='5'>
            <H2>Account not found!</H2>
            <a href='login.php'>
            <input id='submit' type='button' value='Try Again'></a>
            <a href='create_account.php'>
            <input id='submit' type='button' value='Create Account'>
            </a>
        </td>";
    }
} else {    //got here from account creation, so create new user in the database
    $phoneNumber = $_POST["phone1"] . $_POST["phone2"] . $_POST["phone3"];
    if ($_POST['address2'] == "") {
        $address2 = '';
    }else {
        $address2 = $_POST['address2'];
    }
   
    
    //check if the email already exists in the table before creating a new account.
    // $query = "SELECT CustomerID FROM Customers WHERE Email='" . $_POST['email'] . "'";
    $query = "SELECT CustomerID, FirstName FROM Customers WHERE Email='" . $_POST['email'] . "'";
    $result = mysqli_query($connection, $query);
    $customerID = get_mysqli_result($result,0,"CustomerID");

    if($customerID != null){
        echo "<td id='info' colspan='5'>
                <H2>Account with that email already exists!</H2>
                <a href='login.php'>
                <input id='submit' type='button' value='Try Again'></a>
                <a href='create_account.php'>
                <input id='submit' type='button' value='Create Account'>
                </a>
             </td>";
    } else {
        //customerID not found for that email, so add a new customer
        $query = "INSERT INTO Customers (Email, Passwd, FirstName, LastName, Address1,
                                        Address2, ZipCode, State, PhoneNumber, City)
                    VALUES (  '" . $_POST["email"] . "',
                        '" . $_POST["password"] . "',
                        '" . $_POST["first_name"] . "',
                        '" . $_POST["last_name"] . "',
                        '" . $_POST["address1"] . "',
                        ' $address2 ',
                        '" . $_POST["zip_code"] . "',
                        '" . $_POST["state"] . "',
                        ' $phoneNumber ',
                        '" . $_POST["city"] . "')";
        mysqli_query($connection, $query);

        //then make their customerID a session variable 
        $query = "SELECT CustomerID FROM Customers WHERE Email='" . $_POST['email'] . "'";
        $result = mysqli_query($connection, $query);
        $customerID = get_mysqli_result($result, 0, "CustomerID");
        $_SESSION['customerID'] = $customerID;

        echo "<td id='info' colspan='5'>
                <H2>Welcome to Kutztown Generic Grocery, " . $_POST["first_name"] . "!</H2>
                <a href='store.php'>
                <input id='submit' type='button' value='Home'></a>
                <a href='browse.php'>
                <input id='submit' type='button' value='Start Shopping'></a>
            </td>";
    }
}
db_close(); 
require_once("storeFooter.html");
?>

<style type="text/css">
#info {
    text-align: center;
    padding: 24px;
}

a {
    color: #f8f8f2;
    /* font-size: 20px; */
}
</style>