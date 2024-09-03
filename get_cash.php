<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Adjust the query to select cash based on the company name
$company_name = $_SESSION['company_name']; // Replace with actual company name variable
$sql = "SELECT cash FROM liquidity WHERE company_name = ? LIMIT 1";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param('s', $company_name);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['cash' => $row['cash']]);
} else {
    echo json_encode(['cash' => 0]);
}

$stmt->close();
$conn->close();
?>
