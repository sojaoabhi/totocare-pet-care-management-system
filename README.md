# 🐾 Totocare - Pet Care Management System

Totocare is a **web-based pet care platform** designed to help users adopt pets, book pet care services, and purchase pet-related products.  
It also provides an **admin panel** for managing users, pets, services, and bookings.

---

## ✨ Features
- 🐕 **Pet Adoption:** Browse and adopt pets listed by users or admins.  
- 🛁 **Pet Care Services:** Book grooming, boarding, and feeding services.  
- 💳 **Payments:** Basic payment form integration.  
- 👤 **User Accounts:** User registration, login, and history tracking.  
- 🛠️ **Admin Dashboard:** Manage pets, users, adoptions, and bookings.  
- 📞 **Contact Form:** Reach out for support and inquiries.  
- 📊 **Statistics:** View adoption and booking stats.
- 📧 Email Notifications (PHPMailer): Send adoption confirmations, booking updates, and contact form messages via email.
---

## 🏗️ Tech Stack
- **Frontend:** HTML5, CSS3, JavaScript  
- **Backend:** PHP  
- **Database:** PostgreSQL  
- **Server:** Apache / XAMPP / WAMP  

---

## 📂 Project Structure
```
totocare/
├── pet-care-website-template/
│   ├── index.html                # Home page
│   ├── login.php                 # User login
│   ├── register.php              # User registration
│   ├── adoptionform.html         # Adoption form
│   ├── booking.php               # Book services
│   ├── admin/                    # Admin dashboard
│   ├── ... (more PHP & HTML files)
│
├── config.example.php            # Sample config file (copy → config.php)
├── LICENSE
├── .gitignore
└── README.md


```

---

## 🚀 Getting Started

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/YOUR_USERNAME/totocare.git
cd totocare
```

### 2️⃣ Setup Database (PostgreSQL)
1. Create a new PostgreSQL database:
```sql
CREATE DATABASE totocare;
```

2. Import your schema & tables into `totocare`.  

3. Update `config.php` with your PostgreSQL connection details:  

```php
<?php
$host = "localhost";
$port = "5432";
$dbname = "totocare";
$user = "postgres";
$password = "your_password";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>
```

---

### 3️⃣ Run the Project
- Place project folder inside `htdocs` (if using **XAMPP** or **WAMP**).  
- Start **Apache** server.  
- Visit: [http://localhost/totocare](http://localhost/totocare)  

---

## 🤝 Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you’d like to change.  

---

## 📜 License
This project is licensed under the **MIT License** – see the [LICENSE] file for details.
