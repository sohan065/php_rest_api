<?php

$serverName = "localhost";
$username = "root";
$password = "root1234";
$database = "restapi";


$conn = new mysqli($serverName, $username, $password, $database);

if (!$conn) {
    die(mysqli_error($con));
}