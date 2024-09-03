<?php
include 'db_connection.php';
session_start();

// Ensure the session variable is set
if (!isset($_SESSION['company_name'])) {
    die(json_encode(['error' => 'Company name not set in session']));
}

$company_name = $_SESSION['company_name'];
$search_query = $_POST['search_query'];

// Assuming you've connected to the database here...
$sql = "SELECT * FROM products WHERE product_name LIKE '%$search_query%' AND company_name = '$company_name'";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
echo json_encode($products);
?>

