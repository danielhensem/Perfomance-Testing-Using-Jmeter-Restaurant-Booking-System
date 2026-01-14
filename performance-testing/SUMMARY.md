# Performance Testing Package - Summary

## Overview
This package contains everything you need to perform performance testing on the Restaurant Booking System using Apache JMeter.

## What's Included

### Documentation
1. **TEST_PLAN.md** - Comprehensive performance test plan document
2. **README.md** - Quick start guide and instructions
3. **COMMANDS.md** - Command reference for JMeter
4. **JMETER_TEST_PLAN_GUIDE.md** - Step-by-step guide to create test plans in JMeter GUI

### Test Data
- **customers.csv** - Sample customer data for testing
- **test-config.properties** - Test configuration properties

### Helper Scripts
- **run-tests.bat** - Windows batch script to run tests
- **run-tests.sh** - Linux/Mac shell script to run tests

### Folders
- **scripts/** - Place your .jmx test plan files here
- **results/** - Test results will be saved here
- **test-data/** - Test data files

## Quick Start

### 1. Install JMeter
- Download from: https://jmeter.apache.org/download_jmeter.cgi
- Requires Java 8 or higher

### 2. Create Test Plans
- Use JMeter GUI to create test plans
- Follow instructions in `scripts/JMETER_TEST_PLAN_GUIDE.md`
- Save test plans as .jmx files in the `scripts/` folder

### 3. Run Tests

**Basic Command:**
```bash
jmeter -n -t scripts/<test-plan.jmx> -l results/results.jtl -e -o results/html-report
```

**Using Helper Script (Windows):**
```cmd
cd performance-testing
scripts\run-tests.bat
```

**Using Helper Script (Linux/Mac):**
```bash
cd performance-testing
chmod +x scripts/run-tests.sh
./scripts/run-tests.sh
```

### 4. View Results
- Open `results/html-report/index.html` in a web browser
- Or use JMeter GUI listeners to view .jtl files

## Key Files

| File | Purpose |
|------|---------|
| TEST_PLAN.md | Complete test plan documentation |
| README.md | Quick start guide |
| COMMANDS.md | Command reference |
| JMETER_TEST_PLAN_GUIDE.md | How to create test plans |
| customers.csv | Test data |
| run-tests.bat/sh | Helper scripts |

## Next Steps

1. **Read TEST_PLAN.md** - Understand the testing strategy
2. **Read README.md** - Get familiar with the setup
3. **Create Test Plans** - Use JMeter GUI (see JMETER_TEST_PLAN_GUIDE.md)
4. **Run Tests** - Use commands in COMMANDS.md
5. **Analyze Results** - Review HTML reports and metrics

## Notes

- Test plans (.jmx files) must be created using JMeter GUI
- Always use non-GUI mode (`-n` flag) for actual load testing
- Results are saved in JTL format and can be viewed as HTML reports
- Test data files can be customized as needed

---

**Created**: 2025-01-14  
**Testing Tool**: Apache JMeter  
**System**: Restaurant Booking System
