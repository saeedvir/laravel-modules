# PowerShell script to convert UTF-8 BOM files to UTF-8 (without BOM)

param(
    [Parameter(Mandatory=$false)]
    [string]$Path = ".",
    
    [Parameter(Mandatory=$false)]
    [string]$Filter = "*.php",
    
    [Parameter(Mandatory=$false)]
    [switch]$Recursive
)

function Convert-Utf8BomToUtf8 {
    param(
        [string]$FilePath
    )
    
    try {
        # Read file content as bytes
        $bytes = [System.IO.File]::ReadAllBytes($FilePath)
        
        # Check if file has UTF-8 BOM (EF BB BF)
        if ($bytes.Length -ge 3 -and $bytes[0] -eq 0xEF -and $bytes[1] -eq 0xBB -and $bytes[2] -eq 0xBF) {
            Write-Host "Converting: $FilePath" -ForegroundColor Yellow
            
            # Read content with UTF-8 encoding
            $content = [System.IO.File]::ReadAllText($FilePath, [System.Text.Encoding]::UTF8)
            
            # Write back without BOM
            $utf8NoBom = New-Object System.Text.UTF8Encoding $false
            [System.IO.File]::WriteAllText($FilePath, $content, $utf8NoBom)
            
            Write-Host "  [OK] Converted successfully" -ForegroundColor Green
            return $true
        }
        else {
            Write-Host "Skipping (no BOM): $FilePath" -ForegroundColor Gray
            return $false
        }
    }
    catch {
        Write-Host "  [ERROR] $_" -ForegroundColor Red
        return $false
    }
}

# Main script
Write-Host "=== UTF-8 BOM to UTF-8 Converter ===" -ForegroundColor Cyan
Write-Host "Path: $Path"
Write-Host "Filter: $Filter"
Write-Host "Recursive: $Recursive"
Write-Host ""

# Get files
if ($Recursive) {
    $files = Get-ChildItem -Path $Path -Filter $Filter -Recurse -File
}
else {
    $files = Get-ChildItem -Path $Path -Filter $Filter -File
}

$convertedCount = 0
$totalFiles = $files.Count

Write-Host "Found $totalFiles file(s) matching filter" -ForegroundColor Cyan
Write-Host ""

foreach ($file in $files) {
    if (Convert-Utf8BomToUtf8 -FilePath $file.FullName) {
        $convertedCount++
    }
}

Write-Host ""
Write-Host "=== Summary ===" -ForegroundColor Cyan
Write-Host "Total files processed: $totalFiles"
Write-Host "Files converted: $convertedCount" -ForegroundColor Green
Write-Host "Files skipped: $($totalFiles - $convertedCount)" -ForegroundColor Gray
