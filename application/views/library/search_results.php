<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h2><i class="fas fa-search me-2"></i>Search Results</h2>
            <p class="text-muted">
                <?php if($keyword): ?>
                    Results for: "<strong><?php echo htmlspecialchars($keyword); ?></strong>"
                <?php endif; ?>
            </p>
            <a href="<?php echo base_url('library'); ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i>Back to All Books
            </a>
        </div>
    </div>

    <?php if(empty($books)): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No books found</h4>
            <p class="text-muted">
                <?php if($keyword): ?>
                    No books match your search for "<?php echo htmlspecialchars($keyword); ?>"
                <?php else: ?>
                    Please enter a search term to find books
                <?php endif; ?>
            </p>
            <form action="<?php echo base_url('library/search'); ?>" method="GET" class="row justify-content-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" name="keyword" placeholder="Try a different search term..." value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
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
        
        <div class="row mt-4">
            <div class="col text-center">
                <p class="text-muted">Found <?php echo count($books); ?> book(s)</p>
            </div>
        </div>
    <?php endif; ?>
</div>
