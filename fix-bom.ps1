# Fix BOM (Byte Order Mark) in PHP files
# BOM characters cause "namespace declaration" errors in PHP

Write-Host "Scanning for files with BOM..." -ForegroundColor Cyan

$filesFixed = 0
$filesScanned = 0

Get-ChildItem -Path "src" -Recurse -Filter "*.php" | ForEach-Object {
    $filesScanned++
    $content = [System.IO.File]::ReadAllBytes($_.FullName)
    
    # Check for UTF-8 BOM (EF BB BF)
    if ($content.Length -ge 3 -and $content[0] -eq 0xEF -and $content[1] -eq 0xBB -and $content[2] -eq 0xBF) {
        Write-Host "  Found BOM in: $($_.Name)" -ForegroundColor Yellow
        
        # Remove BOM and save
        $newContent = $content[3..($content.Length - 1)]
        [System.IO.File]::WriteAllBytes($_.FullName, $newContent)
        
        $filesFixed++
        Write-Host "  Fixed: $($_.Name)" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "Summary:" -ForegroundColor Cyan
Write-Host "  Files scanned: $filesScanned" -ForegroundColor White
Write-Host "  Files fixed: $filesFixed" -ForegroundColor Green
Write-Host ""

if ($filesFixed -gt 0) {
    Write-Host "BOM characters removed! Run 'composer dump-autoload' to refresh." -ForegroundColor Green
} else {
    Write-Host "No BOM characters found." -ForegroundColor Green
}
