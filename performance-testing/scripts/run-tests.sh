#!/bin/bash
# JMeter Test Runner Script for Linux/Mac
# This script helps run JMeter tests from the performance-testing folder

JMETER_HOME="${JMETER_HOME:-/opt/apache-jmeter-5.6}"
BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
RESULTS_DIR="$BASE_DIR/../results"

# Check if JMETER_HOME is set correctly
if [ ! -f "$JMETER_HOME/bin/jmeter" ]; then
    echo "Error: JMeter not found at $JMETER_HOME"
    echo "Please set JMETER_HOME variable to your JMeter installation directory"
    echo "Example: export JMETER_HOME=/opt/apache-jmeter-5.6"
    exit 1
fi

# Create results directory if it doesn't exist
mkdir -p "$RESULTS_DIR"

echo "============================================"
echo "JMeter Performance Test Runner"
echo "============================================"
echo ""
echo "Select test to run:"
echo "1. Full Booking Flow (Light Load - 10 users)"
echo "2. Full Booking Flow (Medium Load - 30 users)"
echo "3. Full Booking Flow (Heavy Load - 50 users)"
echo "4. API Load Test"
echo "5. Custom Test Plan"
echo ""

read -p "Enter choice (1-5): " choice

case $choice in
    1)
        TEST_PLAN="full-booking-flow.jmx"
        THREADS=10
        RAMPUP=10
        OUTPUT="light-load-test"
        ;;
    2)
        TEST_PLAN="full-booking-flow.jmx"
        THREADS=30
        RAMPUP=30
        OUTPUT="medium-load-test"
        ;;
    3)
        TEST_PLAN="full-booking-flow.jmx"
        THREADS=50
        RAMPUP=60
        OUTPUT="heavy-load-test"
        ;;
    4)
        TEST_PLAN="api-load-test.jmx"
        OUTPUT="api-load-test"
        ;;
    5)
        read -p "Enter test plan filename (.jmx): " TEST_PLAN
        read -p "Enter output name: " OUTPUT
        ;;
    *)
        echo "Invalid choice!"
        exit 1
        ;;
esac

if [ ! -f "$BASE_DIR/$TEST_PLAN" ]; then
    echo "Error: Test plan not found: $BASE_DIR/$TEST_PLAN"
    echo "Please create the test plan first using JMeter GUI"
    exit 1
fi

echo ""
echo "Running test: $TEST_PLAN"
echo "Results will be saved to: $RESULTS_DIR"
echo ""

cd "$BASE_DIR"

# Run JMeter in non-GUI mode
"$JMETER_HOME/bin/jmeter" -n -t "$TEST_PLAN" -l "$RESULTS_DIR/$OUTPUT.jtl" -e -o "$RESULTS_DIR/$OUTPUT-report"

if [ $? -eq 0 ]; then
    echo ""
    echo "============================================"
    echo "Test completed successfully!"
    echo "============================================"
    echo "Results saved to: $RESULTS_DIR/$OUTPUT.jtl"
    echo "HTML Report: $RESULTS_DIR/$OUTPUT-report/index.html"
    echo ""
else
    echo ""
    echo "============================================"
    echo "Test failed with errors!"
    echo "============================================"
    echo "Check jmeter.log for details"
    echo ""
fi
