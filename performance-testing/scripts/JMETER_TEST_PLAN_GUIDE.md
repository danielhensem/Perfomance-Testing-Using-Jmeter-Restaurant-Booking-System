# JMeter Test Plan Creation Guide

## Overview
This guide explains how to create JMeter test plans for the Restaurant Booking System. Since JMeter test plans are best created using the JMeter GUI, this guide provides step-by-step instructions.

## Test Plan 1: Full Booking Flow

### Step 1: Create New Test Plan
1. Open JMeter GUI
2. Right-click **Test Plan** → **Add** → **Threads (Users)** → **Thread Group**
3. Configure Thread Group:
   - **Name**: Full Booking Flow - 10 Users
   - **Number of Threads (users)**: 10
   - **Ramp-up period (seconds)**: 10
   - **Loop Count**: 1

### Step 2: Add HTTP Request Defaults
1. Right-click **Thread Group** → **Add** → **Config Element** → **HTTP Request Defaults**
2. Configure:
   - **Name**: HTTP Request Defaults
   - **Server Name or IP**: `localhost`
   - **Port Number**: `80`
   - **Protocol**: `http`
   - **Path**: `/restaurant-booking/`

### Step 3: Add Cookie Manager
1. Right-click **Thread Group** → **Add** → **Config Element** → **HTTP Cookie Manager**
2. Keep default settings (handles cookies automatically)

### Step 4: Add HTTP Request - Home Page
1. Right-click **Thread Group** → **Add** → **Sampler** → **HTTP Request**
2. Configure:
   - **Name**: 01 - Load Home Page
   - **Path**: `index.php`
   - **Method**: GET

### Step 5: Add HTTP Request - Submit Booking
1. Right-click **Thread Group** → **Add** → **Sampler** → **HTTP Request**
2. Configure:
   - **Name**: 02 - Submit Customer Info
   - **Path**: `process_booking.php`
   - **Method**: POST
   - **Parameters**: Add the following
     - `name`: `Customer ${__threadNum}`
     - `email`: `customer${__threadNum}@test.com`
     - `phone`: `6012345678${__threadNum}`
     - `date`: `${__timeShift(yyyy-MM-dd,P1D,)}`
     - `time`: `18:00`
     - `guests`: `2`

**Note**: Use Functions for dynamic data:
- `${__threadNum}` - Thread number
- `${__time(yyyy-MM-dd)}` - Current date
- `${__Random(1,20)}` - Random number

### Step 6: Add HTTP Request - Load Menu
1. Right-click **Thread Group** → **Add** → **Sampler** → **HTTP Request**
2. Configure:
   - **Name**: 03 - Load Menu Page
   - **Path**: `index.php`
   - **Method**: GET

### Step 7: Add HTTP Request - Save Cart (JSON)
1. Right-click **Thread Group** → **Add** → **Sampler** → **HTTP Request**
2. Configure:
   - **Name**: 04 - Save Cart
   - **Path**: `save_cart.php`
   - **Method**: POST
   - **Body Data**:
     ```json
     {
       "cart": [
         {"id": 1, "name": "Chicken Biryani", "price": 25.00, "quantity": 2},
         {"id": 2, "name": "Beef Rendang", "price": 28.00, "quantity": 1}
       ],
       "total": 78.00
     }
     ```
   - **Header Manager** (add before HTTP Request):
     - Right-click **HTTP Request** → **Add** → **Config Element** → **HTTP Header Manager**
     - Add header: `Content-Type` = `application/json`

### Step 8: Add HTTP Request - Payment Page
1. Right-click **Thread Group** → **Add** → **Sampler** → **HTTP Request**
2. Configure:
   - **Name**: 05 - Load Payment Page
   - **Path**: `index.php?payment=1`
   - **Method**: GET

### Step 9: Add HTTP Request - Process Payment
1. Right-click **Thread Group** → **Add** → **Sampler** → **HTTP Request**
2. Configure:
   - **Name**: 06 - Process Payment
   - **Path**: `process_payment.php`
   - **Method**: POST
   - **Parameters**:
     - `payment_confirmed`: `on`

