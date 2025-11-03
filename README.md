# Sistem Antrian API (Laravel 12)

Sistem Antrian menggunakan Laravel 12, PostgreSQL, dan Laravel Sanctum untuk autentikasi Bearer Token. Docker Compose digunakan untuk orkestrasi API, database, dan frontend (opsional).

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

```
Authorization: Bearer <token>
```

## Docker Compose

* postgres → database, volume persisten
* api → Laravel API, port 8000
* frontend → Next.js, port 3000
* Volume postgres_data menyimpan data database
