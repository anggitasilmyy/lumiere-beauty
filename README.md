<div align="center">

# ✨ Lumiere Beauty

### Beauty Treatment Booking & Management System

A web-based information system designed to support beauty treatment
booking, scheduling, simulated payments, promotions, loyalty points,
reviews, and administrative management.

**Academic Project — Web Programming**

![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap&logoColor=white)

</div>

---

## 📖 Overview

**Lumiere Beauty** is a web-based beauty treatment booking and management system developed as an academic project for the **Web Programming** course.

The project focuses on digitalizing the beauty treatment reservation process by providing a centralized platform where customers can explore available treatments and promotions, make reservations, complete a simulated payment process, manage their bookings, collect loyalty points, and submit reviews.

The system also provides an administrative interface for managing treatments, promotions, and customer bookings.

---

## 💡 Problem Background

Beauty service businesses need an organized process for managing treatment information, reservations, schedules, promotions, and transaction records.

When these activities are handled separately or manually, customers may experience difficulties when checking available services, arranging treatment schedules, or tracking their reservations.

Lumiere Beauty was designed to demonstrate how a web-based information system can integrate these processes into a more structured digital workflow.

---

## 🎯 Project Objectives

The main objectives of this project are to:

- Provide customers with easy access to treatment and promotion information.
- Support an integrated treatment booking process.
- Organize booking and payment information in one system.
- Allow customers to monitor their booking history and loyalty points.
- Provide administrators with tools to manage treatments, bookings, and promotions.
- Apply web programming concepts using the Laravel framework and relational database management.

---

## 👥 User Roles

### Customer

Customers can:

- Register and log in to the system
- Browse available beauty treatments
- View active promotions
- Book a treatment and select a schedule
- Complete a simulated payment process
- View booking history
- View payment receipts
- Monitor loyalty points
- Manage profile information
- Submit treatment reviews

### Administrator

Administrators can:

- Access the administrative dashboard
- Manage treatment information
- Activate or deactivate treatments
- Monitor customer bookings
- Update booking status
- Manage promotional programs

---

## 🔄 Core Business Flow

```text
Customer
   │
   ▼
Browse Treatments
   │
   ▼
Select Treatment
   │
   ▼
Choose Booking Schedule
   │
   ▼
Checkout
   │
   ▼
Simulated Payment
   │
   ▼
Booking Confirmation
   │
   ▼
Booking History / Receipt
   │
   ▼
Treatment & Review

The administrator manages treatment availability, booking status, and promotional information through the administrative dashboard.

⭐ Key Features
Authentication

User registration, login, logout, and role-based access between customers and administrators.

Treatment Management

Customers can explore available beauty treatments while administrators can create, update, and manage treatment availability.

Booking Management

Customers can select treatments and schedules, while administrators can monitor and update booking statuses.

Simulated Payment

The system provides a payment workflow using dummy payment information for academic demonstration purposes.

Disclaimer: No real payment gateway or real financial transaction is used in this project.

Promotions

Customers can view promotional offers while administrators can manage promotion information.

Loyalty Points

Customers can monitor accumulated loyalty points through their accounts.

Payment Receipt

Customers can access booking-related payment receipt information.

Reviews

Authenticated customers can submit reviews related to the service experience.

Admin Dashboard

Provides administrative access for managing treatments, bookings, and promotions.

🛠️ Technology Stack
Category	Technology
Backend	Laravel 10, PHP
Frontend	Blade Template, HTML, CSS
UI Framework	Bootstrap 5
Database	MySQL
Build Tool	Vite
Version Control	Git & GitHub
Local Development	XAMPP
🏗️ System Architecture

Lumiere Beauty follows Laravel's MVC (Model-View-Controller) architecture.

User
  │
  ▼
Routes
  │
  ▼
Controller
  │
  ├── Business Logic
  │
  ▼
Model
  │
  ▼
MySQL Database
  │
  ▼
Blade View
  │
  ▼
User Interface

This separation helps organize application logic, data management, and presentation components.

📸 Screenshots

Project screenshots will be presented here to demonstrate the main customer and administrative workflows.

<!-- Screenshots will be added after documentation preparation -->
🚀 Local Installation
Requirements

Make sure the following software is available:

PHP 8.1 or later
Composer
MySQL
Node.js & NPM
Git
1. Clone Repository
git clone https://github.com/anggitasilmyy/lumiere-beauty.git
cd lumiere-beauty
2. Install PHP Dependencies
composer install
3. Install Frontend Dependencies
npm install
4. Create Environment File
cp .env.example .env

For Windows, you can manually duplicate .env.example and rename it to .env.

5. Generate Application Key
php artisan key:generate
6. Configure Database

Update the following configuration inside .env:

DB_DATABASE=lumiere_beauty
DB_USERNAME=root
DB_PASSWORD=

Adjust the username and password according to your local MySQL configuration.

7. Run Database Migration and Seeder
php artisan migrate --seed
8. Run Frontend Development Server
npm run dev
9. Run Laravel Application
php artisan serve

Open:

http://127.0.0.1:8000
🔐 Security & Demo Data

This repository is intended for academic and portfolio purposes.

Sensitive production credentials are not stored in the repository.

Payment information contained in this project is dummy data used solely to demonstrate the application workflow.

Any administrator account generated through database seeders is intended only for local development and demonstration purposes and must not be used as a production credential.

🎓 Academic Context

This project was developed as part of a Web Programming course project to apply concepts including:

Laravel MVC · Routing · Authentication · CRUD Operations ·
Database Integration · Role-Based Access · Booking Workflow

The project demonstrates the implementation of a complete web-based information system, from customer-facing functionality to administrative management.

📂 Repository

Lumiere Beauty

🔗 https://github.com/anggitasilmyy/lumiere-beauty

👩🏻‍💻 Developer

Silmy Kaffa Anggita
Information Systems Student — Universitas Esa Unggul

LinkedIn ·
GitHub

<div align="center">
✨ Lumiere Beauty

Academic Web Programming Project

</div> ```
