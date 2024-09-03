<?php
include 'db_connection.php';
session_start(); // Start the session to access session variables

$company_name = $_SESSION['company_name']; // Get the company name from session
$product_name = $_POST['product_name'];
$product_nickname = $_POST['product_nickname'];
$product_description = $_POST['product_description'];
$buying_price = $_POST['buying_price'];
$selling_price = $_POST['selling_price'];
$quantity = $_POST['quantity'];
$expiry_date = $_POST['expiry_date'];
$date_bought = $_POST['date_bought'];
$supplier = $_POST['supplier'];

// Calculate the total purchase cost
$total_purchase_cost = $buying_price * $quantity;

$sql = "SELECT * FROM products WHERE product_name = '$product_name' AND company_name = '$company_name'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;
    
    // Update quantity, buying_price, and selling_price in a single query
    $sql = "UPDATE products 
            SET quantity = '$new_quantity', 
                buying_price = '$buying_price', 
                selling_price = '$selling_price' 
            WHERE product_name = '$product_name' AND company_name = '$company_name'";

    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully";
    } else {
        echo "Error updating product: " . $conn->error;
    }
} else {
    $sql = "INSERT INTO products (product_name, product_nickname, product_description, buying_price, selling_price, quantity, expiry_date, date_bought, supplier, company_name)
    VALUES ('$product_name', '$product_nickname', '$product_description', '$buying_price', '$selling_price', '$quantity', '$expiry_date', '$date_bought', '$supplier', '$company_name')";
    if ($conn->query($sql) === TRUE) {
        echo "New product created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update the cash column in the liquidity table based on company_name
$cash_update_query = $conn->prepare("UPDATE liquidity SET cash = cash - ? WHERE company_name = ?");
$cash_update_query->bind_param("ds", $total_purchase_cost, $company_name);
if ($cash_update_query->execute()) {
    echo "Cash updated successfully";
} else {
    echo "Error updating cash: " . $conn->error;
}

$conn->close();
?>

