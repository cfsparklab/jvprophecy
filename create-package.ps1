# AWS Elastic Beanstalk Package Creator
# JV Prophecy Manager

Write-Host "========================================" -ForegroundColor Blue
Write-Host "Creating EBS Deployment Package" -ForegroundColor Blue
Write-Host "========================================" -ForegroundColor Blue
Write-Host ""

$timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
$packageName = "jv-prophecy-ebs-$timestamp.zip"

Write-Host "Package: $packageName" -ForegroundColor Yellow
Write-Host ""

# Step 1: Clean old packages
Write-Host "[1/8] Cleaning old packages..." -ForegroundColor Cyan
Get-ChildItem -Filter "jv-prophecy-ebs-*.zip" | Remove-Item -Force -ErrorAction SilentlyContinue
Write-Host "Done" -ForegroundColor Green

# Step 2: Install dependencies
Write-Host "[2/8] Installing production dependencies..." -ForegroundColor Cyan
composer install --no-dev --optimize-autoloader --no-interaction | Out-Null
Write-Host "Done" -ForegroundColor Green

# Step 3: Clear caches
Write-Host "[3/8] Clearing Laravel caches..." -ForegroundColor Cyan
php artisan config:clear | Out-Null
php artisan route:clear | Out-Null
php artisan view:clear | Out-Null
php artisan cache:clear | Out-Null
Write-Host "Done" -ForegroundColor Green

# Step 4: Create temp directory
Write-Host "[4/8] Creating build directory..." -ForegroundColor Cyan
$buildDir = "build-temp"
if (Test-Path $buildDir) {
    Remove-Item $buildDir -Recurse -Force
}
New-Item -ItemType Directory -Path $buildDir -Force | Out-Null
Write-Host "Done" -ForegroundColor Green

# Step 5: Copy files
Write-Host "[5/8] Copying files..." -ForegroundColor Cyan
$dirs = @("app", "bootstrap", "config", "database", "public", "resources", "routes", "storage", "vendor")
foreach ($dir in $dirs) {
    Copy-Item $dir "$buildDir\$dir" -Recurse -Force
}

$configDirs = @(".ebextensions", ".platform", ".elasticbeanstalk")
foreach ($dir in $configDirs) {
    if (Test-Path $dir) {
        Copy-Item $dir "$buildDir\$dir" -Recurse -Force
    }
}

Copy-Item "artisan" "$buildDir\artisan" -Force
Copy-Item "composer.json" "$buildDir\composer.json" -Force
Copy-Item "composer.lock" "$buildDir\composer.lock" -Force
if (Test-Path ".ebignore") {
    Copy-Item ".ebignore" "$buildDir\.ebignore" -Force
}
Write-Host "Done" -ForegroundColor Green

# Step 6: Clean storage
Write-Host "[6/8] Cleaning storage..." -ForegroundColor Cyan
Get-ChildItem "$buildDir\storage\logs" -Exclude ".gitkeep" | Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
Get-ChildItem "$buildDir\storage\framework\cache" -Exclude ".gitkeep" | Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
Get-ChildItem "$buildDir\storage\framework\sessions" -Exclude ".gitkeep" | Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
Get-ChildItem "$buildDir\storage\framework\views" -Exclude ".gitkeep" | Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
Write-Host "Done" -ForegroundColor Green

# Step 7: Mark scripts as executable (add marker for Linux)
Write-Host "[7/8] Preparing executable permissions..." -ForegroundColor Cyan
$hookFiles = Get-ChildItem -Path "$buildDir\.platform\hooks" -Recurse -Filter "*.sh" -ErrorAction SilentlyContinue
foreach ($file in $hookFiles) {
    # Add execute marker comment at top of file
    $content = Get-Content $file.FullName -Raw
    if ($content -notmatch "#!/bin/bash") {
        Set-Content $file.FullName -Value "#!/bin/bash`n$content"
    }
}
Write-Host "Done" -ForegroundColor Green

# Step 8: Create ZIP
Write-Host "[8/9] Creating ZIP..." -ForegroundColor Cyan
Compress-Archive -Path "$buildDir\*" -DestinationPath $packageName -Force
Write-Host "Done" -ForegroundColor Green

# Step 9: Cleanup
Write-Host "[9/9] Cleaning up..." -ForegroundColor Cyan
Remove-Item $buildDir -Recurse -Force
Write-Host "Done" -ForegroundColor Green

# Summary
$size = [math]::Round((Get-Item $packageName).Length / 1MB, 2)
Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "Package Created Successfully!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "File: $packageName" -ForegroundColor Yellow
Write-Host "Size: $size MB" -ForegroundColor Yellow
Write-Host "Location: $(Get-Location)\$packageName" -ForegroundColor Yellow
Write-Host ""
Write-Host "Deploy with: eb deploy jv-prophecy-prod" -ForegroundColor Cyan
Write-Host ""

