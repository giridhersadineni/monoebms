# EBMS Platform Startup Guide

This directory includes automated startup scripts for both macOS and Windows to simplify the development server setup.

## Requirements

- **Docker Desktop** installed and running
- **Composer** installed globally
- **Node.js** (npm) installed globally

## macOS / Linux

### Quick Start

```bash
./start-dev.sh
```

The script will:
1. ✅ Verify Docker daemon is running
2. 🐳 Start Docker containers (database, Redis, nginx, app)
3. 🌐 Start the Laravel development server

The app will be available at **http://localhost:8000**

### Manual Alternative

```bash
docker compose up -d
composer run dev
```

## Windows

### Option 1: Batch Script (Recommended for Command Prompt)

Double-click `start-dev.bat` or run:

```cmd
start-dev.bat
```

### Option 2: PowerShell Script

Run in PowerShell:

```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope Process
./start-dev.ps1
```

> Note: If you get an execution policy error, the Set-ExecutionPolicy command above allows scripts to run in the current session only.

### What the Scripts Do

1. ✅ Check Docker daemon status
2. 🐳 Start Docker containers (`docker compose up -d`)
3. 🌐 Start Laravel dev server (`composer run dev`)

The app will be available at **http://localhost:8000**

## Troubleshooting

### Docker Daemon Not Running
- **macOS**: Open Docker Desktop from Applications
- **Windows**: Open Docker Desktop from Start Menu

### Port Already in Use
If port 8000 is in use, kill the process:

**macOS/Linux:**
```bash
lsof -i :8000 | grep -v COMMAND | awk '{print $2}' | xargs kill -9
```

**Windows (PowerShell):**
```powershell
Get-NetTCPConnection -LocalPort 8000 | ForEach-Object { Stop-Process -Id $_.OwningProcess -Force }
```

### Container Connection Errors
Wait 5-10 seconds for the database container to fully initialize, then reload the page.

## Development URLs

- **App**: http://localhost:8000
- **Vite Assets**: http://localhost:5175
- **Database**: localhost:3306 (MariaDB)

## Stopping the Servers

Press `Ctrl+C` in the terminal to stop the dev server. Docker containers will remain running.

To stop Docker containers:

```bash
docker compose down
```

To stop and remove all containers:

```bash
docker compose down -v
```

