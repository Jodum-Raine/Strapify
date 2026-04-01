<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Strapify</title>
<link rel="stylesheet" href="Dashboard2.css?v=5">
<style>
    /* Additional styles for remove button */
    .card {
        position: relative;
    }
    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ff4c4c;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
    }
    .remove-btn:hover {
        transform: scale(1.1);
        background: #e04343;
    }
    .processing {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Orders Container Styles */
    .orders-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .order-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .order-id {
        font-weight: bold;
        color: #667eea;
    }

    .order-date {
        color: #999;
        font-size: 14px;
    }

    .status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: bold;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-accepted {
        background: #d4edda;
        color: #155724;
    }

    .status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .order-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .order-item-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .order-total {
        text-align: right;
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px solid #eee;
        font-size: 18px;
        font-weight: bold;
        color: #28a745;
    }

    .shipping-info {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
        font-size: 14px;
    }

    .no-orders {
        text-align: center;
        color: #666;
        padding: 50px;
        background: white;
        border-radius: 10px;
    }

    .loading {
        text-align: center;
        padding: 50px;
        color: white;
        font-size: 18px;
    }
    /* Items list styling */
.items-section {
    margin: 15px 0;
}

.items-header {
    cursor: pointer;
    margin: 10px 0;
    padding: 10px;
    background: #f0f0f0;
    border-radius: 5px;
    transition: background 0.3s;
}

.items-header:hover {
    background: #e0e0e0;
}

.items-list {
    margin-top: 10px;
}

.order-item {
    transition: transform 0.2s;
}

.order-item:hover {
    transform: translateX(5px);
}
</style>
</head>
<body>

<header class="navbar">
    <h2>Strapify</h2>
    <nav class="menu">
        <a href="#" class="active" onclick="showSection('home', event)">Home</a>
        <a href="#" onclick="showSection('items', event)">Items</a>
        <a href="#" onclick="showSection('order', event)">Order</a>
        <a href="#" onclick="showMyOrders(event)">Track Orders</a>
        <a href="#" onclick="showSection('about', event)">About Us</a>
        <a href="/projects/logout.php" class="logout-btn" onclick="return confirmLogout()">Logout</a>
    </nav>
</header>

<main class="main-content">

    <!-- HOME -->
    <section id="home" class="section active">
        <h1>Home</h1>
        <div class="card home-card">
            <p>Welcome to Strapify!</p>
        </div>
    </section>

    <!-- ITEMS -->
    <section id="items" class="section">
        <h1>Items</h1>
        <div class="items-grid">

            <!-- ITEM TEMPLATE -->
            <article class="item-card">
                <div class="img-wrapper">
                    <img src="pictures/y2k.jpg" class="item-img" alt="Y2K Beaded Phone Strap">
                </div>
                <h3>Y2K Beaded Phone Strap</h3>
                <p class="price">₱60.00</p>
                <p>A colorful, playful phone strap inspired by early 2000s fashion.</p>
                <div class="buy-section">
                  <input type="number" min="1" value="1" class="qty">
                  <button onclick="addToOrder('Y2K Beaded Phone Strap', 60, this)">Buy</button>
                </div>
            </article>

            <article class="item-card">
                <div class="img-wrapper">
                    <img src="pictures/cutepasterlPC.jpg" class="item-img" alt="Cute Pastel Phone Strap">
                </div>
                <h3>Cute Pastel Phone Strap</h3>
                <p class="price">₱50.00</p>
                <p>It gives a calm, "cute" aesthetic often associated with minimal and dreamy styles.</p>
                <div class="buy-section">
                  <input type="number" min="1" value="1" class="qty">
                  <button onclick="addToOrder('Cute Pastel Phone Strap', 50, this)">Buy</button>
                </div>
            </article>

            <article class="item-card">
                <div class="img-wrapper">
                    <img src="pictures/flower.jpg" class="item-img" alt="Flower">
                </div>
                <h3>Flower Bead Strap</h3>
                <p class="price">₱70.00</p>
                <p>A strap designed with beads arranged into flower shapes.</p>
                <div class="buy-section">
                  <input type="number" min="1" value="1" class="qty">
                  <button onclick="addToOrder('Flower Bead Strap', 70, this)">Buy</button>
                </div>
            </article>

            <article class="item-card">
                <div class="img-wrapper">
                    <img src="pictures/ocean.jpg" class="item-img" alt="Ocean">
                </div>
                <h3>Ocean Beaded Phone Strap</h3>
                <p class="price">₱60.00</p>
                <p>A classy and elegant strap using pearl-like beads.</p>
                <div class="buy-section">
                  <input type="number" min="1" value="1" class="qty">
                  <button onclick="addToOrder('Ocean Beaded Phone Strap', 60, this)">Buy</button>
                </div>
            </article>

            <article class="item-card">
                <div class="img-wrapper">
                    <img src="pictures/star.jpg" class="item-img" alt="star">
                </div>
                <h3>Star Bead Phone Strap</h3>
                <p class="price">₱55.00</p>
                <p>A dreamy strap using star-shaped beads.</p>
                <div class="buy-section">
                  <input type="number" min="1" value="1" class="qty">
                  <button onclick="addToOrder('Star Bead Phone Strap', 55, this)">Buy</button>
                </div>
            </article>

            <article class="item-card">
                <div class="img-wrapper">
                    <img src="pictures/coqutte.jpg" class="item-img" alt="coquette">
                </div>
                <h3>Coquette Bow Phone Strap</h3>
                <p class="price">₱50.00</p>
                <p>A feminine and trendy strap that includes ribbon or bow designs.</p>
                <div class="buy-section">
                  <input type="number" min="1" value="1" class="qty">
                  <button onclick="addToOrder('Coquette Bow Phone Strap', 50, this)">Buy</button>
                </div>
            </article>

        </div>
    </section>

    <!-- ORDER -->
    <section id="order" class="section">
        <h1>Your Shopping Cart</h1>
        <div class="order-container">
            <div id="order-list" class="order-list"></div>
            <div class="order-summary">
                <h3 id="total-price">Total Amount: ₱0</h3>
                <button class="checkout-btn" onclick="openCheckout()">Proceed to Checkout</button>
            </div>
        </div>
    </section>

    <!-- CHECKOUT MODAL -->
    <div id="checkout-modal" class="checkout-modal">
        <div class="checkout-box">
            <span class="close-btn" onclick="closeCheckout()">&times;</span>
            <h2>Complete Your Order</h2>
            
            <label>Full Address</label>
            <input type="text" id="address" placeholder="Enter your complete address" required>
            
            <label>Payment Method</label>
            <select id="payment" required>
                <option value="">Select payment method</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
                <option value="GCash">GCash</option>
                <option value="Maya">Maya</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>
            
            <button class="confirm-btn" onclick="confirmCheckout()">Place Order</button>
        </div>
    </div>

    <!-- MY ORDERS -->
    <section id="my-orders" class="section">
        <h1>📦 My Orders</h1>
        <div id="orders-container" class="orders-container">
            <div class="loading">Loading your orders...</div>
        </div>
    </section>
    
    <!-- ABOUT -->
    <section id="about" class="section">
        <h1>About Us - Strapify</h1>
        <div class="card">
            <p><strong>Welcome to Strapify!</strong></p>
            <p>We create stylish and durable phone straps and charms.</p>
            <p>Express yourself while keeping your phone secure.</p>
            <p><strong>Strapify - Style Your Phone Your Way.</strong></p>
        </div>
    </section>

</main>

<script>
// ----- SECTION SWITCHING -----
function showSection(sectionId, event) {
    event.preventDefault();
    document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');

    document.querySelectorAll('.menu a').forEach(link => link.classList.remove('active'));
    event.target.classList.add('active');
}

// ----- LOGOUT -----
function confirmLogout() {
    return confirm("Are you sure you want to log out?");
}

// ----- ORDER LOGIC -----
let orders = [];

function addToOrder(product, price, btn) {
    const qtyInput = btn.parentElement.querySelector('.qty');
    const quantity = parseInt(qtyInput.value);

    if (isNaN(quantity) || quantity <= 0) {
        alert("Please enter a valid quantity!");
        return;
    }

    const total = price * quantity;
    
    // Check if product already exists in cart
    const existingItem = orders.find(item => item.product === product);
    
    if (existingItem) {
        existingItem.quantity += quantity;
        existingItem.total = existingItem.price * existingItem.quantity;
        alert(`Updated ${product} quantity to ${existingItem.quantity}`);
    } else {
        orders.push({ product, price, quantity, total });
        alert(`${product} added to cart!`);
    }
    
    showOrders();
}

function showOrders() {
    const container = document.getElementById("order-list");
    container.innerHTML = "";
    
    let totalPrice = 0;
    
    if (orders.length === 0) {
        container.innerHTML = '<div class="card" style="text-align: center;">Your cart is empty. Add some items from the Items section!</div>';
        document.getElementById("total-price").innerText = "Total Amount: ₱0";
        return;
    }
    
    orders.forEach((item, index) => {
        container.innerHTML += `
            <div class="card" style="position: relative; margin-bottom: 15px;">
                <button class="remove-btn" onclick="removeFromOrder(${index})">×</button>
                <p><strong>${item.product}</strong></p>
                <p>Price: ₱${item.price}</p>
                <p>Quantity: ${item.quantity}</p>
                <p>Total: ₱${item.total}</p>
            </div>
        `;
        totalPrice += item.total;
    });
    
    document.getElementById("total-price").innerHTML = "Total Amount: ₱" + totalPrice.toFixed(2);
}

function removeFromOrder(index) {
    const removedItem = orders[index];
    orders.splice(index, 1);
    showOrders();
    alert(`${removedItem.product} removed from cart`);
}

// ----- CHECKOUT MODAL -----
const modal = document.getElementById("checkout-modal");

function closeCheckout() {
    modal.style.display = "none";
}

function openCheckout() {
    if (orders.length === 0) {
        alert("Your cart is empty! Please add items before checking out.");
        return;
    }
    modal.style.display = "flex";
    document.getElementById("address").value = "";
    document.getElementById("payment").value = "";
}

// Confirm checkout - SAVE TO DATABASE
async function confirmCheckout() {
    const address = document.getElementById("address").value.trim();
    const payment = document.getElementById("payment").value;

    if (!address || !payment) {
        alert("Please fill out all fields!");
        return;
    }
    
    // Calculate total
    const totalAmount = orders.reduce((sum, item) => sum + item.total, 0);
    
    // Prepare order data
    const orderData = {
        orders: orders,
        address: address,
        payment: payment,
        total: totalAmount
    };
    
    // Show loading state on button
    const confirmBtn = event.target;
    const originalText = confirmBtn.innerText;
    confirmBtn.innerText = "Processing...";
    confirmBtn.disabled = true;
    confirmBtn.style.opacity = "0.7";
    
    try {
        const response = await fetch('../save_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(orderData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert("✓ Order Placed Successfully!\n\n" + 
                  "Items: " + orders.length + " item(s)\n" +
                  "Total Amount: ₱" + totalAmount.toFixed(2) + "\n" +
                  "Payment: " + payment + "\n\n" +
                  "📦 All items will be shipped together.\n" +
                  "You can track your order in 'My Orders' section.");
            
            // Clear cart
            orders = [];
            showOrders();
            closeCheckout();
            
            // Refresh to show empty cart
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            alert("❌ Error placing order: " + result.message + "\n\nPlease try again or contact support.");
        }
    } catch (error) {
        console.error('Error:', error);
        alert("❌ Network error. Please check your connection and try again.");
    } finally {
        // Reset button state
        confirmBtn.innerText = originalText;
        confirmBtn.disabled = false;
        confirmBtn.style.opacity = "1";
    }
}

