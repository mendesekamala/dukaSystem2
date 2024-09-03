<?php
include 'db_connection.php';

// Assume the user's company_name is stored in the session
$company_name = $_SESSION['company_name'];

$sql = "SELECT product_name, date_bought, buying_price, selling_price, quantity FROM products WHERE company_name = '$company_name'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['product_name']}</td>
                <td>{$row['date_bought']}</td>
                <td>{$row['buying_price']}</td>
                <td>{$row['selling_price']}</td>
                <td>{$row['quantity']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No products found</td></tr>";
}

$conn->close();
?>
