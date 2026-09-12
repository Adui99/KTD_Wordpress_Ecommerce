# One-click Full-Scope Test Runner for Hello Elementor Child (4 Tiers: Unit, Catalog DB, Route E2E, Coupon Flow)
$PHP_BIN = "C:\Users\Administrator\AppData\Roaming\Local\lightning-services\php-8.2.29+0\bin\win64\php.exe"
$PHP_INI = "C:\Users\Administrator\AppData\Roaming\Local\run\3I7VnCb_U\conf\php\php.ini"

$UNIT_PHP_TEST   = Join-Path $PSScriptRoot "test-theme-functions.php"
$UNIT_JS_TEST    = Join-Path $PSScriptRoot "test-theme-custom.js"
$CHATBOT_TEST    = Join-Path $PSScriptRoot "test-chatbot-behavior.php"
$AUTH_TEST       = Join-Path $PSScriptRoot "test-auth-behavior.php"
$CATALOG_DB_TEST = Join-Path $PSScriptRoot "test-catalog-integrity.php"
$ROUTES_E2E_TEST = Join-Path $PSScriptRoot "test-routes-e2e.php"
$COUPON_TEST     = Join-Path $PSScriptRoot "test-coupons.php"
$ORDER_FLOW_TEST = Join-Path $PSScriptRoot "test-order-flow.php"

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host " FULL-SCOPE AUTOMATED TEST RUNNER (KTD E-COMMERCE)" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan

# 1. Unit Tests (PHP)
Write-Host "`n[1/8] Running Tier 1: PHP Backend Unit Tests..." -ForegroundColor Yellow
& $PHP_BIN -c $PHP_INI $UNIT_PHP_TEST
$phpUnitExit = $LASTEXITCODE

# 2. Unit Tests (JS)
Write-Host "`n[2/8] Running Tier 1: JavaScript Frontend Unit Tests..." -ForegroundColor Yellow
node $UNIT_JS_TEST
$jsUnitExit = $LASTEXITCODE

# 3. AI Chatbot Behavior Tests
Write-Host "`n[3/8] Running Tier 1: AI Chatbot Behavior Tests..." -ForegroundColor Yellow
& $PHP_BIN -c $PHP_INI $CHATBOT_TEST
$chatbotExit = $LASTEXITCODE

# 4. Auth & My Account Behavior Tests
Write-Host "`n[4/8] Running Tier 1: Auth & Account Behavior Tests..." -ForegroundColor Yellow
& $PHP_BIN -c $PHP_INI $AUTH_TEST
$authExit = $LASTEXITCODE

# 5. Catalog and Database Integrity Tests
Write-Host "`n[5/8] Running Tier 2: Database and Catalog Integrity Tests..." -ForegroundColor Yellow
& $PHP_BIN -c $PHP_INI $CATALOG_DB_TEST
$catalogExit = $LASTEXITCODE

# 6. Routes and Frontend Enqueue E2E Tests
Write-Host "`n[6/8] Running Tier 3: Routes and Enqueue E2E Health Tests..." -ForegroundColor Yellow
& $PHP_BIN -c $PHP_INI $ROUTES_E2E_TEST
$routesExit = $LASTEXITCODE

# 7. Coupon Engine & Discount Validation Tests
Write-Host "`n[7/8] Running Tier 4: Coupon Engine & Cart Calculations..." -ForegroundColor Yellow
& $PHP_BIN -c $PHP_INI $COUPON_TEST
$couponExit = $LASTEXITCODE

# 8. Shopping Journey & E2E Order Creation Tests
Write-Host "`n[8/9] Running Tier 5: E2E Order Flow & VietQR Verification..." -ForegroundColor Yellow
& $PHP_BIN -c $PHP_INI $ORDER_FLOW_TEST
$orderFlowExit = $LASTEXITCODE

# 9. UI/UX Nielsen Heuristics & WCAG AA Audit Tests
$UIUX_AUDIT_TEST = Join-Path $PSScriptRoot "test-ui-ux-audit.php"
Write-Host "`n[9/9] Running Tier 6: UI/UX Nielsen Heuristics & Mobile A11y Audit..." -ForegroundColor Yellow
& $PHP_BIN -c $PHP_INI $UIUX_AUDIT_TEST
$uiuxExit = $LASTEXITCODE

Write-Host "`n============================================================" -ForegroundColor Cyan
if ($phpUnitExit -eq 0 -and $jsUnitExit -eq 0 -and $chatbotExit -eq 0 -and $authExit -eq 0 -and $catalogExit -eq 0 -and $routesExit -eq 0 -and $couponExit -eq 0 -and $orderFlowExit -eq 0 -and $uiuxExit -eq 0) {
    Write-Host " ALL 6 TIERS PASSED SUCCESSFULLY! (100% OK)" -ForegroundColor Green
    Write-Host " [PASS] Tier 1: PHP, JS, Chatbot & Auth Unit Tests" -ForegroundColor Green
    Write-Host " [PASS] Tier 2: Database and Catalog Integrity Tests" -ForegroundColor Green
    Write-Host " [PASS] Tier 3: Routes and Frontend Enqueue E2E Tests" -ForegroundColor Green
    Write-Host " [PASS] Tier 4: Coupon Engine & Cart Discount Tests" -ForegroundColor Green
    Write-Host " [PASS] Tier 5: E2E Shopping Journey, Order Creation & VietQR Tests" -ForegroundColor Green
    Write-Host " [PASS] Tier 6: Comprehensive UI/UX & WCAG AA Accessibility Audit" -ForegroundColor Green
    Write-Host "============================================================" -ForegroundColor Cyan
    exit 0
} else {
    Write-Host " SOME TEST TIERS FAILED! Check error logs above." -ForegroundColor Red
    Write-Host "============================================================" -ForegroundColor Cyan
    exit 1
}
