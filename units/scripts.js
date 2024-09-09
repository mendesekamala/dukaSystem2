function liveSearch() {
    const searchQuery = document.getElementById('productSearch').value;
    if (searchQuery.length === 0) {
        document.getElementById('suggestions').innerHTML = '';
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'search_product.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // Debugging: log the response text
            const products = JSON.parse(xhr.responseText);
            let suggestionsHTML = '';
            products.forEach(product => {
                suggestionsHTML += `<div class="suggestions-box" onclick="selectProduct(${product.id}, '${product.product_name}')">${product.product_name}</div>`;
            });
            document.getElementById('suggestions').innerHTML = suggestionsHTML;
        }
    };
    xhr.send('search_query=' + encodeURIComponent(searchQuery));
}

function selectProduct(productId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'get_product.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const product = JSON.parse(xhr.responseText);
            document.getElementById('product_name').value = product.product_name;
            document.getElementById('product_nickname').value = product.product_nickname;
            document.getElementById('product_description').value = product.product_description;
            document.getElementById('buying_price').value = product.buying_price;
            document.getElementById('selling_price').value = product.selling_price;
            document.getElementById('quantity').value = product.quantity;
            document.getElementById('expiry_date').value = product.expiry_date;
            document.getElementById('date_bought').value = product.date_bought;
            document.getElementById('supplier').value = product.supplier;
            document.getElementById('suggestions').innerHTML = '';
            document.getElementById('search').value = product.product_name;
        }
    };
    xhr.send('product_id=' + productId);
}

function selectProduct(productId, productName) {
    // Set the selected product's ID and name to the respective fields
    document.getElementById('productId').value = productId;
    document.getElementById('productName').value = productName;

    // Clear suggestions
    document.getElementById('suggestions').innerHTML = '';
}