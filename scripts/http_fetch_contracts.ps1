try {
    $r = Invoke-WebRequest -Uri 'http://127.0.0.1:8000/contracts' -UseBasicParsing -TimeoutSec 30
    $info = [ordered]@{
        status = $r.StatusCode
        contentType = $r.Headers['Content-Type']
    }
    $info | ConvertTo-Json | Out-File -Encoding utf8 -FilePath ./storage/logs/http-contracts-headers.json
    $outPath = Join-Path -Path (Resolve-Path ./storage/app/exports).Path -ChildPath 'http_contracts_body.bin'
    [System.IO.File]::WriteAllBytes($outPath, $r.RawContent)
    Write-Output 'Done'
} catch {
    $_ | Out-String | Out-File -FilePath ./storage/logs/http-contracts-error.log -Append
    Write-Output 'Error saved'
}
