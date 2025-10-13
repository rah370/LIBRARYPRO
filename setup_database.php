<?php
// Database setup script for SQLite
try {
    // Create SQLite database
    $db = new PDO('sqlite:library.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Setting up Library Database</h1>";
    
    // Create users table
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin','student') NOT NULL DEFAULT 'student',
            student_id VARCHAR(20) UNIQUE,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_login DATETIME
        )
    ");
    echo "<p>✅ Created users table</p>";
    
    // Create books table
    $db->exec("
        CREATE TABLE IF NOT EXISTS books (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            isbn VARCHAR(20) UNIQUE NOT NULL,
            description TEXT,
            available BOOLEAN DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "<p>✅ Created books table</p>";
    
    // Create borrows table
    $db->exec("
        CREATE TABLE IF NOT EXISTS borrows (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            book_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            user_name VARCHAR(255) NOT NULL,
            user_email VARCHAR(255) NOT NULL,
            borrow_date DATETIME NOT NULL,
            return_date DATETIME NOT NULL,
            actual_return_date DATETIME NULL,
            status VARCHAR(20) DEFAULT 'borrowed',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "<p>✅ Created borrows table</p>";
    
    // Insert admin user
    $stmt = $db->prepare("
        INSERT OR IGNORE INTO users (username, email, password, role, first_name, last_name) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt->execute(['admin', 'admin@library.com', $admin_password, 'admin', 'Library', 'Administrator']);
    echo "<p>✅ Created admin user (admin/admin123)</p>";
    
    // Insert sample students
    $students = [
        ['john_doe', 'john@example.com', 'student123', 'STU001', 'John', 'Doe'],
        ['jane_smith', 'jane@example.com', 'student123', 'STU002', 'Jane', 'Smith'],
        ['mike_johnson', 'mike@example.com', 'student123', 'STU003', 'Mike', 'Johnson'],
        ['sarah_wilson', 'sarah@example.com', 'student123', 'STU004', 'Sarah', 'Wilson'],
        ['david_brown', 'david@example.com', 'student123', 'STU005', 'David', 'Brown']
    ];
    
    $stmt = $db->prepare("
        INSERT OR IGNORE INTO users (username, email, password, role, student_id, first_name, last_name) 
        VALUES (?, ?, ?, 'student', ?, ?, ?)
    ");
    
    foreach ($students as $student) {
        $password = password_hash($student[2], PASSWORD_DEFAULT);
        $stmt->execute([$student[0], $student[1], $password, $student[3], $student[4], $student[5]]);
    }
    echo "<p>✅ Created " . count($students) . " sample students</p>";
    
    // Insert sample books
    $books = [
        ['The Great Gatsby', 'F. Scott Fitzgerald', '978-0743273565', 'A story of the fabulously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan.'],
        ['To Kill a Mockingbird', 'Harper Lee', '978-0446310789', 'The story of young Scout Finch and her father Atticus in a racially divided Alabama town.'],
        ['1984', 'George Orwell', '978-0451524935', 'A dystopian novel about totalitarianism and surveillance society.'],
        ['Pride and Prejudice', 'Jane Austen', '978-0141439518', 'A romantic novel of manners that follows the emotional development of Elizabeth Bennet.'],
        ['The Hobbit', 'J.R.R. Tolkien', '978-0547928241', 'A fantasy novel about Bilbo Baggins, a hobbit who embarks on a quest.'],
        ['The Catcher in the Rye', 'J.D. Salinger', '978-0316769488', 'A novel about teenage alienation and loss of innocence in post-World War II America.'],
        ['Lord of the Flies', 'William Golding', '978-0399501487', 'A novel about a group of British boys stranded on an uninhabited island.'],
        ['Animal Farm', 'George Orwell', '978-0451526342', 'An allegorical novella about a group of farm animals who rebel against their human farmer.'],
        ['The Alchemist', 'Paulo Coelho', '978-0062315007', 'A novel about a young Andalusian shepherd who dreams of finding a worldly treasure.'],
        ['Brave New World', 'Aldous Huxley', '978-0060850524', 'A dystopian novel about a futuristic World State society.'],
        ['Harry Potter and the Philosopher\'s Stone', 'J.K. Rowling', '978-0747532699', 'The first novel in the Harry Potter series about a young wizard.'],
        ['The Lord of the Rings', 'J.R.R. Tolkien', '978-0544003415', 'An epic high-fantasy novel about the quest to destroy the One Ring.'],
        ['Dune', 'Frank Herbert', '978-0441172719', 'A science fiction novel set in the distant future amidst a feudal interstellar society.'],
        ['The Chronicles of Narnia', 'C.S. Lewis', '978-0066238501', 'A series of seven fantasy novels set in the fictional realm of Narnia.'],
        ['Foundation', 'Isaac Asimov', '978-0553293357', 'A science fiction novel about the collapse and rebirth of a galactic empire.']
    ];
    
    $stmt = $db->prepare("
        INSERT OR IGNORE INTO books (title, author, isbn, description) 
        VALUES (?, ?, ?, ?)
    ");
    
    foreach ($books as $book) {
        $stmt->execute($book);
    }
    echo "<p>✅ Created " . count($books) . " sample books</p>";
    
    // Insert some sample borrows
    $borrows = [
        [1, 2, 'John Doe', 'john@example.com', date('Y-m-d H:i:s', strtotime('-5 days')), date('Y-m-d H:i:s', strtotime('+9 days')), 'borrowed'],
        [3, 3, 'Jane Smith', 'jane@example.com', date('Y-m-d H:i:s', strtotime('-10 days')), date('Y-m-d H:i:s', strtotime('-3 days')), 'borrowed'], // Overdue
        [5, 4, 'Mike Johnson', 'mike@example.com', date('Y-m-d H:i:s', strtotime('-20 days')), date('Y-m-d H:i:s', strtotime('-6 days')), 'returned'],
        [8, 5, 'Sarah Wilson', 'sarah@example.com', date('Y-m-d H:i:s', strtotime('-2 days')), date('Y-m-d H:i:s', strtotime('+12 days')), 'borrowed']
    ];
    
    $stmt = $db->prepare("
        INSERT OR IGNORE INTO borrows (book_id, user_id, user_name, user_email, borrow_date, return_date, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($borrows as $borrow) {
        $stmt->execute($borrow);
    }
    echo "<p>✅ Created " . count($borrows) . " sample borrow records</p>";
    
    // Update book availability
    $db->exec("UPDATE books SET available = 0 WHERE id IN (1, 3, 8)"); // Books that are currently borrowed
    echo "<p>✅ Updated book availability status</p>";
    
    echo "<hr>";
    echo "<h2>✅ Database Setup Complete!</h2>";
    echo "<p><strong>Admin Login:</strong> admin / admin123</p>";
    echo "<p><strong>Student Logins:</strong> john_doe, jane_smith, mike_johnson, sarah_wilson, david_brown / student123</p>";
    echo "<p><a href='index.php' style='color: blue; text-decoration: none; font-size: 18px;'>🚀 Launch Full Library System</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Database Error</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}