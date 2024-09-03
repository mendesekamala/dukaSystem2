<?php
    session_start(); // Start the session if not already started
    include 'db_connection.php';

    // Decode JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    $customer_name = $data['customerName'];
    $payment_method = $data['paymentMethod'];
    $debt_amount = $data['debtAmount'];
    $order_items = $data['orderItems']; // Already an array, no need for json_decode again
    $company_name = $_SESSION['company_name'];


    // Generate order number
    $date_prefix = date('dm'); // Format as 'ddmm'

    // Find the last order number for today for the specific company
    $sql = "SELECT order_number FROM orders WHERE company_name = ? AND order_number LIKE '$date_prefix-%' ORDER BY order_id DESC LIMIT 1";
    $query = $conn->prepare($sql);
    $query->bind_param("s", $company_name);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $last_order = $result->fetch_assoc();
        $last_number = explode('-', $last_order['order_number'])[1];
        $order_number = $date_prefix . '-' . str_pad($last_number + 1, 2, '0', STR_PAD_LEFT);
    } else {
        $order_number = $date_prefix . '-01'; // Start with '01' if no orders exist for today
    }

    // Insert the order with company_name
    $query = $conn->prepare("INSERT INTO orders (order_number, customer_name, payment_method, debt_amount, company_name) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("sssss", $order_number, $customer_name, $payment_method, $debt_amount, $company_name);
    $query->execute();

    $order_id = $conn->insert_id;

    // Insert order items with company_name
    foreach ($order_items as $item) {
        $query = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, price, total, company_name) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("isidds", $order_id, $item['productName'], $item['quantity'], $item['sellingPrice'], $item['total'], $company_name);
        $query->execute();
    }

    // Reduce quantity of sold products for the specific company
    foreach ($order_items as $item) {
        $product_name = $item['productName'];
        $ordered_quantity = $item['quantity'];

        $query = $conn->prepare("SELECT quantity FROM products WHERE product_name = ? AND company_name = ?");
        $query->bind_param("ss", $product_name, $company_name);
        $query->execute();
        $result = $query->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $current_quantity = $row['quantity'];
            
            $new_quantity = $current_quantity - $ordered_quantity;

            $update_query = $conn->prepare("UPDATE products SET quantity = ? WHERE product_name = ? AND company_name = ?");
            $update_query->bind_param("iss", $new_quantity, $product_name, $company_name);
            $update_query->execute();
        }
    }

    // Update cash in the liquidity table for the specific company
    if ($payment_method === 'cash' || $payment_method === 'debt') {
        // Calculate the total amount for the order
        $total_order_amount = array_reduce($order_items, function($carry, $item) {
            return $carry + $item['total'];
        }, 0);

        // Calculate the cash portion of the payment
        $cash_payment = $total_order_amount - $debt_amount;

        // Update the cash column in the liquidity table if there's any cash payment
        if ($cash_payment > 0) {
            $cash_update_query = $conn->prepare("UPDATE liquidity SET cash = cash + ? WHERE company_name = ?");
            $cash_update_query->bind_param("ds", $cash_payment, $company_name);
            $cash_update_query->execute();
        }
    }

    echo json_encode(['message' => 'Order completed successfully', 'order_number' => $order_number]);
?>
