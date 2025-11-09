# Treetan E-Commerce API

Backend sederhana untuk sistem penjualan barang (e-commerce) yang dibangun menggunakan **Laravel**.  
Fitur yang dicakup sudah sesuai dengan technical test:

-   Register & Login user (Laravel Sanctum)
-   Manajemen produk (list & detail)
-   Checkout & pembuatan pesanan
-   Integrasi Payment Gateway (Xendit) sampai webhook
-   Riwayat pemesanan
-   Proteksi API dengan **Access Key** di header + token auth

## Tech Stack

-   PHP 8.x
-   Laravel 11
-   MySQL (Railway)
-   Laravel Sanctum (API auth)
-   Xendit (Payment Gateway)
-   Railway.app (deployment)

## Live URL

-   **Base URL Backend**  
    `https://treetan-ecommerce-api-production.up.railway.app`

-   **API Docs (Postman)**  
    `[<URL_POSTMAN_DOCS_KAMU_DI_SINI>](https://.postman.co/workspace/My-Workspace~01d661ba-12e0-4eec-8232-b1c6f3851c87/request/27678903-f0e649ff-eb5c-464d-9bd2-c897153f4a07?action=share&creator=27678903&ctx=documentation&active-environment=27678903-1051441c-62a3-498a-946b-6143dd55e7b5)`

## Fitur Utama

-   **Auth**

    -   `POST /api/register` — registrasi user baru
    -   `POST /api/login` — login & generate API token (Sanctum)

-   **Produk**

    -   `GET /api/products` — list produk (paginate)
    -   `GET /api/products/{id}` — detail produk

-   **Checkout & Pesanan**

    -   `POST /api/checkout` — buat order baru dari cart sederhana (produk + qty)
    -   `GET /api/orders` — list pesanan milik user login
    -   `GET /api/orders/{id}` — detail pesanan

-   **Pembayaran**

    -   `POST /api/payment/{order}` — membuat invoice pembayaran ke Xendit
    -   `POST /api/payment/webhook` — endpoint webhook untuk update status order ketika pembayaran berhasil

-   **Security**
    -   Semua request ke `/api/*` harus menyertakan header:
        -   `X-Access-Key: supersecretaccesskey`
    -   Endpoint selain `/api/register` dan `/api/login` juga harus menyertakan:
        -   `Authorization: Bearer <token>`

## Cara Menjalankan Secara Lokal

### 1. Clone & install dependency

```bash
git clone <URL_REPO_KAMU>
cd <NAMA_FOLDER_REPO>
composer install
cp .env.example .env
php artisan key:generate
```
