# Sistem Antrian API (Laravel 12)

Sistem Antrian menggunakan Laravel 12, PostgreSQL, dan Laravel Sanctum untuk autentikasi Bearer Token. Docker Compose digunakan untuk menjalankan API, database, dan frontend (opsional).

## Teknologi

* Laravel 12 (PHP 8.3)
* PostgreSQL 16
* Laravel Sanctum
* Docker & Docker Compose
* Nginx
* Next.js (frontend, opsional)

## Struktur

```
sistem-antrian/
├── api-laravel/
├── docs/
├── frontend-next/
├── infra/
│   ├── .env
│   ├── docker-compose.yaml
│   └── sistem_antrian_k3s.yaml
├── nginx/
└── README.md
```

## Contoh Konfigurasi Database

```
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=antrian_demo
DB_USERNAME=user_demo
DB_PASSWORD=password123
```

## Arsitektur

```
Browser / Frontend
        ↓
Laravel API (Port 8000)
        ↓
PostgreSQL Database (Port 5432)
```

## Autentikasi

Bearer Token digunakan pada request yang membutuhkan autentikasi:

```
Authorization: Bearer <token>
```

Login melalui `/api/login`.

## Docker Compose

* postgres → database, volume persisten
* api → Laravel API, port 8000
* frontend → Next.js, port 3000 (opsional)
* Volume `postgres_data` menyimpan data database

### Menjalankan Docker Compose

```bash
cd infra
docker compose up --build
```

Akses layanan:

* API: [http://localhost:8000](http://localhost:8000)
* Frontend: [http://localhost:3000](http://localhost:3000) (opsional)

Hentikan container:

```bash
docker compose down
```

Jalankan container di background:

```bash
docker compose up -d
```

## Kontributor

Gananta – App Developer
