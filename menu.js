let cart = [];
let subtotal = 0;
let orderNumberGenerated = false;
const discount = 0.00;
const taxRate = 0.06;

function addToCart(product, price) {
    if (!orderNumberGenerated) {
        const orderNumber = generateOrderNumber();
        document.getElementById('order-number').textContent = orderNumber;
        document.getElementById('hidden-order-number').value = orderNumber;
        orderNumberGenerated = true;
    }
    cart.push({ product, price });
    updateOrderSummary();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateOrderSummary();
}

function updateOrderSummary() {
    const orderList = document.getElementById('order-list');
    orderList.innerHTML = '';
    subtotal = 0;
    cart.forEach((item, index) => {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `${item.product} - ₱${item.price.toFixed(2)}
            <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">&times;</button>`;
        orderList.appendChild(li);
        subtotal += item.price;
    });

    const tax = subtotal * taxRate;
    const total = subtotal - discount + tax;

    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('tax').textContent = tax.toFixed(2);
    document.getElementById('total').textContent = total.toFixed(2);

    const productNames = cart.map(item => item.product);
    document.getElementById('hidden-products').value = JSON.stringify(productNames);
    document.getElementById('hidden-subtotal').value = subtotal.toFixed(2);
    document.getElementById('hidden-discount').value = discount.toFixed(2);
    document.getElementById('hidden-tax').value = tax.toFixed(2);
    document.getElementById('hidden-total').value = total.toFixed(2);
}

function generateOrderNumber() {
    return 'ORD-' + Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
}

document.getElementById('place-order').addEventListener('click', (e) => {
    if (cart.length === 0) {
        e.preventDefault();
        alert('No items in the cart to place an order.');
    }
});
