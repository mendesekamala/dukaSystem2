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
    <link rel="stylesheet" href="units/styles.css">
    <script src="units/scripts.js"></script>

    <style>
            .suggestions {
                position: absolute;
                border: 1px solid #ccc;
                background-color: #fff;
                max-height: 200px;
                overflow-y: auto;
                width: 20%;
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
            <a href="orders.php">Orders</a>
            <a href="transactions.php">Transactions</a>
            <a href="debts.html">Debts</a>
            <a href="register.php">User</a>
            <a href="#" class="active">Units</a>
        </div>  
    </div>

    <div class="content">
        <div class="container">
            <h2>create unit</h2>
            <form id="unitForm">
                <div class="form-group">
                    <label for="productSearch">search by product's name</label>
                    <input type="text" id="search" name="productSearch" onkeyup="liveSearch()">
                    <p style="text-align: center;">     <div id="suggestions" class="suggestions"></div>    </p>
                </div>

                <div class="form-group">
                    <label for="productName">product's name</label>
                    <input type="text" id="productName" name="productName" disabled>
                    <input type="hidden" id="productId" name="productId">
                </div>

                <div class="form-group">
                    <label for="unitName">unit name</label>
                    <input type="text" id="unitName" name="unitName" required>
                </div>

                <div class="form-group">
                    <label for="noUnits">number of units per single quantity</label>
                    <input type="number" id="noUnits" name="noUnits" required>
                </div>

                <div class="form-group"> 
                    <label for="unitPrice">unit price</label>
                    <input type="number" id="unitPrice" name="unitPrice" step="0.01" required>
                </div>

                <button type="submit">complete</button>
            </form>
            <div id="result"></div>
        </div>
    </div>
</body>

    <script>
        function liveSearch() {
        let searchQuery = encodeURIComponent(document.getElementById('search').value);

        if (searchQuery.length === 0) {
            document.getElementById('suggestions').innerHTML = '';
            return;
        }

        // Fetch company name directly from the session in PHP
        const companyName = '<?php echo $_SESSION["company_name"]; ?>'; // Get the session company_name

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'search_product.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    const products = JSON.parse(xhr.responseText);
                    let suggestionsHTML = '';
                    products.forEach(product => {
                        suggestionsHTML += `<div class="suggestion-item" onclick="selectProduct(${product.id})">${product.product_name}</div>`;
                    });
                    document.getElementById('suggestions').innerHTML = suggestionsHTML;
                } catch (e) {
                    console.error("Parsing error:", e, xhr.responseText); // Log the error and the response
                }
            }
        };

        xhr.send('search_query=' + searchQuery);
    }

    function selectProduct(productId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'get_product.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText); // Add this before JSON.parse
                const product = JSON.parse(xhr.responseText);
                document.getElementById('productName').value = product.product_name;
                document.getElementById('suggestions').innerHTML = '';
            }
        };
        xhr.send('product_id=' + productId);
    }

    document.getElementById('unitForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this); // Create FormData object from the form

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'create_product_unit.php', true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    document.getElementById('result').innerHTML = `<p style="color: green;">${response.message}</p>`;
                } else {
                    document.getElementById('result').innerHTML = `<p style="color: red;">${response.message}</p>`;
                }
            }
        };

        xhr.send(formData); // Send the form data to the server
    });


    </script>


</html>
