<?php
// Database Fix Script - Run this to fix existing database issues
try {
    // Create SQLite database connection
    $db = new PDO('sqlite:library.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔧 Fixing Library Database</h1>";
    
    // Check if tables exist and fix them
    $tables = ['users', 'books', 'borrows'];
    
    foreach ($tables as $table) {
        $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
        if ($result->fetchColumn()) {
            echo "<p>✅ Table '$table' exists</p>";
        } else {
            echo "<p>❌ Table '$table' missing - creating...</p>";
            createTable($db, $table);
        }
    }
    
    // Fix users table structure
    echo "<h2>Fixing Users Table</h2>";
    fixUsersTable($db);
    
    // Fix books table structure  
    echo "<h2>Fixing Books Table</h2>";
    fixBooksTable($db);
    
    // Fix borrows table structure
    echo "<h2>Fixing Borrows Table</h2>";
    fixBorrowsTable($db);
    
    // Add missing indexes
    echo "<h2>Adding Indexes</h2>";
    addIndexes($db);
    
    // Update sample data
    echo "<h2>Updating Sample Data</h2>";
    updateSampleData($db);
    
    echo "<hr>";
    echo "<h2>✅ Database Fix Complete!</h2>";
    echo "<p><strong>Admin Login:</strong> admin / admin123</p>";
    echo "<p><strong>Student Logins:</strong> john_doe, jane_smith, mike_johnson, sarah_wilson, david_brown / student123</p>";
    echo "<p><a href='index.php' style='color: blue; text-decoration: none; font-size: 18px;'>🚀 Launch Library System</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Database Error</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

function createTable($db, $tableName) {
    switch ($tableName) {
        case 'users':
            $db->exec("
                CREATE TABLE users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    username VARCHAR(50) UNIQUE NOT NULL,
                    email VARCHAR(100) UNIQUE NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    role VARCHAR(20) DEFAULT 'student',
                    student_id VARCHAR(20) UNIQUE,
                    first_name VARCHAR(50),
                    last_name VARCHAR(50),
                    phone VARCHAR(20),
                    address TEXT,
                    status VARCHAR(20) DEFAULT 'active',
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    last_login DATETIME
                )
            ");
            break;
        case 'books':
            $db->exec("
                CREATE TABLE books (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title VARCHAR(255) NOT NULL,
                    author VARCHAR(255) NOT NULL,
                    isbn VARCHAR(20) UNIQUE NOT NULL,
                    description TEXT,
                    category VARCHAR(100),
                    publisher VARCHAR(255),
                    publication_year INTEGER,
                    pages INTEGER,
                    copies_total INTEGER DEFAULT 1,
                    copies_available INTEGER DEFAULT 1,
                    location VARCHAR(100),
                    status VARCHAR(20) DEFAULT 'available',
                    cover VARCHAR(255),
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ");
            break;
        case 'borrows':
            $db->exec("
                CREATE TABLE borrows (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    book_id INTEGER NOT NULL,
                    user_id INTEGER NOT NULL,
                    borrow_date DATETIME NOT NULL,
                    due_date DATETIME NOT NULL,
                    return_date DATETIME,
                    status VARCHAR(20) DEFAULT 'active',
                    fine_amount DECIMAL(10,2) DEFAULT 0.00,
                    notes TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )
            ");
            break;
    }
}

function fixUsersTable($db) {
    // Add missing columns
    $columns = [
        'student_id' => 'VARCHAR(20)',
        'phone' => 'VARCHAR(20)',
        'address' => 'TEXT',
        'status' => 'VARCHAR(20) DEFAULT "active"',
        'updated_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'
    ];
    
    foreach ($columns as $column => $type) {
        try {
            $db->exec("ALTER TABLE users ADD COLUMN $column $type");
            echo "<p>✅ Added column '$column' to users table</p>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'duplicate column name') !== false) {
                echo "<p>ℹ️ Column '$column' already exists in users table</p>";
            } else {
                echo "<p>⚠️ Could not add column '$column': " . $e->getMessage() . "</p>";
            }
        }
    }
}

function fixBooksTable($db) {
    // Add missing columns
    $columns = [
        'category' => 'VARCHAR(100)',
        'publisher' => 'VARCHAR(255)',
        'publication_year' => 'INTEGER',
        'pages' => 'INTEGER',
        'copies_total' => 'INTEGER DEFAULT 1',
        'copies_available' => 'INTEGER DEFAULT 1',
        'location' => 'VARCHAR(100)',
        'status' => 'VARCHAR(20) DEFAULT "available"',
        'cover' => 'VARCHAR(255)',
        'updated_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'
    ];
    
    foreach ($columns as $column => $type) {
        try {
            $db->exec("ALTER TABLE books ADD COLUMN $column $type");
            echo "<p>✅ Added column '$column' to books table</p>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'duplicate column name') !== false) {
                echo "<p>ℹ️ Column '$column' already exists in books table</p>";
            } else {
                echo "<p>⚠️ Could not add column '$column': " . $e->getMessage() . "</p>";
            }
        }
    }
    
    // Migrate old 'available' column to new structure
    try {
        $db->exec("UPDATE books SET copies_available = CASE WHEN available = 1 THEN 1 ELSE 0 END WHERE available IS NOT NULL");
        $db->exec("UPDATE books SET status = CASE WHEN available = 1 THEN 'available' ELSE 'unavailable' END WHERE available IS NOT NULL");
        echo "<p>✅ Migrated 'available' column data to new structure</p>";
    } catch (PDOException $e) {
        echo "<p>ℹ️ No 'available' column to migrate</p>";
    }
}

function fixBorrowsTable($db) {
    // Add missing columns
    $columns = [
        'due_date' => 'DATETIME',
        'return_date' => 'DATETIME',
        'status' => 'VARCHAR(20) DEFAULT "active"',
        'fine_amount' => 'DECIMAL(10,2) DEFAULT 0.00',
        'notes' => 'TEXT',
        'updated_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'
    ];
    
    foreach ($columns as $column => $type) {
        try {
            $db->exec("ALTER TABLE borrows ADD COLUMN $column $type");
            echo "<p>✅ Added column '$column' to borrows table</p>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'duplicate column name') !== false) {
                echo "<p>ℹ️ Column '$column' already exists in borrows table</p>";
            } else {
                echo "<p>⚠️ Could not add column '$column': " . $e->getMessage() . "</p>";
            }
        }
    }
    
    // Migrate old borrow structure
    try {
        $db->exec("UPDATE borrows SET due_date = return_date WHERE due_date IS NULL AND return_date IS NOT NULL");
        $db->exec("UPDATE borrows SET status = CASE WHEN actual_return_date IS NOT NULL THEN 'returned' ELSE 'active' END WHERE status IS NULL");
        echo "<p>✅ Migrated old borrow structure</p>";
    } catch (PDOException $e) {
        echo "<p>ℹ️ No old borrow structure to migrate</p>";
    }
}

function addIndexes($db) {
    $indexes = [
        'idx_users_username' => 'CREATE INDEX IF NOT EXISTS idx_users_username ON users(username)',
        'idx_users_email' => 'CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)',
        'idx_users_role' => 'CREATE INDEX IF NOT EXISTS idx_users_role ON users(role)',
        'idx_books_isbn' => 'CREATE INDEX IF NOT EXISTS idx_books_isbn ON books(isbn)',
        'idx_books_title' => 'CREATE INDEX IF NOT EXISTS idx_books_title ON books(title)',
        'idx_books_author' => 'CREATE INDEX IF NOT EXISTS idx_books_author ON books(author)',
        'idx_books_category' => 'CREATE INDEX IF NOT EXISTS idx_books_category ON books(category)',
        'idx_borrows_user_id' => 'CREATE INDEX IF NOT EXISTS idx_borrows_user_id ON borrows(user_id)',
        'idx_borrows_book_id' => 'CREATE INDEX IF NOT EXISTS idx_borrows_book_id ON borrows(book_id)',
        'idx_borrows_status' => 'CREATE INDEX IF NOT EXISTS idx_borrows_status ON borrows(status)'
    ];
    
    foreach ($indexes as $name => $sql) {
        try {
            $db->exec($sql);
            echo "<p>✅ Created index '$name'</p>";
        } catch (PDOException $e) {
            echo "<p>⚠️ Could not create index '$name': " . $e->getMessage() . "</p>";
        }
    }
}

function updateSampleData($db) {
    // Ensure admin user exists
    $stmt = $db->prepare("
        INSERT OR IGNORE INTO users (username, email, password, role, first_name, last_name, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt->execute(['admin', 'admin@library.com', $admin_password, 'admin', 'Library', 'Administrator', 'active']);
    echo "<p>✅ Ensured admin user exists</p>";
    
    // Ensure sample students exist
    $students = [
        ['john_doe', 'john@example.com', 'student123', 'STU001', 'John', 'Doe'],
        ['jane_smith', 'jane@example.com', 'student123', 'STU002', 'Jane', 'Smith'],
        ['mike_johnson', 'mike@example.com', 'student123', 'STU003', 'Mike', 'Johnson'],
        ['sarah_wilson', 'sarah@example.com', 'student123', 'STU004', 'Sarah', 'Wilson'],
        ['david_brown', 'david@example.com', 'student123', 'STU005', 'David', 'Brown']
    ];
    
    $stmt = $db->prepare("
        INSERT OR IGNORE INTO users (username, email, password, role, student_id, first_name, last_name, status) 
        VALUES (?, ?, ?, 'student', ?, ?, ?, 'active')
    ");
    
    foreach ($students as $student) {
        $password = password_hash($student[2], PASSWORD_DEFAULT);
        $stmt->execute([$student[0], $student[1], $password, $student[3], $student[4], $student[5]]);
    }
    echo "<p>✅ Ensured sample students exist</p>";
    
    // Update books with proper structure
    $stmt = $db->prepare("
        UPDATE books SET 
            category = COALESCE(category, 'Fiction'),
            copies_total = COALESCE(copies_total, 1),
            copies_available = COALESCE(copies_available, 1),
            status = COALESCE(status, 'available')
        WHERE category IS NULL OR copies_total IS NULL OR copies_available IS NULL OR status IS NULL
    ");
    $stmt->execute();
    echo "<p>✅ Updated books with proper structure</p>";
}
?>
