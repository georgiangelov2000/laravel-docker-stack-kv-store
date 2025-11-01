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

## 💣 Cleanup Scripts

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