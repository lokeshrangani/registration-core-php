<?php
session_start();

// initializing variables
$name = "";
$birth_date = "";
$address = "";
$profile_pic = "";
$cc_number = "";
$cc_expiry = "";
$cc_CVC = "";
$errors = array();

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'registration');

// REGISTER USER
if (isset($_POST['reg_user'])) {
    // receive all input values from the form
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $birth_date = mysqli_real_escape_string($db, $_POST['birth_date']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $cc_number = mysqli_real_escape_string($db, $_POST['cc_number']);
    $cc_expiry_month = mysqli_real_escape_string($db, $_POST['cc_expiry_month']);
    $cc_expiry_year = mysqli_real_escape_string($db, $_POST['cc_expiry_year']);
    $cc_CVC = mysqli_real_escape_string($db, $_POST['cc_CVC']);
    $cc_number = str_replace("-", "", $cc_number);
    $birth_date = date('Y-m-d', strtotime($birth_date));
    $today = date('Y-m-d');

    // form validation: ensure that the form is correctly filled by adding (array_push()) corresponding error unto $errors array
    if (empty($name)) {
        array_push($errors, "Name is required");
    }
    if (strlen($name) > 200) {
        array_push($errors, "Name is Too Long!");
    }
    if (empty($birth_date)) {
        array_push($errors, "Bitrh Date is required");
    }
    if ($today < $birth_date) {
        array_push($errors, "Invalid Bitrh Date");
    }
    if (empty($address)) {
        array_push($errors, "Address is required");
    }
    if (empty($cc_number)) {
        array_push($errors, "Credit Card Info is required");
    }
    if (!validateCC($cc_number)) {
        array_push($errors, "Invalid Credit Card Number");
    }
    if (empty($cc_expiry_month)) {
        array_push($errors, "Credit Card Expiry Month is required");
    }
    if (empty($cc_expiry_year)) {
        array_push($errors, "Credit Card Expiry Year is required");
    }
    if ($cc_expiry_year > 2030) {
        array_push($errors, "Invalid Credit Card Expiry Year");
    }
    if (empty($cc_CVC)) {
        array_push($errors, "Credit Card CVC is required");
    }
    if ($cc_CVC < 0 || $cc_CVC >= 10000) {
        array_push($errors, "Invalid Credit Card CVC");
    }

    if (!isset($_FILES['profile_pic'])) {
        array_push($errors, "Profile Picture is required");
    }
    if (isset($_FILES['profile_pic'])) {
        $file_name = $_FILES['profile_pic']['name'];
        $file_size = $_FILES['profile_pic']['size'];
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_type = $_FILES['profile_pic']['type'];
        $namePart = explode('.', $file_name);
        $file_ext = strtolower(end($namePart));

        $extensions = array("jpeg", "jpg", "png");

        if (!in_array($file_ext, $extensions)) {
            array_push($errors, "File Extension not allowed, please choose a JPEG or PNG file.");
        }

        if ($file_size > 2097152) {
            array_push($errors, "File size must be excately 2 MB.");
        }
    }

    // first check the database to make sure a user does not already exist with the same name and/or cc number
    $user_check_query = "SELECT * FROM user WHERE name='$name' OR cc_number='$cc_number' LIMIT 1";
    $result = $db->query($user_check_query);

    if ($result && $result->num_rows > 0) {
        // if user exists
        array_push($errors, "User already exists");
    }

    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {

        // create folder if not exist
        if (!file_exists("images/")) {
            mkdir("images/", 0777, true);
        }

        // upload image 
        move_uploaded_file($file_tmp, "images/" . $file_name);
        $image_path = "images/" . $file_name;
        $cc_expiry = $cc_expiry_month . '-' . $cc_expiry_year;

        // insert into table
        $query = "INSERT INTO user (name, birth_date, address,profile_pic,cc_number,cc_expiry,cc_CVC,created_at) VALUES('$name', '$birth_date', '$address','$image_path','$cc_number','$cc_expiry','$cc_CVC',NOW())";
        mysqli_query($db, $query);

        $_SESSION['success'] = "Successfully Register - $name";
        
        $name = "";
        $birth_date = "";
        $address = "";
        $profile_pic = "";
        $cc_number = "";
        $cc_expiry = "";
        $cc_CVC = "";
    }
}

// validate cc 
function validateCC($number)
{
    $cardtype = array(
        "visa" => "/^4[0-9]{12}(?:[0-9]{3})?$/",
        "mastercard" => "/^5[1-5][0-9]{14}$/",
        "amex" => "/^3[47][0-9]{13}$/",
        "discover" => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
    );
    $is_valid = false;

    if (
        preg_match($cardtype['visa'], $number) ||
        preg_match($cardtype['mastercard'], $number) ||
        preg_match($cardtype['amex'], $number) ||
        preg_match($cardtype['discover'], $number)
    ) {
        $is_valid = true;
    }

    return $is_valid;
}
