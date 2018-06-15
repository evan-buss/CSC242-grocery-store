<!DOCTYPE HTML>

<?php
session_start();
session_destroy();
//might have to delete the cookie as well, not sure.
setcookie("PHPSESSID", "", time()-3600);
?>

<?php require_once("storeHeader.html"); ?>

<td id="info" colspan="5">
    <H2>Thanks for shopping at Kutztown Generic Grocery Store&#169;!</H2>
    <H3>Come back soon!</H3>
</td>


<style type="text/css">
    #info {
        text-align: center;
        padding: 20px;
    }
</style>


<?php require_once("storeFooter.html");
