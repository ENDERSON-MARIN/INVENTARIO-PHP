#!/bin/bash

# Docker Setup Script for Laravel 5.7 Inventory System
# This script automates the first-time setup process

set -e

echo "=========================================="
echo "Laravel Docker Environment Setup"
echo "=========================================="
echo ""

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if Docker is installed
echo "Checking prerequisites..."
if ! command -v docker &> /dev/null; then
    echo -e "${RED}Error: Docker is not installed${NC}"
    echo "Please install Docker from https://docs.docker.com/get-docker/"
    exit 1
fi
echo -e "${GREEN}✓ Docker is installed${NC}"

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo -e "${RED}Error: Docker Compose is not installed${NC}"
    echo "Please install Docker Compose from https://docs.docker.com/compose/install/"
    exit 1
fi
echo -e "${GREEN}✓ Docker Compose is installed${NC}"

# Check if Docker daemon is running
if ! docker info &> /dev/null; then
    echo -e "${RED}Error: Docker daemon is not running${NC}"
    echo "Please start Docker and try again"
    exit 1
fi
echo -e "${GREEN}✓ Docker daemon is running${NC}"
echo ""

# Copy .env.docker to .env if .env doesn't exist
if [ ! -f .env ]; then
    if [ -f .env.docker ]; then
        echo "Creating .env file from .env.docker template..."
        cp .env.docker .env
        echo -e "${GREEN}✓ .env file created${NC}"
    else
        echo -e "${RED}Error: .env.docker template not found${NC}"
        exit 1
    fi
else
    echo -e "${YELLOW}⚠ .env file already exists, skipping copy${NC}"
fi
echo ""

# Build and start Docker containers
echo "Building and starting Docker containers..."
echo "This may take a few minutes on first run..."
docker-compose up -d --build

if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Failed to start Docker containers${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Docker containers started${NC}"
echo ""

# Wait for MySQL to be healthy
echo "Waiting for MySQL to be ready..."
RETRY_COUNT=0
MAX_RETRIES=30

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    if docker-compose exec -T mysql mysqladmin ping -h localhost --silent &> /dev/null; then
        echo -e "${GREEN}✓ MySQL is ready${NC}"
        break
    fi
    echo -n "."
    sleep 2
    RETRY_COUNT=$((RETRY_COUNT + 1))
done

if [ $RETRY_COUNT -eq $MAX_RETRIES ]; then
    echo -e "${RED}Error: MySQL failed to start within expected time${NC}"
    echo "Check logs with: docker-compose logs mysql"
    exit 1
fi
echo ""

# Generate APP_KEY if not already set
echo "Checking application key..."
if grep -q "APP_KEY=base64:" .env && ! grep -q "APP_KEY=base64:$" .env; then
    echo -e "${YELLOW}⚠ APP_KEY already set, skipping generation${NC}"
else
    echo "Generating application key..."
    docker-compose exec -T php php artisan key:generate
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Application key generated${NC}"
    else
        echo -e "${RED}Error: Failed to generate application key${NC}"
        exit 1
    fi
fi
echo ""

# Wait a bit more for all services to stabilize
echo "Waiting for all services to stabilize..."
sleep 5

# Verify services are healthy
echo "Verifying services..."
SERVICES_OK=true

# Check web server
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 | grep -q "200\|302"; then
    echo -e "${GREEN}✓ Web server is accessible${NC}"
else
    echo -e "${YELLOW}⚠ Web server may not be fully ready yet${NC}"
    SERVICES_OK=false
fi

# Check phpMyAdmin
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8080 | grep -q "200"; then
    echo -e "${GREEN}✓ phpMyAdmin is accessible${NC}"
else
    echo -e "${YELLOW}⚠ phpMyAdmin may not be fully ready yet${NC}"
    SERVICES_OK=false
fi

echo ""
echo "=========================================="
echo -e "${GREEN}Setup Complete!${NC}"
echo "=========================================="
echo ""
echo "Your Laravel application is now running:"
echo ""
echo "  Application:  http://localhost:8000"
echo "  phpMyAdmin:   http://localhost:8080"
echo ""
echo "Useful commands:"
echo "  View logs:           docker-compose logs -f"
echo "  Stop containers:     docker-compose stop"
echo "  Start containers:    docker-compose start"
echo "  Restart containers:  docker-compose restart"
echo "  Run artisan:         docker-compose exec php php artisan [command]"
echo "  Run composer:        docker-compose exec php composer [command]"
echo "  Access PHP shell:    docker-compose exec php sh"
echo ""

if [ "$SERVICES_OK" = false ]; then
    echo -e "${YELLOW}Note: Some services may need a few more seconds to fully initialize.${NC}"
    echo -e "${YELLOW}If you encounter issues, wait a moment and try accessing the URLs again.${NC}"
    echo ""
fi

echo "For detailed documentation, see docs/README.docker.md"
echo ""
