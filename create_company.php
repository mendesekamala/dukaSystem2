<?php
include 'db_connection.php';

$company_name = $_POST['companyName'];
$username = $_POST['username'];
$capital = $_POST['capital']; // Get the input capital
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

// Insert into the company table
$sql = "INSERT INTO company (company_name, username, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $company_name, $username, $password);

if ($stmt->execute()) {
    // If company registration is successful, insert into the liquidity table
    $sql_liquidity = "INSERT INTO liquidity (cash, company_name) VALUES (?, ?)";
    $stmt_liquidity = $conn->prepare($sql_liquidity);
    $stmt_liquidity->bind_param("ds", $capital, $company_name);
    
    if ($stmt_liquidity->execute()) {
        echo "
            <script>
                alert('Company registered successfully!');
                window.location.href = 'register.php';
            </script>
        ";
    } else {
        $error_message = addslashes($stmt_liquidity->error); // Escape the error message for JavaScript
        echo "
            <script>
                alert('Error: " . $error_message . "');
            </script>
        ";
    }
    
    $stmt_liquidity->close();
} else {
    $error_message = addslashes($stmt->error); // Escape the error message for JavaScript
    echo "
        <script>
            alert('Error: " . $error_message . "');
        </script>
    ";
}

// Close the statement and connection
$stmt->close();
$conn->close();

exit(); // Ensure script termination after output
?>
