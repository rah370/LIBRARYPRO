// Student Dashboard JavaScript
class StudentDashboard {
    constructor() {
        this.currentView = 'list';
        this.currentBooks = [];
        this.currentPage = 1;
        this.booksPerPage = 12;
        this.init();
    }

    init() {
        this.setupNavigation();
        this.loadBooks();
        this.setupEventListeners();
        this.setupSearch();
        console.log('Student Dashboard initialized');
    }

    setupNavigation() {
        const navLinks = document.querySelectorAll('.nav-link[href^="#"]');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                this.showSection(targetId);
                this.updateActiveNav(link);
            });
        });
    }

    showSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('.content-section').forEach(section => {
            section.classList.remove('active');
        });
        
        // Show target section
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.classList.add('active');
            
            // Load section-specific data
            switch(sectionId) {
                case 'browse':
                    this.loadBooks();
                    break;
                case 'borrowed':
                    this.loadBorrowedBooks();
                    break;
                case 'search':
                    // Search section is static
                    break;
            }
        }
    }

    updateActiveNav(activeLink) {
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        activeLink.classList.add('active');
    }

    setupEventListeners() {
        // Quick search
        const quickSearch = document.getElementById('quickSearch');
        if (quickSearch) {
            quickSearch.addEventListener('input', this.debounce((e) => {
                this.performQuickSearch(e.target.value);
            }, 300));
        }

        // Advanced search form
        const advancedForm = document.getElementById('advancedSearchForm');
        if (advancedForm) {
            advancedForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.performAdvancedSearch();
            });
        }

        // View toggle buttons
        document.querySelectorAll('[onclick*="changeView"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const view = e.target.closest('button').getAttribute('onclick').match(/'(\w+)'/)[1];
                this.changeView(view);
            });
        });

        // Borrow book button in modal
        const borrowBtn = document.getElementById('borrowBookBtn');
        if (borrowBtn) {
            borrowBtn.addEventListener('click', () => {
                this.borrowBook();
            });
        }
    }

    setupSearch() {
        // Initialize search functionality
        this.searchDebounced = this.debounce(this.performQuickSearch.bind(this), 300);
    }

    loadBooks() {
        // Show loading state
        this.showLoading(true);
        
        // Simulate API call - replace with actual backend call
        setTimeout(() => {
            this.currentBooks = this.generateSampleBooks();
            this.renderBooks();
            this.showLoading(false);
        }, 1000);
    }

    generateSampleBooks() {
        const sampleBooks = [
            {
                id: 1,
                title: "JavaScript: The Good Parts",
                author: "Douglas Crockford",
                isbn: "978-0596517748",
                category: "Technology",
                year: 2008,
                available: true,
                description: "A comprehensive guide to JavaScript best practices and patterns."
            },
            {
                id: 2,
                title: "Clean Code",
                author: "Robert C. Martin",
                isbn: "978-0132350884",
                category: "Technology",
                year: 2008,
                available: true,
                description: "A handbook of agile software craftsmanship."
            },
            {
                id: 3,
                title: "Design Patterns",
                author: "Gang of Four",
                isbn: "978-0201633610",
                category: "Technology",
                year: 1994,
                available: false,
                description: "Elements of reusable object-oriented software."
            },
            {
                id: 4,
                title: "The Pragmatic Programmer",
                author: "David Thomas, Andrew Hunt",
                isbn: "978-0201616224",
                category: "Technology",
                year: 1999,
                available: true,
                description: "From journeyman to master."
            },
            {
                id: 5,
                title: "Code Complete",
                author: "Steve McConnell",
                isbn: "978-0735619678",
                category: "Technology",
                year: 2004,
                available: true,
                description: "A practical handbook of software construction."
            },
            {
                id: 6,
                title: "Algorithms",
                author: "Robert Sedgewick",
                isbn: "978-0321573513",
                category: "Computer Science",
                year: 2011,
                available: false,
                description: "Comprehensive treatment of algorithms and data structures."
            }
        ];

        return sampleBooks;
    }

    renderBooks() {
        const container = document.getElementById('booksContainer');
        if (!container) return;

        container.innerHTML = '';

        if (this.currentBooks.length === 0) {
            container.innerHTML = `
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-book-open"></i>
                        <h4>No Books Found</h4>
                        <p>Try adjusting your search criteria.</p>
                    </div>
                </div>
            `;
            return;
        }

        this.currentBooks.forEach(book => {
            const bookCard = this.createBookCard(book);
            container.appendChild(bookCard);
        });
    }

    createBookCard(book) {
        const col = document.createElement('div');
        col.className = this.currentView === 'grid' ? 'col-md-4 col-lg-3 mb-4' : 'col-12 mb-3';
        
        const statusClass = book.available ? 'status-available' : 'status-borrowed';
        const statusText = book.available ? 'Available' : 'Borrowed';
        
        col.innerHTML = `
            <div class="card book-card h-100" onclick="studentApp.showBookDetails(${book.id})">
                <div class="book-cover-placeholder">
                    <i class="fas fa-book fa-3x"></i>
                </div>
                <div class="card-body book-info">
                    <h5 class="book-title">${book.title}</h5>
                    <p class="book-author text-muted">${book.author}</p>
                    <p class="text-small"><strong>ISBN:</strong> ${book.isbn}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge ${statusClass}">${statusText}</span>
                        <small class="text-muted">${book.year}</small>
                    </div>
                </div>
            </div>
        `;
        
        return col;
    }

    changeView(view) {
        this.currentView = view;
        
        // Update button states
        document.querySelectorAll('.btn-toolbar .btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        event.target.classList.add('active');
        
        // Re-render books with new view
        this.renderBooks();
    }

    performQuickSearch(query) {
        if (!query || query.length < 2) {
            this.loadBooks();
            return;
        }

        this.showLoading(true);
        
        // Simulate search API call
        setTimeout(() => {
            const filtered = this.currentBooks.filter(book => 
                book.title.toLowerCase().includes(query.toLowerCase()) ||
                book.author.toLowerCase().includes(query.toLowerCase()) ||
                book.isbn.includes(query)
            );
            
            this.currentBooks = filtered;
            this.renderBooks();
            this.showLoading(false);
        }, 500);
    }

    performAdvancedSearch() {
        const formData = {
            title: document.getElementById('searchTitle').value,
            author: document.getElementById('searchAuthor').value,
            isbn: document.getElementById('searchISBN').value,
            category: document.getElementById('searchCategory').value,
            year: document.getElementById('searchYear').value,
            availability: document.getElementById('availabilityFilter').value
        };

        this.showLoading(true);
        
        // Simulate advanced search API call
        setTimeout(() => {
            let results = this.generateSampleBooks();
            
            // Apply filters
            Object.keys(formData).forEach(key => {
                if (formData[key]) {
                    results = results.filter(book => {
                        switch(key) {
                            case 'availability':
                                return formData[key] === 'available' ? book.available : !book.available;
                            case 'year':
                                return book.year.toString() === formData[key];
                            default:
                                return book[key].toLowerCase().includes(formData[key].toLowerCase());
                        }
                    });
                }
            });

            this.displaySearchResults(results);
            this.showLoading(false);
        }, 1000);
    }

    displaySearchResults(results) {
        const resultsSection = document.getElementById('searchResults');
        const resultsContainer = document.getElementById('searchResultsContainer');
        
        if (!resultsSection || !resultsContainer) return;

        resultsContainer.innerHTML = '';
        
        if (results.length === 0) {
            resultsContainer.innerHTML = `
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h4>No Results Found</h4>
                        <p>Try different search criteria.</p>
                    </div>
                </div>
            `;
        } else {
            results.forEach(book => {
                const bookCard = this.createBookCard(book);
                resultsContainer.appendChild(bookCard);
            });
        }
        
        resultsSection.style.display = 'block';
        resultsSection.scrollIntoView({ behavior: 'smooth' });
    }

    showBookDetails(bookId) {
        const book = this.currentBooks.find(b => b.id === bookId) || 
                   this.generateSampleBooks().find(b => b.id === bookId);
        
        if (!book) return;

        // Populate modal with book details
        document.getElementById('modalBookTitle').textContent = book.title;
        document.getElementById('modalBookAuthor').textContent = book.author;
        document.getElementById('modalBookISBN').textContent = book.isbn;
        document.getElementById('modalBookCategory').textContent = book.category;
        document.getElementById('modalBookYear').textContent = book.year;
        document.getElementById('modalBookDescription').textContent = book.description;

        const statusBadge = document.getElementById('modalBookStatus');
        statusBadge.textContent = book.available ? 'Available' : 'Borrowed';
        statusBadge.className = book.available ? 'badge bg-success' : 'badge bg-danger';

        // Update borrow button
        const borrowBtn = document.getElementById('borrowBookBtn');
        borrowBtn.disabled = !book.available;
        borrowBtn.innerHTML = book.available ? 
            '<i class="fas fa-book me-1"></i>Borrow Book' : 
            '<i class="fas fa-ban me-1"></i>Not Available';
        
        // Store current book ID
        borrowBtn.setAttribute('data-book-id', bookId);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('bookModal'));
        modal.show();
    }

    borrowBook() {
        const borrowBtn = document.getElementById('borrowBookBtn');
        const bookId = borrowBtn.getAttribute('data-book-id');
        
        if (!bookId) return;

        this.showLoading(true);
        borrowBtn.disabled = true;
        borrowBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Borrowing...';

        // Simulate API call
        setTimeout(() => {
            // Update book status
            const book = this.currentBooks.find(b => b.id == bookId);
            if (book) {
                book.available = false;
            }

            this.showLoading(false);
            this.showNotification('Book borrowed successfully!', 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('bookModal'));
            modal.hide();
            
            // Refresh view
            this.renderBooks();
        }, 2000);
    }

    loadBorrowedBooks() {
        // This would typically fetch from the backend
        console.log('Loading borrowed books...');
    }

    showLoading(show = true) {
        const container = document.getElementById('booksContainer');
        if (container) {
            if (show) {
                container.classList.add('loading');
            } else {
                container.classList.remove('loading');
            }
        }
    }

    showNotification(message, type = 'info') {
        // Create notification
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible position-fixed`;
        notification.style.cssText = `
            top: 20px; 
            right: 20px; 
            z-index: 9999; 
            min-width: 300px;
            animation: slideInRight 0.3s ease;
        `;
        
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }
        }, 3000);
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Global functions for onclick handlers
function changeView(view) {
    if (window.studentApp) {
        window.studentApp.changeView(view);
    }
}

// Initialize dashboard when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.studentApp = new StudentDashboard();
});

// Add some additional animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);