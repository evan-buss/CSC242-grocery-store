<!DOCTYPE HTML>
<?php
session_unset();
?>
<?php require_once("storeHeader.html"); ?>

<td colspan="5">
    <h2>Create Account</h2>
    <form id="createAccountForm" method="post" action="post_login.php">
        <p>
            <label>First Name:
                <input name="first_name" type="text" size="25"/>
            </label>
        </p>
        <p>
            <label>Last Name:
                <input name="last_name" type="text" size="25"/>
            </label>
        </p>
        <br>
        <p>
            <label>Street Address:
                <input name="address1" type="text" size="25"/>
            </label>
        </p>
        <p>
            <label class="optional">Street Address 2:
                <input name="address2" type="text" size="25" value=""/>
            </label>
        </p>
        <p>
            <label>City:
                <input name="city" type="text" size="25"/>
            </label>
        </p>
        <p>
            <label>State:
                <!-- <input name="state" type="text" size="15"/> -->
                <select name="state">
                    <option value="PA">Pennsylvania</option>
                    <option value="NJ">New Jersey</option>
                    <option value="DE">Delaware</option>
                    <option value="OH">Ohio</option>
                    <option value="MD">Maryland</option>
                    <option value="WB">West Virginia</option>

                </select>
            </label>
            <label>Zip Code:
                <input name="zip_code" type="text" size="6"/>
            </label>
        </p>
        <br>
        <p>
            <label class="optional">Phone:
                <input name="phone1" type="text" maxlength="3" size="1"/>-
                <input name="phone2" type="text" maxlength="3" size="1"/>-
                <input name="phone3" type="text" maxlength="4" size="1"/>
            </label>
        </p>
        <p>
            <label>Email Address:
                <input name="email" type="text" size="25"/>
            </label>
        </p>
        <p>
            <label>Confirm Email Address:
                <input name="email_confirm" type="text" size="25"/>
            </label>
        </p>
        <br>
        <p id="pass_guidelines">Enter a password. (Minimum of 5 Characters)</p>
        <p>
            <label>Password:
                <input name="password" type="password" size="25"/>
            </label>
        </p>
        <p>
            <label>Confirm Password:
                <input name="password_confirm" type="password" size="25"/>
            </label>
        </p>

        <p>
            <input name="submit" type="submit" value="Create Account"/>
            <input name="reset" type="reset" value="Reset Form"/>
        </p>
    </form>
</td>

<script>
    var createAccountForm = document.forms["createAccountForm"];
    function nameValidation() {
        document.getElementById("createAccountForm").addEventListener("submit",
            function (e) {
                if (document.forms["createAccountForm"]["first_name"].value === "") {
                    alert("You need to enter a first name!");
                    e.preventDefault();
                } else if (document.forms["createAccountForm"]["last_name"].value === "") {
                    alert("You need to enter a last name!");
                    e.preventDefault();
                }
            }, false);
    }
    
    function phoneNumberValidation(){
        document.getElementById("createAccountForm").addEventListener("submit",
            function (e) {
                if (createAccountForm["phone1"].value !== "" ||
                    createAccountForm["phone2"].value !== "" ||
                    createAccountForm["phone3"].value !== "") {
                        if (validatePhoneNumber(createAccountForm["phone1"].value) != true) {
                            alert("Phone number can only contain numbers!");
                            e.preventDefault();
                        } else if (validatePhoneNumber(createAccountForm["phone2"].value) != true) {
                            alert("Phone number can only contain numbers!");
                            e.preventDefault();
                        } else if (validatePhoneNumber(createAccountForm["phone3"].value) != true) {
                            alert("Phone number can only contain numbers!");
                            e.preventDefault();
                        }
                    }
            }, false);
    }

    function addressValidation() {
        document.getElementById("createAccountForm").addEventListener("submit",
            function (e) {
                if (document.forms["createAccountForm"]["address1"].value === "") {
                    alert("You need to enter an address!");
                    e.preventDefault();
                } else if (document.forms["createAccountForm"]["city"].value === "") {
                    alert("You need to enter a city!");
                    e.preventDefault();
                } else if (document.forms["createAccountForm"]["zip_code"].value === "") {
                    alert("You need to enter a zip code!");
                    e.preventDefault();
                } else if (validateZipCode(document.forms["createAccountForm"]["zip_code"].value) != true) {
                    alert("Not a valid US zip code!");
                    e.preventDefault();
                }
            }, false);  
    }

    function emailValidation() {
        document.getElementById("createAccountForm").addEventListener("submit",
            function (e) {
                if (validateEmail(document.forms["createAccountForm"]["email"].value) != true) {
                    alert("Not a valid email adress!");
                    e.preventDefault();
                }else if (document.forms["createAccountForm"]["email"].value
                    !== document.forms["createAccountForm"]["email_confirm"].value) {
                    alert("Email addresses do not match!");
                    e.preventDefault();
                }
            }, false);    
    }

     function passwordValidation() {
        document.getElementById("createAccountForm").addEventListener("submit",
            function (e) {
                if (document.forms["createAccountForm"]["password"].value === "") {
                    alert("You need to enter a password!");
                    e.preventDefault();
                } else if (document.forms["createAccountForm"]["password"].value
                    !== document.forms["createAccountForm"]["password_confirm"].value) {
                    alert("Passwords do not match!");
                    e.preventDefault();
                } else if (document.forms["createAccountForm"]["password"].value.length < 5) {
                    alert("Password is too short!\n\nMinimum of 5 characters...");
                    e.preventDefault();
                }
            }, false);
     }

    function validateZipCode(zipcode) {
        //only accepts US formatted zipcodes
        var re = /(^\d{5}$)|(^\d{5}-\d{4}$)/; 
        return re.test(zipcode);
     }

    function validatePhoneNumber(input) {
        //only accepts numbers, no letters
        var re = /(^[0-9]*$)/;
        return re.test(input);
        
    }

    function validateEmail(email) {
        //requires an @ and . 
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

    document.getElementById("createAccountForm").addEventListener("reset",
        function (e) {
            if (!confirm("Are you sure you want to clear your information?")) {
                e.preventDefault();
            }
        }, true);

    window.addEventListener("load", nameValidation, false);
    window.addEventListener("load", addressValidation, false);
    window.addEventListener("load", phoneNumberValidation, false);
    window.addEventListener("load", emailValidation, false);
    window.addEventListener("load", passwordValidation, false);
    
</script>

<?php require_once("storeFooter.html"); ?>
