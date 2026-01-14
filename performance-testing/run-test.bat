@echo off
REM Quick JMeter Test Runner for Restaurant Booking System
REM Uses the JMeter installation at: C:\Users\Irfan\Desktop\software\apache-jmeter-5.6.3

set JMETER_BIN=C:\Users\Irfan\Desktop\software\apache-jmeter-5.6.3\bin
set SCRIPT_DIR=%~dp0

echo ============================================
echo Restaurant Booking System - Performance Test
echo ============================================
echo.

REM Check if JMeter exists
if not exist "%JMETER_BIN%\jmeter.bat" (
    echo ERROR: JMeter not found at %JMETER_BIN%
    echo Please update JMETER_BIN path in this script
    pause
    exit /b 1
)

REM Create results directory if it doesn't exist
if not exist "%SCRIPT_DIR%results" mkdir "%SCRIPT_DIR%results"

echo Running performance test...
echo Test Plan: scripts\full-booking-flow.jmx
echo.
echo Make sure XAMPP/Apache is running before starting the test!
echo Press any key to continue or Ctrl+C to cancel...
pause >nul
echo.

REM Run the test
"%JMETER_BIN%\jmeter.bat" -n -t "%SCRIPT_DIR%scripts\full-booking-flow.jmx" -l "%SCRIPT_DIR%results\test-results.jtl" -e -o "%SCRIPT_DIR%results\html-report" -j "%SCRIPT_DIR%results\jmeter.log"

echo.
if %ERRORLEVEL% EQU 0 (
    echo ============================================
    echo Test completed successfully!
    echo ============================================
    echo.
    echo Results:
    echo - JTL File: results\test-results.jtl
    echo - HTML Report: results\html-report\index.html
    echo - Log File: results\jmeter.log
    echo.
    echo Opening HTML report...
    start "" "%SCRIPT_DIR%results\html-report\index.html"
) else (
    echo ============================================
    echo Test completed with errors
    echo ============================================
    echo Check results\jmeter.log for details
)

pause
