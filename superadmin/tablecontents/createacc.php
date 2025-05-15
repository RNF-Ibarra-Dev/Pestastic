<?php
session_start();
require_once("../../includes/dbh.inc.php");
require_once('../../includes/functions.inc.php');

if (isset($_POST["createacc"]) && $_POST['createacc'] === 'true') {

    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdRepeat"];
    $contactNo = $_POST["contactNo"];
    $empId = $_POST["empId"];
    $address = $_POST["address"];
    $birthdate = $_POST['birthdate'];
    $account = $_POST['account'];


    if (emptyInputSignup($firstName, $lastName, $username, $email, $pwd, $pwdRepeat) !== false) {
        echo "Please fill in all details.";
        exit();
    }
    if (invalidFirstName($firstName) !== false) {
        echo 'Invalid first name.';
        exit();
    }
    if (invalidLastName($lastName) !== false) {
        echo 'Invalid last name.';
        exit();
    }
    if (invalidUsername($username) !== false) {
        echo 'Invalid Username';
        exit();
    }

    if (invalidEmail($email) !== false) {
        echo 'Invalid email.';
        exit();
    }

    if(strlen($contactNo) !== 11){
        echo 'Contact number should contain exactly 11 digits.';
        exit();
    }

    if (pwdMatch($pwd, $pwdRepeat) !== false) {
        echo 'Passwords do not match.';
        exit();
    }
    if (multiUserExists($conn, $username, $email) !== false) {
        echo 'User already exists';
        exit();
    }

    if(strlen($empId) !== 3){
        echo 'Employee ID should only contain three digits.';
        exit();
    }

    if(employeeIdCheck($conn, $empId) !== false) {
        echo 'Employee ID already exist.';
        exit();
    }

    if($account === 'tech'){
        $create = createTechAccount($conn, $firstName, $lastName, $username, $email, $pwd, $contactNo, $address, $empId, $birthdate);
    } elseif($account === 'os'){
        $create = createOpSupAccount($conn, $firstName, $lastName, $username, $email, $pwd, $contactNo, $address, $empId, $birthdate);
    } else{
        echo 'Invalid account type. Please try again.';
        exit();
    }

    if(isset($create['error'])){
        echo 'Error: ' . $create['error'];
        exit();
    } else{
        echo json_encode(['success' => 'Account Created!']);
    }

} 
