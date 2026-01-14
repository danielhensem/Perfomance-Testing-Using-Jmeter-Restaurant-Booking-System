@echo off
REM JMeter Test Runner Script for Windows
REM This script helps run JMeter tests from the performance-testing folder

set JMETER_HOME=C:\apache-jmeter-5.6
set BASE_DIR=%~dp0
set RESULTS_DIR=%BASE_DIR%..\results

REM Check if JMETER_HOME is set correctly
if not exist "%JMETER_HOME%\bin\jmeter.bat" (
    echo Error: JMeter not found at %JMETER_HOME%
    echo Please set JMETER_HOME variable to your JMeter installation directory
    echo Example: set JMETER_HOME=C:\apache-jmeter-5.6
    pause
    exit /b 1
)

REM Create results directory if it doesn't exist
if not exist "%RESULTS_DIR%" mkdir "%RESULTS_DIR%"

echo ============================================
echo JMeter Performance Test Runner
echo ============================================
echo.
echo Select test to run:
echo 1. Full Booking Flow (Light Load - 10 users)
echo 2. Full Booking Flow (Medium Load - 30 users)
echo 3. Full Booking Flow (Heavy Load - 50 users)
echo 4. API Load Test
echo 5. Custom Test Plan
echo.

set /p choice="Enter choice (1-5): "

if "%choice%"=="1" (
    set TEST_PLAN=full-booking-flow.jmx
    set THREADS=10
    set RAMPUP=10
    set OUTPUT=light-load-test
    goto :run
)

if "%choice%"=="2" (
    set TEST_PLAN=full-booking-flow.jmx
    set THREADS=30
    set RAMPUP=30
    set OUTPUT=medium-load-test
    goto :run
)

if "%choice%"=="3" (
    set TEST_PLAN=full-booking-flow.jmx
    set THREADS=50
    set RAMPUP=60
    set OUTPUT=heavy-load-test
    goto :run
)

if "%choice%"=="4" (
    set TEST_PLAN=api-load-test.jmx
    set OUTPUT=api-load-test
    goto :run
)

if "%choice%"=="5" (
    set /p TEST_PLAN="Enter test plan filename (.jmx): "
    set /p OUTPUT="Enter output name: "
    goto :run
)

echo Invalid choice!
pause
exit /b 1

:run
if not exist "%BASE_DIR%%TEST_PLAN%" (
    echo Error: Test plan not found: %BASE_DIR%%TEST_PLAN%
    echo Please create the test plan first using JMeter GUI
    pause
    exit /b 1
)

echo.
echo Running test: %TEST_PLAN%
echo Results will be saved to: %RESULTS_DIR%
echo.

cd /d "%BASE_DIR%"

REM Run JMeter in non-GUI mode
"%JMETER_HOME%\bin\jmeter.bat" -n -t "%TEST_PLAN%" -l "%RESULTS_DIR%\%OUTPUT%.jtl" -e -o "%RESULTS_DIR%\%OUTPUT%-report"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ============================================
    echo Test completed successfully!
    echo ============================================
    echo Results saved to: %RESULTS_DIR%\%OUTPUT%.jtl
    echo HTML Report: %RESULTS_DIR%\%OUTPUT%-report\index.html
    echo.
) else (
    echo.
    echo ============================================
    echo Test failed with errors!
    echo ============================================
    echo Check jmeter.log for details
    echo.
)

pause
