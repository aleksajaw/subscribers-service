<?php
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $postData = file_get_contents('php://input');
        $data = json_decode($postData, true);
        
        $number = $data['number'];
        $fname = $data['fname'];
        $email = $data['email'];

        if ( isset($number) && !empty($number)
            && isset($fname) && !empty($fname)
            && isset($email) && !empty($email) ) {

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "test";

            try {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $mysqli = new mysqli($servername, $username, $password, $dbname);                
                $query = "UPDATE subscribers SET fname=?, email=? WHERE number = ?";
                $stmt = $mysqli->prepare($query);
                
                if (!$stmt) {
                    die("Query preparation error: " . $mysqli->error);
                }
                       
                $stmt->bind_param("ssi", $fname, $email, $number);

                if ($stmt->execute()) {
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
?>