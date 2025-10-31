#!/usr/bin/env bash
set -e

echo "Starting Laravel Docker initialization..."

# Define project root as current directory
PROJECT_ROOT="$(pwd)"

# Step 0: Ensure mysql.env exists
if [ ! -f "$PROJECT_ROOT/mysql.env" ]; then
    echo "mysql.env not found. Copying from env_example..."
    cp "$PROJECT_ROOT/env_example" "$PROJECT_ROOT/mysql.env"
fi

# Step 0.5: Ensure .env exists
if [ ! -f "$PROJECT_ROOT/.env" ]; then
    echo ".env not found. Copying from .env.example..."
    cp "$PROJECT_ROOT/.env.example" "$PROJECT_ROOT/.env"
fi

# Step 1: Build and start all containers
echo "Building and starting containers..."
docker compose -f "$PROJECT_ROOT/docker-compose.yml" up -d --build

# Step 2: Run migrations inside the app container
echo "Running database migrations..."
docker compose exec -T app php artisan migrate --force

echo "✅ Initialization complete!"
echo "🌐 Visit: http://localhost:8000"
