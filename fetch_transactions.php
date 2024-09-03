<?php
session_start();
 
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Example SQL query (make sure it matches your database schema)
$sql = "SELECT id, transaction_type, amount, date_made, description FROM transactions WHERE company_name = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

// Assuming you're passing a company name (replace 'Company Name' with the actual variable)
$company_name = $_SESSION['company_name'];
$stmt->bind_param('s', $company_name);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["transaction_type"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["amount"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["date_made"]) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No transactions found</td></tr>";
}

$stmt->close();
$conn->close();
?>
