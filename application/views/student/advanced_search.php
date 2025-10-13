<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-search"></i> Advanced Book Search</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo site_url('search'); ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="title">Book Title:</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?php echo isset($filters['title']) ? $filters['title'] : ''; ?>"
                                           placeholder="Enter book title">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="author">Author:</label>
                                    <input type="text" class="form-control" id="author" name="author"
                                           value="<?php echo isset($filters['author']) ? $filters['author'] : ''; ?>"
                                           placeholder="Enter author name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="isbn">ISBN:</label>
                                    <input type="text" class="form-control" id="isbn" name="isbn"
                                           value="<?php echo isset($filters['isbn']) ? $filters['isbn'] : ''; ?>"
                                           placeholder="Enter ISBN">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="available_only" name="available_only" value="1"
                                               <?php echo (isset($filters['available_only']) && $filters['available_only'] === '1') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="available_only">
                                            Available books only
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="sort_by">Sort by:</label>
                                    <select class="form-select" id="sort_by" name="sort_by">
                                        <option value="title" <?php echo (isset($filters['sort_by']) && $filters['sort_by'] === 'title') ? 'selected' : ''; ?>>Title</option>
                                        <option value="author" <?php echo (isset($filters['sort_by']) && $filters['sort_by'] === 'author') ? 'selected' : ''; ?>>Author</option>
                                        <option value="created_at" <?php echo (isset($filters['sort_by']) && $filters['sort_by'] === 'created_at') ? 'selected' : ''; ?>>Date Added</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="sort_order">Order:</label>
                                    <select class="form-select" id="sort_order" name="sort_order">
                                        <option value="ASC" <?php echo (isset($filters['sort_order']) && $filters['sort_order'] === 'ASC') ? 'selected' : ''; ?>>Ascending</option>
                                        <option value="DESC" <?php echo (isset($filters['sort_order']) && $filters['sort_order'] === 'DESC') ? 'selected' : ''; ?>>Descending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                        <a href="<?php echo site_url('search'); ?>" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($search_performed): ?>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Search Results (<?php echo count($books); ?> books found)</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($books)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No books found matching your search criteria.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>ISBN</th>
                                        <th>Status</th>
                                        <?php if ($user['role'] === 'student'): ?>
                                        <th>Action</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($books as $book): ?>
                                    <?php
                                        $available = null;
                                        if (isset($book->available)) {
                                            $available = $book->available;
                                        } else {
                                            $available = (isset($book->status) && $book->status === 'available' && isset($book->copies_available) && $book->copies_available > 0) ? 1 : 0;
                                        }
                                    ?>
                                    <?php
                                        // small helper to find an image file by title or isbn
                                        if (!function_exists('find_book_image')) {
                                            function find_book_image($title, $isbn = '') {
                                                $searchDir = __DIR__ . '/../../../frontend/assets/books';
                                                if (!is_dir($searchDir)) return false;
                                                $exts = array('jpg','jpeg','png','webp','avif','svg');
                                                $isbn_norm = preg_replace('/[^a-z0-9]/i','', strtolower($isbn));
                                                // exact isbn file
                                                if ($isbn_norm !== '') {
                                                    foreach ($exts as $e) {
                                                        $p = $searchDir . '/' . $isbn_norm . '.' . $e;
                                                        if (file_exists($p)) return base_url('frontend/assets/books/' . basename($p));
                                                    }
                                                }
                                                // exact normalized title file
                                                $title_exact = preg_replace('/[^a-z0-9]/i','', strtolower($title));
                                                if ($title_exact !== '') {
                                                    foreach ($exts as $e) {
                                                        $p = $searchDir . '/' . $title_exact . '.' . $e;
                                                        if (file_exists($p)) return base_url('frontend/assets/books/' . basename($p));
                                                    }
                                                }
                                                // fallback substring match
                                                $title_norm = strtolower(trim(preg_replace('/[^a-z0-9]+/i', ' ', $title)));
                                                foreach (glob($searchDir . '/*') as $f) {
                                                    if (is_dir($f)) continue;
                                                    $b = strtolower(basename($f));
                                                    $b_alnum = preg_replace('/[^a-z0-9]/i','', $b);
                                                    if ($isbn_norm !== '' && (strpos($b, $isbn_norm) !== false || strpos($b_alnum, $isbn_norm) !== false)) {
                                                        return base_url('frontend/assets/books/' . basename($f));
                                                    }
                                                    if ($title_norm !== '' && (strpos($b, $title_norm) !== false || strpos($b_alnum, $title_exact) !== false)) {
                                                        return base_url('frontend/assets/books/' . basename($f));
                                                    }
                                                }
                                                return false;
                                            }
                                        }
                                        // prefer DB cover if available
                                        $thumb = null;
                                        if (!empty($book->cover)) {
                                            $thumb = base_url($book->cover);
                                        } else {
                                            $thumb = find_book_image($book->title ?? '', $book->isbn ?? '');
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            <?php if ($thumb): ?>
                                                <span style="display:inline-block; width:40px; height:56px; overflow:hidden; vertical-align:middle; margin-right:8px; border-radius:4px;">
                                                    <img loading="lazy" src="<?php echo $thumb; ?>" alt="<?php echo htmlspecialchars($book->title); ?>" style="width:100%; height:100%; object-fit:cover; display:block; opacity:0; transition:opacity .35s ease-in-out;" onload="this.style.opacity=1">
                                                </span>
                                            <?php endif; ?>
                                            <strong><?php echo htmlspecialchars($book->title); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($book->author); ?></td>
                                        <td><?php echo htmlspecialchars($book->isbn); ?></td>
                                        <td>
                                            <?php if ($available): ?>
                                                <span class="badge bg-success">Available</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Borrowed</span>
                                            <?php endif; ?>
                                        </td>
                                        <?php if ($user['role'] === 'student'): ?>
                                        <td>
                                            <?php if ($available): ?>
                                                <form method="post" action="<?php echo site_url('student/borrow/' . $book->id); ?>" class="d-flex align-items-center">
                                                    <input type="number" name="days" class="form-control form-control-sm me-2" min="1" max="30" value="14" style="width:80px;">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-book"></i> Borrow
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fas fa-ban"></i> Not Available
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Auto-submit form when filters change (optional)
document.addEventListener('DOMContentLoaded', function() {
    const quickSearchInput = document.createElement('input');
    quickSearchInput.type = 'text';
    quickSearchInput.className = 'form-control mb-3';
    quickSearchInput.placeholder = 'Quick search books...';
    quickSearchInput.id = 'quick-search';
    
    const searchCard = document.querySelector('.card-body');
    searchCard.insertBefore(quickSearchInput, searchCard.firstChild);
    
    let searchTimeout;
    quickSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const keyword = this.value.trim();
            if (keyword.length > 2) {
                fetch('<?php echo site_url("search/api"); ?>?q=' + encodeURIComponent(keyword))
                    .then(response => response.json())
                    .then(data => {
                        // Update suggestions or results
                        console.log('Quick search results:', data.books);
                    });
            }
        }, 300);
    });
});
</script>