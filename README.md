# 🐳 Laravel Docker Environment (Stack + Key-Value Store)

A Laravel 12 Dockerized environment featuring two SOLID-based modules:

- 🧱 **Stack (LIFO)** — Add / Get items from a MySQL-backed stack  
- 🗝️ **Key-Value Store** — Store data with optional TTLs

---

## 📁 Project Structure
```
.
├── app/
│   ├── Contracts/
│   │   ├── Repositories/
│   │   └── Services/
│   ├── Http/
│   │   ├── Controllers/API/V1/
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   ├── Providers/
│   ├── Repositories/Eloquent/
│   └── Services/
├── database/migrations/
├── docker/
│   ├── php/local.ini
│   ├── nginx/default.conf
│   └── mysql/my.cnf
├── docker-compose.yml
├── Dockerfile
├── init.sh
├── nuke-everything.sh
└── README.md
```

---

## 🚀 Quick Setup

### 1️⃣ Start Everything
```bash
chmod +x init.sh
./init.sh
```

This will:
- Build and start Docker containers  
- Wait for MySQL  
- Run Laravel migrations automatically  

---

## 🧱 Stack (LIFO)

| Method | Endpoint | Description |
|---------|-----------|-------------|
| `POST` | `/api/v1/stack/add` | Add item to the stack |
| `GET` | `/api/v1/stack/get` | Get (and remove) top item from stack |

**Example**
```bash
curl -X POST http://localhost:8000/api/v1/stack/add   -H "Content-Type: application/json"   -d '{"name":"main","value":"Hello"}'

curl http://localhost:8000/api/v1/stack/get
```

---

## 🗝️ Key-Value Store

| Method | Endpoint | Description |
|---------|-----------|-------------|
| `POST` | `/api/v1/kv/add` | Add key-value pair (optional TTL) |
| `GET`  | `/api/v1/kv/get/{key}` | Get value for key |
| `DELETE` | `/api/v1/kv/delete` | Delete key-value pair |

**Example**
```bash
# Add
curl -X POST http://localhost:8000/api/v1/kv/add   -H "Content-Type: application/json"   -d '{"key":"name","value":"John","ttl":30}'

# Get
curl http://localhost:8000/api/v1/kv/get/name

# Delete
curl -X DELETE http://localhost:8000/api/v1/kv/delete   -H "Content-Type: application/json"   -d '{"key":"name"}'
```

---

## 🧰 Common Docker Commands

```bash
# Start / Stop
docker compose up -d
docker compose down

# Access PHP container
docker compose exec app bash

# Run Artisan commands
docker compose exec app php artisan migrate
docker compose exec app php artisan tinker
```

---

## 💣 Cleanup Scripts

| Script | Purpose |
|---------|----------|
| `init.sh` | Build containers, start stack, and run migrations |
| `nuke-everything.sh` | **Deletes ALL** Docker containers, images, volumes, and networks (irreversible) |

**Usage**
```bash
# Full reset
./nuke-everything.sh
# Fresh rebuild
./init.sh
```

---

## ⚙️ Environment

`.env` example:
```dotenv
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password
```

---

## 🧠 Architecture Overview

```
[ HTTP Client ]
      ↓
[ Controller ]
      ↓
[ Service Layer ]
      ↓
[ Repository Interface ]
      ↓
[ Eloquent Repository ]
      ↓
[ MySQL Database ]
```

- **Controllers** → handle HTTP input/output  
- **Services** → contain business logic  
- **Repositories** → abstract data access  
- **Models** → represent database entities  

---

## 🏁 Summary

| Command | Description |
|----------|--------------|
| `./init.sh` | Initialize and migrate Laravel containers |
| `./nuke-everything.sh` | Wipe all Docker data |
---