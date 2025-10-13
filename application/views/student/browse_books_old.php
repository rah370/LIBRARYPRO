<!-- Search Section -->
<div class="search-container">
    <div class="text-center mb-4">
        <h2 class="mb-3"><i class="fas fa-search me-2"></i>Search Library Books</h2>
        <p class="text-muted">Find your next great read from our collection</p>
    </div>
    
    <form method="get" action="<?php echo base_url('student/search'); ?>">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" name="keyword" 
                           placeholder="Search by title, author, or ISBN..." 
                           value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : ''; ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Available Books -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-book me-2"></i>Available Books</h3>
    <span class="badge bg-primary rounded-pill fs-6">
        <?php echo count($books); ?> books available
    </span>
</div>

<?php if (isset($db_error)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?php echo $db_error; ?>
    </div>
<?php endif; ?>

<div class="row">
    <?php if (!empty($books)): ?>
        <?php foreach ($books as $book): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card book-card">
                    <div class="card-img-top">
                        <i class="fas fa-book fa-4x"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($book->title); ?></h5>
                        <p class="card-text">
                            <strong>Author:</strong> <?php echo htmlspecialchars($book->author); ?><br>
                            <strong>ISBN:</strong> <?php echo htmlspecialchars($book->isbn); ?>
                        </p>
                        <?php if (!empty($book->description)): ?>
                            <p class="card-text text-muted">
                                <?php echo htmlspecialchars(substr($book->description, 0, 100)); ?>
                                <?php echo strlen($book->description) > 100 ? '...' : ''; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-transparent">
                        <?php if ($book->available): ?>
                            <a href="<?php echo base_url('student/borrow/' . $book->id); ?>" 
                               class="btn btn-borrow w-100"
                               onclick="return confirm('Are you sure you want to borrow this book?')">
                                <i class="fas fa-plus-circle me-2"></i>Borrow This Book
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="fas fa-clock me-2"></i>Currently Borrowed
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No books available at the moment</h4>
                <p class="text-muted">Please check back later or contact the librarian.</p>
            </div>
        </div>
    <?php endif; ?>
</div>