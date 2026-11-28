param(
    [string]$ProjectRoot = $PSScriptRoot
)

$ErrorActionPreference = 'Stop'

$pluginSlug = 'repository-catalog'
$sourceDirectory = Join-Path $ProjectRoot "codigo\$pluginSlug"
$mainFile = Join-Path $sourceDirectory "$pluginSlug.php"
$distributionDirectory = Join-Path $ProjectRoot 'dist'
$packagePath = Join-Path $distributionDirectory "$pluginSlug.zip"

if (-not (Test-Path -LiteralPath $sourceDirectory -PathType Container)) {
    throw "Diretório-fonte obrigatório não encontrado: $sourceDirectory"
}

if (-not (Test-Path -LiteralPath $mainFile -PathType Leaf)) {
    throw "Arquivo principal obrigatório não encontrado: $mainFile"
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
        throw "O ZIP não contém $pluginSlug/$pluginSlug.php."
    }
    if ($entries | Where-Object { $_ -match '^(?:repository-catalog[-_.]?\d|htgithubforwp-basics|humanitastech-github)' }) {
        throw 'O ZIP contém diretório versionado ou slug legado.'
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
