# ===================================================================
# Script de PowerShell para iniciar el entorno de desarrollo de la API REST
# ===================================================================

[CmdletBinding()]
param(
    [string]$ProjectPath = "C:\Users\Personal\Herd\workshop_API_REST",
    [int]$DockerTimeoutSeconds = 150,
    [string]$DockerDesktopPath = "C:\Program Files\Docker\Docker\Docker Desktop.exe"
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

function Write-Step {
    param(
        [int]$Number,
        [string]$Message
    )
    Write-Host "$Number. $Message" -ForegroundColor Cyan
}

function Assert-Command {
    param(
        [string]$CommandName,
        [string]$FriendlyName
    )

    if (-not (Get-Command -Name $CommandName -ErrorAction SilentlyContinue)) {
        throw "No se encontró el comando '$CommandName'. Instala $FriendlyName y vuelve a intentarlo."
    }
}

function Wait-ForDocker {
    param(
        [int]$TimeoutSeconds = 120,
        [int]$DelaySeconds = 5
    )

    $deadline = (Get-Date).AddSeconds($TimeoutSeconds)

    while ((Get-Date) -lt $deadline) {
        docker info > $null 2>&1
        if ($LASTEXITCODE -eq 0) {
            return $true
        }

        Write-Host "   -> Esperando a que Docker esté listo..." -ForegroundColor DarkYellow
        Start-Sleep -Seconds $DelaySeconds
    }

    return $false
}

Write-Host "🚀 Iniciando el entorno de desarrollo para la API..." -ForegroundColor Cyan
Write-Host "---------------------------------------------------" -ForegroundColor DarkGray

# 0. Validaciones básicas
Write-Step -Number 0 -Message "Comprobando requisitos básicos"
Assert-Command -CommandName "docker" -FriendlyName "Docker Desktop"
Assert-Command -CommandName "php" -FriendlyName "PHP"

# 1. Iniciar Docker Desktop y esperar a que esté listo
Write-Step -Number 1 -Message "Verificando el estado de Docker Desktop"

$dockerProcess = Get-Process -Name "Docker Desktop" -ErrorAction SilentlyContinue
if (-not $dockerProcess) {
    if (-not (Test-Path -Path $DockerDesktopPath)) {
        throw "No se encontró Docker Desktop en '$DockerDesktopPath'. Actualiza la variable `$DockerDesktopPath`."
    }

    Write-Host "   -> Docker Desktop no está corriendo. Iniciándolo..." -ForegroundColor Yellow
    Start-Process -FilePath $DockerDesktopPath | Out-Null
    Write-Host "   -> Esperando a que el servicio de Docker esté disponible..." -ForegroundColor Yellow
} else {
    Write-Host "   -> Docker Desktop ya está en ejecución." -ForegroundColor Green
}

if (-not (Wait-ForDocker -TimeoutSeconds $DockerTimeoutSeconds)) {
    throw "Docker no respondió en el tiempo esperado (${DockerTimeoutSeconds}s)."
}

Write-Host "   -> ✅ Docker está listo para usar." -ForegroundColor Green

# 2. Navegar al directorio del proyecto
Write-Step -Number 2 -Message "Navegando al directorio del proyecto"
if (-not (Test-Path -Path $ProjectPath)) {
    throw "No se encontró el directorio del proyecto en '$ProjectPath'. Ajusta la variable `$ProjectPath`."
}

Set-Location -Path $ProjectPath
Write-Host "   -> Directorio establecido en '$ProjectPath'." -ForegroundColor Green

# 3. Levantar contenedores con Docker Compose
Write-Step -Number 3 -Message "Levantando servicios de Docker Compose"

$composeArgs = @("up", "-d")
if (Get-Command -Name "docker-compose" -ErrorAction SilentlyContinue) {
    Write-Host "   -> Usando 'docker-compose' clásico." -ForegroundColor DarkGray
    & docker-compose @composeArgs
} else {
    Write-Host "   -> Usando 'docker compose' (CLI plugin)." -ForegroundColor DarkGray
    & docker @("compose") + $composeArgs
}
Write-Host "   -> ✅ Contenedores en marcha." -ForegroundColor Green

# 4. Iniciar el servidor PHP en segundo plano (sin abrir nuevas ventanas)
Write-Step -Number 4 -Message "Iniciando el servidor de desarrollo de PHP (http://localhost:8000)"

$phpJob = Start-Job -Name "php-dev-server" -ScriptBlock {
    param($path)
    Set-Location -LiteralPath $path
    php -S localhost:8000
} -ArgumentList $ProjectPath

Write-Host "   -> Servidor PHP en marcha como Job #$($phpJob.Id)." -ForegroundColor Green
Write-Host "      Usa 'Get-Job', 'Receive-Job -Keep', y 'Stop-Job $($phpJob.Id)' desde esta terminal cuando lo necesites." -ForegroundColor DarkGray

Write-Host ""
Write-Host "✅ ¡Entorno de desarrollo listo para despegar!" -ForegroundColor Green