#!/bin/bash
set -e

echo "WARNING: This will delete ALL Docker containers, images, volumes, and networks."

# Stop containers if any exist
containers=$(docker ps -aq)
if [ -n "$containers" ]; then
    echo "Stopping all containers..."
    docker stop $containers
    echo "Removing all containers..."
    docker rm -f $containers
else
    echo "No containers to stop or remove."
fi

# Remove images if any exist
images=$(docker images -q)
if [ -n "$images" ]; then
    echo "Removing all images..."
    docker rmi -f $images
else
    echo "No images to remove."
fi

# Remove volumes if any exist
volumes=$(docker volume ls -q)
if [ -n "$volumes" ]; then
    echo "Removing all volumes..."
    docker volume rm -f $volumes
else
    echo "No volumes to remove."
fi

# Remove networks except default ones
networks=$(docker network ls -q | grep -vE '^(bridge|host|none)$')
if [ -n "$networks" ]; then
    echo "Removing all custom networks..."
    docker network rm $networks
else
    echo "No custom networks to remove."
fi

# Prune system
echo "Pruning system..."
docker system prune -a --volumes -f

echo "Docker environment nuked."
