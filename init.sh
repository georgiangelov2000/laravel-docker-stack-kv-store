#!/usr/bin/env bash
set -e

echo "Starting Laravel Docker initialization..."

# Define project root as current directory
PROJECT_ROOT="$(pwd)"

# Step 0: Ensure mysql.env exists
if [ ! -f "$PROJECT_ROOT/env/mysql.env" ]; then
    echo "mysql.env not found. Copying from env_example..."
    mkdir -p "$PROJECT_ROOT/env"
    cp -r "$PROJECT_ROOT/env_example/mysql.env" "$PROJECT_ROOT/env/mysql.env"
    echo "mysql.env created from env_example."
else
    echo "mysql.env already exists."
fi

# Step 0.5: Ensure .env exists
if [ ! -f "$PROJECT_ROOT/app/.env" ]; then
    echo ".env not found. Copying from .env.example..."
    cp "$PROJECT_ROOT/app/.env.example" "$PROJECT_ROOT/app/.env"
fi

# Step 1: Build and start all containers
echo "Building and starting containers..."
docker compose -f "$PROJECT_ROOT/docker-compose.yml" up -d --build

# Step 2: Run migrations inside the app container
echo "Running database migrations..."
docker compose exec -T app php artisan migrate --force

echo "✅ Initialization complete!"
echo "🌐 Visit: http://localhost:8000"
