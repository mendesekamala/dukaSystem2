<?php
session_start(); // Start the session to access session variables

// Database connection (assuming you have a separate db.php file for connection)
include 'db_connection.php'; // Adjust this to your actual database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve inputs
    $productId = $_POST['productId'];
    $unitName = $_POST['unitName'];
    $noUnits = $_POST['noUnits'];
    $unitPrice = $_POST['unitPrice'];

    // Fetch the product quantity from the 'products' table using the product ID
    $query = "SELECT quantity FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $productQuantity = $row['quantity'];
        
        // Calculate total stock units
        $totalStockUnits = $productQuantity * $noUnits;

        // Insert the new unit into the 'product_units' table
        $insertQuery = "INSERT INTO product_units (id, unit_name, no_units_per_single_stock, total_stock_units, unit_price)
                        VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("isiii", $productId, $unitName, $noUnits, $totalStockUnits, $unitPrice);

        if ($insertStmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Product unit created successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error creating product unit.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
