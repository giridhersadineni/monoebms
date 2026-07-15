@echo off
REM EBMS Platform - Windows Development Server Startup Script
REM Starts Docker containers and the Laravel development server

setlocal enabledelayedexpansion

echo.
echo 🚀 Starting EBMS Platform Development Server...
echo.

REM Check if Docker is installed and running
echo 📦 Checking Docker daemon...
docker ps >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Docker daemon is not running.
    echo.
    echo Please start Docker Desktop and run this script again.
    echo.
    pause
    exit /b 1
)

echo ✅ Docker daemon is running
echo.

REM Start Docker containers
echo 🐳 Starting Docker containers...
call docker compose up -d
if %errorlevel% neq 0 (
    echo ❌ Failed to start Docker containers
    pause
    exit /b 1
)

echo ✅ Docker containers started
echo.

REM Wait for containers to be ready
echo ⏳ Waiting for containers to be ready...
timeout /t 3 /nobreak

REM Start the development server
echo 🌐 Starting Laravel development server...
echo.
call composer run dev

pause
