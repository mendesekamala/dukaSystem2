<?php
session_start(); // Start the session to access session variables
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duka-system</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="debts/style.css">
</head>
<body>
    <div>

        <div class="sidebar">
        <h3 style="text-align:center;">Dashboard</h3>
        
        <a href="stock.php">Stock</a>
        <a href="orders.php">Orders</a>
        <a href="transactions.php">Transactions</a>
        <a href="#" class="active">Debts</a>
        <a href="register.php">User</a>
      </div>
      
    </div>

    <div class="content">
        <div class="container">
            <table border="1" class="table-style">
                <caption><h2>Debtors</h2></caption>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Date made</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody id="stock-table-body">
                    <?php include 'fetch_debts.php'; ?>
                </tbody>
            </table>
        </div>
    </div>
    
</body>
</html>