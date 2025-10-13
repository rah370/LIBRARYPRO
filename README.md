# Sheikh Library - Library Management System

A simple and elegant library borrowing website built with CodeIgniter 3, featuring a modern Bootstrap-based UI and comprehensive book management functionality.

## Features

###  **User Features**
- **Browse Books**: View all available books in an attractive card layout
- **Search Books**: Search by title, author, or ISBN
- **Borrow Books**: Simple borrowing process with user information
- **Track Borrows**: View your borrowed books and return dates
- **Return Books**: Easy book return functionality
- **User Management**: Simple email-based user identification

###  **Admin Features**
- **Dashboard**: Overview of all borrowing activities
- **Statistics**: Real-time counts of total, borrowed, returned, and overdue books
- **Overdue Alerts**: Monitor books that are past due
- **Activity Log**: Complete history of all borrowing transactions
- **Book Management**: Mark books as returned from admin panel

###  **UI/UX Features**
- **Responsive Design**: Works perfectly on all devices
- **Modern Interface**: Clean Bootstrap 5 design with Font Awesome icons
- **Interactive Elements**: Hover effects, smooth transitions, and intuitive navigation
- **Color-coded Status**: Easy-to-understand status indicators for due dates

## Technology Stack

- **Backend**: CodeIgniter 3 (PHP Framework)
- **Frontend**: Bootstrap 5, Font Awesome 6
- **Database**: MySQL
- **Server**: Apache (XAMPP)

## Installation & Setup

### Prerequisites
- XAMPP (or similar local server with PHP 7.4+ and MySQL)
- CodeIgniter 3 framework

### Step 1: Database Setup
1. Start your XAMPP Apache and MySQL services
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database named `sheikh_library`
4. Import the `database_setup.sql` file to create tables and sample data

### Step 2: Configuration
1. Open `application/config/database.php`
2. Update the database configuration:
```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',  // Your MySQL username
    'password' => '',      // Your MySQL password
    'database' => 'sheikh_library',  // Database name you created
    'dbdriver' => 'mysqli',
    // ... other settings remain the same
);
```

### Step 3: Access the Application
1. Navigate to `http://localhost/sheikhapp/`
2. Click on "Library" or go to `http://localhost/sheikhapp/library`
3. Start exploring the library system!

## Usage Guide

### For Library Users
1. **Browse Books**: Visit the home page to see all available books
2. **Search**: Use the search bar to find specific books
3. **Set User**: Enter your email in the top-right corner to identify yourself
4. **Borrow**: Click "Borrow Book" on any available book and fill in your details
5. **Track**: Go to "My Borrows" to see your borrowed books
6. **Return**: Click "Return" when you're done with a book

### For Administrators
1. **Monitor**: Visit the "Admin" section to see all borrowing activities
2. **Statistics**: View real-time counts and overdue alerts
3. **Manage**: Mark books as returned from the admin panel
4. **Track**: Monitor user borrowing patterns and overdue books

## Database Schema

### Books Table
- `id`: Primary key
- `title`: Book title
- `author`: Book author
- `isbn`: Unique ISBN number
- `description`: Book description
- `available`: Availability status (1=available, 0=borrowed)
- `created_at`: Timestamp

### Borrows Table
- `id`: Primary key
- `book_id`: Foreign key to books table
- `user_name`: Borrower's name
- `user_email`: Borrower's email
- `borrow_date`: When book was borrowed
- `return_date`: When book is due
- `actual_return_date`: When book was actually returned
- `status`: Current status (borrowed/returned)
- `created_at`: Timestamp

## File Structure

```
application/
├── controllers/
│   └── Library.php          # Main library controller
├── models/
│   ├── Book_model.php       # Book management model
│   └── Borrow_model.php     # Borrowing operations model
└── views/
    └── library/
        ├── header.php        # Common header with navigation
        ├── footer.php        # Common footer
        ├── index.php         # Home page with book listings
        ├── search_results.php # Search results page
        ├── borrow_form.php   # Book borrowing form
        ├── my_borrows.php    # User's borrowed books
        └── admin.php         # Admin dashboard
```

## Customization

### Adding New Books
1. Insert directly into the `books` table via phpMyAdmin
2. Or create an admin interface for adding books

### Modifying Borrowing Period
1. Edit the `borrow_book` method in `Borrow_model.php`
2. Change the `+14 days` calculation to your preferred period

### Styling Changes
1. Modify the CSS in `header.php`
2. Update Bootstrap classes in view files
3. Customize Font Awesome icons

## Security Features

- **Input Validation**: Form validation for all user inputs
- **SQL Injection Protection**: CodeIgniter's built-in query builder protection
- **XSS Prevention**: HTML escaping for all output
- **Session Management**: Secure user identification

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Troubleshooting

### Common Issues
1. **Database Connection Error**: Check database credentials in `database.php`
2. **Page Not Found**: Ensure Apache mod_rewrite is enabled
3. **Books Not Showing**: Verify database tables are created and populated
4. **Borrowing Not Working**: Check if the book is marked as available

### Debug Mode
- Set `ENVIRONMENT` to `development` in `index.php` for detailed error messages
- Check CodeIgniter logs in `application/logs/`

## Contributing

Feel free to enhance this library system by:
- Adding user authentication
- Implementing book categories
- Adding fine calculation for overdue books
- Creating email notifications
- Adding book reservation system

## License

This project is open source and available under the MIT License.

## Support

For questions or issues, please check the CodeIgniter documentation or create an issue in the project repository.

---

**Happy Reading! 📚✨**
