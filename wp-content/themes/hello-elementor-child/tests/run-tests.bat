@echo off
setlocal
set PHP_BIN=C:\Users\Administrator\AppData\Roaming\Local\lightning-services\php-8.2.29+0\bin\win64\php.exe
set PHP_INI=C:\Users\Administrator\AppData\Roaming\Local\run\3I7VnCb_U\conf\php\php.ini
set SCRIPT_DIR=%~dp0

echo ============================================================
echo  Running Automated Test Suite for Hello Elementor Child
echo ============================================================

echo.
echo [1/8] Running Tier 1: PHP Backend Unit Tests...
"%PHP_BIN%" -c "%PHP_INI%" "%SCRIPT_DIR%test-theme-functions.php"
if %ERRORLEVEL% NEQ 0 exit /b %ERRORLEVEL%

echo.
echo [2/8] Running Tier 1: JavaScript Frontend Unit Tests...
node "%SCRIPT_DIR%test-theme-custom.js"
if %ERRORLEVEL% NEQ 0 exit /b %ERRORLEVEL%

echo.
echo [3/8] Running Tier 1: AI Chatbot Behavior Tests...
"%PHP_BIN%" -c "%PHP_INI%" "%SCRIPT_DIR%test-chatbot-behavior.php"
if %ERRORLEVEL% NEQ 0 exit /b %ERRORLEVEL%

echo.
echo [4/8] Running Tier 1: Auth & Account Behavior Tests...
"%PHP_BIN%" -c "%PHP_INI%" "%SCRIPT_DIR%test-auth-behavior.php"
if %ERRORLEVEL% NEQ 0 exit /b %ERRORLEVEL%

echo.
echo [5/8] Running Tier 2: Database and Catalog Integrity Tests...
"%PHP_BIN%" -c "%PHP_INI%" "%SCRIPT_DIR%test-catalog-integrity.php"
if %ERRORLEVEL% NEQ 0 exit /b %ERRORLEVEL%

echo.
echo [6/8] Running Tier 3: Routes and Enqueue E2E Health Tests...
"%PHP_BIN%" -c "%PHP_INI%" "%SCRIPT_DIR%test-routes-e2e.php"
if %ERRORLEVEL% NEQ 0 exit /b %ERRORLEVEL%

echo.
echo [7/8] Running Tier 4: Coupon Engine & Cart Calculations...
"%PHP_BIN%" -c "%PHP_INI%" "%SCRIPT_DIR%test-coupons.php"
if %ERRORLEVEL% NEQ 0 exit /b %ERRORLEVEL%

echo.
echo [8/9] Running Tier 5: E2E Order Flow & VietQR Verification...
"%PHP_BIN%" -c "%PHP_INI%" "%SCRIPT_DIR%test-order-flow.php"
if %ERRORLEVEL% NEQ 0 exit /b %ERRORLEVEL%

echo.
echo [9/9] Running Tier 6: UI/UX Nielsen Heuristics & Mobile A11y Audit...
"%PHP_BIN%" -c "%PHP_INI%" "%SCRIPT_DIR%test-ui-ux-audit.php"
if %ERRORLEVEL% NEQ 0 exit /b %ERRORLEVEL%

echo.
echo ============================================================
echo  ALL 6 TIERS PASSED SUCCESSFULLY! (100%% OK)
echo ============================================================
exit /b 0
