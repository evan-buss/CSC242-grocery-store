<?php
$connection;
// connect to the bookstore DB
function db_connect($DB_USER = "", $DB_PASS = "")
{
    $DB_NAME = "grocerystore_s18";
    $DB_HOST = "localhost";

    $DB_USER = "ebuss376";
    $DB_PASS = "pra2tegerApr";

    // global keyword required to make variable have global scope
    global $connection;

    $connection = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME)
    or die("<h2>Cannot connect to <i>&#39;$DB_HOST&#39;</i> "
        . "as <i>&#39;$DB_USER&#39;</i></h2>");
}  // end function db_connect


// close connection to bookstore DB
function db_close()
{
    global $connection;
    mysqli_close($connection);
}  // end function db_close

// replacement for mysql_result
function get_mysqli_result($result, $number, $field)
{
    mysqli_data_seek($result, $number);
    $row = mysqli_fetch_assoc($result);
    return $row[$field];
}
