<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 mb-3">
            <i class="fas fa-book-open me-3"></i>Welcome to Sheikh Library
        </h1>
        <p class="lead mb-4">Discover thousands of books available for borrowing</p>
        <form action="<?php echo base_url('library/search'); ?>" method="GET" class="row justify-content-center">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg" name="keyword" placeholder="Search for books, authors, or ISBN...">
                    <button class="btn btn-light btn-lg" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="fas fa-books me-2"></i>Available Books</h2>
            <p class="text-muted">Browse through our collection of available books</p>
        </div>
    </div>

    <?php if(isset($db_error)): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Database Connection Issue:</strong> <?php echo $db_error; ?>
            <br><small>Please check your database configuration in <code>application/config/database.php</code></small>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(empty($books)): ?>
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No books available at the moment</h4>
            <p class="text-muted">Please check back later or contact the librarian.</p>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach($books as $book): ?>
                <div class="col">
                    <div class="card book-card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?php echo htmlspecialchars($book->title); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($book->author); ?>
                            </h6>
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="fas fa-barcode me-1"></i>ISBN: <?php echo htmlspecialchars($book->isbn); ?>
                                </small>
                            </p>
                            <?php if(isset($book->description)): ?>
                                <p class="card-text"><?php echo htmlspecialchars(substr($book->description, 0, 100)) . '...'; ?></p>
                            <?php endif; ?>
                            <div class="mt-auto">
                                <span class="badge bg-success mb-2">
                                    <i class="fas fa-check me-1"></i>Available
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="<?php echo base_url('library/borrow/' . $book->id); ?>" class="btn btn-primary w-100">
                                <i class="fas fa-hand-holding me-1"></i>Borrow Book
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4 text-center">
            <div class="p-4">
                <i class="fas fa-search fa-3x text-primary mb-3"></i>
                <h4>Search Books</h4>
                <p class="text-muted">Find the perfect book by title, author, or ISBN</p>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="p-4">
                <i class="fas fa-hand-holding fa-3x text-success mb-3"></i>
                <h4>Borrow Books</h4>
                <p class="text-muted">Borrow books for up to 14 days</p>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="p-4">
                <i class="fas fa-undo fa-3x text-info mb-3"></i>
                <h4>Return Books</h4>
                <p class="text-muted">Return books on time to avoid late fees</p>
            </div>
        </div>
    </div>
</div>
