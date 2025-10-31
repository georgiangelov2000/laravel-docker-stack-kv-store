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

# Step 1.5: Install composer dependencies if vendor doesn't exist
if [ ! -d "$PROJECT_ROOT/app/vendor" ]; then
    echo "vendor directory not found. Running composer install..."
    docker compose exec -T app composer install
    echo "Composer dependencies installed."
else
    echo "vendor directory exists. Skipping composer install."
fi

# Step 2: Run database migrations
echo "Running database migrations..."
docker compose exec app php artisan migrate
docker compose exec app php artisan key:generate

# Step 3: Generate application key
echo "Generating application key..."
docker compose exec app php artisan key:generate

# Step 4: Refresh Laravel configuration cache
echo "Refreshing configurations..."
docker compose exec app php artisan optimize:clear


echo "Initialization complete!"
echo "Visit: http://localhost:8000"
