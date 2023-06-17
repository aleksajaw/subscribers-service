<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "test";
    
    $mysqli = new mysqli($servername, $username, $password, $dbname);
    if ($mysqli->connect_error) {
        die("Connection Error: " . $mysqli->connect_error);
    }
;?>