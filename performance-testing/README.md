# Performance Testing - Quick Start Guide (JMeter)

## Overview
This folder contains Apache JMeter performance testing plans, documentation, and resources for the Restaurant Booking System.

## Folder Structure
```
performance-testing/
├── README.md                    # This file
├── TEST_PLAN.md                # Comprehensive test plan document
├── scripts/                    # JMeter test plans (.jmx files)
│   ├── full-booking-flow.jmx
│   ├── api-load-test.jmx
│   └── mixed-workload.jmx
├── test-data/                  # Test data files (CSV)
│   ├── customers.csv
│   └── test-config.properties
└── results/                    # Test results (generated after tests)
```

## Prerequisites

### 1. Install Java Runtime Environment (JRE)
JMeter requires Java 8 or higher.

**Check if Java is installed:**
```bash
java -version
```

**Download Java:**
- Windows/Mac/Linux: https://www.oracle.com/java/technologies/downloads/

### 2. Install Apache JMeter
**Download JMeter:**
1. Download from: https://jmeter.apache.org/download_jmeter.cgi
2. Extract the ZIP file to a directory (e.g., `C:\apache-jmeter-5.6` or `/opt/apache-jmeter-5.6`)
3. Add JMeter `bin` directory to PATH (optional but recommended)

**Windows:**
- Extract to `C:\apache-jmeter-5.6`
- Add `C:\apache-jmeter-5.6\bin` to System PATH
- Or run directly: `C:\apache-jmeter-5.6\bin\jmeter.bat`

**Mac/Linux:**
- Extract to `/opt/apache-jmeter-5.6`
- Create symlink: `sudo ln -s /opt/apache-jmeter-5.6/bin/jmeter /usr/local/bin/jmeter`
- Or run directly: `/opt/apache-jmeter-5.6/bin/jmeter`

### 3. Ensure Server is Running
- Start XAMPP (Apache server)
- Ensure the application is accessible at: `http://localhost/restaurant-booking/`
- Verify the application is working correctly manually first

## Quick Start

### Step 1: Review Test Plan
Read `TEST_PLAN.md` to understand the testing strategy and objectives.

### Step 2: Launch JMeter GUI
```bash
# Windows
jmeter.bat

# Mac/Linux
jmeter
```

Or navigate to JMeter directory and run:
```bash
# Windows
cd C:\apache-jmeter-5.6\bin
jmeter.bat

# Mac/Linux
cd /opt/apache-jmeter-5.6/bin
./jmeter
```

### Step 3: Open Test Plan
1. In JMeter GUI, click **File** → **Open**
2. Navigate to `performance-testing/scripts/`
3. Select a test plan (e.g., `full-booking-flow.jmx`)
4. Review the test plan configuration

### Step 4: Configure Test Plan
1. Update the **Base URL** in HTTP Request Defaults (if needed)
2. Review thread groups and their configurations
3. Check test data file paths if using CSV Data Set Config

### Step 5: Run Test Plan

**Option 1: GUI Mode (for development/debugging)**
1. Click the **Start** button (green play icon) or press `Ctrl+R`
2. Watch the results in real-time
3. Stop when done

**Option 2: Non-GUI Mode (for actual load testing)**
```bash
# Windows
jmeter -n -t scripts/full-booking-flow.jmx -l results/results.jtl -e -o results/html-report

# Mac/Linux
jmeter -n -t scripts/full-booking-flow.jmx -l results/results.jtl -e -o results/html-report
```

**Command Parameters:**
- `-n`: Non-GUI mode
- `-t`: Test plan file path
- `-l`: Results file (JTL format)
- `-e`: Generate HTML report
- `-o`: HTML report output directory

### Step 6: View Results
- **GUI Mode**: View results in listeners (View Results Tree, Summary Report, etc.)
- **Non-GUI Mode**: Open `results/html-report/index.html` in a web browser

## Available Test Plans

### 1. Full Booking Flow Test
**File**: `scripts/full-booking-flow.jmx`

Simulates complete user journey from customer info to payment.

**Run Command:**
```bash
jmeter -n -t scripts/full-booking-flow.jmx -l results/full-booking-flow.jtl -e -o results/full-booking-report
```

**Configuration:**
- Threads: 10-50 users (configurable)
- Ramp-up: 10 seconds
- Loop: 1 iteration per user

### 2. API Load Test
**File**: `scripts/api-load-test.jmx`

Tests AJAX endpoints (cart saving) with high concurrency.

**Run Command:**
```bash
jmeter -n -t scripts/api-load-test.jmx -l results/api-load-test.jtl -e -o results/api-load-report
```

**Configuration:**
- Threads: 50-100 users (configurable)
- Focus: API endpoint performance

### 3. Mixed Workload Test
**File**: `scripts/mixed-workload.jmx`

Simulates realistic user behavior with mixed activities.

**Run Command:**
```bash
jmeter -n -t scripts/mixed-workload.jmx -l results/mixed-workload.jtl -e -o results/mixed-workload-report
```

## Running Different Test Scenarios

### Light Load Test
Edit test plan: Set Threads = 10, Ramp-up = 10 seconds
```bash
jmeter -n -t scripts/full-booking-flow.jmx -l results/light-load.jtl -e -o results/light-load-report
```

### Medium Load Test
Edit test plan: Set Threads = 30, Ramp-up = 30 seconds
```bash
jmeter -n -t scripts/full-booking-flow.jmx -l results/medium-load.jtl -e -o results/medium-load-report
```

