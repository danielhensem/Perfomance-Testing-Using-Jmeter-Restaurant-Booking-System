<?php
session_start();

// Check if session has expired
if (isset($_SESSION['booking_expires_at']) && time() > $_SESSION['booking_expires_at']) {
    session_unset();
    session_destroy();
    header('Location: index.php?expired=1');
    exit();
}

// Initialize booking session if starting new booking
if (!isset($_SESSION['booking_started'])) {
    $_SESSION['booking_started'] = true;
    $_SESSION['booking_start_time'] = time();
    $_SESSION['booking_expires_at'] = time() + (15 * 60); // 15 minutes
    $_SESSION['booking_id'] = 'BK' . strtoupper(substr(uniqid(), -8));
    $_SESSION['items'] = [];
    $_SESSION['total'] = 0;
}

$time_left = $_SESSION['booking_expires_at'] - time();
$minutes_left = floor($time_left / 60);
$seconds_left = $time_left % 60;

// Menu items
$menu_items = [
    ['id' => 1, 'name' => 'Chicken Biryani', 'price' => 25.00, 'image' => '🍛'],
    ['id' => 2, 'name' => 'Beef Rendang', 'price' => 28.00, 'image' => '🍖'],
    ['id' => 3, 'name' => 'Nasi Lemak', 'price' => 12.00, 'image' => '🍚'],
    ['id' => 4, 'name' => 'Char Kway Teow', 'price' => 15.00, 'image' => '🍜'],
    ['id' => 5, 'name' => 'Hainanese Chicken Rice', 'price' => 18.00, 'image' => '🍗'],
    ['id' => 6, 'name' => 'Laksa', 'price' => 16.00, 'image' => '🍲'],
    ['id' => 7, 'name' => 'Roti Canai', 'price' => 8.00, 'image' => '🥞'],
    ['id' => 8, 'name' => 'Mee Goreng', 'price' => 14.00, 'image' => '🍝'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Booking System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🍽️ Restaurant Booking</h1>
            <?php if (isset($_GET['expired'])): ?>
                <div class="alert alert-error">Your booking session has expired. Please start a new booking.</div>
            <?php endif; ?>
            <div class="session-info">
                <div class="booking-id">Booking ID: <strong><?php echo htmlspecialchars($_SESSION['booking_id']); ?></strong></div>
                <div class="timer" id="timer">
                    <span class="timer-label">Time Left:</span>
                    <span class="timer-value" id="timerValue"><?php echo sprintf('%02d:%02d', $minutes_left, $seconds_left); ?></span>
                </div>
            </div>
        </header>

        <main>
            <!-- Customer Information -->
            <section class="section" id="customerSection">
                <h2>Customer Information</h2>
                <form action="process_booking.php" method="POST" id="customerForm">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Booking Date *</label>
                        <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="time">Booking Time *</label>
                        <input type="time" id="time" name="time" required>
                    </div>
                    <div class="form-group">
                        <label for="guests">Number of Guests *</label>
                        <input type="number" id="guests" name="guests" min="1" max="20" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Continue to Menu</button>
                </form>
            </section>

            <!-- Menu Section -->
            <section class="section" id="menuSection" style="display: none;">
                <h2>Menu</h2>
                <div class="menu-grid">
                    <?php foreach ($menu_items as $item): ?>
                        <div class="menu-item">
                            <div class="menu-item-image"><?php echo $item['image']; ?></div>
                            <div class="menu-item-info">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="price">RM <?php echo number_format($item['price'], 2); ?></p>
                                <button class="btn btn-small" onclick="addToCart(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['name']); ?>', <?php echo $item['price']; ?>)">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="cart-section">
                    <h3>Your Order</h3>
                    <div id="cartItems"></div>
                    <div class="cart-total">
                        <strong>Total: RM <span id="totalAmount">0.00</span></strong>
                    </div>
                    <button class="btn btn-primary" onclick="proceedToPayment()" id="proceedBtn" style="display: none;">
                        Proceed to Payment
                    </button>
                </div>
            </section>

            <!-- Payment Section -->
            <section class="section" id="paymentSection" style="display: none;">
                <h2>Payment</h2>
                <div class="payment-info">
                    <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($_SESSION['booking_id']); ?></p>
                    <p><strong>Total Amount:</strong> RM <span id="paymentTotal">0.00</span></p>
                </div>
                <div class="payment-methods">
                    <h3>Scan DuitNow QR Code</h3>
                    <div class="qr-code-container">
                        <div class="qr-code">
                            <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                                <!-- Fake QR Code Pattern -->
                                <rect width="200" height="200" fill="white"/>
                                <rect x="10" y="10" width="40" height="40" fill="black"/>
                                <rect x="15" y="15" width="30" height="30" fill="white"/>
                                <rect x="20" y="20" width="20" height="20" fill="black"/>
                                
                                <rect x="150" y="10" width="40" height="40" fill="black"/>
                                <rect x="155" y="15" width="30" height="30" fill="white"/>
                                <rect x="160" y="20" width="20" height="20" fill="black"/>
                                
                                <rect x="10" y="150" width="40" height="40" fill="black"/>
                                <rect x="15" y="155" width="30" height="30" fill="white"/>
                                <rect x="20" y="160" width="20" height="20" fill="black"/>
                                
                                <!-- Random pattern for QR code look -->
                                <rect x="60" y="10" width="15" height="15" fill="black"/>
                                <rect x="80" y="10" width="10" height="10" fill="black"/>
                                <rect x="100" y="15" width="12" height="12" fill="black"/>
                                <rect x="60" y="30" width="8" height="8" fill="black"/>
                                <rect x="75" y="25" width="15" height="10" fill="black"/>
                                <rect x="95" y="30" width="10" height="15" fill="black"/>
                                
                                <rect x="10" y="60" width="12" height="12" fill="black"/>
                                <rect x="30" y="65" width="10" height="10" fill="black"/>
                                <rect x="50" y="70" width="15" height="8" fill="black"/>
                                
                                <rect x="150" y="60" width="12" height="12" fill="black"/>
                                <rect x="170" y="65" width="10" height="10" fill="black"/>
                                <rect x="140" y="70" width="15" height="8" fill="black"/>
                                
                                <rect x="60" y="150" width="15" height="15" fill="black"/>
                                <rect x="80" y="150" width="10" height="10" fill="black"/>
                                <rect x="100" y="155" width="12" height="12" fill="black"/>
                                <rect x="60" y="170" width="8" height="8" fill="black"/>
                                <rect x="75" y="175" width="15" height="10" fill="black"/>
                                <rect x="95" y="170" width="10" height="15" fill="black"/>
                                
                                <rect x="70" y="80" width="10" height="10" fill="black"/>
                                <rect x="85" y="85" width="8" height="8" fill="black"/>
                                <rect x="100" y="90" width="12" height="12" fill="black"/>
                                <rect x="120" y="85" width="10" height="10" fill="black"/>
                                
                                <rect x="70" y="110" width="15" height="8" fill="black"/>
                                <rect x="90" y="115" width="10" height="15" fill="black"/>
                                <rect x="110" y="110" width="8" height="8" fill="black"/>
                                <rect x="125" y="115" width="12" height="12" fill="black"/>
                            </svg>
                        </div>
                        <p class="qr-instruction">Scan with your banking app to pay via DuitNow</p>
                        <p class="qr-note">This is a demo QR code</p>
                    </div>
                    <form action="process_payment.php" method="POST" id="paymentForm">
                        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($_SESSION['booking_id']); ?>">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="payment_confirmed" required>
                                I confirm that I have completed the payment
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-large">Complete Booking</button>
                    </form>
                </div>
            </section>

            <!-- Success Section -->
            <section class="section" id="successSection" style="display: none;">
                <div class="success-message">
                    <h2>✅ Booking Confirmed!</h2>
                    <p>Your booking has been successfully completed.</p>
                    <p><strong>Booking ID:</strong> <span id="successBookingId"></span></p>
                    <a href="index.php" class="btn btn-primary">Start New Booking</a>
                </div>
            </section>
        </main>
    </div>

    <script>
        const expiresAt = <?php echo $_SESSION['booking_expires_at']; ?> * 1000; // Convert to milliseconds
        const bookingId = '<?php echo $_SESSION['booking_id']; ?>';
        let cart = <?php echo json_encode($_SESSION['items']); ?>;
        let total = <?php echo $_SESSION['total']; ?>;

        // Update cart display
        function updateCart() {
            const cartItems = document.getElementById('cartItems');
            const totalAmount = document.getElementById('totalAmount');
            const proceedBtn = document.getElementById('proceedBtn');
            
            if (cart.length === 0) {
                cartItems.innerHTML = '<p>Your cart is empty</p>';
                proceedBtn.style.display = 'none';
            } else {
                cartItems.innerHTML = cart.map(item => `
                    <div class="cart-item">
                        <span>${item.name} x${item.quantity}</span>
                        <span>RM ${(item.price * item.quantity).toFixed(2)}</span>
                        <button onclick="removeFromCart(${item.id})" class="btn-remove">×</button>
                    </div>
                `).join('');
                proceedBtn.style.display = 'block';
            }
            totalAmount.textContent = total.toFixed(2);
            if (document.getElementById('paymentTotal')) {
                document.getElementById('paymentTotal').textContent = total.toFixed(2);
            }
        }

        function addToCart(id, name, price) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id, name, price, quantity: 1 });
            }
            total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            updateCart();
            saveCart();
        }

        function removeFromCart(id) {
            const index = cart.findIndex(item => item.id === id);
            if (index > -1) {
                cart[index].quantity--;
                if (cart[index].quantity === 0) {
                    cart.splice(index, 1);
                }
            }
            total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            updateCart();
            saveCart();
        }

        function saveCart() {
            // Save to server via AJAX
            fetch('save_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ cart, total })
            });
        }

        function proceedToPayment() {
            if (cart.length === 0) {
                alert('Please add items to cart first');
                return;
            }
            // Save cart before proceeding
            saveCart();
            document.getElementById('menuSection').style.display = 'none';
            document.getElementById('paymentSection').style.display = 'block';
            // Update payment total
            if (document.getElementById('paymentTotal')) {
                document.getElementById('paymentTotal').textContent = total.toFixed(2);
            }
        }

        // Timer countdown
        function updateTimer() {
            const now = Date.now();
            const timeLeft = expiresAt - now;
            
            if (timeLeft <= 0) {
                alert('Your booking session has expired. Please start a new booking.');
                window.location.href = 'index.php?expired=1';
                return;
            }
            
            const minutes = Math.floor(timeLeft / 60000);
            const seconds = Math.floor((timeLeft % 60000) / 1000);
            const timerElement = document.getElementById('timerValue');
            if (timerElement) {
                timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                
                // Change color when less than 2 minutes
                if (timeLeft <= 120000) {
                    timerElement.style.color = '#ff6b6b';
                }
            }
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        // Handle form submissions
        const customerForm = document.getElementById('customerForm');
        if (customerForm) {
            customerForm.addEventListener('submit', function(e) {
                // Check if session expired
                if (Date.now() > expiresAt) {
                    e.preventDefault();
                    alert('Your booking session has expired. Please refresh the page.');
                    window.location.href = 'index.php?expired=1';
                    return;
                }
            });
        }

        const paymentForm = document.getElementById('paymentForm');
        if (paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                // Check if session expired
                if (Date.now() > expiresAt) {
                    e.preventDefault();
                    alert('Your booking session has expired. Please start a new booking.');
                    window.location.href = 'index.php?expired=1';
                    return;
                }
            });
        }

        // Initialize cart display
        updateCart();

        // Show menu section if customer info is already filled
        <?php if (isset($_SESSION['customer_info'])): ?>
            document.getElementById('customerSection').style.display = 'none';
            document.getElementById('menuSection').style.display = 'block';
        <?php endif; ?>

        // Show payment section if redirected from menu
        <?php if (isset($_GET['payment'])): ?>
            document.getElementById('customerSection').style.display = 'none';
            document.getElementById('menuSection').style.display = 'none';
            document.getElementById('paymentSection').style.display = 'block';
        <?php endif; ?>

        // Show success section
        <?php if (isset($_GET['success'])): ?>
            document.getElementById('customerSection').style.display = 'none';
            document.getElementById('menuSection').style.display = 'none';
            document.getElementById('paymentSection').style.display = 'none';
            document.getElementById('successSection').style.display = 'block';
            <?php if (isset($_SESSION['last_booking_id'])): ?>
                document.getElementById('successBookingId').textContent = '<?php echo htmlspecialchars($_SESSION['last_booking_id']); ?>';
            <?php else: ?>
                document.getElementById('successBookingId').textContent = bookingId;
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>
</html>

