# Web-based Inventory Tracking and Management System
**Solo Capstone Project | B.S. Information Technology**

## 📌 Project Overview
This project is a comprehensive inventory solution designed to manage complex item lifecycles with real-time tracking. Developed for a specific business beneficiary, the system focuses on replacing manual tracking with an automated transaction and queue system to ensure data synchronization and auditability.

> **Business Analysis Note:** As the sole developer and analyst, I managed the full SDLC—from conducting feasibility studies for three potential businesses to delivering a final system manuscript and user training manual.

## 📄 Project Documentation
The following documents represent the Business Analysis and technical planning phases of this project:
* 📘 [**User Transaction Reference Manual**](./docs/User_Manual.pdf) - Comprehensive guide for system operations.
* 📋 [**Technical Manuscript**](./docs/System_Manuscript.pdf) - Full documentation including BRFS, ERDs, and System Flows.

## 🛠 Tech Stack
* **Backend:** Native PHP (Procedural)
* **Frontend:** HTML, CSS, JavaScript (JQuery/AJAX)
* **Database:** MySQL
* **Environment:** XAMPP 

## ⚖️ Business Analysis Key Responsibilities
This project involved extensive documentation to bridge technical logic with business needs:
* **Requirements Gathering:** Conducted interviews with stakeholders to define functional specifications.
* **Process Mapping:** Designed the system flow for automated transaction-to-inventory synchronization.
* **Audit Tracking:** Built historical usage logs to provide actionable business insights.

## 🚀 Key Features
* **Role-Based Access Control (RBAC):** Secure permissions for Technicians, Operations Supervisors, and Admin Managers.
* **Automated Queue System:** Synchronizes item quantities automatically as transactions progress.
* **Audit Logging:** Detailed tracking of item entries and adjustments for accountability.
* **Transaction Management:** Real-time tracking of item movement from request to fulfillment.

## ⚙️ Installation & Setup
1. **Clone the repository:**
   `git clone https://github.com/RNF-Ibarra-Dev/Pestastic.git`
2. **Move to XAMPP:** Place the folder in your `C:/xampp/htdocs/` directory.
3. **Database Setup:** * Open XAMPP Control Panel and start **Apache** and **MySQL**.
   * Go to `localhost/phpmyadmin` and create a database named `pestastic_db`.
   * Import the `.sql` file located in the `/Database` folder.
4. **Access:** Open your browser and navigate to `localhost/Pestastic`.
