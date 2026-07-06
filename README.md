# BengkelSmart 🛠️

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Filament](https://img.shields.io/badge/Filament-4.x-FDB347?style=for-the-badge&logo=laravel&logoColor=black)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Midtrans](https://img.shields.io/badge/Payment-Midtrans-blue?style=for-the-badge)](https://midtrans.com)
[![Gemini AI](https://img.shields.io/badge/AI-Google_Gemini-8E75C2?style=for-the-badge&logo=google-gemini&logoColor=white)](https://deepmind.google/technologies/gemini)

**BengkelSmart** is a modern, cloud-based Software-as-a-Service (SaaS) platform designed for automotive workshop/repair shop management. It streamlines core business operations, including customer relationships, real-time spareparts inventory tracking, repair service workflows, invoicing, subscription-based billing via Midtrans, financial reporting, and an integrated **Google Gemini AI Assistant** that provides smart insights based on live workshop metrics.

---

## 🗺️ System Flow & Architecture

The following diagram illustrates the workflow of the BengkelSmart platform:

```mermaid
graph TD
    A[Public Visitor] -->|1. Register & Login| B[Workshop Dashboard]
    B -->|2. Select Plan| C[Midtrans Payment Gateway]
    C -->|3. Callback IPN| D[Active Subscription]
    D -->|4. Unlocks| E[Dashboard & Features]
    E -->|Manage| F[Customers]
    E -->|Manage| G[Spareparts Inventory]
    E -->|Manage| H[Repair Services]
    E -->|Generate| I[Transactions & Invoices]
    E -->|Export| J[Excel Financial Reports]
    E -->|Consult| K[Google Gemini AI Chatbot]
```

---

## ✨ Core Features

*   **💳 SaaS Billing & Subscriptions**:
    *   Tiered subscription plans (Free and Premium).
    *   Secure checkout integration via **Midtrans Snap API**.
    *   Automated subscription state management utilizing Midtrans Webhook/IPN callbacks.
*   **🤖 Google Gemini AI Chatbot**:
    *   Context-aware AI assistant tailored specifically to your workshop.
    *   Automatically pulls live metrics (e.g. today's revenue, low stock spareparts, pending repairs) to answer analytical and advisory questions dynamically.
*   **📦 Inventory & Spareparts Management**:
    *   Track inventory, add stock manually, and receive automated visual alerts when items fall below safety thresholds (low stock warning).
*   **🔧 Repair Service Workflow**:
    *   Manage active repairs with live status updates (Pending, Process, Completed, etc.).
    *   Dynamically associate services with spareparts used and calculate total labor/jasa fees.
*   **🧾 Transactions & Automated Invoicing**:
    *   Generate print-friendly invoices automatically upon service completion.
    *   Process payments and record transactions seamlessly.
*   **📊 Financial & Export Reports**:
    *   Visual dashboard charts tracking monthly service traffic and revenue.
    *   Export comprehensive revenue and transaction histories into Excel (`.xlsx`) format.
*   **🔑 System Admin Panel**:
    *   Robust administrative backend powered by **Filament v4** located at `/admin`.
    *   Allows super admins to manage Plan options, active subscriptions, and registered workshops.

---

## 🛠️ Tech Stack

*   **Backend**: Laravel 12.x (PHP 8.2+)
*   **Frontend**: Tailwind CSS, Blade Templates, JavaScript (Vite)
*   **Administration Panel**: Filament v4 (Filament PHP)
*   **Payment Gateway**: Midtrans PHP SDK (Snap & API integration)
*   **AI Integration**: Google Gemini API
*   **Excel Export**: Maatwebsite Laravel Excel v3
*   **Database & Cache**: MySQL/SQLite, Laravel Cache wrapper

---

## 🚀 Installation & Local Setup

Follow these steps to set up BengkelSmart on your local machine:

### 1. Prerequisites
Ensure you have the following installed:
*   PHP 8.2 or higher
*   Composer
*   Node.js & NPM
*   A local database engine (e.g. MySQL, SQLite)

### 2. Clone the Repository
```bash
git clone https://github.com/your-username/BengkelSmart.git
cd BengkelSmart
```

### 3. Install Dependencies
```bash
composer install
npm install
```

### 4. Configure Environment File
Copy the sample environment file to `.env`:
```bash
cp .env.example .env
```
Open `.env` and fill in your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bengkel_smart
DB_USERNAME=root
DB_PASSWORD=
```
Add your **Midtrans** and **Google Gemini** API credentials:
```env
# Midtrans Credentials
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_IS_PRODUCTION=false

# Google Gemini AI
GEMINI_API_KEY=your_google_gemini_api_key
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Run Database Migrations & Seeds
Run the migrations along with the database seeder to create the default super admin account:
```bash
php artisan migrate --seed
```

### 7. Build Assets & Start Servers
Start the Laravel development server:
```bash
php artisan serve
```
In another terminal tab, run Vite to compile frontend assets:
```bash
npm run dev
```

---

## 🔑 Default Credentials

### System Admin Panel
You can access the System Admin Panel at `http://localhost:8000/admin`.
*   **Email**: `admin@gmail.com`
*   **Password**: `adminsistem123`

---

## 📄 License

The BengkelSmart software is open-sourced software licensed under the [MIT license](LICENSE).
