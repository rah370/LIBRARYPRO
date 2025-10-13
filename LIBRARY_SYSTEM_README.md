# Library Management System - Admin & Student Portal

This is a complete library management system built with CodeIgniter that separates admin and student functionalities with role-based access control.

## Features

### Student Portal
- **Login System**: Students can log in with username/email and password
- **Book Browsing**: View all available books in the library
- **Search Functionality**: Search books by title, author, or ISBN
- **Borrow Books**: Borrow available books (14-day loan period)
- **My Borrowed Books**: Track borrowed books, due dates, and overdue status
- **Profile Management**: Update personal information and password
- **Student Registration**: New students can register themselves

### Admin Portal
- **Admin Dashboard**: Overview of library statistics and quick actions
- **Book Management**: Add, edit, delete, and view all books
- **Borrow Management**: View all borrows, mark books as returned
- **Student Management**: Add and manage student accounts
- **Statistics**: Detailed analytics on books, borrows, and popular titles
- **Overdue Tracking**: Monitor and manage overdue books

## Installation & Setup

### 1. Database Setup
Run the SQL script in `database_setup.sql` to create the required tables and sample data.

```sql
-- The script will create:
-- - books table (with sample books)
-- - borrows table (borrow records)
-- - users table (admin and student accounts)
```

### 2. Database Configuration
Update `application/config/database.php` with your database credentials:

```php
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'your_database_name',
    'dbdriver' => 'mysqli',
    // ... other settings
);
```

### 3. Base URL Configuration
Update `application/config/config.php` with your base URL:

```php
$config['base_url'] = 'http://localhost/sheikhapp/';
```

## Default Login Credentials

### Admin Account
- **Username**: `admin`
- **Password**: `admin123`
- **Email**: `admin@library.com`

### Student Accounts
- **Username**: `john_doe` | **Password**: `student123`
- **Username**: `jane_smith` | **Password**: `student123`
- **Username**: `mike_johnson` | **Password**: `student123`

## System Flow

1. **Landing Page**: `/` - Redirects to login page
2. **Login**: `/auth/login` - Role-based authentication
3. **Admin Dashboard**: `/admin` - Full library management
4. **Student Portal**: `/student` - Book browsing and borrowing
5. **Registration**: `/auth/register` - Student self-registration

## Key URLs

### Authentication
- `/auth/login` - Login page
- `/auth/register` - Student registration
- `/auth/logout` - Logout

### Admin Routes
- `/admin` - Dashboard
- `/admin/books` - Manage books
- `/admin/add_book` - Add new book
- `/admin/borrows` - Manage borrows
- `/admin/students` - Manage students
- `/admin/statistics` - View statistics

### Student Routes
- `/student` - Browse books
- `/student/search` - Search books
- `/student/borrow/{id}` - Borrow a book
- `/student/my_borrows` - View borrowed books
- `/student/profile` - Manage profile

## Features Details

### Security Features
- Password hashing using PHP's `password_hash()`
- Session-based authentication
- Role-based access control
- SQL injection protection via CodeIgniter's Query Builder
- XSS protection with form validation

### Book Management
- Add/Edit/Delete books
- ISBN uniqueness validation
- Book availability tracking
- Borrowing statistics per book

### Borrow System
- 14-day loan period
- Automatic due date calculation
- Overdue book tracking
- Return processing
- Borrow history

### User Management
- Student self-registration
- Admin can add students
- Profile management
- Username/Email uniqueness validation

### Statistics & Analytics
- Total books, available books, borrowed books
- Active borrows and overdue tracking
- Most popular books ranking
- Borrowing trends and rates

## Technology Stack
- **Framework**: CodeIgniter 3.x
- **Database**: MySQL
- **Frontend**: Bootstrap 5, FontAwesome
- **PHP Version**: 7.4+ (with PHP 8.2 compatibility)

## File Structure
```
application/
├── controllers/
│   ├── Auth.php          # Authentication controller
│   ├── Admin.php         # Admin functionality
│   └── Student.php       # Student functionality
├── models/
│   ├── User_model.php    # User management
│   ├── Book_model.php    # Book operations
│   └── Borrow_model.php  # Borrow operations
└── views/
    ├── auth/             # Login & registration views
    ├── admin/            # Admin panel views
    └── student/          # Student portal views
```

## Notes
- The system automatically creates sample data when you run the database setup
- Book images are represented with FontAwesome icons (can be extended to support actual images)
- The system includes responsive design for mobile compatibility
- All forms include proper validation and error handling

## Support
For any issues or questions, please check the database configuration and ensure all required tables are created with the provided SQL script.