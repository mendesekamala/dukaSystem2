<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Duka-system</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="orders/style.css">
        <script src="orders/scripts.js"></script>

        <style>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            th, td {
                padding: 10px;
                text-align: left;
            }
            .td-but {
                display: flex;
                justify-content: space-around; /* Distribute buttons evenly */
                align-items: center; /* Align buttons vertically in the center */
                gap: 5px; /* Optional: adds spacing between buttons */
            }

            .td-but button {
                background-color: transparent; /* Make the button background transparent */
                border: none; /* Remove button border */
                padding: 0; /* Remove button padding */
                cursor: pointer;
            }

            .td-but img {
                width: 20px; /* Adjust icon size if needed */
                height: 20px;
            }
            .orderListing {
                margin: 50px 150px;
            }

            .suggestions {
                position: absolute;
                border: 1px solid #ccc;
                background-color: #fff;
                max-height: 200px;
                overflow-y: auto;
                width: 30%;
                box-shadow: 0 8px 16px rgba(0,0,0,0.2);
                z-index: 1000;
            }
            .suggestion-item {
                padding: 10px;
                cursor: pointer;
            }
            .suggestion-item:hover {
                background-color: #f0f0f0;
            }
        </style>

    </head>

    <body>
        <div>
            <div class="sidebar">
                <h3 style="text-align:center;">Dashboard</h3>
                <a href="stock.php">Stock</a>
                <a href="#" class="active">Orders</a>
                <a href="transactions.php">Transactions</a>
                <a href="debts.php">Debts</a>
                <a href="register.php">User</a>
            </div>
        </div>

        <div class="content">
            
            <div id="CreateOrder" class="tabcontent">
                <div class="order-form-container">
                    <div class="form-left">
                        
                        <div class="tab">
                            <button class="tablinks active" id="createOrderTab" onclick="openTab(event, 'CreateOrder')">Create New Order</button>
                            <button class="tablinks" id="orderListTab" onclick="openTab(event, 'OrderList')">Order List</button>
                        </div>

                        <div class="order-form">
                            <p style="text-align: center;"> <label for="search">Search by product name:</label> </p>
                            <input type="text" id="search" onkeyup="liveSearch()">
                            <div id="suggestions" class="suggestions"></div>

                            <label for="product_name">Product Name:</label>
                            <input type="text" id="product_name" readonly>

                            <label for="product_nickname">Product Nickname:</label>
                            <input type="text" id="product_nickname" readonly>

                            <label for="expiry_date">Expiry Date:</label>
                            <input type="text" id="expiry_date" readonly>

                            <label for="customer_name">Customer's Name:</label>
                            <input type="text" id="customer_name">

                            <div class="price-quantity">
                                <div>
                                    <label for="buying_price">Buying Price:</label>
                                    <input type="text" id="buying_price" readonly>
                                </div>
                                <div>
                                    <label for="selling_price">Selling Price:</label>
                                    <input type="text" id="selling_price">
                                </div>
                                <div>
                                    <label for="quantity">Quantity:</label>
                                    <input type="number" id="quantity">
                                </div>
                            </div>

                            <button type="button" onclick="addProduct()">Add Product</button>
                        </div>
                    </div>

                    <div class="form-right">
                        <div class="order-list">

                            <ul id="orderItems"></ul>

                            <div class="total">
                                <span>TOTAL</span>
                                <span id="grandTotal">0</span>
                            </div>
                            <div class="payment">
                                <label for="payment_method">Pay by:</label>
                                <select id="payment_method" onchange="toggleDebtField()">
                                    <option value="cash">Cash</option>
                                    <option value="debt">Debt</option>
                                </select>
                                <input type="text" id="debt_input" placeholder="Enter debt amount" style="display: none;">
                            </div> 
                            <button type="button" onclick="completeOrder()">Complete Order</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="OrderList" class="tabcontent" style="display:none;">
                
                <div class="orderListing">

                    <div class="tab">
                        <button class="tablinks" id="createOrderTab" onclick="openTab(event, 'CreateOrder')">Create New Order</button>
                        <button class="tablinks active" id="orderListTab" onclick="openTab(event, 'OrderList')">Order List</button>
                    </div>

                    <?php
                        include 'db_connection.php';

                        $company_name = $_SESSION['company_name'];
                    
                        // Fetch orders for the specific company
                        $sql = "SELECT order_id, order_number, customer_name, order_date, status, debt_amount FROM orders WHERE company_name = '$company_name'";
                        $result = $conn->query($sql);

                        if ($result === false) {
                            // Query failed
                            echo "Error: " . $conn->error;
                        } else {
                            if ($result->num_rows > 0) {
                                echo "<table border='1'>
                                        <tr>
                                            <th>Order No</th>
                                            <th>Customer Name</th>
                                            <th>Order Date and Time</th>
                                            <th>Status</th>
                                            <th>Debt Amount</th>
                                            <th>Actions</th>
                                        </tr>";
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['order_number']}</td>
                                            <td>{$row['customer_name']}</td>
                                            <td>{$row['order_date']}</td>
                                            <td>{$row['status']}</td>
                                            <td>{$row['debt_amount']}</td>
                                            <td class='td-but'>
                                                <button onclick='editOrder({$row['order_id']})'><img src='images/brush.ico' alt='Edit'></button>
                                                <button onclick='deleteOrder({$row['order_id']})'><img src='images/trash.ico' alt='Delete'></button>
                                                <button onclick='viewOrder({$row['order_id']})'><img src='images/view.ico' alt='View'></button>
                                            </td>
                                        </tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "<tr><td colspan='6'>No orders found</td></tr>";
                            }
                        }

                        $conn->close();
                    ?>
                </div>
                

            </div>

        </div>

        <script>
            function openTab(evt, tabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            // Default open
            document.getElementById("createOrderTab").click();
        </script>

    </body>
</html>
