# 🛒 E-Commerce App – Admin & Cart API (Laravel 10)

This project is a **Laravel 10** based practical task that includes a secure **Admin Product Management Panel** and a **Customer Cart API** powered by **Laravel Sanctum**.

---

## ✅ Features & Checklist

* [x] Admin Authentication & Middleware
* [x] Product CRUD (Create, Read, Update, Delete)
* [x] AJAX Product Search
* [x] Product Status Toggle (Active / Inactive)
* [x] Sanctum API Authentication (Register / Login / Logout)
* [x] Cart Module (Add, Update, Delete, List)
* [x] Checkout API with Stock Validation & Database Transactions

---

## 🛠️ Installation & Setup

Follow the steps below to run the project on your local machine.

---

## 1️⃣ Clone the Repository

```bash
git clone https://github.com/akashnikam114/ecommerce-app.git
cd ecommerce-app
```

---

## 2️⃣ Environment Configuration

Create the environment file:

```bash
cp .env.example .env
```

### Database Setup

Create a MySQL database named:

```text
ecommerce_db
```

Update the following values in your `.env` file:

```env
DB_DATABASE=ecommerce_db
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

---

## 3️⃣ Install Dependencies

### PHP Dependencies

```bash
composer update
```

### Frontend Assets (Vite)

```bash
npm install
npm run dev
```

---

## 4️⃣ Application Key, Migration & Seeding

Run the following commands:

```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

---

## 5️⃣ Run the Application

```bash
php artisan serve
```

The application will be available at:

```text
http://127.0.0.1:8000
```

---

## 🔐 Admin Login Details

Use the following credentials to access the Admin Panel:

* **Login URL:** [http://127.0.0.1:8000/login]
* **Email:** [admin@example.com]
* **Password:** Admin@123

---

## 📦 API Authentication (Sanctum)

All customer APIs (Cart & Checkout) are protected using **Laravel Sanctum**.

* Register API
* Login API
* Logout API
* Authenticated Cart APIs

---
