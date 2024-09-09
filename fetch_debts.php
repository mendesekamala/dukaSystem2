<?php
include 'db_connection.php';

// Assume the user's company_name is stored in the session
$company_name = $_SESSION['company_name'];

$sql = "SELECT * FROM orders WHERE company_name = '$company_name' AND debt_amount > 0 ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo 
            "<tr>
                <td>{$row['customer_name']}</td>
                <td>{$row['order_date']}</td>
                <td>{$row['debt_amount']}</td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No debts found</td></tr>";
}

$conn->close();
?>