<!-- Search Section -->
<div class="search-container">
    <h2 class="text-center mb-4">
        <i class="fas fa-search me-2"></i>Find Your Next Great Read
    </h2>
    
    <form method="get" action="<?php echo base_url('student/search'); ?>" class="row g-3">
        <div class="col-md-10">
            <input type="text" class="form-control form-control-lg" 
                   name="keyword" placeholder="Search by title, author, or ISBN..." 
                   value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : ''; ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<!-- Database Error Display -->
<?php if (isset($db_error)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?php echo $db_error; ?>
        <hr>
        <p class="mb-0">
            <a href="<?php echo base_url('setup_database.php'); ?>" class="btn btn-primary">
                <i class="fas fa-database me-2"></i>Setup Database
            </a>
        </p>
    </div>
<?php endif; ?>

<!-- Books Grid -->
<div class="row">
    <?php if (!empty($books)): ?>
        <?php foreach ($books as $book): ?>
                    <?php
                // helper: deterministic image lookup in frontend/assets/books
                if (!function_exists('find_book_image')) {
                    function find_book_image($title, $isbn = '') {
                        $searchDir = __DIR__ . '/../../../frontend/assets/books';
                        if (!is_dir($searchDir)) return false;

                        $exts = array('jpg','jpeg','png','webp','avif','svg');
                        $isbn_norm = preg_replace('/[^a-z0-9]/i','', strtolower($isbn));
                        // try exact isbn filenames first
                        if ($isbn_norm !== '') {
                            foreach ($exts as $e) {
                                $p = $searchDir . '/' . $isbn_norm . '.' . $e;
                                if (file_exists($p)) return base_url('frontend/assets/books/' . basename($p));
                            }
                        }

                        // try normalized exact title filenames
                        $title_exact = preg_replace('/[^a-z0-9]/i','', strtolower($title));
                        if ($title_exact !== '') {
                            foreach ($exts as $e) {
                                $p = $searchDir . '/' . $title_exact . '.' . $e;
                                if (file_exists($p)) return base_url('frontend/assets/books/' . basename($p));
                            }
                        }

                        // fallback: substring match of filename
                        $title_norm = strtolower(trim(preg_replace('/[^a-z0-9]+/i', ' ', $title)));
                        foreach (glob($searchDir . '/*') as $f) {
                            if (is_dir($f)) continue;
                            $b = strtolower(basename($f));
                            $b_alnum = preg_replace('/[^a-z0-9]/i','', $b);
                            if ($isbn_norm !== '' && (strpos($b, $isbn_norm) !== false || strpos($b_alnum, $isbn_norm) !== false)) {
                                return base_url('frontend/assets/books/' . basename($f));
                            }
                            // match either the spaced-normalized title or the alnum-normalized title
                            if ($title_norm !== '' && (strpos($b, $title_norm) !== false || strpos($b_alnum, $title_exact) !== false)) {
                                return base_url('frontend/assets/books/' . basename($f));
                            }
                        }
                        return false;
                    }
                }
                // Prefer DB cover if set
                $cover = null;
                if (!empty($book->cover)) {
                    $cover = base_url($book->cover);
                } else {
                    $cover = find_book_image($book->title ?? '', $book->isbn ?? '');
                }
            ?>
            <?php
                // Ensure we have an `available` flag to avoid undefined property warnings.
                // Prefer an explicit property if present, otherwise infer from status/copies_available.
                $available = null;
                if (isset($book->available)) {
                    $available = $book->available;
                } else {
                    $available = (isset($book->status) && $book->status === 'available' && isset($book->copies_available) && $book->copies_available > 0) ? 1 : 0;
                }
            ?>
            <div class="col-12 mb-3">
                <div class="card book-row-card position-relative">
                    <div class="row g-0 align-items-center">
                        <!-- Cover: left on md+, top on small -->
                        <div class="col-12 d-block d-md-none">
                            <div class="book-cover" style="height:160px; overflow:hidden; background:linear-gradient(90deg,#7b61ff,#b26bff);">
                                <?php if ($cover): ?>
                                    <img loading="lazy" src="<?php echo $cover; ?>" alt="<?php echo htmlspecialchars($book->title); ?>" style="width:100%; height:100%; object-fit:cover; display:block; opacity:0; transition:opacity .35s ease-in-out;" onload="this.style.opacity=1">
                                <?php else: ?>
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.9);">
                                        <i class="fas fa-book fa-3x"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <?php if ($available): ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Borrowed</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 d-none d-md-block">
                            <div class="book-cover" style="height:160px; overflow:hidden; border-top-left-radius: .375rem; border-bottom-left-radius: .375rem; background:linear-gradient(90deg,#7b61ff,#b26bff);">
                                <?php if ($cover): ?>
                                    <img loading="lazy" src="<?php echo $cover; ?>" alt="<?php echo htmlspecialchars($book->title); ?>" style="width:100%; height:100%; object-fit:cover; display:block; opacity:0; transition:opacity .35s ease-in-out;" onload="this.style.opacity=1">
                                <?php else: ?>
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.9);">
                                        <i class="fas fa-book fa-3x"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <?php if ($available): ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Borrowed</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-9">
                            <div class="card-body">
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($book->title); ?></h5>
                                <p class="mb-1 text-muted"><strong>Author:</strong> <?php echo htmlspecialchars($book->author); ?> &nbsp; <strong>ISBN:</strong> <code><?php echo htmlspecialchars($book->isbn); ?></code></p>
                                <?php if (!empty($book->description)): ?>
                                    <p class="card-text text-muted mb-2">
                                        <?php echo htmlspecialchars(substr($book->description, 0, 180)); ?>
                                        <?php if (strlen($book->description) > 180): ?>...<?php endif; ?>
                                    </p>
                                <?php endif; ?>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <?php if ($available): ?>
                                            <form method="post" action="<?php echo base_url('student/borrow/' . $book->id); ?>" class="d-flex align-items-center">
                                                <input type="number" name="days" class="form-control me-2" min="1" max="30" value="14" style="width:100px;" aria-label="Days to borrow">
                                                <button type="submit" class="btn btn-borrow">
                                                    <i class="fas fa-plus me-2"></i>Borrow
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-secondary" disabled>
                                                <i class="fas fa-clock me-2"></i>Currently Borrowed
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted">Added: <?php echo isset($book->created_at) ? date('M d, Y', strtotime($book->created_at)) : ''; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No books found</h4>
                <?php if (isset($keyword)): ?>
                    <p class="text-muted">Try searching with different keywords.</p>
                    <a href="<?php echo base_url('student'); ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to All Books
                    </a>
                <?php else: ?>
                    <p class="text-muted">No books are currently available in the library.</p>
                    <?php if (!isset($db_error)): ?>
                        <a href="<?php echo base_url('setup_database.php'); ?>" class="btn btn-primary">
                            <i class="fas fa-database me-2"></i>Setup Database
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($books)): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Borrowing Information:</strong> Books can be borrowed for 14 days. 
            Late returns may incur fees. You can track your borrowed books in 
            <a href="<?php echo base_url('student/my_borrows'); ?>" class="alert-link">My Borrowed Books</a>.
        </div>
    </div>
</div>
<?php endif; ?>