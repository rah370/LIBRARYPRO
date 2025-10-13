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
                <div class="card-header d-flex justify-content-between">
                    <h4>Search Results (<?php echo count($books); ?> books found)</h4>
                    <div>
                        <a href="<?php echo site_url('admin/add_book'); ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Add New Book
                        </a>
                    </div>
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
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>ISBN</th>
                                        <th>Status</th>
                                        <th>Added On</th>
                                        <th>Actions</th>
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
                                    <tr>
                                        <td><?php echo $book->id; ?></td>
                                        <td>
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
                                        <td><?php echo date('M j, Y', strtotime($book->created_at)); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo site_url('admin/edit_book/' . $book->id); ?>" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="<?php echo site_url('admin/delete_book/' . $book->id); ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Are you sure you want to delete this book?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
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
// Admin-specific quick actions
document.addEventListener('DOMContentLoaded', function() {
    // Quick actions for admin
    const adminActions = {
        bulkEdit: function() {
            console.log('Bulk edit functionality');
        },
        exportResults: function() {
            const books = <?php echo json_encode($books ?? []); ?>;
            if (books.length > 0) {
                // Convert to CSV
                let csv = 'ID,Title,Author,ISBN,Status,Added On\n';
                books.forEach(book => {
                    const jsAvailable = (typeof book.available !== 'undefined') ? book.available : ((book.status === 'available' && book.copies_available > 0) ? 1 : 0);
                    csv += `${book.id},"${book.title}","${book.author}","${book.isbn}","${jsAvailable ? 'Available' : 'Borrowed'}","${book.created_at}"\n`;
                });
                
                // Download CSV
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.setAttribute('href', url);
                a.setAttribute('download', 'search_results.csv');
                a.click();
            }
        }
    };
    
    // Add export button if results exist
    <?php if ($search_performed && !empty($books)): ?>
    const cardHeader = document.querySelector('.card-header');
    const exportBtn = document.createElement('button');
    exportBtn.className = 'btn btn-info btn-sm ms-2';
    exportBtn.innerHTML = '<i class="fas fa-download"></i> Export CSV';
    exportBtn.onclick = adminActions.exportResults;
    cardHeader.querySelector('div').appendChild(exportBtn);
    <?php endif; ?>
});
</script>