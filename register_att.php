<?php
	require 'gigs_connect.php';
	
	redirect_admin();
	redirect_attendee();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Attendee</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to register a new attendee" />
    <link rel="stylesheet" href="assignment_styles1.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        fieldset {
            width: 350px;
        }
    </style>
    <script>
        function validateForm() {
            //get the form and the inputs
            var form = document.forms["register_form"];
            var mobile = form.mobile;
            var pass = form.pword;
            var passConf = form.pword_conf;
            var fname = form.firstName;
            var lname = form.lastName;
            var dob = form.dob;
            var validation = true;
            var errorString = "";
            var numeric = /^[0-9]+$/;
            var alphabetic = /^[a-z]+$/i;

            //reset the background color of the inputs
            mobile.style.backgroundColor = '';
            pass.style.backgroundColor = '';
            passConf.style.backgroundColor = '';
            fname.style.backgroundColor = '';
            lname.style.backgroundColor = '';
            dob.style.backgroundColor = '';

            if (mobile.value.length != 10) {//check if the mobile number is 10 numbers only
                errorString += "The mobile number must be 10 numbers!\n";
                mobile.style.backgroundColor = '#FFC8C8';
                if (validation == true) {
                    validation = false;
                }
            }
            if (!numeric.test(mobile.value)) {
                errorString += "The mobile number must only contain numeric characters!\n";
                mobile.style.backgroundColor = '#FFC8C8';
                if (validation == true) {
                    validation = false;
                }
            }
            if (!alphabetic.test(fname.value)) {//check if the first name is only letters
                errorString += "The first name must only contain alphabetic characters!\n";
                fname.style.backgroundColor = '#FFC8C8';
                if (validation == true) {
                    validation = false;
                }
            }
            if (!alphabetic.test(lname.value)) {//check if the last name is only letters
                errorString += "The last name must only contain alphabetic characters!\n";
                lname.style.backgroundColor = '#FFC8C8';
                if (validation == true) {
                    validation = false;
                }
            }
            if (pass.value.length < 5) {//check if the password is at least 5 characters
                errorString += "The password must be at least 5 characters!\n";
                pass.style.backgroundColor = '#FFC8C8';
                if (validation == true) {
                    validation = false;
                }
            }
            if (pass.value != passConf.value) {
                errorString += "Password and confirmation must be the same!\n";
                pass.style.backgroundColor = '#FFC8C8';
                passConf.style.backgroundColor = '#FFC8C8';
                if (validation == true) {
                    validation = false;
                }
            }
            if (dob.value == null || dob.value == '') {//check if the date of birth is empty
                errorString += "Date of Birth cannot be empty.\n";
                dob.style.backgroundColor = '#FFC8C8';
                if (validation == true) {
                    validation = false;
                }
            }
            if (fname.value == null || fname.value == '') {//check if the first name is empty
                errorString += "First Name cannot be empty.\n";
                fname.style.backgroundColor = '#FFC8C8';
                if (validation == true) {
                    validation = false;
                }
            }
            if (lname.value == null || lname.value == '') {//check if the last name is empty
                errorString += "Last name cannot be empty.\n";
                lname.style.backgroundColor = '#FFC8C8';
                if (validation == true) {
                    validation = false;
                }
            }
            if (validation == false) {
                alert(errorString);
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <header>
        <h1>Register New Attendee</h1>
    </br>
    <p><a href="Index.php">Back to Home</a></p>
    </header>
    <form method="post" name="register_form" action="process_registration.php" onsubmit="return validateForm()">
        <fieldset><legend>User Credentials</legend>
            <p><input type="text" name="mobile" placeholder="Mobile Phone" title="Mobile Phone" /></p>
            <p>
                <input type="password" name="pword" placeholder="Password" title="Password" />
                <input type="password" name="pword_conf" placeholder="Confirm password" title="Confirm password" />
            </p>
        </fieldset>

        <fieldset><legend>Personal Details</legend>
            <p><input type="text" name="firstName" placeholder="First Name" title="First Name" /></p>
            <p><input type="text" name="lastName" placeholder="Last Name" title="Last Name" /></p>
            <p><input type="date" name="dob" placeholder="dd/mm/yyyy" title="DateOfBirth" /></p>
            <p><input type="submit" name="submit"></p>
        </fieldset>
    </form>
</body>
</html>
