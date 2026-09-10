@echo off
setlocal
set PHP_BIN=C:\Users\Administrator\AppData\Roaming\Local\lightning-services\php-8.2.29+0\bin\win32\php.exe
set SCRIPT_DIR=%~dp0

echo ============================================================
echo  Running Automated Unit Tests for Hello Elementor Child
echo ============================================================

echo.
echo [1/2] Running PHP Backend Tests...
"%PHP_BIN%" "%SCRIPT_DIR%test-theme-functions.php"
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP tests failed!
    exit /b %ERRORLEVEL%
)

echo.
echo [2/2] Running JavaScript Frontend Tests...
node "%SCRIPT_DIR%test-theme-custom.js"
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] JS tests failed!
    exit /b %ERRORLEVEL%
)

echo.
echo ============================================================
echo  ALL TEST SUITES PASSED SUCCESSFULLY! (100%% OK)
echo ============================================================
exit /b 0
