# Berans Trading Project

Berans Trading is a comprehensive inventory and order management system designed for businesses involved in international trade and currency exchange. This project simplifies and streamlines day-to-day trading operations with features that include:

📊 Inventory Management – Track stock levels, product details, and availability in real-time.

💱 Currency Exchange Tracking – Monitor and manage currency rates for multi-currency transactions.

🛒 Order & Product Management – Efficiently process customer orders, manage product listings, and ensure accurate order fulfillment.

🚚 Delivery & Shipping Tracking – Keep tabs on both local and international shipping statuses, with detailed tracking for overseas delivery.

💼 Commission System – Integrated commission tracking for agents, affiliates, or sales representatives involved in the transaction process.

Built to support growing trading operations, Berans Trading offers a robust backend foundation for seamless workflow management and cross-border trading visibility.

# 📁 Project Modular Architecture

The Berans Trading project is organized using a modular directory structure designed for clarity, separation of concerns, and ease of maintenance. The folders are grouped by functionality, with special attention to backend/frontend separation, code reuse, and automation.

Here’s a breakdown of the main structure:

**🔐 User-Based Modules**

Each user-type module (admin/, user/, and authentication/) follows a consistent internal structure:

`public/` – Contains frontend assets like UI pages, forms, and displays.

`private/` – Contains backend logic, scripts, and controllers.

`include/` – Holds reusable components like headers, footers, navigation bars, etc.

This separation ensures a clean distinction between presentation, logic, and shared elements.

**🛡️ authentication/**

Handles all authentication-related processes such as login, registration, and session management, with its own public/, private/, and include/ folders just like the user and admin modules.

**🌐 global/**

Contains globally used resources like:

`db_connect.php` – Establishes database connections.

`db_close.php` – Handles closing connections.

Other utility files that are shared project-wide.

**🗂️ siteidentity/**

Houses branding elements:

`logo/` – Stores uploaded logos.

`favicon/` – Stores site favicons.

Useful for dynamic site identity management.

**🖼️ media/**

Stores user-uploaded files, such as product images, receipts, or other assets.

**💾 backup/**

Automates and organizes project backups with:

`databases/` – Stores SQL dump backups of the database.

`websites/` – Stores zipped or mirrored backups of the web application files.

**🗃️ database/**

Contains raw SQL files (e.g. schema.sql, seeders.sql) necessary for setting up or resetting the database. This is not connected to the backup automation—rather, it's used for manual or development setups.

**📄 index.php and .htaccess**

Each main folder (admin/, user/, etc.) includes an index.php file to handle redirection or access control.
A root .htaccess file enables clean URLs, routing, and basic security controls (e.g., preventing directory listing).

**Overview of Modular**


<pre> ```text 

  
ProjectRoot/
├── htaccess
├── index.php
├── admin/
│   ├── htaccess
│   ├── index.php
│   ├── include/         # Shared frontend components (e.g. header, footer)
│   ├── private/         # Backend logic for admin
│   └── public/          # Admin frontend pages
├── user/
│   ├── htaccess
│   ├── index.php
│   ├── include/         # Shared frontend components for user
│   ├── private/         # Backend logic for user
│   └── public/          # User frontend pages
├── authentication/
│   ├── htaccess
│   ├── index.php
│   ├── include/         # Shared login UI parts
│   ├── private/         # Login/session backend logic
│   └── public/          # Login/register pages
├── backup/
│   ├── databases/       # Auto-generated database backups
│   └── websites/        # Auto-generated website file backups
├── database/            # Raw SQL files (manual setup/schema)
├── global/              # Global PHP utilities (e.g. DB connect/close)
├── media/               # Uploaded media files
├── siteidentity/
│   ├── logo/            # Site logo uploads
│   └── favicon/         # Site favicon uploads

``` </pre>









