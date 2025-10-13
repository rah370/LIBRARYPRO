-- Library Management System Database Setup for SQLite
-- This script creates the necessary tables for the library management system

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'student',
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    phone VARCHAR(20),
    address TEXT,
    status VARCHAR(20) DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME
);

-- Create books table
CREATE TABLE IF NOT EXISTS books (
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
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create borrows table
CREATE TABLE IF NOT EXISTS borrows (
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
);

-- Create reservations table
CREATE TABLE IF NOT EXISTS reservations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    book_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    reservation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'active',
    expires_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT OR IGNORE INTO users (username, email, password, role, first_name, last_name, status) 
VALUES ('admin', 'admin@library.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Library', 'Administrator', 'active');

-- Insert sample student user (password: student123)
INSERT OR IGNORE INTO users (username, email, password, role, first_name, last_name, status) 
VALUES ('student', 'student@library.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'John', 'Doe', 'active');

-- Insert sample categories
INSERT OR IGNORE INTO categories (name, description) VALUES
('Fiction', 'Fictional literature and novels'),
('Non-Fiction', 'Factual books and educational material'),
('Science', 'Scientific books and research materials'),
('Technology', 'Technology and computer science books'),
('History', 'Historical books and documents'),
('Biography', 'Biographical works and memoirs'),
('Reference', 'Reference materials and encyclopedias'),
('Children', 'Children''s books and educational materials');

-- Insert sample books
INSERT OR IGNORE INTO books (title, author, isbn, description, category, publisher, publication_year, pages, copies_total, copies_available, location) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', '978-0743273565', 'A story of the fabulously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan.', 'Fiction', 'Scribner', 1925, 180, 3, 3, 'A-001'),
('To Kill a Mockingbird', 'Harper Lee', '978-0446310789', 'The story of young Scout Finch and her father Atticus in a racially divided Alabama town.', 'Fiction', 'J. B. Lippincott & Co.', 1960, 376, 2, 2, 'A-002'),
('1984', 'George Orwell', '978-0451524935', 'A dystopian novel about totalitarianism and surveillance society.', 'Fiction', 'Secker & Warburg', 1949, 328, 4, 4, 'A-003'),
('Pride and Prejudice', 'Jane Austen', '978-0141439518', 'A romantic novel of manners that follows the emotional development of Elizabeth Bennet.', 'Fiction', 'T. Egerton', 1813, 432, 2, 2, 'A-004'),
('The Hobbit', 'J.R.R. Tolkien', '978-0547928241', 'A fantasy novel about Bilbo Baggins, a hobbit who embarks on a quest.', 'Fiction', 'George Allen & Unwin', 1937, 310, 3, 3, 'F-001'),
('The Catcher in the Rye', 'J.D. Salinger', '978-0316769488', 'A novel about teenage alienation and loss of innocence in post-World War II America.', 'Fiction', 'Little, Brown and Company', 1951, 277, 2, 2, 'F-002'),
('Lord of the Flies', 'William Golding', '978-0399501487', 'A novel about a group of British boys stranded on an uninhabited island.', 'Fiction', 'Faber and Faber', 1954, 224, 2, 2, 'F-003'),
('Animal Farm', 'George Orwell', '978-0451526342', 'An allegorical novella about a group of farm animals who rebel against their human farmer.', 'Fiction', 'Secker & Warburg', 1945, 112, 3, 3, 'F-004'),
('The Alchemist', 'Paulo Coelho', '978-0062315007', 'A novel about a young Andalusian shepherd who dreams of finding a worldly treasure.', 'Fiction', 'HarperTorch', 1988, 163, 2, 2, 'F-005'),
('Brave New World', 'Aldous Huxley', '978-0060850524', 'A dystopian novel about a futuristic World State society.', 'Fiction', 'Chatto & Windus', 1932, 311, 2, 2, 'S-001'),
('A Brief History of Time', 'Stephen Hawking', '978-0553380163', 'A popular science book on cosmology by theoretical physicist Stephen Hawking.', 'Science', 'Bantam Doubleday Dell', 1988, 256, 2, 2, 'S-002'),
('The Art of Computer Programming', 'Donald Knuth', '978-0201896831', 'A comprehensive monograph written by computer scientist Donald Knuth.', 'Technology', 'Addison-Wesley', 1968, 650, 1, 1, 'T-001'),
('Clean Code', 'Robert C. Martin', '978-0132350884', 'A handbook of agile software craftsmanship.', 'Technology', 'Prentice Hall', 2008, 464, 3, 3, 'T-002'),
('Sapiens', 'Yuval Noah Harari', '978-0062316097', 'A brief history of humankind covering the evolution of Homo sapiens.', 'History', 'Harvill Secker', 2011, 443, 2, 2, 'H-001'),
('Steve Jobs', 'Walter Isaacson', '978-1451648539', 'The exclusive biography of Steve Jobs based on extensive interviews.', 'Biography', 'Simon & Schuster', 2011, 656, 1, 1, 'B-001');

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_users_username ON users(username);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_users_role ON users(role);
CREATE INDEX IF NOT EXISTS idx_books_isbn ON books(isbn);
CREATE INDEX IF NOT EXISTS idx_books_title ON books(title);
CREATE INDEX IF NOT EXISTS idx_books_author ON books(author);
CREATE INDEX IF NOT EXISTS idx_books_category ON books(category);
CREATE INDEX IF NOT EXISTS idx_borrows_user_id ON borrows(user_id);
CREATE INDEX IF NOT EXISTS idx_borrows_book_id ON borrows(book_id);
CREATE INDEX IF NOT EXISTS idx_borrows_status ON borrows(status);
CREATE INDEX IF NOT EXISTS idx_reservations_user_id ON reservations(user_id);
CREATE INDEX IF NOT EXISTS idx_reservations_book_id ON reservations(book_id);