### Heavy Load Test
Edit test plan: Set Threads = 50, Ramp-up = 60 seconds
```bash
jmeter -n -t scripts/full-booking-flow.jmx -n -t scripts/full-booking-flow.jmx -l results/heavy-load.jtl -e -o results/heavy-load-report
```

### Stress Test
Edit test plan: Set Threads = 100, Ramp-up = 120 seconds
```bash
jmeter -n -t scripts/full-booking-flow.jmx -l results/stress-test.jtl -e -o results/stress-test-report
```

### Endurance Test
Edit test plan: Set Threads = 30, Duration = 3600 seconds (1 hour)
```bash
jmeter -n -t scripts/full-booking-flow.jmx -l results/endurance-test.jtl -e -o results/endurance-test-report
```

## Understanding JMeter Results

### Key Metrics

1. **Sample**: Individual request
2. **Average (ms)**: Average response time
3. **Min/Max (ms)**: Minimum/Maximum response time
4. **Std Dev**: Standard deviation (consistency measure)
5. **Error %**: Percentage of failed requests
6. **Throughput**: Requests per second
7. **KB/sec**: Data transfer rate

### Important Metrics to Monitor

- **Average Response Time**: Should be < 2000ms
- **95th Percentile**: 95% of requests should be < 2000ms
- **Error Rate**: Should be < 1%
- **Throughput**: Requests per second
- **Active Threads**: Number of concurrent users

## Customizing Test Plans

### Changing Base URL
1. Open test plan in JMeter GUI
2. Expand **Test Plan** → **Config Element** → **HTTP Request Defaults**
3. Update **Server Name or IP** field
4. Save test plan

### Adjusting Load
1. Open test plan in JMeter GUI
2. Select **Thread Group**
3. Update:
   - **Number of Threads (users)**
   - **Ramp-up period (seconds)**
   - **Loop Count** or **Duration**
4. Save test plan

### Using Test Data
1. Add **CSV Data Set Config** element
2. Point to CSV file in `test-data/` folder
3. Configure variable names
4. Use variables in HTTP requests (e.g., `${name}`, `${email}`)

## Generating Reports

### HTML Report (Recommended)
```bash
jmeter -n -t script.jmx -l results.jtl -e -o html-report-folder
```

### Summary Report (Console)
```bash
jmeter -n -t script.jmx -l results.jtl -g results.jtl -o summary-report.html
```

### View JTL File
1. Open JMeter GUI
2. Add **View Results Tree** or **Summary Report** listener
3. Click **Browse** and select `.jtl` file

## JMeter GUI Best Practices

### For Test Development
- Use GUI mode for creating/editing test plans
- Use **View Results Tree** for debugging
- Use **Summary Report** for quick overview

### For Load Testing
- **NEVER use GUI mode for actual load testing**
- Always use non-GUI mode (`-n` flag)
- GUI mode consumes significant resources

## Command-Line Reference

### Basic Command
```bash
jmeter -n -t <test-plan.jmx> -l <results.jtl> -e -o <html-report-dir>
```

### Advanced Options
```bash
jmeter -n -t script.jmx \
  -l results.jtl \
  -e -o html-report \
  -Jthreads=50 \
  -Jrampup=30 \
  -Jduration=300 \
  -j jmeter.log
```

### Properties File
Create `jmeter.properties` or pass properties:
```bash
jmeter -n -t script.jmx -p custom.properties
```

## Troubleshooting

### Issue: Java Not Found
- **Solution**: Install Java and add to PATH

### Issue: JMeter Not Found
- **Solution**: Use full path or add to PATH

### Issue: Connection Refused
- **Solution**: Ensure Apache server is running

### Issue: Out of Memory
- **Solution**: Increase heap size in `jmeter.bat` or `jmeter.sh`:
  ```bash
  set HEAP=-Xms1g -Xmx4g -XX:MaxMetaspaceSize=256m
  ```

### Issue: High Error Rate
- **Solution**: Reduce threads or check server resources

### Issue: Session Conflicts
- **Solution**: Expected behavior - ensure Cookie Manager is enabled

## Performance Benchmarks

### Expected Performance (Reference)

| Scenario | Threads | Avg Response Time | 95th Percentile | Error Rate |
|----------|---------|-------------------|-----------------|------------|
| Light Load | 10-20 | < 500ms | < 1s | < 0.1% |
| Medium Load | 30-50 | < 1s | < 2s | < 0.5% |
| Heavy Load | 50-100 | < 1.5s | < 3s | < 1% |
| Stress Test | 100+ | Variable | Variable | < 5% |

*Note: Actual results may vary based on hardware and environment*

## Best Practices

1. **Use Non-GUI Mode**: Always use `-n` flag for load testing
2. **Start Small**: Begin with light load and gradually increase
3. **Monitor Resources**: Watch CPU, memory, and disk usage
4. **Run Multiple Times**: Execute tests multiple times for consistency
5. **Save Results**: Keep JTL files for comparison and analysis
6. **Use HTML Reports**: Generate HTML reports for easy sharing
7. **Isolate Environment**: Test in isolated environment
8. **Review Logs**: Check `jmeter.log` for errors

## Additional Resources

- **JMeter Documentation**: https://jmeter.apache.org/usermanual/
- **JMeter Tutorial**: https://jmeter.apache.org/usermanual/get-started.html
- **Test Plan Examples**: See `scripts/` folder
- **Performance Testing Best Practices**: See `TEST_PLAN.md`

## Support

For questions or issues:
1. Review `TEST_PLAN.md` for detailed information
2. Check JMeter documentation
3. Review server logs for errors
4. Verify server configuration
5. Check `jmeter.log` for JMeter errors

---

**Last Updated**: 2025-01-14
