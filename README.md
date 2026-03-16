# Pet Adoption System 🐾
### PHP & MySQL CRUD Practice Project

A practice PHP and MySQL web application for managing a pet adoption center. Built to prepare for the 2nd Laboratory Exam of IT 9a — Professional Track for IT 3 at the University of Mindanao.

---

## 📄 Pages

| Page | Description |
|---|---|
| `index.php` | Public homepage showing available pets |
| `pet.php` | Admin — manage pets (add, edit, delete) |
| `category.php` | Admin — manage pet categories |

---

## 🛠️ Built With

- PHP
- MySQL
- Bootstrap 5

---

## ✨ Features

- Public homepage displaying all available pets with images
- Pet management — add, edit, delete pets with LONGBLOB image storage
- Category management — add, edit, delete pet categories
- Pet status tracking — Available, Pending, Reserved, Under Treatment, Adopted
- Category dropdown in pet form pulls from database
- Image display using `base64_encode()` from LONGBLOB
- Auto-creates database and tables on first run
- Success alerts on add, update, and delete

---

## 💻 PHP & MySQL Concepts Applied

- MySQLi database connection
- `CREATE DATABASE IF NOT EXISTS` and `CREATE TABLE IF NOT EXISTS`
- CRUD operations using `INSERT`, `SELECT`, `UPDATE`, `DELETE`
- LONGBLOB image storage with `file_get_contents()` and `addslashes()`
- `base64_encode()` for displaying images from database
- `$_POST`, `$_GET`, `$_FILES` for form handling
- PRG pattern (Post/Redirect/Get) with `header()` and `exit()`
- `htmlspecialchars()` for output sanitization

---

## 🗄️ Database

**Database:** `petadoption_db`

| Table | Description |
|---|---|
| `pet_tbl` | Pet records with name, description, fee, status, category, and image |
| `category_tbl` | Pet categories with name and description |

**Setup:**
1. Import `petadoption_db.sql` into phpMyAdmin
2. Place project files in `htdocs/Practice_2ndLabExam/`
3. Open `localhost/Practice_2ndLabExam/`

---

## 📁 Folder Structure

```
Practice_2ndLabExam/
├── index.php
├── pet.php
├── category.php
└── petadoption_db.sql
```

---

## 👩‍💻 Author

**Arianne Danielle V. Añora**
2nd Year BSIT Student — University of Mindanao
IT 9a — Professional Track for IT 3
