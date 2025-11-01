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

## Quick Setup

### Start Everything
```bash
chmod +x init.sh
./init.sh
```

This will:
- Build and start Docker containers  
- Wait for MySQL  
- Run Laravel migrations automatically  

---

```bash
|CONTAINER ID  | IMAGE                              | PORTS                                                   | NAMES
|3a8bbc2ce1c3  | nginx:alpine                       | 0.0.0.0:8000->80/tcp, [::]:8000->80/tcp                 | laravel_nginx
|37f989c2302e  | laravel-docker-stack-kv-store-app  | 9000/tcp                                                | laravel_app
|11cf60e2b2bd  | mysql:8.0                          | 0.0.0.0:3306->3306/tcp, [::]:3306->3306/tcp, 33060/tcp  | laravel_mysql
```

## Stack (LIFO)

| Method | Endpoint | Description |
|---------|-----------|-------------|
| `POST` | `/api/v1/stack/add` | Add item to the stack |
| `GET` | `/api/v1/stack/get` | Get (and remove) top item from stack |

**Example**
```bash
#Add "Hello" to stack
curl -X POST http://localhost:8000/api/v1/stack/add \
  -H "Content-Type: application/json" \
  -d '{"value":"Hello"}'

#Add "World" to stack
curl -X POST http://localhost:8000/api/v1/stack/add \
  -H "Content-Type: application/json" \
  -d '{"value":"World"}'

#Get (pop) top item — should return "World"
curl http://localhost:8000/api/v1/stack/get

#Add "Again" to stack
curl -X POST http://localhost:8000/api/v1/stack/add \
  -H "Content-Type: application/json" \
  -d '{"value":"Again"}'

#Get (pop) top item — should return "Again"
curl http://localhost:8000/api/v1/stack/get

#Get (pop) next item — should return "Hello"
curl http://localhost:8000/api/v1/stack/get

```

---

## Key-Value Store

| Method | Endpoint | Description |
|---------|-----------|-------------|
| `POST` | `/api/v1/kv/add` | Add key-value pair (optional TTL) |
| `GET`  | `/api/v1/kv/get/{key}` | Get value for key |
| `DELETE` | `/api/v1/kv/delete` | Delete key-value pair |

**Example**
```bash
# 1) Set "name" = "John"  (no TTL)
curl -X POST http://localhost:8000/api/v1/kv/add \
  -H "Content-Type: application/json" \
  -d '{"key":"name","value":"John"}'

# 2) Get "name"  → returns "John"
curl http://localhost:8000/api/v1/kv/get/name

# 3) Get "age" (never set)  → returns empty/not found
curl http://localhost:8000/api/v1/kv/get/age

# 4) Overwrite "name" = "Larry" with TTL = 30s
curl -X POST http://localhost:8000/api/v1/kv/add \
  -H "Content-Type: application/json" \
  -d '{"key":"name","value":"Larry","ttl":30}'

# 5) Get "name" within 30s  → returns "Larry"
curl http://localhost:8000/api/v1/kv/get/name

# 6) Get "name" after >30s  → returns empty/not found (expired)
curl http://localhost:8000/api/v1/kv/get/name

# 7) Delete a key explicitly (body carries the key)
curl -X DELETE http://localhost:8000/api/v1/kv/delete \
  -H "Content-Type: application/json" \
  -d '{"key":"name"}'
```

---

## Cleanup Scripts

| Script | Purpose |
|---------|----------|
| `init.sh` | Build containers, start stack, and run migrations |
| `nuke.sh` | **Deletes ALL** Docker containers, images, volumes, and networks (irreversible) |

**Usage**
```bash
# Full reset
./nuke.sh
# Fresh rebuild
./init.sh
```

---

## Architecture Overview

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