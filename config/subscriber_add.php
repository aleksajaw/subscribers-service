<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $postData = file_get_contents('php://input');
        $data = json_decode($postData, true);

        $fname = $data['fname'];
        $email = $data['email'];

        if ( isset($fname) && !empty($fname)
            && isset($email) && !empty($email) ) {

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "test";

            try {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $mysqli = new mysqli($servername, $username, $password, $dbname);
                $query = "INSERT INTO subscribers (fname, email) VALUES (?, ?)";
                $stmt = $mysqli->prepare($query);
                
                if (!$stmt) {
                    die("Query preparation error: " . $mysqli->error);
                }

                $stmt->bind_param("ss", $fname, $email);

                if ( $stmt->execute() ) {
                    $stmt->close();
                    $mysqli->close();
                    echo "Done.";
                
                } else {
                    echo "Error: " . $stmt->error;
                    $stmt->close();
                    $mysqli->close();
                }

                
            } catch (Exception $e) {
                die("Connection error: " . $e->getMessage());
            }
        }
    }


    /*$refererArray = [
                        'http://localhost/subscribers/index.php',
                        'https://localhost/subscribers/index.php',
                        'http://localhost/subscribers/',
                        'https://localhost/subscribers/'
                    ];

    if ( in_array( $_SERVER['HTTP_REFERER'], $refererArray ) )
        header("Location: index.php");*/

        
    exit(); 
?>