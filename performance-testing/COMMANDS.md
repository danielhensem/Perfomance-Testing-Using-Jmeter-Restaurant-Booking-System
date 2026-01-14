# JMeter Command Reference

## Quick Command Reference

### Basic Command Structure
```bash
jmeter -n -t <test-plan.jmx> -l <results.jtl> -e -o <html-report-dir>
```

### Parameters
- `-n`: Run in non-GUI mode (required for load testing)
- `-t`: Test plan file (.jmx)
- `-l`: Results file (.jtl format)
- `-e`: Generate HTML report
- `-o`: HTML report output directory (must be empty or non-existent)
- `-j`: Log file (optional)
- `-J`: Set JMeter property (e.g., `-Jthreads=50`)

## Common Commands

### Run Basic Test
```bash
jmeter -n -t scripts/full-booking-flow.jmx -l results/results.jtl
```

### Run Test with HTML Report
```bash
jmeter -n -t scripts/full-booking-flow.jmx -l results/results.jtl -e -o results/html-report
```

### Run Test with Custom Properties
```bash
jmeter -n -t scripts/full-booking-flow.jmx -l results/results.jtl -Jthreads=50 -Jrampup=30
```

### Run Test from Specific Directory
```bash
cd performance-testing
jmeter -n -t scripts/full-booking-flow.jmx -l results/results.jtl -e -o results/html-report
```

### Run Test with Log File
```bash
jmeter -n -t scripts/full-booking-flow.jmx -l results/results.jtl -j results/jmeter.log
```

## Windows Commands

### Using JMeter.bat
```cmd
C:\apache-jmeter-5.6\bin\jmeter.bat -n -t scripts\full-booking-flow.jmx -l results\results.jtl -e -o results\html-report
```

### Using Full Path
```cmd
"C:\Program Files\Apache\JMeter\bin\jmeter.bat" -n -t scripts\full-booking-flow.jmx -l results\results.jtl -e -o results\html-report
```

### Using Script (if JMeter is in PATH)
```cmd
cd performance-testing
scripts\run-tests.bat
```

## Linux/Mac Commands

### Using JMeter Script
```bash
/opt/apache-jmeter-5.6/bin/jmeter -n -t scripts/full-booking-flow.jmx -l results/results.jtl -e -o results/html-report
```

### Using Script (if JMeter is in PATH)
```bash
cd performance-testing
chmod +x scripts/run-tests.sh
./scripts/run-tests.sh
```

### Using Full Path
```bash
/usr/local/bin/jmeter -n -t scripts/full-booking-flow.jmx -l results/results.jtl -e -o results/html-report
```

## Viewing Results

### Open HTML Report
```bash
# Windows
start results\html-report\index.html

# Mac
open results/html-report/index.html

# Linux
xdg-open results/html-report/index.html
```

### View JTL File in GUI
1. Open JMeter GUI
2. Add listener (View Results Tree, Summary Report, etc.)
3. Click "Browse" and select .jtl file

## Advanced Commands

### Run Multiple Tests Sequentially
```bash
jmeter -n -t scripts/test1.jmx -l results/test1.jtl
jmeter -n -t scripts/test2.jmx -l results/test2.jtl
```

### Run Test with Remote Servers
```bash
jmeter -n -t scripts/test.jmx -R server1,server2,server3 -l results/results.jtl
```

### Run Test with Properties File
```bash
jmeter -n -t scripts/test.jmx -p test-config.properties -l results/results.jtl
```

### Generate Summary Report from JTL
```bash
jmeter -g results/results.jtl -o results/summary-report
```

## Examples

### Example 1: Light Load Test
```bash
cd performance-testing
jmeter -n -t scripts/full-booking-flow.jmx -l results/light-load.jtl -e -o results/light-load-report
```

### Example 2: Medium Load Test
```bash
cd performance-testing
jmeter -n -t scripts/full-booking-flow.jmx -l results/medium-load.jtl -e -o results/medium-load-report
```

### Example 3: API Load Test
```bash
cd performance-testing
jmeter -n -t scripts/api-load-test.jmx -l results/api-test.jtl -e -o results/api-report
```

### Example 4: Custom Thread Count
```bash
cd performance-testing
jmeter -n -t scripts/full-booking-flow.jmx -Jthreads=100 -Jrampup=60 -l results/heavy-load.jtl -e -o results/heavy-load-report
```

## Troubleshooting Commands

### Check JMeter Version
```bash
jmeter -v
```

### Check Java Version
```bash
java -version
```

### Check JMeter Help
```bash
jmeter -h
```

### Run Test in GUI Mode (for debugging)
```bash
jmeter -t scripts/full-booking-flow.jmx
```

---

**Note**: Replace paths and filenames with your actual paths and test plan names.
