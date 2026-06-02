# NexusCRM

NexusCRM is a clean, modern, and highly responsive Customer Relationship Management (CRM) platform designed for modern sales and operations teams. It streamlines your sales pipeline, automates invoicing and billing, tracks customer engagements, and provides visual, real-time insights into your business metrics.

---

## 🚀 Core Features

- **Dynamic Dashboard**:
  - Live statistics overview (Total Customers, Proposals, Invoices, and Transactions).
  - **Revenue progress gauge** (using ApexCharts) showing live earnings against targeted revenue.
  - **Invoice status distributions** represented as an interactive Pie Chart (Paid, Sent, Draft).
  - **Recent Transactions Feed** showcasing the latest activities.
- **Customer Hub**: Centrally manage customer profiles, toggle active status, and track historical proposals/invoices.
- **Proposal Management**: Draft custom proposals with validity terms and update status directly from your pipeline.
- **Invoicing & Billing**: Auto-generate unique invoices, manage payment status, and send beautiful HTML invoices via email.
- **Stripe Payments Integration**: Integrated checkout sessions and Stripe Webhook handlers to automatically track and update transaction logs when payments are completed.
- **Transaction Logs**: Global log tracing payment references, amount, dates, and gateways.

---

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 10
- **Language**: PHP 8.1.25
- **Authentication**: Laravel Breeze (scaffolded with Vue 3 & Inertia)
- **Database**: Eloquent ORM (SQLite / MySQL / PostgreSQL support)
- **Payment Gateway**: Stripe API (`stripe/stripe-php` Integration)
- **Email Delivery / Testing**: Mailtrap (sandbox SMTP service)
- **Emailing Layouts**: Laravel Mailables (Markdown/HTML templates)

### Frontend
- **Framework**: Vue 3 (Composition API / Script Setup)
- **Integration**: Inertia.js (Modern monolith single-page app structure)
- **Build Tool**: Vite
- **Styling**: Tailwind CSS
- **Visualization**: Vue3 ApexCharts (Pie Charts, Semi-Circular Gauge Charts)
- **Icons**: Lucide Vue Next

---

## 📂 Project Structure

```
├── app/
│   ├── Http/Controllers/       # Customer, Proposal, Invoice, Stripe, and Dashboard Controllers
│   ├── Mail/                   # Mail configurations (InvoiceMail, WelcomeMail)
│   └── Models/                 # Eloquent Database Models (User, Customer, Proposal, Invoice, Transaction)
├── bootstrap/                  # Framework bootstrap configuration
├── config/                     # Application configurations (database, mail, services, inertia)
├── database/
│   ├── factories/              # Factories for testing and seeding sample database rows
│   ├── migrations/             # Schema migration files for tables structure
│   └── seeders/                # Database seeders (sets up default admin credentials and dummy data)
├── public/                     # Static media and build index files (e.g., logo, favicon)
├── resources/
│   ├── css/                    # Core styles (app.css)
│   ├── js/
│   │   ├── Components/         # Reusable Vue components (Charts, StatusBadges, Confirmation Modals)
│   │   ├── Layouts/            # App view shells (AppLayout with sidebar navigation, GuestLayout)
│   │   ├── Pages/              # Page views (Dashboard, Customers, Proposals, Invoices, Profile)
│   │   └── app.js              # Application entry point
│   └── views/                  # Base Blade layout template
├── routes/                     # Application routing (web.php, auth.php, api.php)
├── tests/                      # Automated Feature and Unit test files
├── tailwind.config.js          # Tailwind styling presets configuration
└── vite.config.js              # Vite assembly settings
```

---

## ⚙️ Setup and Installation

Follow these steps to set up NexusCRM locally on your machine.

### Prerequisites
- **PHP** >= 8.1
- **Composer** (PHP Package Manager)
- **Node.js** & **npm**

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd crm-app
```

### Step 2: Install Backend Dependencies
```bash
composer install
```

### Step 3: Install Frontend Dependencies
```bash
npm install
```

### Step 4: Configure Environment Settings
Copy the template configuration file to create your local `.env`:
```bash
cp .env.example .env
```
Open `.env` and fill in your local system details:
- **Database Connection**: Set up SQLite (e.g., `DB_CONNECTION=sqlite`, `DB_DATABASE=database.sqlite`) or configure your MySQL/PostgreSQL server details.
- **Mail Configuration**: Set up Mailtrap or SMTP credentials to test invoice delivery.
- **Stripe Credentials**: Insert your Stripe API public key, secret key, and webhook signing secret.

### Step 5: Initialize Application Keys
```bash
php artisan key:generate
```

### Step 6: Migrations & Seeders
Run database schema builds and populate dummy records:
```bash
php artisan migrate --seed
```
*Note: Seeding creates a default administrator user:*
- **Email**: `admin@example.com`
- **Password**: `password`

### Step 7: Run the Application
Open two terminal windows to boot the servers:

**Terminal 1 (Backend Artisan Server):**
```bash
php artisan serve
```

**Terminal 2 (Frontend Vite Compiler):**
```bash
npm run dev
```

Your app will be live and ready at `http://localhost:8000`.

---

## 🧪 Running Tests

NexusCRM has complete test coverage for all features (Auth, Profile, Customers, Proposals, and Invoices):
```bash
php artisan test
```

---

## 📝 License

NexusCRM is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
