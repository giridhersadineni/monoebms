# EBMS Platform - Windows PowerShell Development Server Startup Script
# Starts Docker containers and the Laravel development server

$ErrorActionPreference = "Stop"

Write-Host ""
Write-Host "🚀 Starting EBMS Platform Development Server..." -ForegroundColor Green
Write-Host ""

# Get the script directory
$scriptPath = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $scriptPath

# Check if Docker is running
Write-Host "📦 Checking Docker daemon..." -ForegroundColor Blue
try {
    docker ps | Out-Null
} catch {
    Write-Host "❌ Docker daemon is not running." -ForegroundColor Red
    Write-Host ""
    Write-Host "Please start Docker Desktop and run this script again." -ForegroundColor Yellow
    Write-Host ""
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host "✅ Docker daemon is running" -ForegroundColor Green
Write-Host ""

# Start Docker containers
Write-Host "🐳 Starting Docker containers..." -ForegroundColor Blue
try {
    docker compose up -d
} catch {
    Write-Host "❌ Failed to start Docker containers" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host "✅ Docker containers started" -ForegroundColor Green
Write-Host ""

# Wait for containers to be ready
Write-Host "⏳ Waiting for containers to be ready..." -ForegroundColor Blue
Start-Sleep -Seconds 3

# Start the development server
Write-Host "🌐 Starting Laravel development server..." -ForegroundColor Blue
Write-Host ""
& composer run dev

