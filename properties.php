<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "real_property_tax";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load JSON data from file
$jsonFilePath = 'assets/json/properties_coordinates.json';
$jsonData = file_get_contents($jsonFilePath);

// Decode JSON data
$data = json_decode($jsonData, true);

// Check if JSON decoding was successful
if (is_array($data)) {
    foreach ($data as $property) {
        $type = isset($property['type']) ? $property['type'] : null;
        $coordinates = json_encode($property['coordinates']); // Convert coordinates to JSON format

        // Insert data into the properties table
        $stmt = $conn->prepare("INSERT INTO properties (type, coordinates) VALUES (?, ?)");
        $stmt->bind_param("ss", $type, $coordinates);
        $stmt->execute();
    }

    echo "Data inserted successfully!";
    $stmt->close();
} else {
    echo "Failed to decode JSON or JSON file is empty.";
}

$conn->close();
?>
