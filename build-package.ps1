param(
    [string]$ProjectRoot = $PSScriptRoot
)

$ErrorActionPreference = 'Stop'

$pluginSlug = 'htgithubforwp-basics'
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

    $entries = @(tar -tf $temporaryPackage)
    if ($LASTEXITCODE -ne 0) {
        throw 'Não foi possível validar o conteúdo do pacote.'
    }
    if ($entries -notcontains "$pluginSlug/$pluginSlug.php") {
        throw "O ZIP não contém $pluginSlug/$pluginSlug.php."
    }
    if ($entries | Where-Object { $_ -match '^(?:htgithubforwp-basics[-_.]?\d|humanitastech-github)' }) {
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
