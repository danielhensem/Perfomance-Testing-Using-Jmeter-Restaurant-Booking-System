RESTAURANT BOOKING SYSTEM
==========================

SETUP INSTRUCTIONS FOR XAMPP:
-----------------------------

1. Copy the entire "restaurant-booking" folder to your XAMPP htdocs directory:
   C:\xampp\htdocs\restaurant-booking

2. Start XAMPP Control Panel

3. Start Apache server

4. Open your web browser and navigate to:
   http://localhost/restaurant-booking/

FEATURES:
---------
- 15-minute booking session timer
- Customer information form
- Menu selection with shopping cart
- DuitNow QR code payment (demo)
- Session expiration handling
- Real-time countdown timer display

HOW IT WORKS:
-------------
1. When you first visit the page, a 15-minute session timer starts
2. Fill in customer information
3. Select menu items and add to cart
4. Proceed to payment
5. View the DuitNow QR code (demo)
6. Confirm payment to complete booking
7. Session expires after 15 minutes if not completed

NOTE:
-----
- No database required - uses PHP sessions
- All booking data is stored in session only
- Session expires after 15 minutes as per standard
- Fake QR code is displayed for demonstration

