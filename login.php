<?php
include 'db_connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT id, password, company_name FROM company WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $hashed_password, $company_name);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        // Start a session and store user information
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['company_name'] = $company_name; // Store company name in session

        header("Location: transactions.php"); // Redirect to the dashboard or main page
        exit();
    } else {
        echo "<script>
                alert('Incorrect password.');
                window.location.href = 'transactions.php';
            </script>";
    }
} else {
    echo "<script>
            alert('user not found.');
            window.location.href = 'register.php';
        </script>";
}

$stmt->close();
$conn->close();
?>
