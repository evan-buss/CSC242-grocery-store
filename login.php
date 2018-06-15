<!DOCTYPE HTML>

<?php require_once("storeHeader.html"); ?>

<td colspan=5>
    <h2>Login</h2>
    <form id="loginForm" method="post" action="post_login.php">
        <p>
            <label>Email Address:
                <input name="email" type="text" size="25"/>
            </label>
        </p>
        <p>
            <label>Password:
                <input name="password" type="password" size="25"/>
            </label>
        </p>
        <p>
            <input type="submit" value="Log In"/>
            <input type="reset" value="Reset"/>
        </p>
    </form>
</td>

<script>
    function emailValidation() {
        document.getElementById("loginForm").addEventListener("submit",
            function (e) {
                if (document.forms["loginForm"]["email"].value === "") {
                    alert("Email cannot be blank!");
                    e.preventDefault();
                } else if (validateEmail(document.forms["loginForm"]["email"].value) != true) {
                    alert("Not a valid email adress!");
                    e.preventDefault();
                }
            }, false);    
    }

    function validateEmail(email) 
    {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

   function passwordValidation() {
        document.getElementById("loginForm").addEventListener("submit",
            function (e) {
                if (document.forms["loginForm"]["password"].value === "") {
                    alert("You need to enter a password!");
                    e.preventDefault();
                } 
            }, false);
     }

    document.getElementById("loginForm").addEventListener("reset",
        function (e) {
            if (!confirm("Are you sure you want to clear your information?")) {
                e.preventDefault();
            }
        }, false);

    window.addEventListener("load", emailValidation, false);
    window.addEventListener("load", passwordValidation, false);
</script>

<?php require_once("storeFooter.html"); ?>
