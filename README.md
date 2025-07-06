# Birzeit Flat Rent 🏘️

A web-based flat rental system developed by **Amer Eid** for COMP334. The system facilitates the listing, search, preview, and rental of flats in Birzeit with support for multiple user roles including customers, owners, and managers.

---

## 🧑‍💼 User Roles

- **Customers**  
  - Register and log in  
  - Search for flats with filtering  
  - View flat details and photos  
  - Request preview appointments  
  - Add rentals to basket and checkout  

- **Owners**  
  - Register and log in  
  - Submit new flats with marketing info and photos  
  - Manage preview appointment slots  
  - View feedback from managers  

- **Managers**  
  - Log in  
  - Review and approve submitted flats  
  - View inquiries and user information  
  - Manage system-wide messages  

---

## 🧩 Technologies Used

- **Frontend:** HTML5, CSS3 (no Bootstrap, all custom styling)
- **Backend:** PHP 8+ (Procedural)
- **Database:** MySQL with PDO
- **Session management:** PHP $_SESSION
- **Validation:** HTML5 + PHP-side

---

## 🚀 Features

- Fully searchable flat catalog with filters
- Custom photo upload and storage per flat
- Role-based navigation and dashboard
- Customer rental cart ("basket") system
- Manager inquiry and sort/filter tools
- Flat availability control logic

---

## 📝 Installation

1. Clone the repo:
   ```bash
   git clone https://github.com/amereid/birzeit-flat-rent.git
   ```
2. Import the SQL database file:  
   `birzeit_flatrent_1220505.sql` into your MySQL server.

3. Configure your `dbconfig.inc.php` file with your DB credentials:
   ```php
   $pdo = new PDO("mysql:host=localhost;dbname=birzeit_flatrent_1220505", "username", "password");
   ```

4. Host using XAMPP/LAMP or live PHP server.

---

## ⚠️ Notes

- Avoid using the `approved` flag to hide flats after rental (now replaced with availability logic).
- Sort preferences are stored in cookies for managers.
- All UI is built using clean and accessible HTML and CSS without JavaScript or frameworks.

---

## 🙋 Author

Developed by **Amer Eid**  
📧 amereid666@gmail.com

---

## 📃 License

This project is for educational use only.
