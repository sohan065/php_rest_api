<?php

include 'connection.php';

//  data table creation query
$createTableQuery = "CREATE TABLE IF NOT EXISTS reviews (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        product INT NOT NULL,
                        user INT NOT NULL,
                        text VARCHAR(255) NOT NULL
                    )";

$conn->query($createTableQuery);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $selectQuery = "SELECT * FROM reviews";
    $result = $conn->query($selectQuery);

    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);
    } else {
        echo json_encode(array());
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // JSON data is sent in the request body
    $input_data = json_decode(file_get_contents("php://input"), true);

    // Validate required fields
    if (
        isset($input_data['user']) && is_numeric($input_data['user']) &&
        isset($input_data['product']) && is_numeric($input_data['product']) && isset($input_data['text'])
    ) {
        $user = intval($input_data['user']);
        $product = intval($input_data['product']);
        $text = $input_data['text'];
        if (empty($text)) {
            http_response_code(400); // Bad Request
            echo json_encode(array("error" => "Text field cannot be empty"));
        } else {
            try {
                // Insert data into the database
                $insertQuery = "INSERT INTO reviews (user, product, text) VALUES ($user, '$product', '$text')";
                $conn->query($insertQuery);

                echo json_encode(array("message" => "Data inserted successfully"));
            } catch (Exception $e) {

                http_response_code(500); // Internal Server Error
                echo json_encode(array("error" => "Failed to insert data: " . $e->getMessage()));
            }
        }
    } else {

        http_response_code(400); // Bad Request
        echo json_encode(array("error" => "Invalid or missing user, product, or text fields. user and product type is numeric"));
    }
}

// handle update request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    try {
        // Assuming you get the review ID from the request (adjust as needed)
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Validate the ID to ensure it's a non-zero positive integer
        if ($id <= 0) {
            http_response_code(400); // Bad Request
            echo json_encode(array("error" => "Invalid review ID"));
        } else {
            //  JSON data is sent in the request body
            $input_data = json_decode(file_get_contents("php://input"), true);

            // Validate required fields
            if (
                isset($input_data['user']) && is_numeric($input_data['user']) &&
                isset($input_data['product']) && isset($input_data['text'])
            ) {
                $user = intval($input_data['user']);
                $product = intval($input_data['product']);
                $text = $input_data['text'];

                // Check if $text is empty
                if (empty($text)) {
                    http_response_code(400); // Bad Request
                    echo json_encode(array("error" => "Text field cannot be empty"));
                } else {
                    // Check if the review with the given ID exists
                    $checkQuery = "SELECT * FROM reviews WHERE id=$id";
                    $checkResult = $conn->query($checkQuery);

                    if ($checkResult->num_rows > 0) {
                        // Update data in the database
                        $updateQuery = "UPDATE reviews SET user=$user, product='$product', text='$text' WHERE id=$id";
                        $result = $conn->query($updateQuery);

                        if ($result) {
                            echo json_encode(array("message" => "Review updated successfully"));
                        } else {
                            http_response_code(500); // Internal Server Error
                            echo json_encode(array("error" => "Failed to update review"));
                        }
                    } else {
                        http_response_code(404); // Not Found
                        echo json_encode(array("error" => "Review with ID $id not found"));
                    }
                }
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(array("error" => "Invalid or missing user, product, or text fields. user and product type is numeric"));
            }
        }
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("error" => "Exception: " . $e->getMessage()));
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        //  review ID from the request 
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Validate the ID to ensure it's a non-zero positive integer
        if ($id <= 0) {
            http_response_code(400); // Bad Request
            echo json_encode(array("error" => "Invalid review ID"));
        } else {
            // Check if the review with the given ID exists
            $checkQuery = "SELECT * FROM reviews WHERE id=$id";
            $checkResult = $conn->query($checkQuery);

            if ($checkResult->num_rows > 0) {
                // Delete data from the database
                $deleteQuery = "DELETE FROM reviews WHERE id=$id";
                $result = $conn->query($deleteQuery);

                if ($result) {
                    echo json_encode(array("message" => "Review deleted successfully"));
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(array("error" => "Failed to delete review"));
                }
            } else {
                http_response_code(404); // Not Found
                echo json_encode(array("error" => "Review with ID $id not found"));
            }
        }
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("error" => "Exception: " . $e->getMessage()));
    }
}

// Close the database connection
$conn->close();
