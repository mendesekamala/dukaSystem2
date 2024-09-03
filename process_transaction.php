<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_management";

// Start a session to get the company name (or user ID if using that approach)
session_start();
$company_name = $_SESSION['company_name']; // Assuming company_name is stored in the session

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$transaction_type = $_POST['transaction_type'];
$amount = $_POST['amount'];
$description = $_POST['description'];
$date_made = date('Y-m-d H:i:s');

// Insert transaction into transactions table with the company name
$sql = "INSERT INTO transactions (transaction_type, amount, date_made, description, company_name)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sdsss", $transaction_type, $amount, $date_made, $description, $company_name);

if ($stmt->execute()) {
    // Update cash in liquidity table based on transaction type and company name
    if ($transaction_type == 'expenses' || $transaction_type == 'drawings') {
        $update_sql = "UPDATE liquidity SET cash = cash - ? WHERE company_name = ?";
    } elseif ($transaction_type == 'add_capital') {
        $update_sql = "UPDATE liquidity SET cash = cash + ? WHERE company_name = ?";
    }

    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ds", $amount, $company_name);

    if ($update_stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $update_stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
