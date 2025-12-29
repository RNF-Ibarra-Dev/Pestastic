# Web-based Inventory Tracking and Management System
**Capstone Project | B.S. Information Technology**

## 📌 Project Overview
This project is a comprehensive inventory solution designed to manage complex item lifecycles with real-time tracking. 
It features an automated transaction and queue system to ensure data synchronization and auditability.

## 🛠 Tech Stack
* **Backend:** Native PHP (Procedural)
* **Frontend:** HTML, CSS, JavaScript 
* **Database:** MySQL
* **Environment:** XAMPP 

## 🚀 Key Features
* **Role-Based Access Control (RBAC):** Secure login and permissions for Technicians, Operations Supervisors, and Admin Managers.
* **Transaction Management System:** Tracks transaction information and progress.
* **Automated Queue System:** Synchronizes item quantities automatically as transactions progress through the workflow.
* **Audit Logging:** Detailed tracking of item entries, adjustments, and historical usage for business reporting.
* **Simple Account Management:** Simple Technician and Operations Supervisor accounts management for Admin Managers.

## ⚙️ Installation & Setup
1. **Clone the repository:**
   `git clone https://github.com/RNF-Ibarra-Dev/Pestastic.git`
2. **Move to XAMPP:** Place the folder in your `C:/xampp/htdocs/` directory.
3. **Database Setup:** * Open XAMPP Control Panel and start **Apache** and **MySQL**.
   * Go to `localhost/phpmyadmin`.
   * Create a new database named `pestastic_db`.
   * Import the provided `.sql` file located in the `/Database` folder.
4. **Configuration:** Update any database credentials in the connection file.
5. **Access:** Open your browser and navigate to `localhost/Pestastic`.