// Close modal when clicking outside
window.onclick = (event) => {
    if (event.target === modal) {
        closeCheckout();
    }
};

// Hide modal on page load
window.addEventListener('DOMContentLoaded', () => {
    if (modal) {
        modal.style.display = "none";
    }
    showOrders(); // Initialize empty cart display
});

// Function to show My Orders section
async function showMyOrders(event) {
    if (event) event.preventDefault();
    
    // Switch to my-orders section
    document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
    document.getElementById('my-orders').classList.add('active');
    
    // Update active menu link
    document.querySelectorAll('.menu a').forEach(link => link.classList.remove('active'));
    event.target.classList.add('active');
    
    // Fetch and display orders
    await fetchOrders();
}

// Function to fetch orders from database
async function fetchOrders() {
    const container = document.getElementById('orders-container');
    container.innerHTML = '<div class="loading">Loading your orders...</div>';
    
    try {
        const response = await fetch('../get_orders.php');
        
        // Check if response is ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Check if data is valid
        if (data && data.success) {
            if (data.orders && data.orders.length > 0) {
                displayOrders(data.orders);
            } else {
                container.innerHTML = `
                    <div class="no-orders">
                        <p>🛍️ You haven't placed any orders yet.</p>
                        <p>Go to the Items section to start shopping!</p>
                    </div>
                `;
            }
        } else {
            container.innerHTML = `
                <div class="no-orders">
                    <p>❌ ${data.message || 'Error loading orders'}</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error details:', error);
        container.innerHTML = `
            <div class="no-orders">
                <p>❌ Network error. Please try again.</p>
                <p style="font-size: 12px;">Error: ${error.message}</p>
            </div>
        `;
    }
}

// Function to display orders (always show items)
function displayOrders(orders) {
    const container = document.getElementById('orders-container');
    container.innerHTML = '';
    
    orders.forEach(order => {
        const orderCard = document.createElement('div');
        orderCard.className = 'order-card';
        
        // Create items list HTML
        let itemsHtml = '';
        
        if (order.items && order.items.length > 0) {
            itemsHtml = `
                <div class="items-section">
                    <div style="margin: 10px 0 5px 0; padding: 8px; background: #f0f0f0; border-radius: 5px;">
                        📦 <strong>${order.item_count} item(s)</strong> in this order:
                    </div>
            `;
            
            order.items.forEach(item => {
                itemsHtml += `
                    <div class="order-item" style="border-left: 3px solid #40a99b; margin-bottom: 10px;">
                        <div class="order-item-info">
                            <div>
                                <strong>${escapeHtml(item.product_name)}</strong>
                                <br>
                                <small>Quantity: ${item.quantity} × ₱${parseFloat(item.price).toFixed(2)}</small>
                            </div>
                            <div style="font-weight: bold; color: #28a745;">
                                ₱${parseFloat(item.total).toFixed(2)}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            itemsHtml += `</div>`;
        }
        
        orderCard.innerHTML = `
            <div class="order-header">
                <span class="order-id">Receipt #${order.id}</span>
                <span class="order-date">📅 ${new Date(order.created_at).toLocaleDateString()} ${new Date(order.created_at).toLocaleTimeString()}</span>
                <span class="status status-${order.status.toLowerCase()}">
                    ${order.status}
                </span>
            </div>
            
            ${itemsHtml}
            
            <div class="order-total">
                <strong>Total: ₱${order.total_amount.toFixed(2)}</strong>
            </div>
            
            <div class="shipping-info">
                <strong>📍 Address:</strong> ${escapeHtml(order.address)}<br>
                <strong>💳 Payment:</strong> ${escapeHtml(order.payment_method)}
            </div>
        `;
        
        container.appendChild(orderCard);
    });
}
// Helper function to escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
</body>
</html>