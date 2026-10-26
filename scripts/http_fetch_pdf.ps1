try {
    $r = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/contracts/export?type=pdf' -UseBasicParsing -TimeoutSec 30
    $info = [ordered]@{
        status = $r.StatusCode
        contentType = $r.Headers['Content-Type']
        contentDisposition = $r.Headers['Content-Disposition']
        length = $r.RawContentLength
    }
    $info | ConvertTo-Json | Out-File -Encoding utf8 -FilePath ./storage/logs/http-export-pdf-headers.json
    $outPath = Join-Path -Path (Resolve-Path ./storage/app/exports).Path -ChildPath 'http_export_pdf.bin'
    [System.IO.File]::WriteAllBytes($outPath, $r.RawContent)
    Write-Output 'Done'
} catch {
    $_ | Out-String | Out-File -FilePath ./storage/logs/http-export-pdf-error.log -Append
    Write-Output 'Error saved'
}