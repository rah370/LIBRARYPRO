<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-cogs text-primary"></i> Advanced Book Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Book Management</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo base_url('admin/add_book'); ?>" class="btn btn-success">
                <i class="fas fa-plus"></i> Add New Book
            </a>
        </div>
    </div>

    <!-- Advanced Search and Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Search & Filter Books</h5>
        </div>
        <div class="card-body">
            <?php echo form_open('admin/book_management', ['method' => 'get', 'class' => 'row g-3']); ?>
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Books</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?php echo htmlspecialchars($search ?? ''); ?>" 
                           placeholder="Title, author, ISBN...">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        <?php if(!empty($categories)): ?>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" 
                                        <?php echo ($selected_category == $cat) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="available" <?php echo ($selected_status == 'available') ? 'selected' : ''; ?>>Available</option>
                        <option value="unavailable" <?php echo ($selected_status == 'unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                        <option value="maintenance" <?php echo ($selected_status == 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                        <option value="retired" <?php echo ($selected_status == 'retired') ? 'selected' : ''; ?>>Retired</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <!-- Books Table -->
    <div class="card">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Books Library</h5>
                </div>
                <div class="col-md-6 text-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="exportBooks('csv')">
                            <i class="fas fa-file-csv"></i> Export CSV
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="bulkActions()">
                            <i class="fas fa-tasks"></i> Bulk Actions
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if(!empty($books)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                                </th>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-book"></i> Book Details</th>
                                <th><i class="fas fa-user"></i> Author</th>
                                <th><i class="fas fa-tag"></i> Category</th>
                                <th><i class="fas fa-layer-group"></i> Copies</th>
                                <th><i class="fas fa-map-marker-alt"></i> Location</th>
                                <th><i class="fas fa-circle"></i> Status</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($books as $book): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="book-checkbox" value="<?php echo $book->id; ?>">
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">#<?php echo $book->id; ?></span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($book->title); ?></strong>
                                            <?php if($book->isbn): ?>
                                                <br><small class="text-muted">ISBN: <?php echo htmlspecialchars($book->isbn); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($book->author); ?></td>
                                    <td>
                                        <?php if($book->category): ?>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($book->category); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Uncategorized</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2"><?php echo $book->copies_available; ?>/<?php echo $book->copies_total; ?></span>
                                            <?php 
                                            $percentage = ($book->copies_total > 0) ? ($book->copies_available / $book->copies_total) * 100 : 0;
                                            $bar_color = $percentage > 50 ? 'bg-success' : ($percentage > 20 ? 'bg-warning' : 'bg-danger');
                                            ?>
                                            <div class="progress" style="width: 60px; height: 8px;">
                                                <div class="progress-bar <?php echo $bar_color; ?>" 
                                                     role="progressbar" 
                                                     style="width: <?php echo $percentage; ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($book->location): ?>
                                            <code><?php echo htmlspecialchars($book->location); ?></code>
                                        <?php else: ?>
                                            <span class="text-muted">Not set</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $status_colors = [
                                            'available' => 'bg-success',
                                            'unavailable' => 'bg-danger',
                                            'maintenance' => 'bg-warning',
                                            'retired' => 'bg-secondary'
                                        ];
                                        $status_color = $status_colors[$book->status] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?php echo $status_color; ?>">
                                            <?php echo ucfirst($book->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('admin/edit_book/' . $book->id); ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Edit Book">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info" 
                                                    onclick="viewBook(<?php echo $book->id; ?>)"
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete(<?php echo $book->id; ?>, '<?php echo htmlspecialchars($book->title); ?>')"
                                                    title="Delete Book">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No books found</h5>
                    <p class="text-muted">Try adjusting your search criteria or add some books to get started.</p>
                    <a href="<?php echo base_url('admin/add_book'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add First Book
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Book Details Modal -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalLabel">
                    <i class="fas fa-book"></i> Book Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookDetails">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the book <strong id="bookTitle"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i> This action cannot be undone. The book will be permanently removed from the library.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Book
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75em;
}

.progress {
    background-color: #e9ecef;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}
</style>

<script>
// Select all functionality
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.book-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

// View book details
function viewBook(bookId) {
    const modal = new bootstrap.Modal(document.getElementById('bookModal'));
    
    // Reset modal content
    document.getElementById('bookDetails').innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // In a real application, you would fetch book details via AJAX
    // For now, we'll show a placeholder
    setTimeout(() => {
        document.getElementById('bookDetails').innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Book details would be loaded here via AJAX in a complete implementation.
            </div>
        `;
    }, 1000);
}

// Delete confirmation
function confirmDelete(bookId, bookTitle) {
    document.getElementById('bookTitle').textContent = bookTitle;
    document.getElementById('deleteBtn').href = '<?php echo base_url('admin/delete_book/'); ?>' + bookId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Export books
function exportBooks(format) {
    const selectedBooks = Array.from(document.querySelectorAll('.book-checkbox:checked'))
                               .map(cb => cb.value);
    
    if (selectedBooks.length === 0) {
        alert('Please select books to export or use the main export button to export all books.');
        return;
    }
    
    // In a real implementation, you would send the selected IDs to the export endpoint
    window.location.href = '<?php echo base_url('admin/export_data?type=books'); ?>';
}

// Bulk actions
function bulkActions() {
    const selectedBooks = document.querySelectorAll('.book-checkbox:checked');
    
    if (selectedBooks.length === 0) {
        alert('Please select books to perform bulk actions.');
        return;
    }
    
    const action = prompt('Available actions: delete, status\nEnter action:');
    
    if (action === 'delete') {
        if (confirm('Are you sure you want to delete ' + selectedBooks.length + ' selected books?')) {
            // Implement bulk delete
            console.log('Bulk delete:', Array.from(selectedBooks).map(cb => cb.value));
        }
    } else if (action === 'status') {
        const newStatus = prompt('Enter new status (available, unavailable, maintenance, retired):');
        if (newStatus) {
            // Implement bulk status change
            console.log('Bulk status change:', Array.from(selectedBooks).map(cb => cb.value), 'to', newStatus);
        }
    }
}

// Real-time search
document.getElementById('search').addEventListener('input', function() {
    // In a real implementation, you would implement debounced AJAX search here
});

// Auto-submit form when filters change
document.getElementById('category').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});
</script>