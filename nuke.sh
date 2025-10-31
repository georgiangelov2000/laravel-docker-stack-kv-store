#!/bin/bash

echo "Stopping all containers..."
docker stop $(docker ps -aq)

echo "Removing all containers..."
docker rm -f $(docker ps -aq)

echo "Removing all images..."
docker rmi -f $(docker images -q)

echo "Removing all volumes..."
docker volume rm -f $(docker volume ls -q)

echo "Removing all networks..."
docker network rm $(docker network ls -q | grep -v "bridge\|host\|none")

echo "Pruning system..."
docker system prune -a --volumes -f

echo  Docker environment nuked."
