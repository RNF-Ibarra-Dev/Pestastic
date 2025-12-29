# Web-based Inventory Tracking and Management System
**Capstone Project (Solo) | B.S. [cite_start]Information Technology** [cite: 5, 45]

## 📌 Project Overview
[cite_start]This project is a comprehensive inventory solution designed to manage complex item lifecycles with real-time tracking[cite: 45]. [cite_start]It features an automated transaction and queue system to ensure data synchronization and auditability[cite: 48, 50].

## 🛠 Tech Stack
* [cite_start]**Backend:** Native PHP [cite: 29, 47]
* [cite_start]**Frontend:** HTML, CSS, JavaScript [cite: 21]
* [cite_start]**Database:** MySQL [cite: 31, 47]
* [cite_start]**Environment:** XAMPP [cite: 31, 47]

## 🚀 Key Features
* [cite_start]**Role-Based Access Control (RBAC):** Secure login and permissions for Technicians, Operations Supervisors, and Admin Managers[cite: 49].
* [cite_start]**Automated Queue System:** Synchronizes item quantities automatically as transactions progress through the workflow[cite: 48].
* [cite_start]**Audit Logging:** Detailed tracking of item entries, adjustments, and historical usage for business reporting[cite: 50].

## ⚙️ Installation & Setup
1. **Clone the repository:**
   [cite_start]`git clone https://github.com/RNF-Ibarra-Dev/Pestastic.git` [cite: 46]
2. **Move to XAMPP:** Place the folder in your `C:/xampp/htdocs/` directory.
3. **Database Setup:** * Open XAMPP Control Panel and start **Apache** and **MySQL**.
   * Go to `localhost/phpmyadmin`.
   * Create a new database named `pestastic_db`.
   * Import the provided `.sql` file located in the `/Database` folder.
4. **Configuration:** Update any database credentials in the connection file.
5. **Access:** Open your browser and navigate to `localhost/Pestastic`.
