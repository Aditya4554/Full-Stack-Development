# Movie Database Application

## Login Credentials

- Admin: username: `admin`, password: `admin123`
- Signup to login via user.

## Setup Instructions

- Install XAMPP/WAMP/MAMP and start Apache & MySQL
- Copy the project folder into `htdocs` or `www`
- Create a database named `movie_db` in phpMyAdmin
- Create/import tables: users, movies, genres
- Configure database in `config/db.php`
- Run the project at `http://localhost/movie_app/public/index.php`

## Features Implemented

- User login and signup
- Admin and user roles
- Add, edit, delete movies (admin only)
- View movies with genres
- Live search using AJAX
- Advanced search by year and rating
- Secure password hashing and CSRF protection

## Known Issues

- Admin role set manually
- No movie image upload
- No password reset
