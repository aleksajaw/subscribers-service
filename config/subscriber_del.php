<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $postData = file_get_contents('php://input');
        $data = json_decode($postData, true);

        $number = $data['number'];

        if ( isset($number) && !empty($number) ) {

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "test";

            try {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $mysqli = new mysqli($servername, $username, $password, $dbname);
                $query = "DELETE FROM subscribers WHERE number = ?";
                $stmt = $mysqli->prepare($query);
                
                if (!$stmt) {
                    die("Query preparation error: " . $mysqli->error);
                }
                       
                $stmt->bind_param("i", $number);

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