### Step 10: Add Timers (Think Time)
1. Right-click **HTTP Request (01 - Load Home Page)** → **Add** → **Timer** → **Constant Timer**
2. Configure:
   - **Thread Delay (in milliseconds)**: 1000 (1 second)
3. Repeat for other requests as needed

### Step 11: Add Listeners (for GUI mode)
1. Right-click **Thread Group** → **Add** → **Listener** → **View Results Tree**
2. Right-click **Thread Group** → **Add** → **Listener** → **Summary Report**
3. Right-click **Thread Group** → **Add** → **Listener** → **Aggregate Report**

### Step 12: Save Test Plan
1. **File** → **Save Test Plan As...**
2. Save as: `full-booking-flow.jmx`

## Test Plan 2: API Load Test

### Create Thread Group
- **Number of Threads**: 50
- **Ramp-up**: 30 seconds
- **Loop Count**: 10

### Add Requests
1. **HTTP Request Defaults** (same as above)
2. **HTTP Cookie Manager**
3. **HTTP Request - Load Home Page** (to initialize session)
4. **Loop Controller** → **Loop Count**: 20
   - Inside Loop: **HTTP Request - Save Cart** (repeat API calls)
5. **Add Timer**: Constant Timer (100ms between API calls)

## Test Plan 3: Mixed Workload

### Create Thread Group
- **Number of Threads**: 30
- **Ramp-up**: 60 seconds
- **Loop Count**: Forever (use Scheduler instead)
- **Scheduler**: Check "Scheduler"
- **Duration (seconds)**: 600 (10 minutes)

### Create Logic Controllers
1. **Random Controller** (50% chance)
   - Contains: Home page + Booking submission
2. **Random Controller** (30% chance)
   - Contains: Menu page + Cart operations
3. **Random Controller** (20% chance)
   - Contains: Payment page + Payment processing

## Using CSV Data

### Step 1: Add CSV Data Set Config
1. Right-click **Thread Group** → **Add** → **Config Element** → **CSV Data Set Config**
2. Configure:
   - **Name**: Customer Data
   - **Filename**: `../test-data/customers.csv`
   - **Variable Names**: `name,email,phone,date,time,guests`
   - **Delimiter**: `,`
   - **Recycle on EOF**: True
   - **Stop thread on EOF**: False
   - **Sharing mode**: All threads

### Step 2: Use Variables in Requests
In HTTP Request parameters, use:
- `${name}`
- `${email}`
- `${phone}`
- `${date}`
- `${time}`
- `${guests}`

## Best Practices

1. **Always use Cookie Manager** for session handling
2. **Use HTTP Request Defaults** to avoid repetition
3. **Add Think Times** (Timers) to simulate real user behavior
4. **Remove Listeners** before running in non-GUI mode
5. **Use Functions** for dynamic data (${__threadNum}, ${__time}, etc.)
6. **Save test plans** before running
7. **Test with 1 user first** to verify script works
8. **Use Assertions** to validate responses

## Running Tests

### GUI Mode (Development)
- Click **Start** button or press **Ctrl+R**
- Use for script development only

### Non-GUI Mode (Load Testing)
```bash
jmeter -n -t full-booking-flow.jmx -l results.jtl -e -o html-report
```

## Troubleshooting

### Sessions Not Working
- Ensure **HTTP Cookie Manager** is added
- Check that cookies are being captured

### JSON Request Not Working
- Add **HTTP Header Manager** with `Content-Type: application/json`
- Use **Body Data** tab (not Parameters) for JSON

### Test Too Fast/Slow
- Add/Adjust **Timers** between requests
- Modify **Ramp-up** period in Thread Group

### Memory Issues
- Reduce number of threads
- Remove unnecessary listeners in non-GUI mode
- Increase JVM heap size
