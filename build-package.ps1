param(
    [string]$ProjectRoot = $PSScriptRoot
)

$ErrorActionPreference = 'Stop'

$pluginSlug = 'ht-repository-catalog'
$sourceDirectory = Join-Path $ProjectRoot "src\$pluginSlug"
$mainFile = Join-Path $sourceDirectory "$pluginSlug.php"
$distributionDirectory = Join-Path $ProjectRoot 'dist'
$packagePath = Join-Path $distributionDirectory "$pluginSlug.zip"

if (-not (Test-Path -LiteralPath $sourceDirectory -PathType Container)) {
    throw "Required source directory not found: $sourceDirectory"
}

if (-not (Test-Path -LiteralPath $mainFile -PathType Leaf)) {
    throw "Required main plugin file not found: $mainFile"
}

New-Item -ItemType Directory -Path $distributionDirectory -Force | Out-Null
$temporaryPackage = Join-Path ([System.IO.Path]::GetTempPath()) ("$pluginSlug-" + [guid]::NewGuid().ToString('N') + '.zip')

try {
    Compress-Archive -LiteralPath $sourceDirectory -DestinationPath $temporaryPackage -CompressionLevel Optimal

    $archive = [System.IO.Compression.ZipFile]::OpenRead($temporaryPackage)
    try {
        $entries = @($archive.Entries | ForEach-Object { $_.FullName.Replace('\', '/') })
    }
    finally {
        $archive.Dispose()
    }
    if ($entries -notcontains "$pluginSlug/$pluginSlug.php") {
        throw "The ZIP does not contain $pluginSlug/$pluginSlug.php."
    }
    if ($entries | Where-Object { $_ -match '^(?:ht-repository-catalog[-_.]?\d|repository-catalog|htgithubforwp-basics|humanitastech-github)' }) {
        throw 'The ZIP contains a versioned directory or a legacy slug.'
    }

    Move-Item -LiteralPath $temporaryPackage -Destination $packagePath -Force
}
finally {
    if (Test-Path -LiteralPath $temporaryPackage) {
        Remove-Item -LiteralPath $temporaryPackage -Force
    }
}

$checksum = (Get-FileHash -LiteralPath $packagePath -Algorithm SHA256).Hash.ToLowerInvariant()
[IO.File]::WriteAllText("$packagePath.sha256", "$checksum  $pluginSlug.zip" + [Environment]::NewLine, [Text.UTF8Encoding]::new($false))
Write-Output $packagePath
