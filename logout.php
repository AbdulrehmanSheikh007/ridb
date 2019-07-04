<?php
include("dbs.php");
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header('Location: login.php');
}

unset($_SESSION['id']); 
unset($_SESSION['fullname']); 
unset($_SESSION['password']); 
unset($_SESSION['email']); 
unset($_SESSION['api_access_key']); 
unset($_SESSION['admin_email']); 

header('Location: login.php');