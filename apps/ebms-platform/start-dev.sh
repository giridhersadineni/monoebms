#!/bin/bash
# EBMS Platform - macOS/Linux Development Server Startup Script
# Starts Docker containers and the Laravel development server

set -e

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo "🚀 Starting EBMS Platform Development Server..."
echo ""

# Check if Docker is running
echo "📦 Checking Docker daemon..."
if ! docker ps &> /dev/null; then
    echo "❌ Docker daemon is not running."
    echo ""
    echo "Please start Docker Desktop and run this script again."
    echo ""
    exit 1
fi

echo "✅ Docker daemon is running"
echo ""

# Start Docker containers
echo "🐳 Starting Docker containers..."
docker compose up -d

echo "✅ Docker containers started"
echo ""

# Check if containers are healthy (wait a moment for them to be ready)
echo "⏳ Waiting for containers to be ready..."
sleep 3

# Start the development server
echo "🌐 Starting Laravel development server..."
echo ""
composer run dev

