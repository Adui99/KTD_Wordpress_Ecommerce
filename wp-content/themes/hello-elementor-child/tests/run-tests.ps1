# One-click Full-Scope Test Runner for Hello Elementor Child (3 Tiers: Unit, Catalog DB, Route E2E)
$PHP_BIN = "C:\Users\Administrator\AppData\Roaming\Local\lightning-services\php-8.2.29+0\bin\win64\php.exe"
$PHP_INI = "C:\Users\Administrator\AppData\Roaming\Local\run\3I7VnCb_U\conf\php\php.ini"

$UNIT_PHP_TEST   = Join-Path $PSScriptRoot "test-theme-functions.php"
$UNIT_JS_TEST    = Join-Path $PSScriptRoot "test-theme-custom.js"
$CATALOG_DB_TEST = Join-Path $PSScriptRoot "test-catalog-integrity.php"
$ROUTES_E2E_TEST = Join-Path $PSScriptRoot "test-routes-e2e.php"

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host " FULL-SCOPE AUTOMATED TEST RUNNER (KTD E-COMMERCE)" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan

# 1. Unit Tests (PHP)
Write-Host "`n[1/4] Running Tier 1: PHP Backend Unit Tests..." -ForegroundColor Yellow
& $PHP_BIN -c $PHP_INI $UNIT_PHP_TEST
$phpUnitExit = $LASTEXITCODE

# 2. Unit Tests (JS)
Write-Host "`n[2/4] Running Tier 1: JavaScript Frontend Unit Tests..." -ForegroundColor Yellow
node $UNIT_JS_TEST
$jsUnitExit = $LASTEXITCODE

# 3. Catalog and Database Integrity Tests
Write-Host "`n[3/4] Running Tier 2: Database and Catalog Integrity Tests..." -ForegroundColor Yellow
& $PHP_BIN -c $PHP_INI $CATALOG_DB_TEST
$catalogExit = $LASTEXITCODE

# 4. Routes and Frontend Enqueue E2E Tests
Write-Host "`n[4/4] Running Tier 3: Routes and Enqueue E2E Health Tests..." -ForegroundColor Yellow
& $PHP_BIN -c $PHP_INI $ROUTES_E2E_TEST
$routesExit = $LASTEXITCODE

Write-Host "`n============================================================" -ForegroundColor Cyan
if ($phpUnitExit -eq 0 -and $jsUnitExit -eq 0 -and $catalogExit -eq 0 -and $routesExit -eq 0) {
    Write-Host " ALL 3 TIERS PASSED SUCCESSFULLY! (100% OK)" -ForegroundColor Green
    Write-Host " [PASS] Tier 1: PHP and JS Unit Tests" -ForegroundColor Green
    Write-Host " [PASS] Tier 2: Database and Catalog Integrity Tests" -ForegroundColor Green
    Write-Host " [PASS] Tier 3: Routes and Frontend Enqueue E2E Tests" -ForegroundColor Green
    Write-Host "============================================================" -ForegroundColor Cyan
    exit 0
} else {
    Write-Host " SOME TEST TIERS FAILED! Check error logs above." -ForegroundColor Red
    Write-Host "============================================================" -ForegroundColor Cyan
    exit 1
}
